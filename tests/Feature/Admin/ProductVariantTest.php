<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Product $product;
    private string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $role = Role::where('name', 'Admin')->first();

        $this->admin = Admin::create([
            'role_id' => $role->id,
            'name' => 'Admin User',
            'email' => 'admin@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);

        Category::factory()->create();
        Brand::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_index_displays_variants(): void
    {
        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'label' => 'Index Test Variant',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.variants.index', $this->product));

        $response->assertOk();
        $response->assertSee('Index Test Variant');
    }

    public function test_create_displays_form(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.variants.create', $this->product));

        $response->assertOk();
        $response->assertSee($this->product->name);
    }

    public function test_store_creates_variant(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.variants.store', $this->product), [
                'label' => '50ml',
                'price' => 150000,
                'sku' => 'SKU-TEST-001',
                'is_active' => 1,
            ]);

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $this->product->id,
            'label' => '50ml',
            'price' => 150000,
            'sku' => 'SKU-TEST-001',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.variants.store', $this->product), []);

        $response->assertSessionHasErrors(['label', 'price', 'sku', 'is_active']);
    }

    public function test_sku_must_be_unique(): void
    {
        ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'SKU-UNIQUE',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.variants.store', $this->product), [
                'label' => '100ml',
                'price' => 200000,
                'sku' => 'SKU-UNIQUE',
                'is_active' => 1,
            ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_price_must_be_greater_than_zero(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.variants.store', $this->product), [
                'label' => '50ml',
                'price' => 0,
                'sku' => 'SKU-ZERO',
                'is_active' => 1,
            ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_edit_displays_form(): void
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.variants.edit', [$this->product, $variant]));

        $response->assertOk();
        $response->assertSee($variant->label);
    }

    public function test_update_variant(): void
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'SKU-ORIGINAL',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.variants.update', [$this->product, $variant]), [
                'label' => '100ml Updated',
                'price' => 250000,
                'sku' => 'SKU-UPDATED',
                'is_active' => 0,
            ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'label' => '100ml Updated',
            'price' => 250000,
            'sku' => 'SKU-UPDATED',
            'is_active' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_update_preserves_sku_uniqueness_except_self(): void
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'sku' => 'SKU-SELF',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.variants.update', [$this->product, $variant]), [
                'label' => 'Updated Label',
                'price' => 100000,
                'sku' => 'SKU-SELF',
                'is_active' => 1,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_destroy_soft_deletes_variant(): void
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.variants.destroy', [$this->product, $variant]));

        $this->assertSoftDeleted($variant);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_destroy_fails_if_variant_has_orders(): void
    {
        $variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
        ]);
        OrderItem::factory()->create(['product_variant_id' => $variant->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.variants.destroy', [$this->product, $variant]));

        $this->assertNotSoftDeleted($variant);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
