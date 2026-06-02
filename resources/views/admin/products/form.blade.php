@extends('admin.layouts.app')

@section('title', $product ? 'Edit Produk — EssenseLuxe Admin' : 'Buat Produk — EssenseLuxe Admin')
@section('page_title', $product ? 'Edit Produk' : 'Buat Produk')

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $product ? 'Edit Produk' : 'Buat Produk Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $product ? 'Ubah data produk.' : 'Buat produk baru untuk toko.' }}</p>
            </div>

            <form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6 space-y-5">
                @csrf
                @if ($product)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Produk</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $product->name ?? '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               placeholder="Masukkan nama produk"
                               required autofocus>
                        @error('name')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-slate-700 mb-1.5">Brand</label>
                        <select name="brand_id"
                                id="brand_id"
                                class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('brand_id') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                            <option value="">— Pilih Brand —</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                    <select name="category_id"
                            id="category_id"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('category_id') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="">— Pilih Kategori —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Thumbnail {{ $product ? '(biarkan kosong jika tidak diganti)' : '' }}
                    </label>
                    @if ($product && $product->thumbnailImage)
                        <div class="mb-2">
                            <img src="{{ Storage::url($product->thumbnailImage->link) }}" alt="Current thumbnail"
                                 class="w-32 h-32 object-cover rounded-xl border border-slate-200">
                        </div>
                    @endif
                    <input type="file"
                           name="thumbnail"
                           id="thumbnail"
                           accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('thumbnail') border-red-400 @enderror"
                           {{ $product ? '' : 'required' }}>
                    @error('thumbnail')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('description') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                              placeholder="Deskripsi produk">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="features" class="block text-sm font-medium text-slate-700 mb-1.5">Fitur / Keunggulan</label>
                    <textarea name="features"
                              id="features"
                              rows="4"
                              class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('features') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                              placeholder="Fitur-fitur produk">{{ old('features', $product->features ?? '') }}</textarea>
                    @error('features')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Gender</label>
                    <div class="flex items-center gap-6">
                        @php $gender = old('gender', $product->gender ?? 3); @endphp
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="1" {{ $gender == 1 ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Wanita</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="2" {{ $gender == 2 ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Pria</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="gender" value="3" {{ $gender == 3 ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Unisex</span>
                        </label>
                    </div>
                    @error('gender')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <div class="flex items-center gap-6">
                        @php $isActive = old('is_active', $product->is_active ?? 1); @endphp
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" {{ $isActive == 1 ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Active</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0" {{ $isActive == 0 ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Inactive</span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $product ? 'Update Produk' : 'Simpan Produk' }}
                    </button>
                    <a href="{{ $product ? route('admin.products.show', $product) : route('admin.products.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
