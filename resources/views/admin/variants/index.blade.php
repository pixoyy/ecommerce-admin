@extends('admin.layouts.app')

@section('title', 'Varian — ' . $product->name . ' — EssenseLuxe Admin')
@section('page_title', 'Varian: ' . $product->name)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.show', $product) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                <i class="bi bi-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <a href="{{ route('admin.products.variants.create', $product) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Tambah Varian
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Label</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">SKU</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Harga</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($variants as $variant)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $variant->label }}</td>
                            <td class="px-5 py-4 text-slate-600 font-mono text-xs">{{ $variant->sku }}</td>
                            <td class="px-5 py-4 text-slate-700">Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
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
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    @if ($variant->order_items_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed"
                                              title="Varian sudah memiliki pesanan">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </span>
                                    @else
                                        <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST"
                                              onsubmit="return confirm('Hapus varian {{ $variant->label }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                                <i class="bi bi-trash3"></i>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="bi bi-columns-gap text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada varian untuk produk ini.</p>
                                <a href="{{ route('admin.products.variants.create', $product) }}"
                                   class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                                    <i class="bi bi-plus-lg"></i>
                                    Tambah Varian Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
