<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
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

    public function test_can_view_order_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Order::factory()->count(3)->create();

        $response = $this->get(route('admin.orders.index'));
        $response->assertStatus(200);
    }

    public function test_can_filter_orders_by_status(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Order::factory()->create(['status' => 1]);
        Order::factory()->create(['status' => 6]);

        $response = $this->get(route('admin.orders.index', ['status' => 6]));
        $response->assertStatus(200);
    }

    public function test_can_search_orders_by_order_number(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Order::factory()->create(['order_number' => 'ORD-UNIQUE-001']);
        Order::factory()->create(['order_number' => 'ORD-OTHER-002']);

        $response = $this->get(route('admin.orders.index', ['search' => 'UNIQUE']));
        $response->assertStatus(200);
    }

    public function test_can_view_order_detail(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        OrderItem::factory()->create(['order_id' => $order->id]);

        $response = $this->get(route('admin.orders.show', $order));
        $response->assertStatus(200);
    }

    public function test_can_cancel_pending_order_with_stock_return(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create(['price' => 100000]);
        WarehouseStock::create([
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => 10,
        ]);

        $order = Order::factory()->create([
            'status' => 1,
            'warehouse_id' => $warehouse->id,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => 3,
            'unit_price' => 100000,
            'subtotal' => 300000,
        ]);

        $response = $this->post(route('admin.orders.cancel', $order));
        $response->assertSessionHas('success');

        $this->assertEquals(6, $order->fresh()->status);
        $this->assertEquals(13, WarehouseStock::first()->quantity);
    }

    public function test_can_cancel_processing_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $warehouse = Warehouse::factory()->create();
        $variant = ProductVariant::factory()->create();
        WarehouseStock::create([
            'warehouse_id' => $warehouse->id,
            'product_variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $order = Order::factory()->create([
            'status' => 3,
            'warehouse_id' => $warehouse->id,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->post(route('admin.orders.cancel', $order))->assertSessionHas('success');
        $this->assertEquals(6, $order->fresh()->status);
    }

    public function test_cannot_cancel_shipped_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 4]);

        $this->post(route('admin.orders.cancel', $order))->assertSessionHasErrors();
    }

    public function test_cannot_cancel_delivered_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 5]);

        $this->post(route('admin.orders.cancel', $order))->assertSessionHasErrors();
    }

    public function test_cannot_cancel_already_cancelled_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 6]);

        $this->post(route('admin.orders.cancel', $order))->assertSessionHasErrors();
    }
}
