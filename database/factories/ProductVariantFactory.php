<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'label' => fake()->word() . ' ' . fake()->randomElement(['50ml', '100ml', '250g', '500g']),
            'price' => fake()->randomFloat(2, 10000, 500000),
            'sku' => strtoupper(Str::random(8)),
            'is_active' => 1,
        ];
    }
}
