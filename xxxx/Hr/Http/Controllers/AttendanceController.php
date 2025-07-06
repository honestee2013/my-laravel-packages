<?php 

namespace App\Modules\Hr\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Modules\Hr\Services\AttendanceService;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'employee_id' => 'required|exists:employee_profiles,employee_id',
            'attendance_time' => 'required|date',
            'attendance_date' => 'required|date',
            'attendance_type' => 'required|in:check-in,check-out',
            'device_id' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
            
        $attendance = $this->attendanceService->record($validated);

        return response()->json([
            'message' => $attendance? 'Attendance recorded successfully': 'Attendance record failed. Possibly a duplicate record.',
            'success' => $attendance? true: false,
            'data' => $attendance,
        ]);
    }



    public function syncBatch(Request $request, AttendanceService $service)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employee_profiles,id',
            'attendances.*.attendance_time' => 'required|date',
            'attendances.*.attendance_type' => 'required|in:check-in,check-out',
            'attendances.*.device_id' => 'required|string',
        ]);

        $responses = [];

        foreach ($request->attendances as $record) {
            try {
                $attendance = $service->record($record);
                $responses[] = [
                    'employee_id' => $record['employee_id'],
                    'status' => 'success',
                    'timestamp' => $attendance->attendance_time,
                ];
            } catch (\Throwable $e) {
                $responses[] = [
                    'employee_id' => $record['employee_id'],
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return response()->json(['results' => $responses]);
    }





}
