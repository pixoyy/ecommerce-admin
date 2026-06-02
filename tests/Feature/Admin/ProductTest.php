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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
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
    }

    public function test_index_displays_products(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index'));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_create_displays_form(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.create'));

        $response->assertOk();
    }

    public function test_store_creates_product(): void
    {
        Storage::fake('public');

        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Test Product',
                'thumbnail' => UploadedFile::fake()->image('product.jpg'),
                'description' => 'Test description',
                'features' => 'Feature 1',
                'gender' => 1,
                'is_active' => 1,
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'slug' => 'test-product',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['brand_id', 'category_id', 'name', 'thumbnail', 'gender', 'is_active']);
    }

    public function test_show_displays_product_detail(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.show', $product));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_edit_displays_form(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.edit', $product));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_update_product(): void
    {
        Storage::fake('public');

        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.update', $product), [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Updated Product',
                'description' => 'Updated description',
                'features' => 'Updated features',
                'gender' => 2,
                'is_active' => 0,
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'slug' => 'updated-product',
            'gender' => 2,
            'is_active' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_update_with_new_thumbnail(): void
    {
        Storage::fake('public');

        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.update', $product), [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Updated Product',
                'thumbnail' => UploadedFile::fake()->image('new-thumb.jpg'),
                'description' => 'Updated description',
                'features' => 'Updated features',
                'gender' => 1,
                'is_active' => 1,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_destroy_soft_deletes_product(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.destroy', $product));

        $this->assertSoftDeleted($product);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_destroy_fails_if_product_has_orders(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        OrderItem::factory()->create(['product_variant_id' => $variant->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.destroy', $product));

        $this->assertNotSoftDeleted($product);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    public function test_slug_generation_on_store(): void
    {
        Storage::fake('public');

        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'Produk Baru',
                'thumbnail' => UploadedFile::fake()->image('product.jpg'),
                'description' => 'Test',
                'features' => 'Test',
                'gender' => 3,
                'is_active' => 1,
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Produk Baru',
            'slug' => 'produk-baru',
        ]);
    }

    public function test_can_filter_products_by_category(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index', ['category_id' => $product->category_id]));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_can_filter_products_by_brand(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index', ['brand_id' => $product->brand_id]));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_can_filter_products_by_gender(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create(['gender' => 1]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index', ['gender' => 1]));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_can_search_products_by_name(): void
    {
        Category::factory()->create();
        Brand::factory()->create();
        $product = Product::factory()->create(['name' => 'Unique Search Name']);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index', ['search' => 'Unique']));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_thumbnail_required_on_store(): void
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'name' => 'No Thumbnail',
                'gender' => 1,
                'is_active' => 1,
            ]);

        $response->assertSessionHasErrors('thumbnail');
    }
}
