@extends('admin.layouts.app')

@section('title', $promotion->name . ' — EssenseLuxe Admin')
@section('page_title', $promotion->name)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Detail promosi dan daftar item.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.promotions.edit', $promotion) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                <i class="bi bi-pencil-square"></i>
                Edit
            </a>
            <a href="{{ route('admin.promotions.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Periode</p>
            <p class="text-sm text-slate-700">
                {{ $promotion->start_at->format('d M Y H:i') }} —
                {{ $promotion->end_at->format('d M Y H:i') }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Status</p>
            @php
                $now = now();
                $isActive = $promotion->is_active && $now >= $promotion->start_at && $now <= $promotion->end_at;
            @endphp
            @if ($isActive)
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Active
                </span>
            @elseif ($now > $promotion->end_at)
                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Expired
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Scheduled
                </span>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Jumlah Item</p>
            <p class="text-2xl font-bold text-slate-700">{{ $promotion->promotionItems->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-700">Daftar Item Promosi</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Produk</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Varian</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Harga Normal</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Harga Promo</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($promotion->promotionItems as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $item->productVariant->product->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $item->productVariant->label ?? '-' }}</td>
                            <td class="px-5 py-4 text-right text-slate-600">Rp {{ number_format($item->productVariant->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-indigo-600">Rp {{ number_format($item->override_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('admin.promotions.items.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus item ini dari promosi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="bi bi-trash3"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="bi bi-box text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada item dalam promosi ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mt-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700">Tambah Item Baru</h3>
        </div>
        <form action="{{ route('admin.promotions.items.store', $promotion) }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-3 gap-5">
                <div>
                    <label for="product_variant_id" class="block text-sm font-medium text-slate-700 mb-1.5">Varian Produk</label>
                    <select name="product_variant_id" id="product_variant_id" required
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('product_variant_id') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="">Pilih Varian</option>
                        @foreach ($products as $product)
                            <optgroup label="{{ $product->name }}">
                                @foreach ($product->productVariants as $variant)
                                    <option value="{{ $variant->id }}" {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->label }} — Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('product_variant_id')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="override_price" class="block text-sm font-medium text-slate-700 mb-1.5">Harga Promo (Rp)</label>
                    <input type="number"
                           name="override_price"
                           id="override_price"
                           value="{{ old('override_price') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('override_price') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="100000"
                           min="0"
                           required>
                    @error('override_price')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Item
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
