<?php

namespace App\Modules\Hr\Services;


use App\Modules\Hr\Models\DailyAttendance;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\RoleSchedule;
use App\Modules\Hr\Models\BreakRule;
use Carbon\Carbon;


use Illuminate\Support\Facades\Log; // For logging warnings/errors

class PayrollCalculatorService
{
    /**
     * Calculates the paid hours (regular and overtime) for a given check-in/check-out pair,
     * applying role schedules and break rules.
     *
     * @param DailyAttendance $checkIn
     * @param DailyAttendance $checkOut
     * @param EmployeeProfile $employeeProfile
     * @param RoleSchedule|null $roleSchedule The specific RoleSchedule for the period, passed from AttendanceService
     * @return array Contains 'regular_minutes', 'overtime_minutes', 'unpaid_break_minutes'
     */
    public function calculatePaidHours(DailyAttendance $checkIn, DailyAttendance $checkOut, EmployeeProfile $employeeProfile, ?RoleSchedule $roleSchedule): array
    {


        $totalWorkedMinutes = Carbon::parse($checkIn->attendance_time)->diffInMinutes($checkOut->attendance_time);

        if (!$roleSchedule) {
            return $this->calculateBaseOnEmployeeProfileShift($checkIn, $checkOut, $employeeProfile);
        }



        // Helper to get effective scheduled times
        list($scheduledStart, $scheduledEnd, $isOvernight) = $this->getEffectiveShiftTimes(
            $checkIn->attendance_time->copy()->startOfDay(), // Base date for calculation
            $roleSchedule
        );

        // Initialize variables
        $regularMinutes = 0;
        $overtimeMinutes = 0;
        $unpaidBreakMinutes = 0;

        // 1. Calculate Unpaid Break Minutes
        if ($roleSchedule->breakRule) {
            $unpaidBreakMinutes = $this->calculateUnpaidBreakMinutes($totalWorkedMinutes, $roleSchedule->breakRule);
        }

        $netWorkedMinutes = $totalWorkedMinutes - $unpaidBreakMinutes;
        if ($netWorkedMinutes < 0) {
            $netWorkedMinutes = 0; // Should not happen, but a safeguard
        }

        // 2. Calculate Regular Hours
        // Actual time employee was in, clamped within the *effective* scheduled window
        $actualWorkStart = $checkIn->attendance_time->max($scheduledStart);
        $actualWorkEnd = $checkOut->attendance_time->min($scheduledEnd);

        // Adjust actualWorkEnd if it crosses midnight but scheduledEnd is same day
        if ($isOvernight && $actualWorkEnd->isSameDay($scheduledStart) && $actualWorkEnd->lessThan($scheduledStart)) {
            // This scenario means actualWorkEnd is earlier than scheduledStart on the same calendar day
            // which can happen if actualWorkEnd is from the next calendar day's portion of an overnight shift.
            // Ensure actualWorkEnd is relative to scheduledEnd (which might be next day).
            $actualWorkEnd = $actualWorkEnd->addDay(); // Temporarily add day for comparison
        }

        if ($actualWorkEnd->greaterThan($actualWorkStart)) {
            $regularMinutes = $actualWorkStart->diffInMinutes($actualWorkEnd);
        }

        // Ensure regular minutes don't exceed net worked minutes
        $regularMinutes = min($regularMinutes, $netWorkedMinutes);


        // 3. Calculate Overtime Hours
        $remainingMinutes = $netWorkedMinutes - $regularMinutes;

        if ($remainingMinutes > 0) {
            // Overtime based on specific time window (if defined)
            if ($roleSchedule->overtime_start && $roleSchedule->overtime_end) {
                $otWindowStart = Carbon::parse($scheduledStart->toDateString() . ' ' . $roleSchedule->overtime_start);
                $otWindowEnd = Carbon::parse($scheduledStart->toDateString() . ' ' . $roleSchedule->overtime_end);

                // Adjust OT window for overnight shifts (if it crosses midnight)
                if ($roleSchedule->overtime_start > $roleSchedule->overtime_end) {
                     $otWindowEnd->addDay();
                }

                // Intersection of worked time and OT window
                $otIntersectionStart = $checkIn->attendance_time->max($otWindowStart);
                $otIntersectionEnd = $checkOut->attendance_time->min($otWindowEnd);

                if ($otIntersectionEnd->greaterThan($otIntersectionStart)) {
                    $overtimeMinutes += $otIntersectionStart->diffInMinutes($otIntersectionEnd);
                }
            }

            // Overtime based on hours worked threshold (e.g., after 8 hours)
            if ($roleSchedule->overtime_after_hours) {
                $thresholdMinutes = $roleSchedule->overtime_after_hours * 60;
                if ($netWorkedMinutes > $thresholdMinutes) {
                    // This rule should only apply to minutes *after* regular scheduled hours + threshold
                    // It's more complex if time-based OT also exists. Here, we take the MAX of different OT calculations.
                    $overtimeMinutes = max($overtimeMinutes, $netWorkedMinutes - $thresholdMinutes);
                }
            }
        }

        // Apply Overtime Caps (crucial for your requirement)
        if ($roleSchedule->max_paid_overtime_hours !== null) {
            $maxOvertimeMinutes = $roleSchedule->max_paid_overtime_hours * 60;
            $overtimeMinutes = min($overtimeMinutes, $maxOvertimeMinutes);
        }

        // Ensure total paid minutes don't exceed net worked minutes
        $totalPaidMinutes = $regularMinutes + $overtimeMinutes;
        if ($totalPaidMinutes > $netWorkedMinutes) {
            $overtimeMinutes = max(0, $netWorkedMinutes - $regularMinutes);
            $totalPaidMinutes = $regularMinutes + $overtimeMinutes;
        }

        // Apply Max Daily Hours Cap (highest level cap)
        if ($roleSchedule->max_daily_hours !== null) {
            $maxDailyMinutes = $roleSchedule->max_daily_hours * 60;
            if ($totalPaidMinutes > $maxDailyMinutes) {
                $diff = $totalPaidMinutes - $maxDailyMinutes;
                $overtimeMinutes = max(0, $overtimeMinutes - $diff); // Reduce overtime first
                $totalPaidMinutes = $regularMinutes + $overtimeMinutes;

                if ($totalPaidMinutes > $maxDailyMinutes) { // If still over, reduce regular
                    $regularMinutes = max(0, $regularMinutes - ($totalPaidMinutes - $maxDailyMinutes));
                }
            }
        }

        // Ensure no negative values after all calculations
        $regularMinutes = max(0, $regularMinutes);
        $overtimeMinutes = max(0, $overtimeMinutes);
        $unpaidBreakMinutes = max(0, $unpaidBreakMinutes);


        return [
            'regular_minutes' => $regularMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'unpaid_break_minutes' => $unpaidBreakMinutes,
        ];
    }

