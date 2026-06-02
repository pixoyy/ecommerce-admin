@extends('admin.layouts.app')

@section('title', $brand ? 'Edit Merek — EssenseLuxe Admin' : 'Create Merek — EssenseLuxe Admin')
@section('page_title', $brand ? 'Edit Merek' : 'Create Merek')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $brand ? 'Edit Merek' : 'Buat Merek Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $brand ? 'Ubah nama merek.' : 'Buat merek produk baru.' }}</p>
            </div>

            <form action="{{ $brand ? route('admin.brands.update', $brand) : route('admin.brands.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($brand)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Merek</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $brand->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Masukkan nama merek"
                           required autofocus>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $brand ? 'Update Brand' : 'Save Brand' }}
                    </button>
                    <a href="{{ route('admin.brands.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
