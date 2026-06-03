<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\FileStorage;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $role = \App\Models\Role::where('name', 'Superadmin')->first();
        $this->superadmin = Admin::factory()->create(['role_id' => $role->id]);
    }

    public function test_can_view_file_storage_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        FileStorage::create(['link' => 'https://example.com/file.jpg']);

        $response = $this->get(route('admin.file-storages.index'));
        $response->assertStatus(200);
    }
}
