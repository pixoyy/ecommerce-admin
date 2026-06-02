<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use App\Models\FileStorage;
use App\Models\Product;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_superadmin_can_view_category_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_superadmin_can_create_category(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Skincare',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Skincare',
            'slug' => 'skincare',
            'sort_order' => 1,
            'is_active' => 1,
        ]);
    }

    public function test_category_slug_auto_generated(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $this->post(route('admin.categories.store'), [
            'name' => 'Body Care',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('categories', ['slug' => 'body-care']);
    }

    public function test_superadmin_can_create_category_with_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superadmin, 'admin');

        $file = UploadedFile::fake()->image('category.jpg', 100, 100);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Premium',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => $file,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Premium']);
        $this->assertEquals(1, FileStorage::count());
    }

    public function test_superadmin_can_update_category(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $category = Category::factory()->create(['name' => 'Old Name', 'sort_order' => 1]);

        $response = $this->put(route('admin.categories.update', $category), [
            'name' => 'New Name',
            'sort_order' => 2,
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category->refresh();
        $this->assertEquals('New Name', $category->name);
        $this->assertEquals(2, $category->sort_order);
        $this->assertFalse($category->is_active);
    }

    public function test_cannot_delete_category_with_active_products(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $category = Category::factory()->create();
        $brand = \App\Models\Brand::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_can_delete_category_without_products(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted($category);
    }
}
