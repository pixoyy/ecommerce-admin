<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\FileStorage;
use App\Models\User;
use App\Models\UserPoint;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointTest extends TestCase
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

    public function test_can_view_point_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $user = User::factory()->create();
        UserPoint::create(['user_id' => $user->id, 'balance' => 500]);

        $response = $this->get(route('admin.point-transactions.index'));
        $response->assertStatus(200);
    }

    public function test_can_search_point_by_user_name(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $user = User::factory()->create(['name' => 'Unique Name']);
        UserPoint::create(['user_id' => $user->id, 'balance' => 500]);

        $response = $this->get(route('admin.point-transactions.index', ['search' => 'Unique']));
        $response->assertStatus(200);
    }

    public function test_can_view_user_point_detail(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $user = User::factory()->create();
        UserPoint::create(['user_id' => $user->id, 'balance' => 1000]);

        $response = $this->get(route('admin.point-transactions.show', $user));
        $response->assertStatus(200);
    }
}
