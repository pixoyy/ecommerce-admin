@extends('admin.layouts.app')

@section('title', 'Dashboard — EssenseLuxe Admin')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Pesanan Hari Ini</div>
            <div class="text-3xl font-bold">0</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Pendapatan Bulan Ini</div>
            <div class="text-3xl font-bold">Rp 0</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg shadow-purple-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-people"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Total Customer</div>
            <div class="text-3xl font-bold">0</div>
        </div>
        <div class="rounded-xl p-5 text-white bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg shadow-amber-200">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl mb-3">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="text-sm text-white/80 mb-0.5">Produk Aktif</div>
            <div class="text-3xl font-bold">0</div>
        </div>
    </div>

    {{-- Charts & Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Recent Orders --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <span class="font-semibold text-slate-700">Pesanan Terbaru</span>
                <span class="text-xs font-medium bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">Hari Ini</span>
            </div>
            <div class="p-5 text-center py-12">
                <i class="bi bi-inbox text-4xl text-slate-300 block mb-4"></i>
                <p class="text-slate-400 text-sm">Belum ada pesanan hari ini</p>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <span class="font-semibold text-slate-700">Stok Menipis</span>
                <span class="text-xs font-medium bg-red-50 text-red-600 px-2.5 py-1 rounded-full">Perhatian</span>
            </div>
            <div class="p-5 text-center py-12">
                <i class="bi bi-check-circle text-4xl text-emerald-400 block mb-4"></i>
                <p class="text-slate-400 text-sm">Semua stok dalam kondisi aman</p>
            </div>
        </div>
    </div>
@endsection
