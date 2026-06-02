<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $superadminRole = \App\Models\Role::where('name', 'Superadmin')->first();

        $this->superadmin = Admin::create([
            'role_id' => $superadminRole->id,
            'name' => 'Superadmin',
            'email' => 'super@essenseluxe.com',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);
    }

    public function test_superadmin_can_view_brand_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.brands.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.brands.index');
    }

    public function test_superadmin_can_create_brand(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.brands.store'), [
            'name' => 'EssenseLuxe',
        ]);

        $response->assertRedirect(route('admin.brands.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('brands', [
            'name' => 'EssenseLuxe',
            'slug' => 'essenseluxe',
        ]);
    }

    public function test_brand_name_must_be_unique(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        Brand::create(['name' => 'EssenseLuxe', 'slug' => 'essenseluxe']);

        $response = $this->post(route('admin.brands.store'), [
            'name' => 'EssenseLuxe',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_superadmin_can_update_brand(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $brand = Brand::factory()->create(['name' => 'Old Brand']);

        $response = $this->put(route('admin.brands.update', $brand), [
            'name' => 'New Brand',
        ]);

        $response->assertRedirect(route('admin.brands.index'));

        $brand->refresh();
        $this->assertEquals('New Brand', $brand->name);
        $this->assertEquals('new-brand', $brand->slug);
    }

    public function test_cannot_delete_brand_with_active_products(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        Product::factory()->create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);

        $response = $this->delete(route('admin.brands.destroy', $brand));

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'deleted_at' => null]);
    }

    public function test_can_delete_brand_without_products(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $brand = Brand::factory()->create();

        $response = $this->delete(route('admin.brands.destroy', $brand));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted($brand);
    }
}
