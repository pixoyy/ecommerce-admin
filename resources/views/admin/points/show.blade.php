@extends('admin.layouts.app')

@section('title', 'Detail Poin — ' . $user->name . ' — EssenseLuxe Admin')
@section('page_title', 'Poin ' . $user->name)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Riwayat transaksi poin {{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.point-transactions.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center">
                <i class="bi bi-star-fill text-2xl text-indigo-500"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Saldo Saat Ini</p>
                <p class="text-3xl font-bold text-slate-700">{{ number_format($balance) }}</p>
                <p class="text-xs text-slate-400">Poin</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700">Riwayat Mutasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Tipe</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Amount</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Deskripsi</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Pesanan</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transactions as $pt)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $transactions->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $typeLabels = [
                                        1 => ['label' => 'Credit', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                                        2 => ['label' => 'Redeem', 'class' => 'bg-red-50 text-red-600 border-red-200'],
                                        3 => ['label' => 'Adjustment', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
                                    ];
                                    $t = $typeLabels[$pt->type] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full border {{ $t['class'] }}">
                                    {{ $t['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold {{ $pt->type == 1 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $pt->type == 1 ? '+' : '-' }}{{ number_format($pt->amount) }}
                            </td>
                            <td class="px-5 py-4 text-slate-600 max-w-xs truncate">{{ $pt->description ?: '-' }}</td>
                            <td class="px-5 py-4">
                                @if ($pt->order)
                                    <span class="font-mono text-xs text-slate-500">{{ $pt->order->order_number }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $pt->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="bi bi-arrow-left-right text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada transaksi poin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
