@extends('admin.layouts.app')

@section('title', $product->name . ' — EssenseLuxe Admin')
@section('page_title', $product->name)

@section('content')
    <div class="mb-5">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke daftar produk
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-700">Informasi Produk</h2>
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                        <i class="bi bi-pencil-square"></i>
                        Edit
                    </a>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Brand</label>
                            <p class="text-sm text-slate-700 mt-1">{{ $product->brand->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Kategori</label>
                            <p class="text-sm text-slate-700 mt-1">{{ $product->category->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Slug</label>
                            <p class="text-sm text-slate-700 mt-1">{{ $product->slug }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Gender</label>
                            <p class="text-sm text-slate-700 mt-1">
                                @if ($product->gender == 1) Wanita
                                @elseif ($product->gender == 2) Pria
                                @else Unisex
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Status</label>
                            <p class="mt-1">
                                @if ($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Inactive
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Dibuat</label>
                            <p class="text-sm text-slate-700 mt-1">{{ $product->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if ($product->description)
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Deskripsi</label>
                            <p class="text-sm text-slate-700 mt-1 whitespace-pre-wrap">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if ($product->features)
                        <div>
                            <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Fitur / Keunggulan</label>
                            <p class="text-sm text-slate-700 mt-1 whitespace-pre-wrap">{{ $product->features }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-700">Varian Produk</h2>
                    <a href="{{ route('admin.products.variants.index', $product) }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Semua <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if ($product->productVariants->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-6">Belum ada varian.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="text-left px-3 py-3 font-semibold text-slate-600">Label</th>
                                        <th class="text-left px-3 py-3 font-semibold text-slate-600">SKU</th>
                                        <th class="text-left px-3 py-3 font-semibold text-slate-600">Harga</th>
                                        <th class="text-left px-3 py-3 font-semibold text-slate-600">Status</th>
                                        <th class="text-center px-3 py-3 font-semibold text-slate-600 w-40">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($product->productVariants as $variant)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-3 py-3 font-medium text-slate-700">{{ $variant->label }}</td>
                                            <td class="px-3 py-3 text-slate-600 font-mono text-xs">{{ $variant->sku }}</td>
                                            <td class="px-3 py-3 text-slate-700">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                            <td class="px-3 py-3">
                                                @if ($variant->is_active)
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full border border-red-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    @if ($variant->order_items_count > 0)
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed"
                                                              title="Varian sudah memiliki pesanan">
                                                            <i class="bi bi-trash3"></i>
                                                        </span>
                                                    @else
                                                        <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST"
                                                              onsubmit="return confirm('Hapus varian {{ $variant->label }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                                                <i class="bi bi-trash3"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3">Tambah Varian Baru</h3>
                        <form action="{{ route('admin.products.variants.store', $product) }}" method="POST" class="grid grid-cols-5 gap-3">
                            @csrf
                            <div>
                                <input type="text" name="label" placeholder="Label (e.g. 50ml)"
                                       class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                                       required>
                            </div>
                            <div>
                                <input type="text" name="sku" placeholder="SKU"
                                       class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                                       required>
                            </div>
                            <div>
                                <input type="number" name="price" placeholder="Harga" step="0.01" min="0.01"
                                       class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                                       required>
                            </div>
                            <div>
                                <select name="is_active"
                                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit"
                                        class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                                    <i class="bi bi-plus-lg mr-1"></i>
                                    Tambah
                                </button>
                            </div>
                        </form>
                        @error('label')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('sku')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('price')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-base font-semibold text-slate-700">Thumbnail</h2>
                </div>
                <div class="p-6">
                    @if ($product->thumbnailImage)
                        <img src="{{ Storage::url($product->thumbnailImage->link) }}" alt="{{ $product->name }}"
                             class="w-full rounded-xl border border-slate-200">
                    @else
                        <div class="w-full aspect-square rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                            <i class="bi bi-image text-4xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            @include('admin.products.gallery')
        </div>
    </div>
@endsection
