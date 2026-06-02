<?php

namespace Database\Seeders;

use App\Models\AuthorizationType;
use Illuminate\Database\Seeder;

class AuthorizationTypeSeeder extends Seeder
{
    public function run(): void
    {
        AuthorizationType::firstOrCreate(['name' => 'create']);
        AuthorizationType::firstOrCreate(['name' => 'read']);
        AuthorizationType::firstOrCreate(['name' => 'update']);
        AuthorizationType::firstOrCreate(['name' => 'delete']);
    }
}
