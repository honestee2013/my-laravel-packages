<?php

namespace App\Modules\Hr\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\DailyEarning;
use App\Models\Employee;




class DailyEarningFactory extends Factory
{
    protected $model = DailyEarning::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'work_date' => $this->faker->date(),
            'hours_worked' => 0.00,
            'regular_hours' => 0.00,
            'overtime_hours' => 0.00,
            'amount_earned' => 0.00,
            'regular_amount' => 0.00,
            'overtime_amount' => 0.00,
        ];
    }
}