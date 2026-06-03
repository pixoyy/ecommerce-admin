<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\PromotionItem;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionTest extends TestCase
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

    public function test_can_view_promotion_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Promotion::factory()->count(3)->create();

        $response = $this->get(route('admin.promotions.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_promotion(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->post(route('admin.promotions.store'), [
            'name' => 'Flash Sale Akhir Bulan',
            'is_active' => 1,
            'start_at' => now()->format('Y-m-d\TH:i'),
            'end_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('promotions', ['name' => 'Flash Sale Akhir Bulan']);
    }

    public function test_validation_end_at_must_be_after_start_at(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $response = $this->post(route('admin.promotions.store'), [
            'name' => 'Invalid Promo',
            'is_active' => 1,
            'start_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'end_at' => now()->format('Y-m-d\TH:i'),
        ]);
        $response->assertSessionHasErrors('end_at');
    }

    public function test_can_update_promotion(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        $this->put(route('admin.promotions.update', $promotion), [
            'name' => 'Updated Promo',
            'is_active' => 0,
            'start_at' => now()->format('Y-m-d\TH:i'),
            'end_at' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ])->assertRedirect();
        $this->assertDatabaseHas('promotions', ['name' => 'Updated Promo', 'is_active' => 0]);
    }

    public function test_can_soft_delete_promotion(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        $this->delete(route('admin.promotions.destroy', $promotion))->assertSessionHas('success');
        $this->assertSoftDeleted($promotion);
    }

    public function test_can_add_promotion_item(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        $variant = ProductVariant::factory()->create(['price' => 150000]);

        $response = $this->post(route('admin.promotions.items.store', $promotion), [
            'product_variant_id' => $variant->id,
            'override_price' => 100000,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('promotion_items', [
            'promotion_id' => $promotion->id,
            'product_variant_id' => $variant->id,
            'override_price' => 100000,
        ]);
    }

    public function test_override_price_must_be_less_than_normal_price(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        $variant = ProductVariant::factory()->create(['price' => 150000]);

        $response = $this->post(route('admin.promotions.items.store', $promotion), [
            'product_variant_id' => $variant->id,
            'override_price' => 200000,
        ]);
        $response->assertSessionHasErrors('override_price');
    }

    public function test_cannot_duplicate_variant_in_same_promotion(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        $variant = ProductVariant::factory()->create(['price' => 150000]);

        PromotionItem::factory()->create([
            'promotion_id' => $promotion->id,
            'product_variant_id' => $variant->id,
            'override_price' => 100000,
        ]);

        $response = $this->post(route('admin.promotions.items.store', $promotion), [
            'product_variant_id' => $variant->id,
            'override_price' => 90000,
        ]);
        $response->assertSessionHasErrors('product_variant_id');
    }

    public function test_can_remove_promotion_item(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $item = PromotionItem::factory()->create();

        $this->delete(route('admin.promotions.items.destroy', $item))->assertSessionHas('success');
        $this->assertSoftDeleted($item);
    }

    public function test_promotion_show_page_has_products_for_selection(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $promotion = Promotion::factory()->create();
        Product::factory()->count(2)->create();

        $response = $this->get(route('admin.promotions.show', $promotion));
        $response->assertStatus(200);
    }
}
