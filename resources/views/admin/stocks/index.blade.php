@extends('admin.layouts.app')

@section('title', 'Stok — EssenseLuxe Admin')
@section('page_title', 'Stok')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Manajemen stok per gudang.</p>
        </div>
        <a href="{{ route('admin.stocks.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Set Stock
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.stocks.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="w-56">
                <label for="warehouse_id" class="block text-xs font-medium text-slate-500 mb-1">Gudang</label>
                <select name="warehouse_id" id="warehouse_id"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua Gudang</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-slate-500 mb-1">Cari Produk / SKU</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="search"
                           class="flex-1 px-3 py-2 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           value="{{ request('search') }}" placeholder="Nama produk atau SKU...">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    <i class="bi bi-funnel mr-1"></i> Filter
                </button>
                @if (request()->anyFilled(['warehouse_id', 'search']))
                    <a href="{{ route('admin.stocks.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Produk</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Varian</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">SKU</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Gudang</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($stocks as $stock)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $stocks->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $stock->productVariant?->product?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $stock->productVariant?->label ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ $stock->productVariant?->sku ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $stock->warehouse?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-right font-semibold {{ $stock->quantity > 0 ? 'text-slate-700' : 'text-red-500' }}">
                                {{ number_format($stock->quantity) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="bi bi-boxes text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada data stok.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($stocks->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $stocks->links() }}</div>
        @endif
    </div>
@endsection
