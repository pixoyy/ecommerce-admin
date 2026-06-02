<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Authorization;
use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = Role::where('name', 'Superadmin')->first();

        Admin::firstOrCreate(
            ['email' => 'superadmin@essenseluxe.com'],
            [
                'role_id' => $superadmin->id,
                'name' => 'Superadmin',
                'password' => bcrypt('password'),
                'status' => 1,
            ]
        );

        $this->syncSuperadminAuthorizations($superadmin->id);
    }

    private function syncSuperadminAuthorizations(int $roleId): void
    {
        $modules = Module::all();
        $authTypes = AuthorizationType::all();

        Authorization::where('role_id', $roleId)->forceDelete();

        $authorizations = [];

        foreach ($modules as $module) {
            foreach ($authTypes as $authType) {
                $authorizations[] = [
                    'role_id' => $roleId,
                    'module_id' => $module->id,
                    'authorization_type_id' => $authType->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Authorization::insert($authorizations);
    }
}
