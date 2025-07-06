<?php

namespace App\Modules\Hr\Database\Factories;

use App\Modules\Hr\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // Assuming an employee has a user account
            'employee_code' => $this->faker->unique()->randomNumber(5),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            // Add other base employee fields
        ];
    }
}