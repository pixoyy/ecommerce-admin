<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(2),
            'type' => 1,
            'is_active' => 1,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDays(7),
        ];
    }
}
