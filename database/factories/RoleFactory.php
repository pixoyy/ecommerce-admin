<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'status' => 1,
        ];
    }

    public function superadmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Superadmin',
        ]);
    }
}
