<?php

namespace App\Modules\Hr\Database\Factories;


use App\Modules\Hr\Models\EmployeeProfile;
use App\Modules\Hr\Models\Employee;
use App\Modules\Hr\Models\Shift;
use App\Modules\Access\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeProfileFactory extends Factory
{
    protected $model = EmployeeProfile::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'shift_id' => Shift::factory(),
            'role_id' => Role::factory(),
            'hourly_rate' => $this->faker->randomFloat(2, 500, 5000), // Adjust range as per your currency
            // Add other profile fields
        ];
    }
}