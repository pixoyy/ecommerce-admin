<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
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

    public function test_can_view_stock_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->get(route('admin.stocks.index'));
        $response->assertStatus(200);
    }

    public function test_can_set_new_stock(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create();
        $this->post(route('admin.stocks.store'), [
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => 50,
        ])->assertRedirect();
        $this->assertDatabaseHas('warehouse_stocks', [
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => 50,
        ]);
    }

    public function test_can_update_existing_stock(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create();
        WarehouseStock::create(['warehouse_id' => $warehouse->id, 'product_variant_id' => $variant->id, 'quantity' => 10]);
        $this->post(route('admin.stocks.store'), [
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => 75,
        ])->assertRedirect();
        $this->assertEquals(75, WarehouseStock::where('warehouse_id', $warehouse->id)->where('product_variant_id', $variant->id)->value('quantity'));
    }

    public function test_stock_cannot_be_negative(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create();
        $this->post(route('admin.stocks.store'), [
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => -1,
        ])->assertSessionHasErrors('quantity');
    }
}
