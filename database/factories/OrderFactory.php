<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'warehouse_id' => Warehouse::factory(),
            'order_number' => fake()->unique()->bothify('ORD-######'),
            'status' => 1,
            'subtotal' => fake()->randomFloat(2, 100000, 500000),
            'shipping_cost' => fake()->randomFloat(2, 10000, 50000),
            'total' => fn (array $attrs) => $attrs['subtotal'] + $attrs['shipping_cost'],
            'point_redeemed' => 0,
            'point_earned' => fake()->numberBetween(0, 500),
            'buyer_name' => fake()->name(),
            'buyer_email' => fake()->email(),
            'buyer_phone' => fake()->phoneNumber(),
            'shipping_address' => fake()->address(),
        ];
    }
}
