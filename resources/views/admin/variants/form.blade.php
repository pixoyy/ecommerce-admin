@extends('admin.layouts.app')

@section('title', $variant ? 'Edit Varian — EssenseLuxe Admin' : 'Tambah Varian — EssenseLuxe Admin')
@section('page_title', $variant ? 'Edit Varian' : 'Tambah Varian')

@section('content')
    <div class="mb-5">
        <a href="{{ route('admin.products.show', $product) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke detail produk
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $variant ? 'Edit Varian' : 'Tambah Varian Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $variant ? 'Ubah varian untuk produk' : 'Buat varian baru untuk produk' }}
                    <strong>{{ $product->name }}</strong>.
                </p>
            </div>

            <form action="{{ $variant ? route('admin.products.variants.update', [$product, $variant]) : route('admin.products.variants.store', $product) }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($variant)
                    @method('PUT')
                @endif

                <div>
                    <label for="label" class="block text-sm font-medium text-slate-700 mb-1.5">Label</label>
                    <input type="text"
                           name="label"
                           id="label"
                           value="{{ old('label', $variant->label ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('label') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Contoh: 50ml"
                           required autofocus>
                    @error('label')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sku" class="block text-sm font-medium text-slate-700 mb-1.5">SKU</label>
                    <input type="text"
                           name="sku"
                           id="sku"
                           value="{{ old('sku', $variant->sku ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('sku') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="SKU unik"
                           required>
                    @error('sku')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-1.5">Harga</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400">Rp</span>
                        <input type="number"
                               name="price"
                               id="price"
                               value="{{ old('price', $variant->price ?? '') }}"
                               step="0.01"
                               min="0.01"
                               class="block w-full pl-10 pr-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('price') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               placeholder="0"
                               required>
                    </div>
                    @error('price')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="is_active"
                            id="is_active"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="1" {{ old('is_active', $variant->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $variant->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $variant ? 'Update Varian' : 'Simpan Varian' }}
                    </button>
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
