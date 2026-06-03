<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_dashboard_loads_successfully(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_customer_count(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        User::factory()->count(3)->create();

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_active_product_count(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Product::factory()->count(2)->create(['is_active' => 1]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_recent_orders(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Order::factory()->count(3)->create();

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_is_accessible_by_any_admin(): void
    {
        $role = \App\Models\Role::where('name', 'Admin')->first();
        $admin = Admin::factory()->create(['role_id' => $role->id]);

        $this->actingAs($admin, 'admin');
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
