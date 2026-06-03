@extends('admin.layouts.app')

@section('title', 'Buat Pengiriman — EssenseLuxe Admin')
@section('page_title', 'Buat Pengiriman')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">Buat Pengiriman Baru</h2>
                <p class="text-sm text-slate-500 mt-0.5">Pesanan: <span class="font-mono font-medium">{{ $order->order_number }}</span></p>
            </div>

            <form action="{{ route('admin.orders.shipments.store', $order) }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="courier_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kurir</label>
                    <input type="text" name="courier_name" id="courier_name"
                           value="{{ old('courier_name') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                           placeholder="Mis: JNE, J&T, SiCepat" required>
                    @error('courier_name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="shipping_cost" class="block text-sm font-medium text-slate-700 mb-1.5">Biaya Kirim (Rp)</label>
                    <input type="number" name="shipping_cost" id="shipping_cost"
                           value="{{ old('shipping_cost', $order->shipping_cost) }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                           min="0" placeholder="0" required>
                    @error('shipping_cost')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600 space-y-1">
                    <p><span class="text-slate-500">Gudang:</span> {{ $order->warehouse->name ?? '-' }}</p>
                    <p><span class="text-slate-500">Alamat Pengiriman:</span> {{ $order->shipping_address ?: '-' }}</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        <i class="bi bi-truck"></i>
                        Buat Pengiriman
                    </button>
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
