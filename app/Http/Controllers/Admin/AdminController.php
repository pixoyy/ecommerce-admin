<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Admin::with('role');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admins = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view('admin.admins.index', compact('admins', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view('admin.admins.form', ['admin' => null, 'roles' => $roles]);
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit(Admin $admin): View
    {
        $roles = Role::where('status', 1)->orderBy('name')->get();

        return view('admin.admins.form', compact('admin', 'roles'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {
        $admin->update($request->validated());

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.admins.index')->withErrors(
                'Anda tidak dapat menghapus akun Anda sendiri.'
            );
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function editPassword(Admin $admin): View
    {
        return view('admin.admins.change-password', compact('admin'));
    }

    public function updatePassword(Request $request, Admin $admin): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $admin->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.admins.edit', $admin)
            ->with('success', 'Password berhasil direset.');
    }
}