    /**
     * Helper method to get effective start and end times for a schedule,
     * prioritizing role_schedule overrides over shift defaults.
     *
     * @param Carbon $baseDate The date component to combine with time strings.
     * @param RoleSchedule|null $roleSchedule The RoleSchedule object.
     * @return array [Carbon $effectiveStart, Carbon $effectiveEnd, bool $isOvernight]
     */
    protected function getEffectiveShiftTimes(Carbon $baseDate, ?RoleSchedule $roleSchedule): array
    {
        $shiftStartTime = null;
        $shiftEndTime = null;
        $isOvernight = false;

        if ($roleSchedule) {
            // Prioritize override times from RoleSchedule
            if ($roleSchedule->override_time_start && $roleSchedule->override_time_end) {
                $shiftStartTime = Carbon::parse($roleSchedule->override_time_start);
                $shiftEndTime = Carbon::parse($roleSchedule->override_time_end);
                // For overrides, we must determine if it's overnight based on the override times themselves
                if ($shiftStartTime->greaterThan($shiftEndTime)) {
                    $isOvernight = true;
                }
            } else if ($roleSchedule->shift) {
                // Fallback to linked Shift times
                $shiftStartTime = Carbon::parse($roleSchedule->shift->start_time);
                $shiftEndTime = Carbon::parse($roleSchedule->shift->end_time);
                $isOvernight = $roleSchedule->shift->is_overnight; // Use the flag from shift
            }
        }

        if (!$shiftStartTime || !$shiftEndTime) {
            // This should ideally be handled by ensuring a roleSchedule always exists or by strict validation.
            // As a last resort, return a default range or throw an exception.
            Log::warning("No effective shift times found for date {$baseDate->toDateString()} in PayrollCalculatorService.");
            return [$baseDate->copy()->startOfDay(), $baseDate->copy()->endOfDay(), false];
        }

        $effectiveStart = $baseDate->copy()->setTimeFromTimeString($shiftStartTime->format('H:i:s'));
        $effectiveEnd = $baseDate->copy()->setTimeFromTimeString($shiftEndTime->format('H:i:s'));

        if ($isOvernight) {
            $effectiveEnd->addDay();
        }

        return [$effectiveStart, $effectiveEnd, $isOvernight];
    }

