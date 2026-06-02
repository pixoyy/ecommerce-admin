<?php

namespace Database\Seeders;

use App\Models\ModuleGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ModuleGroupSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        ModuleGroup::truncate();
        DB::statement('SET foreign_key_checks = 1;');

        ModuleGroup::insert([
            ['name' => 'Dashboard', 'order' => 1, 'icon' => 'grid-1x2-fill', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Akses', 'order' => 2, 'icon' => 'shield-lock', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengguna', 'order' => 3, 'icon' => 'people', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kategori', 'order' => 4, 'icon' => 'tags', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Merek', 'order' => 5, 'icon' => 'bookmark', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Produk', 'order' => 6, 'icon' => 'box-seam', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inventaris', 'order' => 7, 'icon' => 'building', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Promosi & Pembayaran', 'order' => 8, 'icon' => 'credit-card', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pesanan', 'order' => 9, 'icon' => 'cart-check', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lainnya', 'order' => 10, 'icon' => 'grid-3x3-gap', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
