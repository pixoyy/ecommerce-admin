<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Gudang Jakarta Pusat',
                'is_active' => 1,
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
            ],
            [
                'name' => 'Gudang Surabaya',
                'is_active' => 1,
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60111',
            ],
            [
                'name' => 'Gudang Bandung',
                'is_active' => 1,
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40111',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
