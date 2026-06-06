<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id');
        $variants = ProductVariant::pluck('id');

        $carts = [
            ['user_idx' => 0, 'variant_idx' => 0, 'quantity' => 2],
            ['user_idx' => 0, 'variant_idx' => 5, 'quantity' => 1],
            ['user_idx' => 1, 'variant_idx' => 10, 'quantity' => 1],
            ['user_idx' => 1, 'variant_idx' => 15, 'quantity' => 3],
            ['user_idx' => 2, 'variant_idx' => 3, 'quantity' => 1],
            ['user_idx' => 2, 'variant_idx' => 8, 'quantity' => 2],
            ['user_idx' => 2, 'variant_idx' => 20, 'quantity' => 1],
            ['user_idx' => 3, 'variant_idx' => 25, 'quantity' => 1],
        ];

        foreach ($carts as $c) {
            $variantId = $variants->get($c['variant_idx']);
            $userId = $users->get($c['user_idx']);

            if ($variantId && $userId) {
                Cart::create([
                    'user_id' => $userId,
                    'product_variant_id' => $variantId,
                    'quantity' => $c['quantity'],
                ]);
            }
        }
    }
}
