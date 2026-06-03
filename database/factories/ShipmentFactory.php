<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'warehouse_id' => fn (array $attrs) => Order::find($attrs['order_id'])?->warehouse_id ?? \App\Models\Warehouse::factory(),
            'shipping_cost' => fake()->randomFloat(2, 10000, 50000),
            'status' => 1,
            'courier_name' => fake()->randomElement(['JNE', 'J&T', 'SiCepat', 'GoSend']),
        ];
    }
}
