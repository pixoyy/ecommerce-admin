<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Authorization;
use App\Models\AuthorizationType;
use App\Models\Module;
use App\Models\Role;
use Database\Seeders\AuthorizationTypeSeeder;
use Database\Seeders\ModuleGroupSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superadmin;
    private Admin $regularAdmin;
    private string $password = 'password123';
    private Role $superadminRole;
    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(AuthorizationTypeSeeder::class);
        $this->seed(ModuleGroupSeeder::class);
        $this->seed(ModuleSeeder::class);

        $this->superadminRole = Role::where('name', 'Superadmin')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();

        $this->superadmin = Admin::create([
            'role_id' => $this->superadminRole->id,
            'name' => 'Superadmin User',
            'email' => 'super@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);

        $this->regularAdmin = Admin::create([
            'role_id' => $this->adminRole->id,
            'name' => 'Regular Admin',
            'email' => 'admin@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);
    }

    public function test_superadmin_can_view_role_list(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.index');
        $response->assertSee('Superadmin');
        $response->assertSee('Admin');
    }

    public function test_superadmin_can_view_create_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.roles.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.form');
    }

    public function test_superadmin_can_create_role(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.roles.store'), [
            'name' => 'Admin Gudang',
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('roles', [
            'name' => 'Admin Gudang',
            'status' => 1,
            'deleted_at' => null,
        ]);
    }

    public function test_role_name_must_be_unique(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $this->post(route('admin.roles.store'), [
            'name' => 'Admin Gudang',
            'status' => 1,
        ]);

        $response = $this->post(route('admin.roles.store'), [
            'name' => 'Admin Gudang',
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_role_name_is_required(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->post(route('admin.roles.store'), [
            'name' => '',
            'status' => 1,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_superadmin_can_view_edit_form(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Editor']);

        $response = $this->get(route('admin.roles.edit', $role));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.form');
        $response->assertSee('Editor');
    }

    public function test_superadmin_can_update_role(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Editor', 'status' => 1]);

        $response = $this->put(route('admin.roles.update', $role), [
            'name' => 'Editor Senior',
            'status' => 0,
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $role->refresh();
        $this->assertEquals('Editor Senior', $role->name);
        $this->assertFalse($role->status);
    }

    public function test_cannot_delete_role_with_active_admins(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Staff']);

        Admin::create([
            'role_id' => $role->id,
            'name' => 'Staff Admin',
            'email' => 'staff@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);

        $response = $this->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHasErrors();

        $this->assertDatabaseHas('roles', ['id' => $role->id, 'deleted_at' => null]);
    }

    public function test_superadmin_can_delete_role_without_admins(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Temporary Role']);

        $response = $this->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted($role);
    }

    public function test_superadmin_can_view_authorization_matrix(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $response = $this->get(route('admin.roles.authorizations.edit', $this->adminRole));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.authorizations');
        $response->assertSee($this->adminRole->name);
    }

    public function test_superadmin_can_update_authorizations(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Limited Role', 'status' => 1]);
        $modules = Module::take(2)->get();
        $authTypes = AuthorizationType::all();

        $authData = [];
        foreach ($modules as $module) {
            foreach ($authTypes as $type) {
                $authData[$module->id][] = $type->id;
            }
        }

        $response = $this->put(route('admin.roles.authorizations.update', $role), [
            'auth' => $authData,
        ]);

        $response->assertRedirect(route('admin.roles.authorizations.edit', $role));
        $response->assertSessionHas('success');

        $this->assertEquals(
            $modules->count() * $authTypes->count(),
            Authorization::where('role_id', $role->id)->count()
        );
    }

    public function test_superadmin_can_clear_all_authorizations(): void
    {
        $this->actingAs($this->superadmin, 'admin');

        $role = Role::factory()->create(['name' => 'Empty Role', 'status' => 1]);

        $response = $this->put(route('admin.roles.authorizations.update', $role), [
            'auth' => [],
        ]);

        $response->assertRedirect(route('admin.roles.authorizations.edit', $role));

        $this->assertEquals(0, Authorization::where('role_id', $role->id)->count());
    }

    public function test_non_superadmin_cannot_access_role_list(): void
    {
        $this->actingAs($this->regularAdmin, 'admin');

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(403);
    }

    public function test_non_superadmin_cannot_access_authorization_matrix(): void
    {
        $this->actingAs($this->regularAdmin, 'admin');

        $response = $this->get(route('admin.roles.authorizations.edit', $this->adminRole));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_role_routes(): void
    {
        $response = $this->get(route('admin.roles.index'));

        $response->assertRedirect(route('admin.login'));
    }
}
