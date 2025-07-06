<?php

namespace App\Modules\Hr\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Modules\Hr\Services\AttendanceService;
use App\Modules\Hr\Services\PayrollCalculatorService;

use App\Modules\Hr\Http\Requests\StoreDailyAttendanceRequest;
use App\Modules\Hr\Rules\ValidAttendanceSequence; // Import the rule

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;



class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function store(StoreDailyAttendanceRequest $request): JsonResponse
    {

        try {
            // Apply the custom sequence rule using the validated data
            $request->validate([
                'attendance_time' => [
                    'required',
                    'date',
                    new ValidAttendanceSequence(
                        $request->employee_id,
                        $request->attendance_time,
                        $request->attendance_type,
                        $request->attendance_date
                    )
                ],
            ]);




            /*$validated = $request->validate([
                    'employee_id' => 'required|exists:employee_profiles,employee_id',
                    'attendance_time' => 'required|date',
                    'attendance_date' => 'required|date',
                    'attendance_type' => 'required|in:check-in,check-out',
                    'device_id' => 'required|string',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'notes' => 'nullable|string',
                ]);

            return response()->json(['message' => $validated]);*/




            $attendance = $this->attendanceService->record($request->validated());

            return response()->json(['message' => 'Attendance recorded successfully', 'data' => $attendance], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            \Log::error("Attendance recording failed: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
}
