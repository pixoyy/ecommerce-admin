<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->back()->with('error', 'Email atau password salah.')
                ->withInput($request->except('password'));
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();

        if (!$admin->status) {
            Auth::guard('admin')->logout();

            return redirect()->back()->with('error', 'Akun Anda tidak aktif. Hubungi Superadmin.');
        }

        Admin::where('id', $admin->id)->update(['last_login' => now()]);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah logout.');
    }
}
