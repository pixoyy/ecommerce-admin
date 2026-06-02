@extends('admin.layouts.app')

@section('title', $admin ? 'Edit Admin — EssenseLuxe Admin' : 'Create Admin — EssenseLuxe Admin')
@section('page_title', $admin ? 'Edit Admin' : 'Create Admin')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $admin ? 'Edit Admin' : 'Buat Admin Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $admin ? 'Ubah data admin.' : 'Buat akun admin baru.' }}</p>
            </div>

            <form action="{{ $admin ? route('admin.admins.update', $admin) : route('admin.admins.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($admin)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $admin->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Masukkan nama admin"
                           required autofocus>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $admin->email ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="admin@essenseluxe.com"
                           required>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                @if (!$admin)
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
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
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                               placeholder="Ulangi password"
                               required>
                    </div>
                @endif

                <div>
                    <label for="role_id" class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                    <select name="role_id"
                            id="role_id"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('role_id') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="">— Pilih Role —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $admin->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status"
                            id="status"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('status') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="1" {{ old('status', $admin->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $admin->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $admin ? 'Update Admin' : 'Save Admin' }}
                    </button>
                    <a href="{{ route('admin.admins.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