    // Keep calculateUnpaidBreakMinutes as is
    protected function calculateUnpaidBreakMinutes(int $totalWorkedMinutes, BreakRule $breakRule): int
    {
        if ($breakRule->break_type === 'paid') {
            return 0; // No unpaid breaks
        }

        if ($totalWorkedMinutes < $breakRule->min_shift_minutes) {
            return 0; // Not enough hours for a mandatory break
        }

        $unpaidBreaks = 0;

        if ($breakRule->max_breaks > 0 && $breakRule->break_interval_minutes) {
            $intervalThreshold = $breakRule->break_interval_minutes;
            $durationPerBreak = $breakRule->break_duration_minutes;

            $intervalsCrossed = floor($totalWorkedMinutes / $intervalThreshold);
            $possibleBreaks = min($intervalsCrossed, $breakRule->max_breaks);

            $unpaidBreaks = $possibleBreaks * $durationPerBreak;
        } else {
            if ($breakRule->is_mandatory) {
                $unpaidBreaks = $breakRule->break_duration_minutes * min(1, $breakRule->max_breaks);
            }
        }

        return (int) $unpaidBreaks;
    }

    // This method is now primarily used by AttendanceService to determine the work_date for DailyEarning aggregation.
    // It is less about "effective shift times" for calculation and more about the payroll period alignment.
    // So, it can remain in AttendanceService using the base Shift times.
    protected function determineShiftWorkDate(Carbon $checkInTime, ?\App\Models\Shift $assignedShift): Carbon
    {
        // This method determines the work date for payroll aggregation.
        // It relies on the *core* shift definition, not potential overrides,
        // to consistently assign a single "work day" to an overnight shift.
        if (!$assignedShift) {
            return $checkInTime->copy()->startOfDay();
        }

        if (!$assignedShift->is_overnight) {
            return $checkInTime->copy()->startOfDay();
        }

        // Overnight shift: if check-in is after midnight but before shift end time (of next day),
        // the work date is the previous day (shift start day).
        $shiftStartTime = Carbon::parse($assignedShift->start_time);
        $shiftEndTime = Carbon::parse($assignedShift->end_time);

        // To correctly check if checkInTime is within the "next day" portion of an overnight shift,
        // we compare it to the shift's end time, assuming the shift end time is on the next calendar day.
        $checkInTimeOnly = $checkInTime->copy()->format('H:i:s');
        if ($checkInTimeOnly < $assignedShift->end_time) {
            return $checkInTime->copy()->subDay()->startOfDay();
        } else {
            return $checkInTime->copy()->startOfDay();
        }
    }




    private function calculateBaseOnEmployeeProfileShift(DailyAttendance $checkIn, DailyAttendance $checkOut,  EmployeeProfile $employeeProfile): array {
            Log::warning("No RoleSchedule found for employee ID {$employeeProfile->employee_id} for date {$checkIn->attendance_time}. Calculating all hours as regular.");

            $shiftStart = Carbon::parse($employeeProfile->shift->start_time);
            $shiftEnd = Carbon::parse($employeeProfile->shift->end_time);
            $checkOutTime = Carbon::parse($checkOut->attendance_time);

            // Calculate shift duration
            $shiftDuration = $shiftStart->diffInMinutes($shiftEnd);

            if ($checkOutTime->gt($shiftEnd)) {
                // Overtime is anything after shift end
                $overtime = $shiftEnd->diffInMinutes($checkOutTime);
                $totalWorkedMinutes = $shiftDuration;
            } else {
                // No overtime
                $overtime = 0;
                $totalWorkedMinutes = $checkIn->attendance_time ? $checkOutTime->diffInMinutes(Carbon::parse($checkIn->attendance_time)) : 0;
            }

            return [
                'regular_minutes' => $totalWorkedMinutes,
                'overtime_minutes' => $overtime,
                'unpaid_break_minutes' => 0
            ];
    }




}
