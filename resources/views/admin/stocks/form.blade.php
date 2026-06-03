@extends('admin.layouts.app')

@section('title', 'Set Stok — EssenseLuxe Admin')
@section('page_title', 'Set Stok')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">Atur Stok Varian</h2>
                <p class="text-sm text-slate-500 mt-0.5">Set atau update stok varian di gudang.</p>
            </div>
            <form action="{{ route('admin.stocks.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-slate-700 mb-1.5">Gudang</label>
                    <select name="warehouse_id" id="warehouse_id" required
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('warehouse_id') border-red-400 @enderror">
                        <option value="">— Pilih Gudang —</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="product_variant_id" class="block text-sm font-medium text-slate-700 mb-1.5">Varian Produk</label>
                    <select name="product_variant_id" id="product_variant_id" required
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('product_variant_id') border-red-400 @enderror">
                        <option value="">— Pilih Produk & Varian —</option>
                        @foreach ($products as $product)
                            <optgroup label="{{ $product->name }}">
                                @foreach ($product->productVariants as $variant)
                                    <option value="{{ $variant->id }}" {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->label }} (SKU: {{ $variant->sku }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('product_variant_id')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1.5">Quantity</label>
                    <input type="number" name="quantity" id="quantity"
                           value="{{ old('quantity', 0) }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('quantity') border-red-400 @enderror"
                           min="0" required>
                    @error('quantity')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        <i class="bi bi-check2 mr-1"></i> Save Stock
                    </button>
                    <a href="{{ route('admin.stocks.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
