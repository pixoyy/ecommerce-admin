<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAccountTest extends TestCase
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

    public function test_can_view_payment_account_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        PaymentAccount::factory()->count(3)->create();

        $response = $this->get(route('admin.payment-accounts.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_payment_account(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->post(route('admin.payment-accounts.store'), [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT EssenseLuxe',
            'is_active' => 1,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('payment_accounts', ['account_number' => '1234567890']);
    }

    public function test_can_update_payment_account(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $account = PaymentAccount::factory()->create();
        $this->put(route('admin.payment-accounts.update', $account), [
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'PT EssenseLuxe Updated',
            'is_active' => 0,
        ])->assertRedirect();
        $this->assertDatabaseHas('payment_accounts', ['bank_name' => 'Mandiri', 'is_active' => 0]);
    }

    public function test_can_delete_unused_payment_account(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $account = PaymentAccount::factory()->create();
        $this->delete(route('admin.payment-accounts.destroy', $account))->assertSessionHas('success');
        $this->assertSoftDeleted($account);
    }

    public function test_cannot_delete_payment_account_with_payments(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $account = PaymentAccount::factory()->create();
        $order = Order::factory()->create();
        Payment::create([
            'order_id' => $order->id,
            'payment_account_id' => $account->id,
            'amount' => 100000,
            'status' => 'pending',
        ]);
        $this->delete(route('admin.payment-accounts.destroy', $account))->assertSessionHasErrors();
        $this->assertDatabaseHas('payment_accounts', ['id' => $account->id, 'deleted_at' => null]);
    }
}
