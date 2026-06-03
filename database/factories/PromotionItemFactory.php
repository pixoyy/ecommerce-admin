<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\PromotionItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionItemFactory extends Factory
{
    protected $model = PromotionItem::class;

    public function definition(): array
    {
        $variant = ProductVariant::factory()->create();

        return [
            'promotion_id' => Promotion::factory(),
            'product_variant_id' => $variant->id,
            'override_price' => fake()->randomFloat(2, 1000, $variant->price - 1000),
        ];
    }
}
