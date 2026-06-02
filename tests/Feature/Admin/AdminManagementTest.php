<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superadmin;
    private Role $regularRole;
    private string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $superadminRole = Role::where('name', 'Superadmin')->first();
        $this->regularRole = Role::where('name', 'Admin')->first();

        $this->superadmin = Admin::create([
            'role_id' => $superadminRole->id,
            'name' => 'Superadmin',
            'email' => 'super@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);
    }

    public function test_superadmin_can_view_admin_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.admins.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.admins.index');
        $response->assertSee('Superadmin');
    }

    public function test_superadmin_can_view_create_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.admins.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.admins.form');
        $response->assertSee($this->regularRole->name);
    }

    public function test_superadmin_can_create_admin(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.admins.store'), [
            'name' => 'Staff Baru',
            'email' => 'staff@essenseluxe.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role_id' => $this->regularRole->id,
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'name' => 'Staff Baru',
            'email' => 'staff@essenseluxe.com',
            'role_id' => $this->regularRole->id,
            'status' => 1,
            'deleted_at' => null,
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $this->post(route('admin.admins.store'), [
            'name' => 'Staff Pertama',
            'email' => 'staff@essenseluxe.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role_id' => $this->regularRole->id,
            'status' => 1,
        ]);

        $response = $this->post(route('admin.admins.store'), [
            'name' => 'Staff Kedua',
            'email' => 'staff@essenseluxe.com',
            'password' => 'secret456',
            'password_confirmation' => 'secret456',
            'role_id' => $this->regularRole->id,
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_min_8_characters(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.admins.store'), [
            'name' => 'Staff Baru',
            'email' => 'staff@essenseluxe.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role_id' => $this->regularRole->id,
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_required(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.admins.store'), [
            'name' => 'Staff Baru',
            'email' => 'staff@essenseluxe.com',
            'password' => 'secret123',
            'password_confirmation' => 'different',
            'role_id' => $this->regularRole->id,
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_superadmin_can_view_edit_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $admin = Admin::factory()->create(['role_id' => $this->regularRole->id]);

        $response = $this->get(route('admin.admins.edit', $admin));

        $response->assertStatus(200);
        $response->assertViewIs('admin.admins.form');
        $response->assertSee($admin->name);
    }

    public function test_superadmin_can_update_admin(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $admin = Admin::factory()->create(['role_id' => $this->regularRole->id, 'name' => 'Lama']);

        $response = $this->put(route('admin.admins.update', $admin), [
            'name' => 'Baru',
            'email' => $admin->email,
            'role_id' => $this->regularRole->id,
            'status' => 0,
        ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertEquals('Baru', $admin->name);
        $this->assertFalse($admin->status);
    }

    public function test_superadmin_can_reset_password(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $admin = Admin::factory()->create(['role_id' => $this->regularRole->id]);

        $response = $this->put(route('admin.admins.password.update', $admin), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.admins.edit', $admin));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('newpassword123', $admin->password));
    }

    public function test_superadmin_cannot_delete_self(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->delete(route('admin.admins.destroy', $this->superadmin));

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHasErrors();

        $this->assertDatabaseHas('admins', ['id' => $this->superadmin->id, 'deleted_at' => null]);
    }

    public function test_superadmin_can_delete_other_admin(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $admin = Admin::factory()->create(['role_id' => $this->regularRole->id]);

        $response = $this->delete(route('admin.admins.destroy', $admin));

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($admin);
    }

    public function test_non_superadmin_cannot_access_admin_list(): void
    {
        $regular = Admin::factory()->create(['role_id' => $this->regularRole->id]);
        $this->actingAs($regular, 'admin');

        $response = $this->get(route('admin.admins.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.admins.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_superadmin_can_filter_admins_by_role(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        Admin::factory()->count(3)->create(['role_id' => $this->regularRole->id]);

        $response = $this->get(route('admin.admins.index', ['role_id' => $this->regularRole->id]));

        $response->assertStatus(200);
    }

    public function test_superadmin_can_search_admins(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        Admin::factory()->create([
            'role_id' => $this->regularRole->id,
            'name' => 'Staff Gudang',
        ]);

        $response = $this->get(route('admin.admins.index', ['search' => 'Gudang']));

        $response->assertStatus(200);
        $response->assertSee('Staff Gudang');
    }
}
