@extends('admin.layouts.app')

@section('title', $role ? 'Edit Role — EssenseLuxe Admin' : 'Create Role — EssenseLuxe Admin')
@section('page_title', $role ? 'Edit Role' : 'Create Role')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $role ? 'Edit Role' : 'Buat Role Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $role ? 'Ubah nama atau status role.' : 'Buat role baru untuk admin panel.' }}</p>
            </div>

            <form action="{{ $role ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($role)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Role Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $role->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Masukkan nama role"
                           required autofocus>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status"
                            id="status"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('status') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="1" {{ old('status', $role->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $role->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $role ? 'Update Role' : 'Save Role' }}
                    </button>
                    <a href="{{ route('admin.roles.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
