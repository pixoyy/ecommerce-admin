<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private Admin $activeAdmin;
    private Admin $inactiveAdmin;
    private string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Superadmin', 'status' => 1]);

        $this->activeAdmin = Admin::create([
            'role_id' => $role->id,
            'name' => 'Active Admin',
            'email' => 'active@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 1,
        ]);

        $this->inactiveAdmin = Admin::create([
            'role_id' => $role->id,
            'name' => 'Inactive Admin',
            'email' => 'inactive@essenseluxe.com',
            'password' => bcrypt($this->password),
            'status' => 0,
        ]);
    }

    public function test_guest_can_view_login_form(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
    }

    public function test_guest_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('admin.login'), [
            'email' => $this->activeAdmin->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->activeAdmin, 'admin');
    }

    public function test_guest_cannot_login_with_invalid_password(): void
    {
        $response = $this->post(route('admin.login'), [
            'email' => $this->activeAdmin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest('admin');
    }

    public function test_inactive_admin_cannot_login(): void
    {
        $response = $this->post(route('admin.login'), [
            'email' => $this->inactiveAdmin->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest('admin');
    }

    public function test_soft_deleted_admin_cannot_login(): void
    {
        $this->activeAdmin->delete();

        $response = $this->post(route('admin.login'), [
            'email' => $this->activeAdmin->email,
            'password' => $this->password,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_cannot_access_login(): void
    {
        $this->actingAs($this->activeAdmin, 'admin');

        $response = $this->get(route('admin.login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_logout(): void
    {
        $this->actingAs($this->activeAdmin, 'admin');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('success');
        $this->assertGuest('admin');
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_login_updates_last_login_timestamp(): void
    {
        $this->post(route('admin.login'), [
            'email' => $this->activeAdmin->email,
            'password' => $this->password,
        ]);

        $this->activeAdmin->refresh();
        $this->assertNotNull($this->activeAdmin->last_login);
    }
}
