@extends('admin.layouts.app')

@section('title', 'Pesanan ' . $order->order_number . ' — EssenseLuxe Admin')
@section('page_title', 'Pesanan ' . $order->order_number)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            @php
                $statusLabels = [
                    1 => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
                    2 => ['label' => 'Paid', 'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
                    3 => ['label' => 'Processing', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-200'],
                    4 => ['label' => 'Shipped', 'class' => 'bg-purple-50 text-purple-600 border-purple-200'],
                    5 => ['label' => 'Delivered', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                    6 => ['label' => 'Cancelled', 'class' => 'bg-red-50 text-red-600 border-red-200'],
                ];
                $s = $statusLabels[$order->status] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'];
            @endphp
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border {{ $s['class'] }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                {{ $s['label'] }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            @if (in_array($order->status, [1, 3]))
                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan {{ $order->order_number }}? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all duration-200">
                        <i class="bi bi-x-circle"></i>
                        Batalkan Pesanan
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pesanan</p>
            <p class="text-2xl font-bold text-slate-700">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Subtotal</p>
            <p class="text-lg font-semibold text-slate-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Poin</p>
            <p class="text-sm text-slate-700">Earned: <span class="font-semibold text-emerald-600">{{ $order->point_earned ?? 0 }}</span></p>
            <p class="text-sm text-slate-700">Redeemed: <span class="font-semibold text-amber-600">{{ $order->point_redeemed ?? 0 }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Informasi Pembeli</p>
            <div class="space-y-1.5 text-sm">
                <p><span class="text-slate-500">Nama:</span> <span class="text-slate-700">{{ $order->buyer_name ?: ($order->user->name ?? '-') }}</span></p>
                <p><span class="text-slate-500">Email:</span> <span class="text-slate-700">{{ $order->buyer_email ?: ($order->user->email ?? '-') }}</span></p>
                <p><span class="text-slate-500">Telepon:</span> <span class="text-slate-700">{{ $order->buyer_phone ?: ($order->user->phone ?? '-') }}</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Pengiriman</p>
            <div class="space-y-1.5 text-sm">
                <p><span class="text-slate-500">Alamat:</span> <span class="text-slate-700">{{ $order->shipping_address ?: '-' }}</span></p>
                <p><span class="text-slate-500">Catatan:</span> <span class="text-slate-700">{{ $order->shipping_note ?: '-' }}</span></p>
                @if ($order->shipments->isNotEmpty())
                    @php $s = $order->shipments->first(); @endphp
                    <p><span class="text-slate-500">Kurir:</span> <span class="text-slate-700">{{ $s->courier_name ?: '-' }}</span></p>
                    <p><span class="text-slate-500">Resi:</span> <span class="text-slate-700 font-mono">{{ $s->tracking_number ?: '-' }}</span></p>
                @else
                    <p class="text-slate-400 italic">Belum ada shipment.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700">Item Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Produk</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Varian</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Harga</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600">Qty</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($order->orderItems as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-700">{{ $item->product_name }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $item->variant_label }}</td>
                            <td class="px-5 py-4 text-right text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center text-slate-700">{{ $item->quantity }}</td>
                            <td class="px-5 py-4 text-right font-medium text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Pembayaran</p>
            @if ($order->payments->isNotEmpty())
                @foreach ($order->payments as $payment)
                    <div class="text-sm space-y-1.5 p-3 bg-slate-50 rounded-lg mb-2">
                        <p><span class="text-slate-500">Akun:</span> <span class="text-slate-700">{{ $payment->paymentAccount?->bank_name }} — {{ $payment->paymentAccount?->account_number }}</span></p>
                        <p><span class="text-slate-500">Total:</span> <span class="text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></p>
                        <p><span class="text-slate-500">Status:</span>
                            @php
                                $payStatus = match($payment->status) {
                                    1 => 'Pending',
                                    2 => 'Paid',
                                    3 => 'Failed',
                                    4 => 'Expired',
                                    default => 'Unknown',
                                };
                            @endphp
                            <span class="font-medium">{{ $payStatus }}</span>
                        </p>
                        @if ($payment->approvedBy)
                            <p><span class="text-slate-500">Disetujui oleh:</span> <span class="text-slate-700">{{ $payment->approvedBy->name }}</span></p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-sm text-slate-400 italic">Belum ada pembayaran.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Riwayat Poin</p>
            @if ($order->pointTransactions->isNotEmpty())
                <div class="text-sm space-y-1.5">
                    @foreach ($order->pointTransactions as $pt)
                        <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg">
                            <div>
                                <span class="text-slate-700">{{ $pt->description ?: 'Transaksi poin' }}</span>
                                <span class="block text-xs text-slate-400">{{ $pt->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <span class="font-semibold {{ $pt->type == 1 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $pt->type == 1 ? '+' : '-' }}{{ $pt->amount }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 italic">Belum ada transaksi poin.</p>
            @endif
        </div>
    </div>

    @if ($order->note)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">Catatan</p>
            <p class="text-sm text-slate-700">{{ $order->note }}</p>
        </div>
    @endif
@endsection
