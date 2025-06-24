<?php

namespace App\Modules\Hr\Services;

use App\Modules\Hr\Models\Attendance;
use App\Modules\Hr\Models\DailyEarning;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\Shift;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function record(array $data): Attendance
    {
        return DB::transaction(function () use ($data) {
            $employeeId = $data['employee_id'];
            $attendanceTime = Carbon::parse($data['attendance_time']);
            $type = $data['attendance_type'];

            if (!in_array($type, ['check-in', 'check-out'])) {
                throw new \InvalidArgumentException('Invalid attendance type');
            }


            // Create the attendance record
            $attendance = Attendance::create([
                'employee_id' => $employeeId,
                'attendance_time' => $attendanceTime,
                'attendance_type' => $type,
                'device_id' => $data['device_id'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'sync_status' => 'pending',
                'sync_attempts' => 0,
                'attendance_date' => $attendanceTime->toDateString(),
                'notes' => $data['notes'] ?? null,
            ]);


            // Handle check-in and check-out logic
            if ($type === 'check-in') {
                $this->validateCheckInTime($attendance);
            } elseif ($type === 'check-out') {
                $this->handleCheckOut($attendance);
            }


            // If check-in, set scheduled start and end times based on employee's shift
            $employeeProfile = EmployeeProfile::where('employee_id', $employeeId)->with('shift')->first();
            if ($type === 'check-in' && $employeeProfile->shift) {
                $shift = $employeeProfile->shift;
                $checkInDate = Carbon::parse($data['attendance_time'])->toDateString();

                $scheduledStart = Carbon::parse("$checkInDate {$shift->start_time}");
                $scheduledEnd = Carbon::parse("$checkInDate {$shift->end_time}");

                // Handle night shift ending next day
                if ($shift->start_time > $shift->end_time) {
                    $scheduledEnd->addDay();
                }

                $attendance->update([
                    'scheduled_start' => $scheduledStart,
                    'scheduled_end' => $scheduledEnd,
                ]);
            }






            return $attendance;
        });
    }



    protected function validateCheckInTime(Attendance $checkIn)
    {
        $employee = $checkIn->employee;
        $shift = $employee->shift;

        if (!$shift) {
            return;
        }

        $scheduledStart = Carbon::parse($checkIn->attendance_time->toDateString() . ' ' . $shift->start_time);
        $actualCheckIn = $checkIn->attendance_time;

        $difference = $scheduledStart->diffInMinutes($actualCheckIn, false); // negative = early

        $status = match (true) {
            $difference < -5 => 'early',
            $difference >= -5 && $difference <= 5 => 'on_time',
            $difference > 5 => 'late',
            default => null,
        };

        $checkIn->update([
            'checkin_status' => $status,
            'minutes_difference' => $difference,
        ]);
    }



    protected function handleCheckOut(Attendance $checkout)
    {
        $employeeId = $checkout->employee_id;

        // 1. Find the latest unmatched check-in
        $checkIn = Attendance::where('employee_id', $employeeId)
            ->where('attendance_type', 'check-in')
            ->where('attendance_time', '<', $checkout->attendance_time)
            ->whereNotIn('id', function ($q) use ($employeeId) {
                $q->select('checkin_id')
                    ->from('attendances')
                    ->where('employee_id', $employeeId)
                    ->whereNotNull('checkin_id');
            })
            ->orderBy('attendance_time', 'desc')
            ->first();

        if (!$checkIn) {
            return;
        }

        $checkout->update(['checkin_id' => $checkIn->id]);

        $workedMinutes = $checkIn->attendance_time->diffInMinutes($checkout->attendance_time);
        $workedHours = round($workedMinutes / 60, 2);

        $employee = EmployeeProfile::find($employeeId);
        $hourlyRate = $employee->hourly_rate ?? 1000;

        $amountEarned = $workedHours * $hourlyRate;

        // 2. Determine shift
        $workDate = $this->determineShiftWorkDate($checkIn->attendance_time);

        DailyEarning::updateOrCreate(
            [
                'employee_profile_id' => $employeeId,
                'work_date' => $workDate->toDateString(),
            ],
            [
                'hours_worked' => DB::raw("hours_worked + $workedHours"),
                'amount_earned' => DB::raw("amount_earned + $amountEarned"),
            ]
        );
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
