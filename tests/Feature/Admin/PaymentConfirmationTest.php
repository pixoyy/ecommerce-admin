<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentConfirmationTest extends TestCase
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

    public function test_can_view_payment_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        PaymentAccount::factory()->create();
        Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => 1,
            'amount' => 100000,
            'status' => 1,
        ]);

        $response = $this->get(route('admin.payments.index'));
        $response->assertStatus(200);
    }

    public function test_can_view_payment_detail(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 1,
        ]);

        $response = $this->get(route('admin.payments.show', $payment));
        $response->assertStatus(200);
    }

    public function test_can_approve_payment(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 1]);
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 1,
        ]);

        $this->post(route('admin.payments.approve', $payment))->assertSessionHas('success');

        $this->assertEquals(2, $payment->fresh()->status);
        $this->assertEquals($this->superadmin->id, $payment->fresh()->approved_by);
        $this->assertNotNull($payment->fresh()->approved_at);
        $this->assertEquals(3, $order->fresh()->status);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_can_reject_payment(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create(['status' => 1]);
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 1,
        ]);

        $this->post(route('admin.payments.reject', $payment), [
            'rejected_reason' => 'Bukti transfer tidak jelas, harap upload ulang.',
        ])->assertSessionHas('success');

        $this->assertEquals(3, $payment->fresh()->status);
        $this->assertEquals($this->superadmin->id, $payment->fresh()->rejected_by);
        $this->assertNotNull($payment->fresh()->rejected_at);
        $this->assertNotNull($payment->fresh()->rejected_reason);
        $this->assertEquals(1, $order->fresh()->status);
    }

    public function test_cannot_reject_without_reason(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 1,
        ]);

        $this->post(route('admin.payments.reject', $payment), [])
            ->assertSessionHasErrors('rejected_reason');
    }

    public function test_cannot_reject_with_short_reason(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 1,
        ]);

        $this->post(route('admin.payments.reject', $payment), [
            'rejected_reason' => 'Tidak',
        ])->assertSessionHasErrors('rejected_reason');
    }

    public function test_cannot_approve_already_approved_payment(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 2,
        ]);

        $this->post(route('admin.payments.approve', $payment))->assertSessionHasErrors();
    }

    public function test_cannot_reject_already_rejected_payment(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $order = Order::factory()->create();
        $account = PaymentAccount::factory()->create();
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 3,
        ]);

        $this->post(route('admin.payments.reject', $payment), [
            'rejected_reason' => 'Bukti transfer tidak jelas, harap upload ulang.',
        ])->assertSessionHasErrors();
    }
}
