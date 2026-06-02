<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET foreign_key_checks = 0;');
        Module::truncate();
        DB::statement('SET foreign_key_checks = 1;');

        Module::insert([
            ['module_group_id' => 1, 'name' => 'Dashboard', 'route' => 'admin.dashboard', 'order' => 1, 'icon' => 'grid-1x2-fill', 'is_shown' => 1],

            ['module_group_id' => 2, 'name' => 'Role & Otorisasi', 'route' => 'admin.roles', 'order' => 1, 'icon' => 'shield-lock', 'is_shown' => 1],
            ['module_group_id' => 2, 'name' => 'Admin', 'route' => 'admin.admins', 'order' => 2, 'icon' => 'person-badge', 'is_shown' => 1],

            ['module_group_id' => 3, 'name' => 'Pelanggan', 'route' => 'admin.users', 'order' => 1, 'icon' => 'people', 'is_shown' => 1],

            ['module_group_id' => 4, 'name' => 'Kategori', 'route' => 'admin.categories', 'order' => 1, 'icon' => 'tags', 'is_shown' => 1],

            ['module_group_id' => 5, 'name' => 'Merek', 'route' => 'admin.brands', 'order' => 1, 'icon' => 'bookmark', 'is_shown' => 1],

            ['module_group_id' => 6, 'name' => 'Produk', 'route' => 'admin.products', 'order' => 1, 'icon' => 'box', 'is_shown' => 1],
            ['module_group_id' => 6, 'name' => 'Varian Produk', 'route' => 'admin.products.variants', 'order' => 2, 'icon' => 'columns-gap', 'is_shown' => 0],

            ['module_group_id' => 7, 'name' => 'Gudang', 'route' => 'admin.warehouses', 'order' => 1, 'icon' => 'building', 'is_shown' => 1],
            ['module_group_id' => 7, 'name' => 'Stok', 'route' => 'admin.stocks', 'order' => 2, 'icon' => 'boxes', 'is_shown' => 1],

            ['module_group_id' => 8, 'name' => 'Promosi', 'route' => 'admin.promotions', 'order' => 1, 'icon' => 'megaphone', 'is_shown' => 1],
            ['module_group_id' => 8, 'name' => 'Akun Pembayaran', 'route' => 'admin.payment-accounts', 'order' => 2, 'icon' => 'credit-card', 'is_shown' => 1],
            ['module_group_id' => 8, 'name' => 'Konfirmasi Pembayaran', 'route' => 'admin.payments', 'order' => 3, 'icon' => 'cash-stack', 'is_shown' => 1],

            ['module_group_id' => 9, 'name' => 'Pesanan', 'route' => 'admin.orders', 'order' => 1, 'icon' => 'cart', 'is_shown' => 1],
            ['module_group_id' => 9, 'name' => 'Pengiriman', 'route' => 'admin.shipments', 'order' => 2, 'icon' => 'truck', 'is_shown' => 1],

            ['module_group_id' => 10, 'name' => 'Poin Reward', 'route' => 'admin.point-transactions', 'order' => 1, 'icon' => 'star', 'is_shown' => 1],
            ['module_group_id' => 10, 'name' => 'Ulasan', 'route' => 'admin.reviews', 'order' => 2, 'icon' => 'chat-square-text', 'is_shown' => 1],
            ['module_group_id' => 10, 'name' => 'File Storage', 'route' => 'admin.file-storages', 'order' => 3, 'icon' => 'folder', 'is_shown' => 1],
        ]);
    }
}
