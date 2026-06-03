@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran — EssenseLuxe Admin')
@section('page_title', 'Detail Pembayaran')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <span class="font-mono text-sm text-slate-500">{{ $payment->order->order_number ?? '-' }}</span>
            @php
                $statusLabels = [
                    1 => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
                    2 => ['label' => 'Approved', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                    3 => ['label' => 'Rejected', 'class' => 'bg-red-50 text-red-600 border-red-200'],
                    4 => ['label' => 'Expired', 'class' => 'bg-slate-50 text-slate-500 border-slate-200'],
                ];
                $s = $statusLabels[$payment->status] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'];
            @endphp
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border {{ $s['class'] }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                {{ $s['label'] }}
            </span>
        </div>
        <div>
            <a href="{{ route('admin.payments.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Amount</p>
            <p class="text-2xl font-bold text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Rekening Tujuan</p>
            <p class="text-sm font-semibold text-slate-700">{{ $payment->paymentAccount?->bank_name }}</p>
            <p class="text-xs text-slate-500 font-mono">{{ $payment->paymentAccount?->account_number }}</p>
            <p class="text-xs text-slate-500">{{ $payment->paymentAccount?->account_name }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Tanggal</p>
            <p class="text-sm text-slate-700">{{ $payment->created_at->format('d M Y H:i') }}</p>
            @if ($payment->approved_at)
                <p class="text-xs text-emerald-600 mt-1">Disetujui: {{ $payment->approved_at->format('d M Y H:i') }}</p>
            @endif
            @if ($payment->rejected_at)
                <p class="text-xs text-red-600 mt-1">Ditolak: {{ $payment->rejected_at->format('d M Y H:i') }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Informasi Pesanan</p>
            <div class="space-y-1.5 text-sm">
                <p><span class="text-slate-500">No. Pesanan:</span> <span class="text-slate-700 font-mono">{{ $payment->order->order_number ?? '-' }}</span></p>
                <p><span class="text-slate-500">Pembeli:</span> <span class="text-slate-700">{{ $payment->order->buyer_name ?: ($payment->order->user->name ?? '-') }}</span></p>
                <p><span class="text-slate-500">Total Pesanan:</span> <span class="text-slate-700">Rp {{ number_format($payment->order->total, 0, ',', '.') }}</span></p>
                <p><span class="text-slate-500">Status Pesanan:</span>
                    @php
                        $orderStatus = match($payment->order->status) {
                            1 => 'Pending', 2 => 'Paid', 3 => 'Processing',
                            4 => 'Shipped', 5 => 'Delivered', 6 => 'Cancelled',
                            default => 'Unknown',
                        };
                    @endphp
                    <span class="font-medium">{{ $orderStatus }}</span>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-3">Bukti Transfer</p>
            @if ($payment->proofPath)
                <a href="{{ $payment->proofPath->link }}" target="_blank"
                   class="block border border-slate-200 rounded-xl overflow-hidden hover:ring-2 hover:ring-indigo-500/20 transition-all">
                    <img src="{{ $payment->proofPath->link }}"
                         alt="Bukti Transfer"
                         class="w-full h-48 object-contain bg-slate-50">
                </a>
                <p class="text-xs text-slate-400 mt-2">Klik gambar untuk melihat ukuran penuh.</p>
            @else
                <div class="flex items-center justify-center h-48 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-center">
                        <i class="bi bi-image text-3xl text-slate-300 block mb-2"></i>
                        <p class="text-sm text-slate-400">Tidak ada bukti transfer</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($payment->status === 1)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-4">Aksi</p>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST"
                      onsubmit="return confirm('Setujui pembayaran ini? Pesanan akan diproses.')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-200">
                        <i class="bi bi-check-circle"></i>
                        Setujui Pembayaran
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all duration-200">
                    <i class="bi bi-x-circle"></i>
                    Tolak
                </button>
            </div>
        </div>

        <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-700">Tolak Pembayaran</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Berikan alasan mengapa pembayaran ditolak.</p>
                </div>
                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="rejected_reason" class="block text-sm font-medium text-slate-700 mb-1.5">Alasan Penolakan</label>
                        <textarea name="rejected_reason" id="rejected_reason" rows="4" required minlength="10"
                                  class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                  placeholder="Jelaskan alasan penolakan (min. 10 karakter)"></textarea>
                        <p class="text-xs text-slate-400 mt-1">Minimal 10 karakter.</p>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all duration-200">
                            <i class="bi bi-x-circle"></i>
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($payment->rejected_reason)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">Alasan Penolakan</p>
            <p class="text-sm text-slate-700">{{ $payment->rejected_reason }}</p>
            @if ($payment->rejectedBy)
                <p class="text-xs text-slate-400 mt-2">Ditolak oleh: {{ $payment->rejectedBy->name }}</p>
            @endif
        </div>
    @endif
@endsection
