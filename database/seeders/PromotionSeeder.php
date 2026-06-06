<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\PromotionItem;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'name' => 'Flash Sale Akhir Pekan',
                'type' => 1,
                'is_active' => 1,
                'start_at' => now()->subDay(),
                'end_at' => now()->addDays(6),
            ],
            [
                'name' => 'Promo Spesial Bulan Ini',
                'type' => 1,
                'is_active' => 1,
                'start_at' => now()->subDays(3),
                'end_at' => now()->addDays(10),
            ],
        ];

        $variants = ProductVariant::inRandomOrder()->take(8)->get();

        foreach ($promotions as $promoData) {
            $promotion = Promotion::create($promoData);

            $promoVariants = $variants->shift(4);
            foreach ($promoVariants as $variant) {
                if (!$variant) continue;

                $discount = fake()->randomElement([0.2, 0.25, 0.3, 0.35, 0.4, 0.5]);
                $overridePrice = round($variant->price * (1 - $discount), -3);

                PromotionItem::create([
                    'promotion_id' => $promotion->id,
                    'product_variant_id' => $variant->id,
                    'override_price' => $overridePrice,
                ]);
            }
        }
    }
}
