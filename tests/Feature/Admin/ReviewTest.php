<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    public function test_can_view_review_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Review::factory()->create();

        $response = $this->get(route('admin.reviews.index'));
        $response->assertStatus(200);
    }

    public function test_can_filter_reviews_by_product(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $product = Product::factory()->create();
        Review::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('admin.reviews.index', ['product_id' => $product->id]));
        $response->assertStatus(200);
    }

    public function test_can_filter_reviews_by_rating(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        Review::factory()->create(['rating' => 5]);

        $response = $this->get(route('admin.reviews.index', ['rating' => 5]));
        $response->assertStatus(200);
    }

    public function test_can_toggle_review_visibility(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $review = Review::factory()->create(['is_visible' => 1]);

        $this->post(route('admin.reviews.toggle', $review))->assertSessionHas('success');
        $this->assertEquals(0, $review->fresh()->is_visible);
    }

    public function test_can_toggle_review_back_to_visible(): void
    {
        $this->actingAs($this->superadmin, 'admin');
        $review = Review::factory()->create(['is_visible' => 0]);

        $this->post(route('admin.reviews.toggle', $review))->assertSessionHas('success');
        $this->assertEquals(1, $review->fresh()->is_visible);
    }
}
