@extends('admin.layouts.app')

@section('title', 'Reset Password — ' . $admin->name . ' — EssenseLuxe Admin')
@section('page_title', 'Reset Password: ' . $admin->name)

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">Reset Password</h2>
                <p class="text-sm text-slate-500 mt-0.5">Ubah password untuk akun <strong>{{ $admin->name }}</strong> ({{ $admin->email }}).</p>
            </div>

            <form action="{{ route('admin.admins.password.update', $admin) }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                           placeholder="Ulangi password baru"
                           required>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        <i class="bi bi-key mr-1"></i>
                        Update Password
                    </button>
                    <a href="{{ route('admin.admins.edit', $admin) }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
