<?php

namespace App\Modules\Hr\Database\Factories;



use App\Modules\Access\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;




class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle,
            'description' => $this->faker->sentence,
        ];
    }
}