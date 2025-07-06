<?php

namespace App\Modules\Hr\Services;

use App\Modules\Hr\Models\DailyAttendance;
use App\Modules\Hr\Models\DailyEarning;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\Shift;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function record(array $data)
    {
        return DB::transaction(function () use ($data) {
            $employeeId = $data['employee_id'];

            $attendanceTime = Carbon::parse($data['attendance_time']);
            $type = $data['attendance_type'];

            if (!in_array($type, ['check-in', 'check-out'])) {
                throw new \InvalidArgumentException('Invalid attendance type');
            }

            // CHECK DailyAttendance sequence and duplicate
            if (!$this->isValidAttendance($data)) {
                throw new \Exception("Invalid attendance sequence or duplicate entry.");
            }


            // Create the attendance record
            $attendance = DailyAttendance::create([
                'employee_id' => $employeeId,
                'attendance_time' => $attendanceTime,
                'attendance_type' => $type,
                'device_id' => $data['device_id'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'sync_status' => 'pending',
                'sync_attempts' => 0,
                'attendance_date' => $attendanceTime->toDateString(),
            ]);


            $employeeProfile = EmployeeProfile::where('employee_id', $employeeId)->with("shift")->firstOrFail();
            // Handle check-in and check-out logic
            if ($type === 'check-in' && $employeeProfile) {
                $this->validateCheckInTime($attendance, $employeeProfile->shift);
            } elseif ($type === 'check-out') {
                $this->handleCheckOut($attendance);
                $this->validateCheckOutTime($attendance, $employeeProfile->shift);
            }



            // Check if the employee has an assigned shift
            if ($employeeProfile && $employeeProfile->shift) {
                $shift = $employeeProfile->shift;
                // Use the date part of the attendance_time for calculations
                $attendanceDate = Carbon::parse($data['attendance_time'])->toDateString();

                // Calculate the scheduled start time for the attendance date
                $scheduledStart = Carbon::parse("$attendanceDate {$shift->start_time}");

                // Calculate the scheduled end time for the attendance date
                $scheduledEnd = Carbon::parse("$attendanceDate {$shift->end_time}");

                // Handle night shifts: If the shift end time is earlier than the start time,
                // it means the shift spans into the next day.
                if ($shift->start_time > $shift->end_time) {
                    $scheduledEnd->addDay();
                }

                // Update the attendance record with the calculated scheduled start and end times.
                // These fields are useful for both check-in and check-out to determine
                // if the employee was early, on-time, or late relative to their shift.
                $attendance->update([
                    'scheduled_start' => $scheduledStart,
                    'scheduled_end' => $scheduledEnd,
                ]);
                
            }



            return $attendance;
        });
    }



protected function isValidAttendance(array $data): bool
{
    // Check data
    if (!isset($data['employee_id'], $data['attendance_time'], $data['attendance_date'], $data['attendance_type'])) {
        throw new \InvalidArgumentException('Missing required attendance data');
    }

    $employeeId = $data['employee_id'];
    $attendanceTime = $data['attendance_time'];
    $attendanceDate = $data['attendance_date'];
    $attendanceType = $data['attendance_type'];

    // ✅ 1. Prevent exact duplicate
    $exists = DailyAttendance::where('employee_id', $employeeId)
        ->where('attendance_time', $attendanceTime)
        ->where('attendance_type', $attendanceType)
        ->exists();

    if ($exists) {
        return false;
    }

    // ✅ 2. Get the last record for that employee on that day
    $last = DailyAttendance::where('employee_id', $employeeId)
        ->where('attendance_date', $attendanceDate)
        ->orderBy('attendance_time', 'desc')
        ->first();

    if ($attendanceType === 'check-in') {
        // ❌ Invalid if last was also a check-in
        if ($last && $last->attendance_type === 'check-in') {
            return false;
        }
    }

    if ($attendanceType === 'check-out') {
        // ❌ Invalid if no check-in yet or last was also a check-out
        if (!$last || $last->attendance_type === 'check-out') {
            return false;
        }
    }

    return true; // ✅ Valid entry
}




    protected function validateCheckInTime(DailyAttendance $checkIn, ?Shift $shift)
    {
        //$employee = $checkIn->employee;
        //$shift = $employee->shift;

        if (!$shift) {
            return;
        }

        $scheduledStart = Carbon::parse($checkIn->attendance_time->toDateString() . ' ' . $shift->start_time);
        $actualCheckIn = $checkIn->attendance_time;

        $difference = $scheduledStart->diffInMinutes($actualCheckIn, false); // negative = early

        $status = match (true) {
            $difference < -5 => 'early',
            $difference >= -5 || $difference <= 5 => 'on_time',
            $difference > 5 => 'late',
            default => null,
        };

        $checkIn->update([
            'check_status' => $status,
            'minutes_difference' => $difference,
        ]);
    }



    protected function validateCheckOutTime(DailyAttendance $checkOut, ?Shift $shift)
    {
        // If no shift is provided, or the shift doesn't have an end time,
        // we cannot validate, so we return.
        if (!$shift || !$shift->end_time) {
            return;
        }

        // Combine the attendance date with the shift's scheduled end time
        // to create a full datetime object for the scheduled end.
        $scheduledEnd = Carbon::parse($checkOut->attendance_time->toDateString() . ' ' . $shift->end_time);

        // Get the actual checkout time from the attendance record.
        $actualCheckOut = $checkOut->attendance_time;

        // Calculate the difference in minutes between the scheduled end and actual checkout.
        // The 'false' argument ensures a negative value if actualCheckOut is earlier than scheduledEnd,
        // and a positive value if actualCheckOut is later than scheduledEnd.
        $difference = $scheduledEnd->diffInMinutes($actualCheckOut, false);

        // Determine the checkout status based on the difference.
        // - A negative difference (e.g., -10) means checking out early.
        // - A positive difference (e.g., 10) means checking out late.
        $status = match (true) {
            $difference < -5 => 'early_checkout',     // More than 5 minutes before scheduled end
            $difference >= -5 && $difference <= 5 => 'on_time_checkout', // Within 5 minutes of scheduled end
            $difference > 5 => 'late_checkout',       // More than 5 minutes after scheduled end
            default => null, // Fallback if none of the conditions are met (should not happen with match(true))
        };

        // Update the attendance record with the calculated status and difference.
        $checkOut->update([
            'check_status' => $status,
            'minutes_difference' => $difference,
        ]);

        
    }




    protected function handleCheckOut(DailyAttendance $checkout)
    {
        $employeeId = $checkout->employee_id;

        // 1. Find the latest unmatched check-in
        $checkIn = DailyAttendance::where('employee_id', $employeeId)
            ->where('attendance_type', 'check-in')
            ->where('attendance_time', '<', $checkout->attendance_time)
            ->whereNotIn('id', function ($q) use ($employeeId) {
                $q->select('checkin_id')
                    ->from('daily_attendances')
                    ->where('employee_id', $employeeId)
                    ->whereNotNull('checkin_id');
            })
            ->orderBy('attendance_time', 'desc')
            ->first();

        if (!$checkIn) {
            return;
        }


        $checkout->update(['checkin_id' => $checkIn->id]);

        $checkInTime = Carbon::parse($checkIn->attendance_time);
        $checkOutTime = Carbon::parse($checkout->attendance_time);



        

        $workedMinutes = $checkInTime->diffInMinutes($checkOutTime);
        $workedHours = round($workedMinutes / 60, 2);

        $employee = EmployeeProfile::where('employee_id', $employeeId)->with('shift')->first();
        $hourlyRate = $employee->hourly_rate ?? 1000;

        $amountEarned = $workedHours * $hourlyRate;

        // 2. Determine shift
        $workDate = $this->determineShiftWorkDate($checkInTime, $employee->shift);

        $earning = DailyEarning::firstOrNew([
            'employee_id' => $employeeId,
            'work_date' => $workDate,
        ]);

        $earning->hours_worked = ($earning->hours_worked ?? 0) + $workedHours;
        $earning->amount_earned = ($earning->amount_earned ?? 0) + $amountEarned;

        $earning->save();

    }

    protected function determineShiftWorkDate(Carbon $checkInTime, ?Shift $assignedShift): Carbon
    {
        $timeOnly = $checkInTime->format('H:i:s');

        if (!$assignedShift) {
            return $checkInTime;
        }

        if ($assignedShift->start_time > $assignedShift->end_time) {
            // Overnight shift
            if ($timeOnly >= $assignedShift->start_time || $timeOnly < $assignedShift->end_time) {
                return $timeOnly < $assignedShift->end_time
                    ? $checkInTime->copy()->subDay()
                    : $checkInTime->copy();
            }
        } else {
            if ($timeOnly >= $assignedShift->start_time && $timeOnly < $assignedShift->end_time) {
                return $checkInTime->copy();
            }
        }

        return $checkInTime; // fallback
    }






}
