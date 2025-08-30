<?php

namespace App\Modules\Hr\Tests\Unit;


use Tests\TestCase;

use App\Modules\Hr\Services\PayrollCalculatorService;
use App\Modules\Hr\Models\DailyAttendance;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\RoleSchedule;
use App\Modules\Hr\Models\Shift;
use App\Modules\Access\Models\Role;
use App\Modules\Hr\Database\Factories\EmployeeProfileFactory;
use App\Modules\Hr\Database\Factories\ShiftFactory;
use App\Modules\Hr\Database\Factories\RoleFactory;
use App\Modules\Hr\Database\Factories\DayOfWeekFactory;
use App\Modules\Hr\Database\Factories\BreakRuleFactory;
use App\Modules\Access\Database\Factories\RoleScheduleFactory;

use App\Modules\User\Database\Factories\CustomUserFactory as EmployeeFactory;


use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Modules\Hr\Services\AttendanceService;
use App\Modules\User\Models\EmployeeProfile as ModelsEmployeeProfile;
use InvalidArgumentException;







class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AttendanceService $attendanceService;
    protected PayrollCalculatorService $payrollCalculatorServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        (new \App\Modules\Access\Database\Seeders\RoleSeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\JobTitleSeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\WorkDaySeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\WorkShiftSeeder())->run();
        (new \App\Modules\User\Database\Seeders\UserSeeder())->run();



        // Seed DayOfWeek only if not already seeded
        if (\DB::table('day_of_weeks')->count() === 0) {
            DayOfWeekFactory::new()->monday()->create();
            DayOfWeekFactory::new()->tuesday()->create();
            DayOfWeekFactory::new()->wednesday()->create();
            DayOfWeekFactory::new()->thursday()->create();
            DayOfWeekFactory::new()->friday()->create();
            DayOfWeekFactory::new()->saturday()->create();
            DayOfWeekFactory::new()->sunday()->create();
        }

        // Mock the PayrollCalculatorService as its logic is tested separately
        $this->payrollCalculatorServiceMock = $this->createMock(PayrollCalculatorService::class);
        $this->attendanceService = new AttendanceService($this->payrollCalculatorServiceMock);


    }




    public function test_it_records_a_check_in_successfully()
    {
        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();
        $checkInTime = Carbon::now()->setTime(5, 54, 0);
        $data = $this->getPreparedAttendanceData($employeeProfile, $checkInTime, 'check-in');

        $attendance = $this->attendanceService->record($data);

        $this->assertInstanceOf(DailyAttendance::class, $attendance);
        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employeeProfile->employee_id,
            'attendance_type' => 'check-in',
            'check_status' => 'early',
            'scheduled_start' => $employeeProfile->shift->start_time,
        ]);
    }



    public function test_it_records_a_check_out_and_calculates_daily_earnings()
    {
        $this->payrollCalculatorServiceMock->method('calculatePaidHours')->willReturn([
            'regular_minutes' => 480, // 8 hours
            'overtime_minutes' => 120, // 2 hours
            'unpaid_break_minutes' => 0,
        ]);

        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();

        $checkInTime = Carbon::now()->setTime(8, 0, 0); // 2 shifts par day
        $checkOutTime = Carbon::now()->setTime(18, 0, 0); // 10 hours total


        $this->app->instance(PayrollCalculatorService::class, $this->payrollCalculatorServiceMock);

        // Record Check-in
        $this->attendanceService->record(
            $this->getPreparedAttendanceData($employeeProfile, $checkInTime, 'check-in')
        );


        // Record Check-out
        $attendance = $this->attendanceService->record(
            $this->getPreparedAttendanceData($employeeProfile, $checkOutTime, 'check-out')
        );

        $this->assertInstanceOf(DailyAttendance::class, $attendance);

        $this->assertDatabaseHas('daily_attendances', [
            'id' => $attendance->id,
            'employee_id' => $employeeProfile->employee_id,
            'attendance_type' => 'check-out',
            // 'check_status'    => 'overtime',
            'scheduled_end' => $employeeProfile->shift->end_time,
        ]);


        $this->assertDatabaseHas('daily_earnings', [
            'employee_id' => $employeeProfile->employee_id,
            'work_date' => $checkInTime->toDateString(),
            'regular_hours' => 8.00,
            'overtime_hours' => 0,
            'total_hours' => 8.00,
            'regular_amount' => 800.00,
            'overtime_amount' => 0,
            'total_amount' => 800.00,
        ]);
    }

    public function test_employee_clocking_out_with_overtime_is_marked_overtime()
    {
        $this->payrollCalculatorServiceMock
            ->method('calculatePaidHours')
            ->willReturn([
                'regular_minutes' => 480,
                'overtime_minutes' => 120,
                'unpaid_break_minutes' => 0,
            ]);

        $employee = $this->createTestEmployeeProfileWithSchedule();
        $today = Carbon::today();

        $checkInTime = $today->copy()->setTimeFromTimeString($employee->shift->start_time);
        $checkOutTime = $today->copy()->setTimeFromTimeString($employee->shift->end_time)->addHours(2); // 2 hrs overtime

        // Set overtime to start after 8 hours
        RoleSchedule::where('day_of_week_id', now()->dayOfWeekIso)
            ->where('role_id', $employee->role_id)
            ->update(['overtime_after_hours' => 8]);


        $this->attendanceService->record(
            $this->getPreparedAttendanceData($employee, $checkInTime, 'check-in')
        );

        $this->attendanceService->record(
            $this->getPreparedAttendanceData($employee, $checkOutTime, 'check-out')
        );

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employee->employee_id,
            'attendance_type' => 'check-out',
            'check_status' => 'overtime',
        ]);

        $this->assertDatabaseHas('daily_earnings', [
            'employee_id' => $employee->employee_id,
            'work_date' => $checkInTime->toDateString(),
            'regular_hours' => 8.00,
            'overtime_hours' => 2.00,
            'total_hours' => 10.00,
            'regular_amount' => 800.00,
            'overtime_amount' => 200.00,
            'total_amount' => 1000.00,
        ]);


    }




    public function test_it_handles_multiple_check_in_check_out_pairs_in_a_day()
    {

        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();

        // Mock payroll calculator
        $this->payrollCalculatorServiceMock->method('calculatePaidHours')
            ->willReturnOnConsecutiveCalls(
                ['regular_minutes' => 120, 'overtime_minutes' => 0, 'unpaid_break_minutes' => 0], // 2h
                ['regular_minutes' => 180, 'overtime_minutes' => 0, 'unpaid_break_minutes' => 0]  // 3h
            );

        $date = Carbon::create(2025, 7, 1);

        // First session
        $this->attendanceService->record([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => $date->copy()->setTime(9, 0)->toDateTimeString(),
            'attendance_type' => 'check-in',
            'attendance_date' => $date->toDateString(),
            'device_id' => 'device-123',

        ]);
        $this->attendanceService->record([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => $date->copy()->setTime(11, 0)->toDateTimeString(),
            'attendance_type' => 'check-out',
            'attendance_date' => $date->toDateString(),
            'device_id' => 'device-123',
        ]);

        // Second session
        $this->attendanceService->record([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => $date->copy()->setTime(12, 0)->toDateTimeString(),
            'attendance_type' => 'check-in',
            'attendance_date' => $date->toDateString(),
            'device_id' => 'device-123',
        ]);

        $this->attendanceService->record([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => $date->copy()->setTime(15, 0)->toDateTimeString(),
            'attendance_type' => 'check-out',
            'attendance_date' => $date->toDateString(),
            'device_id' => 'device-123',
        ]);

        $this->assertDatabaseHas('daily_earnings', [
            'employee_id' => $employeeProfile->employee_id,
            'work_date' => $date->toDateString(),
            'total_hours' => 5.00,
            'regular_hours' => 5.00,
            'overtime_hours' => 0.00,
            'total_amount' => 500.00,
        ]);
    }






    public function test_it_throws_exception_for_invalid_attendance_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid attendance type');

        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();

        $this->attendanceService->record([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => Carbon::now()->toDateTimeString(),
            'attendance_type' => 'invalid-type',
            'attendance_date' => Carbon::now()->toDateString(),
            'device_id' => 'device-123',
        ]);
    }



    public function test_employee_clocking_in_on_time_is_marked_on_time()
    {
        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();
        $shiftStart = Carbon::parse($employeeProfile->shift->start_time);

        $data = $this->getPreparedAttendanceData($employeeProfile, $shiftStart, 'check-in');
        $this->attendanceService->record($data);

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employeeProfile->employee_id,
            'check_status' => 'on_time',
        ]);
    }

    public function test_employee_clocking_in_late_is_marked_late()
    {
        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();
        $lateTime = Carbon::parse($employeeProfile->shift->start_time)->addMinutes(5);

        $data = $this->getPreparedAttendanceData($employeeProfile, $lateTime, 'check-in');
        $this->attendanceService->record($data);

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employeeProfile->employee_id,
            'check_status' => 'late',
        ]);
    }


    public function test_employee_clocking_in_early_is_marked_early()
    {
        $employee = $this->createTestEmployeeProfileWithSchedule();
        $earlyTime = Carbon::parse($employee->shift->start_time)->subMinutes(10); // 10 mins early

        $data = $this->getPreparedAttendanceData($employee, $earlyTime, 'check-in');
        $this->attendanceService->record($data);

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employee->employee_id,
            'check_status' => 'early',
        ]);
    }






    public function test_employee_exceeding_grace_period_is_marked_late()
    {
        $employee = $this->createTestEmployeeProfileWithSchedule();

        // Set grace period to 3 minutes
        RoleSchedule::where('role_id', $employee->role_id)
            ->where('day_of_week_id', now()->dayOfWeekIso)
            ->update(['late_grace_minutes' => 3]);

        $lateBeyondGrace = Carbon::parse($employee->shift->start_time)->addMinutes(5);

        $this->attendanceService->record(
            $this->getPreparedAttendanceData($employee, $lateBeyondGrace, 'check-in')
        );

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employee->employee_id,
            'check_status' => 'late',
        ]);
    }





    private function createTestEmployeeProfileWithSchedule(): EmployeeProfile
    {
        // Create or get existing shift
        $shift = Shift::firstOrCreate([
            'name' => 'Morning Shift',
        ], [
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'break_start_time' => '12:00:00',
            'break_end_time' => '13:00:00',
            'is_overnight' => false,
            'is_active' => true,
        ]);

        // Create or get existing role
        $role = Role::firstOrCreate(['name' => 'Test Role']);

        // Create EmployeeProfile
        $employeeProfile = EmployeeProfile::factory()->create([
            'employee_id' => 'EMP-2025-001',
            'shift_id' => $shift->id,
            'role_id' => $role->id,
        ]);

        // Create role schedule for the current weekday
        RoleSchedule::create([
            'name' => 'Default Role Schedule',
            'late_grace_minutes' => 0,
            'early_leave_grace_minutes' => 0,
            'effective_date' => now()->subWeek(),
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => now()->dayOfWeekIso,
        ]);

        return $employeeProfile;
    }






    private function createRoleSchedule(): EmployeeProfile
    {
        $employeeProfile = $this->createTestEmployeeProfileWithSchedule();

        RoleSchedule::create([
            'name' => "Test Schedule",
            'late_grace_minutes' => 0,
            'early_leave_grace_minutes' => 0,
            'effective_date' => Carbon::now()->subWeek(),
            'role_id' => $employeeProfile->role->id,
            'shift_id' => $employeeProfile->shift->id,
            'day_of_week_id' => Carbon::now()->dayOfWeekIso,
        ]);

        return $employeeProfile;
    }

    private function getPreparedAttendanceData(EmployeeProfile $employeeProfile, Carbon $time, string $attendanceType = 'check-in'): array
    {

        return [
            'employee_id' => $employeeProfile->employee_id,
            'device_id' => 'device-123',
            'latitude' => 10.0,
            'longitude' => 20.0,
            'attendance_date' => Carbon::now()->toDateString(),
            'attendance_type' => $attendanceType,
            'attendance_time' => $time->toTimeString(),
        ];
    }





}
