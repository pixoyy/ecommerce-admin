<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShipmentTrackingLog;
use App\Models\UserPoint;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentTest extends TestCase
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

    public function test_can_create_shipment_from_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $order = Order::factory()->create(['status' => 2, 'warehouse_id' => $warehouse->id]);

        $response = $this->post(route('admin.orders.shipments.store', $order), [
            'courier_name' => 'JNE',
            'shipping_cost' => 15000,
        ]);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id,
            'courier_name' => 'JNE',
            'status' => 1,
        ]);
        $this->assertDatabaseHas('shipment_tracking_logs', [
            'status' => 1,
            'updated_by' => $this->superadmin->id,
        ]);
    }

    public function test_cannot_create_shipment_for_unpaid_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 1]);

        $this->post(route('admin.orders.shipments.store', $order), [
            'courier_name' => 'JNE',
            'shipping_cost' => 15000,
        ])->assertSessionHasErrors();
    }

    public function test_cannot_create_second_shipment_for_same_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $warehouse = Warehouse::factory()->create();
        $order = Order::factory()->create(['status' => 2, 'warehouse_id' => $warehouse->id]);
        Shipment::factory()->create(['order_id' => $order->id]);

        $this->post(route('admin.orders.shipments.store', $order), [
            'courier_name' => 'JNE',
            'shipping_cost' => 15000,
        ])->assertSessionHasErrors();
    }

    public function test_can_update_status_progression(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $shipment = Shipment::factory()->create(['status' => 1]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 2,
            'tracking_number' => 'JNE001234',
        ])->assertSessionHas('success');
        $this->assertEquals(2, $shipment->fresh()->status);
    }

    public function test_tracking_number_required_for_shipped(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $shipment = Shipment::factory()->create(['status' => 1]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 2,
        ])->assertSessionHasErrors('tracking_number');
    }

    public function test_cannot_regress_status(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $shipment = Shipment::factory()->create(['status' => 2]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 1,
        ])->assertSessionHasErrors();
    }

    public function test_cannot_skip_status(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $shipment = Shipment::factory()->create(['status' => 1]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 3,
        ])->assertSessionHasErrors();
    }

    public function test_delivered_credits_points_and_updates_order(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create([
            'status' => 3,
            'point_earned' => 500,
        ]);
        $shipment = Shipment::factory()->create([
            'order_id' => $order->id,
            'status' => 3,
        ]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 4,
        ])->assertSessionHas('success');

        $this->assertEquals(4, $shipment->fresh()->status);
        $this->assertNotNull($shipment->fresh()->delivered_at);
        $this->assertEquals(5, $order->fresh()->status);

        $this->assertDatabaseHas('user_points', [
            'user_id' => $order->user_id,
            'balance' => 500,
        ]);
        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => 1,
            'amount' => 500,
        ]);
    }

    public function test_tracking_log_created_on_each_update(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $shipment = Shipment::factory()->create(['status' => 1]);

        $this->put(route('admin.shipments.update', $shipment), [
            'status' => 2,
            'tracking_number' => 'RESI001',
            'note' => 'Paket telah dikirim',
            'location' => 'Jakarta',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('shipment_tracking_logs', [
            'shipment_id' => $shipment->id,
            'status' => 2,
            'note' => 'Paket telah dikirim',
            'location' => 'Jakarta',
            'updated_by' => $this->superadmin->id,
        ]);
    }
}
