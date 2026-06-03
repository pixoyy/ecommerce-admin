@extends('admin.layouts.app')

@section('title', 'Pesanan — EssenseLuxe Admin')
@section('page_title', 'Pesanan')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Kelola pesanan pelanggan.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-slate-500 mb-1">Cari No. Pesanan</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="search"
                           class="flex-1 px-3 py-2 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           value="{{ request('search') }}"
                           placeholder="Ketik no. pesanan...">
                </div>
            </div>

            <div class="w-44">
                <label for="status" class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="status" id="status"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Paid</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Processing</option>
                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Shipped</option>
                    <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Delivered</option>
                    <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="w-44">
                <label for="date_from" class="block text-xs font-medium text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>

            <div class="w-44">
                <label for="date_to" class="block text-xs font-medium text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    <i class="bi bi-funnel mr-1"></i>
                    Filter
                </button>
                @if (request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.orders.index') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Reset
                    </a>
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
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Pesanan</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Pembeli</th>
                        <th class="text-right px-5 py-4 font-semibold text-slate-600">Total</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Tanggal</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $orders->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-medium text-slate-700">{{ $order->order_number }}</span>
                                <span class="block text-xs text-slate-400">{{ $order->orderItems->count() }} item</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-slate-700">{{ $order->buyer_name ?: ($order->user->name ?? '-') }}</span>
                            </td>
                            <td class="px-5 py-4 text-right font-medium text-slate-700">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
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
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full border {{ $s['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="bi bi-box-seam text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada pesanan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
