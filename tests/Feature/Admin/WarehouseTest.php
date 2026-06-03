<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseTest extends TestCase
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

    public function test_can_view_warehouse_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->get(route('admin.warehouses.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_warehouse(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->post(route('admin.warehouses.store'), [
            'name' => 'Gudang Utama', 'is_active' => 1,
            'city' => 'Jakarta', 'province' => 'DKI Jakarta', 'postal_code' => '12345',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['name' => 'Gudang Utama']);
    }

    public function test_can_update_warehouse(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $this->put(route('admin.warehouses.update', $warehouse), [
            'name' => 'Updated', 'is_active' => 0,
        ])->assertRedirect();
        $this->assertDatabaseHas('warehouses', ['name' => 'Updated', 'is_active' => 0]);
    }

    public function test_cannot_delete_warehouse_with_stock(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create();
        WarehouseStock::create(['warehouse_id' => $warehouse->id, 'product_variant_id' => $variant->id, 'quantity' => 10]);
        $this->delete(route('admin.warehouses.destroy', $warehouse))->assertSessionHasErrors();
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'deleted_at' => null]);
    }

    public function test_can_delete_warehouse_without_stock(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $this->delete(route('admin.warehouses.destroy', $warehouse))->assertSessionHas('success');
        $this->assertSoftDeleted($warehouse);
    }
}
