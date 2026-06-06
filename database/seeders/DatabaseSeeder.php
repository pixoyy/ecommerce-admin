<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call([
            // Admin & RBAC
            RoleSeeder::class,
            AuthorizationTypeSeeder::class,
            ModuleGroupSeeder::class,
            ModuleSeeder::class,
            AdminSeeder::class,

            // Master data
            FileStorageSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            WarehouseSeeder::class,
            UserSeeder::class,
            PaymentAccountSeeder::class,

            // Ecommerce data
            ProductSeeder::class,
            PromotionSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
