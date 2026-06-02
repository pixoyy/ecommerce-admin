@extends('admin.layouts.app')

@section('title', $user ? 'Edit Customer — EssenseLuxe Admin' : 'Create Customer — EssenseLuxe Admin')
@section('page_title', $user ? 'Edit Customer' : 'Create Customer')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $user ? 'Edit Customer' : 'Buat Customer Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $user ? 'Ubah data customer.' : 'Daftarkan customer baru.' }}</p>
            </div>

            <form action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($user)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Nama lengkap customer"
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
                           value="{{ old('email', $user->email ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="customer@email.com"
                           required>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">No. Telepon <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           value="{{ old('phone', $user->phone ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('phone') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="0812xxxxxx">
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Password @if ($user)<span class="text-slate-400 font-normal">(biarkan kosong jika tidak diganti)</span>@endif
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="{{ $user ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter' }}"
                           {{ $user ? '' : 'required' }}>
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
                           {{ $user ? '' : 'required' }}>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $user ? 'Update Customer' : 'Save Customer' }}
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
