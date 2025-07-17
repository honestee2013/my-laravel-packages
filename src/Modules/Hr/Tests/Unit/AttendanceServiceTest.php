<?php

namespace App\Modules\Hr\Tests\Unit;


use Tests\TestCase;

use App\Modules\Hr\Services\PayrollCalculatorService;
use App\Modules\Hr\Models\DailyAttendance;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\RoleSchedule;
use App\Modules\Hr\Database\Factories\EmployeeProfileFactory;
use App\Modules\Hr\Database\Factories\ShiftFactory;
use App\Modules\Hr\Database\Factories\RoleFactory;
use App\Modules\Hr\Database\Factories\DayOfWeekFactory;
use App\Modules\Hr\Database\Factories\BreakRuleFactory;
use App\Modules\Access\Database\Factories\RoleScheduleFactory;

use App\Modules\User\Database\Factories\CustomUserFactory as  EmployeeFactory;


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




    /*App\Modules\Access\Database\Seeders\RoleSeeder ........................................................................................... RUNNING
    App\Modules\Access\Database\Seeders\RoleSeeder ....................................................................................... 936 ms DONE

    App\Modules\Core\Database\Seeders\StatusesSeeder ......................................................................................... RUNNING
    App\Modules\Core\Database\Seeders\StatusesSeeder ....................................................................................... 0 ms DONE

    App\Modules\Hr\Database\Seeders\BonusAndDeductionTypeSeeders ............................................................................. RUNNING
    App\Modules\Hr\Database\Seeders\BonusAndDeductionTypeSeeders .......................................................................... 27 ms DONE

    App\Modules\Hr\Database\Seeders\DailyAttendanceSeeder .................................................................................... RUNNING
    App\Modules\Hr\Database\Seeders\DailyAttendanceSeeder .................................................................................. 0 ms DONE

    App\Modules\Hr\Database\Seeders\DailyEarningsSeeder ...................................................................................... RUNNING
    App\Modules\Hr\Database\Seeders\DailyEarningsSeeder ................................................................................... 17 ms DONE

    App\Modules\Hr\Database\Seeders\JobTitleSeeder ........................................................................................... RUNNING
    App\Modules\Hr\Database\Seeders\JobTitleSeeder ....................................................................................... 134 ms DONE

    App\Modules\Hr\Database\Seeders\OnboardingTaskSeeder ..................................................................................... RUNNING
    App\Modules\Hr\Database\Seeders\OnboardingTaskSeeder .................................................................................. 50 ms DONE

    App\Modules\Hr\Database\Seeders\WorkDaySeeder ............................................................................................ RUNNING
    App\Modules\Hr\Database\Seeders\WorkDaySeeder ......................................................................................... 22 ms DONE

    App\Modules\Hr\Database\Seeders\WorkShiftSeeder .......................................................................................... RUNNING
    App\Modules\Hr\Database\Seeders\WorkShiftSeeder ........................................................................................ 7 ms DONE

    App\Modules\User\Database\Seeders\UserSeeder ............................................................................................. RUNNING
    App\Modules\User\Database\Seeders\UserSeeder ...................................................................................... 17,621 ms DONE

    App\Modules\User\Database\Seeders\UserStatusCategorySeeder ............................................................................... RUNNING
    App\Modules\User\Database\Seeders\UserStatusCategorySeeder ............................................................................ 17 ms DONE

    App\Modules\User\Database\Seeders\UserStatusSeeder ....................................................................................... RUNNING
    App\Modules\User\Database\Seeders\UserStatusSeeder ................................................................................... 263 ms DONE

        */


        (new \App\Modules\Access\Database\Seeders\RoleSeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\JobTitleSeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\WorkDaySeeder())->run();
        (new \App\Modules\Hr\Database\Seeders\WorkShiftSeeder())->run();
        (new \App\Modules\User\Database\Seeders\UserSeeder())->run();





        // Seed common lookup data
        /*DayOfWeekFactory::new()->monday()->create();
        DayOfWeekFactory::new()->tuesday()->create();
        DayOfWeekFactory::new()->wednesday()->create();
        DayOfWeekFactory::new()->thursday()->create();
        DayOfWeekFactory::new()->friday()->create();
        DayOfWeekFactory::new()->saturday()->create();
        DayOfWeekFactory::new()->sunday()->create();*/

        // Mock the PayrollCalculatorService as its logic is tested separately
        $this->payrollCalculatorServiceMock = $this->createMock(PayrollCalculatorService::class);
        $this->attendanceService = new AttendanceService($this->payrollCalculatorServiceMock);


    }

    /** @test */
    public function it_records_a_check_in_successfully()
    {


        /*$employee = EmployeeFactory::new()->create();
        $shift = ShiftFactory::new()->dayShift()->create();
        $role = RoleFactory::new()->create();
        EmployeeProfileFactory::new()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'role_id' => $role->id,
        ]);*/

        // Attendance need Role for the employeee shedule to be ready
        //$employeeProfile = EmployeeProfile::find(1); // Only one employee seeded
        $employeeProfile = $this->createRoleSchedule();

        // Prepare Clock-in Attendance Data
        $checkInTime = Carbon::now()->setTime(05, 54, 0);
        
        $data = $this->getPreparedAttendanceData($employeeProfile, $checkInTime,  'check-in');
        
        $attendance = $this->attendanceService->record($data);

        $this->assertDatabaseHas('daily_attendances', [
            'employee_id' => $employeeProfile->employee_id,
            'attendance_type' => 'check-in',
            'check_status' => 'early', // Based on default grace period of 5 mins
            //'scheduled_start' => Carbon::now()->setTime(9, 0, 0)->toDateTimeString(),
            'scheduled_start' => $employeeProfile->shift->start_time, // or format as datetime string

        ]);
        $this->assertInstanceOf(DailyAttendance::class, $attendance);
    }

    

    /** @test */
    public function it_records_a_check_out_and_calculates_daily_earnings()
    {
        /*$employee = EmployeeFactory::new()->create();
        $shift = ShiftFactory::new()->dayShift()->create(); // 09:00-17:00
        $role = RoleFactory::new()->create();*/

        /*$employeeProfile = EmployeeProfileFactory::new()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'role_id' => $role->id,
            'hourly_rate' => 100,
        ]);
        $roleSchedule = RoleScheduleFactory::new()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::now()->dayOfWeekIso,
            'break_rule_id' => null,
        ]);*/



        // Mock the payroll calculator service to return fixed values
        $this->payrollCalculatorServiceMock->method('calculatePaidHours')
            ->willReturn([
                'regular_minutes' => 8 * 60, // 8 hours
                'overtime_minutes' => 2 * 60, // 2 hours
                'unpaid_break_minutes' => 0,
            ]);


        $checkInTime = Carbon::now()->setTime(06, 0, 0);
        $checkOutTime = Carbon::now()->setTime(16, 0, 0); // Total 10 hours raw (overtime = 2 hours)



        $employeeProfile = $this->createRoleSchedule();

        // Create a check-in first
        /*$checkIn = DailyAttendance::create([
            'employee_id' => $employeeProfile->employee_id,
            'attendance_time' => $checkInTime,
            'attendance_type' => 'check-in',
            'attendance_date' => $checkInTime->toDateString(),
        ]);*/

        /*$data = [
            'employee_id' => $employee->id,
            'attendance_time' => $checkOutTime->toDateTimeString(),
            'attendance_type' => 'check-out',
            'device_id' => 'device-123',
            'latitude' => 10.0,
            'longitude' => 20.0,
            'attendance_date' => $checkOutTime->toDateString(),
        ];*/



        // shift->start_time for moning shift "06:00:00" 
        // shift->end_time for moning shift "14:00:00" 


        // Check-in log
        $data = $this->getPreparedAttendanceData($employeeProfile, $checkInTime,  'check-in');
        $attendanceIn = $this->attendanceService->record($data);

        // Check-out testing
        $data = $this->getPreparedAttendanceData($employeeProfile, $checkOutTime,  'check-out');
        $attendance = $this->attendanceService->record($data);


        $this->assertDatabaseHas('daily_attendances', [
            'id' => $attendance->id,
            'employee_id' => $employeeProfile->employee_id,
            'attendance_type' => 'check-out',
            //'checkin_id' => $checkIn->id,
            'check_status' => 'late_checkout', // From original 17:00 end
            'scheduled_end' => $employeeProfile->shift->end_time, // or format as datetime string
        ]);

        $this->assertDatabaseHas('daily_earnings', [
            'employee_id' => $employeeProfile->employee_id,
            'work_date' => $checkInTime->toDateString(),
            
            'regular_hours' => 8.00,
            'overtime_hours' => 2.00,
            'total_hours' => 10.00, // 8 regular + 2 overtime

            'regular_amount' => 800.00,
            'overtime_amount' => 300.00,
            'total_amount' => (8 * 100) + (2 * 100 * 1.5), // 800 + 300 = 1100

        ]);
    }

    /** @test */
    /*public function it_handles_multiple_check_in_check_out_pairs_in_a_day()
    {
        $employee = EmployeeFactory::new()->create();
        $shift = ShiftFactory::new()->dayShift()->create(); // 09:00-17:00
        $role = RoleFactory::new()->create();
        $employeeProfile = EmployeeProfileFactory::new()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'role_id' => $role->id,
            'hourly_rate' => 100,
        ]);
        $roleSchedule = RoleScheduleFactory::new()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::now()->dayOfWeekIso,
            'break_rule_id' => null,
        ]);

        // Mock the payroll calculator service for this test
        $this->payrollCalculatorServiceMock->method('calculatePaidHours')
            ->willReturnOnConsecutiveCalls(
                ['regular_minutes' => 2 * 60, 'overtime_minutes' => 0, 'unpaid_break_minutes' => 0], // First session
                ['regular_minutes' => 3 * 60, 'overtime_minutes' => 0, 'unpaid_break_minutes' => 0]  // Second session
            );

        $currentDate = Carbon::create(2025, 7, 1); // Specific date for consistency

        // First Check-in/Out pair (2 hours)
        $this->attendanceService->record([
            'employee_id' => $employee->id,
            'attendance_time' => $currentDate->copy()->setTime(9, 0, 0)->toDateTimeString(),
            'attendance_type' => 'check-in',
            'attendance_date' => $currentDate->toDateString(),
        ]);
        $this->attendanceService->record([
            'employee_id' => $employee->id,
            'attendance_time' => $currentDate->copy()->setTime(11, 0, 0)->toDateTimeString(),
            'attendance_type' => 'check-out',
            'attendance_date' => $currentDate->toDateString(),
        ]);

        // Second Check-in/Out pair (3 hours)
        $this->attendanceService->record([
            'employee_id' => $employee->id,
            'attendance_time' => $currentDate->copy()->setTime(12, 0, 0)->toDateTimeString(),
            'attendance_type' => 'check-in',
            'attendance_date' => $currentDate->toDateString(),
        ]);
        $this->attendanceService->record([
            'employee_id' => $employee->id,
            'attendance_time' => $currentDate->copy()->setTime(15, 0, 0)->toDateTimeString(),
            'attendance_type' => 'check-out',
            'attendance_date' => $currentDate->toDateString(),
        ]);

        $this->assertDatabaseHas('daily_earnings', [
            'employee_id' => $employee->id,
            'work_date' => $currentDate->toDateString(),
            'hours_worked' => 5.00, // 2 hours + 3 hours
            'regular_hours' => 5.00,
            'overtime_hours' => 0.00,
            'amount_earned' => 500.00, // 5 hours * 100
        ]);
    }*/

    /** @test */
    /*public function it_throws_exception_for_invalid_attendance_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid attendance type');

        $employee = EmployeeFactory::new()->create();
        $shift = ShiftFactory::new()->dayShift()->create();
        $role = RoleFactory::new()->create();
        EmployeeProfileFactory::new()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'role_id' => $role->id,
        ]);
        RoleScheduleFactory::new()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::now()->dayOfWeekIso,
        ]);

        $data = [
            'employee_id' => $employee->id,
            'attendance_time' => Carbon::now()->toDateTimeString(),
            'attendance_type' => 'invalid-type', // Invalid type
            'attendance_date' => Carbon::now()->toDateString(),
        ];

        $this->attendanceService->record($data);
    }*/

    // Add more test cases for:
    // - Overnight shift `determineShiftWorkDate` (specific times)
    // - `getRoleScheduleForDate` (no schedule found, inactive, expired)
    // - Check-in/out validation rules (e.g., checkout before checkin) - though largely handled by ValidAttendanceSequence rule









    private function createRoleSchedule(): EmployeeProfile {

            $employeeProfile = EmployeeProfile::find(1); // Only one employee seeded
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



    private function getPreparedAttendanceData(EmployeeProfile $employeeProfile, Carbon $time,  String $attendanceType = 'check-in'): array {
        $data = [
            'employee_id' => $employeeProfile->employee_id,
            'device_id' => 'device-123',
            'latitude' => 10.0,
            'longitude' => 20.0,
            'attendance_date' => Carbon::now()->toDateString(), // From StoreDailyAttendanceRequest
            'attendance_type' => $attendanceType,
            'attendance_time' => $time->toString(),
        ];


        /*$attendance_time = Carbon::now()->setTime($time, 0, 0);//->format('H:i:s'),

        if ($attendanceType = 'check-in')
            $data['attendance_time'] = $attendance_time->subMinutes($timeDiff); // 5 mins early
        else
            $data['attendance_time'] = $attendance_time->addMinutes($timeDiff); // after*/


        return $data;
    }









}
