<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Superadmin'], ['status' => 1]);
        Role::firstOrCreate(['name' => 'Admin'], ['status' => 1]);
    }
}
