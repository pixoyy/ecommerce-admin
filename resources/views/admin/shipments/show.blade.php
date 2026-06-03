@extends('admin.layouts.app')

@section('title', 'Pengiriman — EssenseLuxe Admin')
@section('page_title', 'Pengiriman ' . ($shipment->order->order_number ?? ''))

@section('content')
    @php
        $statusLabels = [
            1 => ['label' => 'Preparing', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
            2 => ['label' => 'Shipped', 'class' => 'bg-indigo-50 text-indigo-600 border-indigo-200'],
            3 => ['label' => 'In Transit', 'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
            4 => ['label' => 'Delivered', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
        ];
        $s = $statusLabels[$shipment->status] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'];
    @endphp

    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border {{ $s['class'] }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                {{ $s['label'] }}
            </span>
            @if ($shipment->tracking_number)
                <span class="font-mono text-xs text-slate-500">Resi: {{ $shipment->tracking_number }}</span>
            @endif
        </div>
        <div>
            <a href="{{ route('admin.orders.show', $shipment->order) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Pesanan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Kurir</p>
            <p class="text-sm font-semibold text-slate-700">{{ $shipment->courier_name ?: '-' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Biaya Kirim</p>
            <p class="text-sm font-semibold text-slate-700">Rp {{ number_format($shipment->shipping_cost, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Gudang</p>
            <p class="text-sm text-slate-700">{{ $shipment->warehouse->name ?? '-' }}</p>
        </div>
    </div>

    @if ($shipment->status < 4)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Update Status Pengiriman</h3>
            @php
                $nextStatus = match($shipment->status) {
                    1 => 2,
                    2 => 3,
                    3 => 4,
                    default => null,
                };
            @endphp
            @if ($nextStatus)
                <form action="{{ route('admin.shipments.update', $shipment) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $nextStatus }}">

                    @if ($nextStatus === 2)
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-slate-700 mb-1.5">Nomor Resi</label>
                            <input type="text" name="tracking_number" id="tracking_number" required
                                   class="block w-full max-w-md px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                   placeholder="Masukkan nomor resi">
                            @error('tracking_number')
                                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 max-w-md">
                        <div>
                            <label for="note" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                            <input type="text" name="note" id="note"
                                   class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                   placeholder="Catatan">
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-slate-700 mb-1.5">Lokasi <span class="text-slate-400">(opsional)</span></label>
                            <input type="text" name="location" id="location"
                                   class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                   placeholder="Kota">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                            <i class="bi bi-arrow-up-circle"></i>
                            Update ke {{ $statusLabels[$nextStatus]['label'] ?? '' }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-700 mb-4">Tracking Log</h3>
        <div class="relative">
            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-200"></div>
            <div class="space-y-6">
                @forelse ($shipment->trackingLogs->sortByDesc('created_at') as $log)
                    @php $ls = $statusLabels[$log->status] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600 border-slate-200']; @endphp
                    <div class="relative pl-14">
                        <div class="absolute left-3.5 top-1 w-4 h-4 rounded-full bg-white border-2 {{ $loop->first ? 'border-indigo-500' : 'border-slate-300' }}"></div>
                        <div>
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full border {{ $ls['class'] }}">
                                {{ $ls['label'] }}
                            </span>
                            <p class="text-sm text-slate-700 mt-1">{{ $log->note ?: 'Tidak ada catatan' }}</p>
                            @if ($log->location)
                                <p class="text-xs text-slate-400"><i class="bi bi-geo-alt"></i> {{ $log->location }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                                <span>{{ $log->created_at->format('d M Y H:i') }}</span>
                                @if ($log->updatedBy)
                                    <span>&middot; {{ $log->updatedBy->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic pl-14">Belum ada tracking log.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
