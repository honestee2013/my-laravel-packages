<?php

namespace App\Modules\Hr\Tests\Unit;


use Tests\TestCase;

use App\Modules\Hr\Services\PayrollCalculatorService;
use App\Modules\Hr\Models\DailyAttendance;
use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\Shift;
use App\Modules\Hr\Models\Role;
use App\Modules\Hr\Models\DayOfWeek;
use App\Modules\Hr\Models\BreakRule;
use App\Modules\Hr\Models\RoleSchedule;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayrollCalculatorServiceTest extends TestCase
{
    use RefreshDatabase; // Resets the database for each test

    protected PayrollCalculatorService $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PayrollCalculatorService();

        // Seed common lookup data
        DayOfWeek::factory()->monday()->create();
        DayOfWeek::factory()->tuesday()->create();
        DayOfWeek::factory()->wednesday()->create();
        DayOfWeek::factory()->thursday()->create();
        DayOfWeek::factory()->friday()->create();
        DayOfWeek::factory()->saturday()->create();
        DayOfWeek::factory()->sunday()->create();
    }

    /** @test */
    public function it_calculates_regular_hours_without_breaks_or_overtime()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // 09:00-17:00 (8 hours)

        $roleSchedule = RoleSchedule::factory()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::MONDAY,
            'break_rule_id' => null, // No breaks
        ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 9, 0, 0); // Tuesday
        $checkOutTime = Carbon::create(2025, 7, 1, 17, 0, 0);
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']); // 8 hours
        $this->assertEquals(0, $result['overtime_minutes']);
        $this->assertEquals(0, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_deducts_unpaid_break_minutes()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // 09:00-17:00 (8 hours)
        $breakRule = BreakRule::factory()->unpaidStandard()->create([ // 30 min unpaid break after 4 hours
            'break_type' => 'unpaid',
            'min_shift_minutes' => 240,
            'break_duration_minutes' => 30,
            'max_breaks' => 1,
        ]);

        $roleSchedule = RoleSchedule::factory()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::MONDAY,
            'break_rule_id' => $breakRule->id,
        ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 9, 0, 0);
        $checkOutTime = Carbon::create(2025, 7, 1, 17, 0, 0); // 8 hours worked
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(7 * 60 + 30, $result['regular_minutes']); // 8 hours - 30 min break = 7.5 hours
        $this->assertEquals(0, $result['overtime_minutes']);
        $this->assertEquals(30, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_calculates_overtime_after_hours_threshold()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // 09:00-17:00 (8 hours)

        $roleSchedule = RoleSchedule::factory()->withOvertimeAfterHours(8.0)->create([ // OT after 8 hours
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::MONDAY,
            'break_rule_id' => null,
        ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 9, 0, 0);
        $checkOutTime = Carbon::create(2025, 7, 1, 19, 0, 0); // Worked 10 hours (2 hours OT)
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']); // 8 hours regular
        $this->assertEquals(2 * 60, $result['overtime_minutes']); // 2 hours overtime
        $this->assertEquals(0, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_caps_overtime_using_max_paid_overtime_hours()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // 09:00-17:00 (8 hours)

        $roleSchedule = RoleSchedule::factory()
            ->withOvertimeAfterHours(8.0)
            ->withMaxPaidOvertime(1.5) // Max 1.5 hours paid OT
            ->create([
                'role_id' => $role->id,
                'shift_id' => $shift->id,
                'day_of_week_id' => Carbon::MONDAY,
                'break_rule_id' => null,
            ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 9, 0, 0);
        $checkOutTime = Carbon::create(2025, 7, 1, 19, 0, 0); // Worked 10 hours (2 hours OT actual)
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']);
        $this->assertEquals(1.5 * 60, $result['overtime_minutes']); // Capped at 1.5 hours
        $this->assertEquals(0, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_caps_total_daily_hours_using_max_daily_hours()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // 09:00-17:00 (8 hours)

        $roleSchedule = RoleSchedule::factory()
            ->withOvertimeAfterHours(8.0)
            ->withMaxDailyHours(10.0) // Max 10.0 total hours
            ->create([
                'role_id' => $role->id,
                'shift_id' => $shift->id,
                'day_of_week_id' => Carbon::MONDAY,
                'break_rule_id' => null,
            ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 9, 0, 0);
        $checkOutTime = Carbon::create(2025, 7, 1, 20, 0, 0); // Worked 11 hours (3 hours OT actual)
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']); // Regular hours are untouched
        $this->assertEquals(2 * 60, $result['overtime_minutes']); // Max daily hours (10) - Regular (8) = 2 hours OT
        $this->assertEquals(0, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_handles_overnight_shifts_correctly()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->nightShift()->create(); // 22:00-06:00 (8 hours), is_overnight = true

        $roleSchedule = RoleSchedule::factory()->create([
            'role_id' => $role->id,
            'shift_id' => $shift->id,
            'day_of_week_id' => Carbon::MONDAY, // Shift starts Monday night
            'break_rule_id' => null,
        ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        // Check-in Monday 22:00, Check-out Tuesday 06:00
        $checkInTime = Carbon::create(2025, 7, 1, 22, 0, 0); // Monday
        $checkOutTime = Carbon::create(2025, 7, 2, 6, 0, 0);   // Tuesday
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']);
        $this->assertEquals(0, $result['overtime_minutes']);
        $this->assertEquals(0, $result['unpaid_break_minutes']);
    }

    /** @test */
    public function it_handles_overrides_in_role_schedule()
    {
        $employee = EmployeeProfile::factory()->create();
        $role = Role::factory()->create();
        $shift = Shift::factory()->dayShift()->create(); // Original 09:00-17:00

        $roleSchedule = RoleSchedule::factory()
            ->withOverrideTimes('10:00:00', '18:00:00') // Override to 10:00-18:00 (8 hours)
            ->create([
                'role_id' => $role->id,
                'shift_id' => $shift->id,
                'day_of_week_id' => Carbon::MONDAY,
                'break_rule_id' => null,
            ]);
        $employee->update(['role_id' => $role->id, 'shift_id' => $shift->id]);

        $checkInTime = Carbon::create(2025, 7, 1, 10, 0, 0);
        $checkOutTime = Carbon::create(2025, 7, 1, 18, 0, 0);
        $checkIn = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkInTime, 'attendance_type' => 'check-in']);
        $checkOut = DailyAttendance::factory()->create(['employee_id' => $employee->employee_id, 'attendance_time' => $checkOutTime, 'attendance_type' => 'check-out']);

        $result = $this->calculator->calculatePaidHours($checkIn, $checkOut, $employee->fresh(), $roleSchedule->fresh());

        $this->assertEquals(8 * 60, $result['regular_minutes']);
        $this->assertEquals(0, $result['overtime_minutes']);
    }

    // Add more test cases:
    // - Multiple check-in/check-out for a single day (PayrollCalculator does one pair, AttendanceService aggregates)
    // - Paid breaks
    // - Overtime_start/end windows
    // - Grace periods (though validated in AttendanceService, ensure they don't break calculation here if times are exact)
    // - Edge cases around midnight, 0 hours worked, very short shifts.
}