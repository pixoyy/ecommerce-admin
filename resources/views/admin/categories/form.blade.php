@extends('admin.layouts.app')

@section('title', $category ? 'Edit Kategori — EssenseLuxe Admin' : 'Create Kategori — EssenseLuxe Admin')
@section('page_title', $category ? 'Edit Kategori' : 'Create Kategori')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $category ? 'Edit Kategori' : 'Buat Kategori Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $category ? 'Ubah data kategori.' : 'Buat kategori produk baru.' }}</p>
            </div>

            <form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6 space-y-5">
                @csrf
                @if ($category)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kategori</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Masukkan nama kategori"
                           required autofocus>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-1.5">Slug <span class="text-slate-400 font-normal">(biarkan kosong untuk auto-generate)</span></label>
                    <input type="text"
                           name="slug"
                           id="slug"
                           value="{{ old('slug', $category->slug ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('slug') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="auto-generate">
                    @error('slug')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-slate-700 mb-1.5">Gambar <span class="text-slate-400 font-normal">(opsional, maks 2MB)</span></label>
                    @if ($category && $category->image)
                        <div class="mb-3">
                            <img src="{{ $category->image->link }}" alt="{{ $category->name }}"
                                 class="w-24 h-24 rounded-xl object-cover border border-slate-200">
                        </div>
                    @endif
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 @error('image') border-red-400 @enderror">
                    @error('image')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-slate-700 mb-1.5">Urutan</label>
                        <input type="number"
                               name="sort_order"
                               id="sort_order"
                               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('sort_order') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               min="0"
                               required>
                        @error('sort_order')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                        <select name="is_active"
                                id="is_active"
                                class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('is_active') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                            <option value="1" {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $category ? 'Update Category' : 'Save Category' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
