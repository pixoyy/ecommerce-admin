<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superadmin;
    private Admin $regularAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $superadminRole = \App\Models\Role::where('name', 'Superadmin')->first();
        $regularRole = \App\Models\Role::where('name', 'Admin')->first();

        $this->superadmin = Admin::create([
            'role_id' => $superadminRole->id,
            'name' => 'Superadmin',
            'email' => 'super@essenseluxe.com',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);

        $this->regularAdmin = Admin::create([
            'role_id' => $regularRole->id,
            'name' => 'Regular',
            'email' => 'regular@essenseluxe.com',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);
    }

    public function test_superadmin_can_view_user_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        User::factory()->count(3)->create();

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_superadmin_can_search_users(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

        $response = $this->get(route('admin.users.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    public function test_superadmin_can_view_create_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.form');
    }

    public function test_superadmin_can_create_user(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.users.store'), [
            'name' => 'New Customer',
            'email' => 'customer@example.com',
            'phone' => '08123456789',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Customer',
            'email' => 'customer@example.com',
            'phone' => '08123456789',
        ]);
    }

    public function test_create_user_email_must_be_unique(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Duplicate',
            'email' => 'existing@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_superadmin_can_view_edit_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $user = User::factory()->create();

        $response = $this->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.form');
        $response->assertSee($user->name);
    }

    public function test_superadmin_can_update_user(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->put(route('admin.users.update', $user), [
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
    }

    public function test_superadmin_can_update_user_password(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $user = User::factory()->create();

        $this->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $user->password));
    }

    public function test_superadmin_can_view_user_detail(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $user = User::factory()->create(['name' => 'Detail User']);

        $response = $this->get(route('admin.users.show', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertSee('Detail User');
    }

    public function test_superadmin_can_soft_delete_user(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($user);
    }

    public function test_non_superadmin_cannot_access_user_routes(): void
    {
        $this->actingAs($this->regularAdmin, 'admin');

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_user_routes(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }
}
