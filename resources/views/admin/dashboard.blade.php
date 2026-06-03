@extends('admin.layouts.app')

@section('title', 'Dashboard — EssenseLuxe Admin')
@section('page_title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Pesanan Hari Ini</div>
            <div class="text-3xl font-bold">{{ $ordersToday->sum('total') }}</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Total Pendapatan</div>
            <div class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg shadow-purple-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-people"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Total Customer</div>
            <div class="text-3xl font-bold">{{ number_format($totalCustomers) }}</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg shadow-amber-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Produk Aktif</div>
            <div class="text-3xl font-bold">{{ number_format($activeProducts) }}</div>
        </div>
    </div>

    @if ($ordersToday->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach ($orderStatusLabels as $status => $info)
                @php $count = $ordersToday->get($status)->total ?? 0; @endphp
                @if ($count > 0)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border {{ $info['class'] }} border-current">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                        {{ $info['label'] }}: {{ $count }}
                    </span>
                @endif
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <span class="font-semibold text-slate-700">Pesanan Terbaru</span>
                <a href="{{ route('admin.orders.index') }}"
                   class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                    Lihat Semua
                </a>
            </div>
            @if ($recentOrders->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach ($recentOrders as $order)
                        <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <span class="font-mono text-xs font-medium text-slate-700">{{ $order->order_number }}</span>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $order->buyer_name ?: ($order->user->name ?? '-') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-slate-700">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                @php $s = $orderStatusLabels[$order->status] ?? ['label' => 'Unknown', 'class' => 'bg-slate-50 text-slate-600']; @endphp
                                <span class="block text-xs mt-0.5 {{ $s['class'] }} px-2 py-0.5 rounded-full inline-flex items-center gap-1 border border-current">
                                    <span class="w-1 h-1 rounded-full bg-current opacity-50"></span>
                                    {{ $s['label'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center py-12">
                    <i class="bi bi-inbox text-4xl text-slate-300 block mb-4"></i>
                    <p class="text-slate-400 text-sm">Belum ada pesanan</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <span class="font-semibold text-slate-700">Stok Menipis</span>
                <a href="{{ route('admin.stocks.index') }}"
                   class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                    Kelola Stok
                </a>
            </div>
            @if ($lowStock->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach ($lowStock as $stock)
                        <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-slate-700">{{ $stock->productVariant->product->name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $stock->productVariant->label ?? '-' }} | {{ $stock->warehouse->name ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full {{ $stock->quantity == 0 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-amber-50 text-amber-600 border border-amber-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                {{ $stock->quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center py-12">
                    <i class="bi bi-check-circle text-4xl text-emerald-400 block mb-4"></i>
                    <p class="text-slate-400 text-sm">Semua stok aman</p>
                </div>
            @endif
        </div>
    </div>
@endsection
