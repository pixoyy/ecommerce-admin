@extends('admin.layouts.app')

@section('title', $user->name . ' — Detail Customer — EssenseLuxe Admin')
@section('page_title', 'Detail Customer: ' . $user->name)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Informasi lengkap customer.</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 px-6 py-5 mb-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-400 to-cyan-500 flex items-center justify-center text-lg font-semibold text-white flex-shrink-0 shadow-sm">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <div class="text-base font-semibold text-slate-700">{{ $user->name }}</div>
                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                <div class="flex items-center gap-2 mt-1">
                    @if ($user->trashed())
                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-medium px-2 py-0.5 rounded-full border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Nonaktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-medium px-2 py-0.5 rounded-full border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @endif
                    @if ($user->phone)
                        <span class="text-xs text-slate-400">{{ $user->phone }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-5">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $ordersCount }}</div>
                    <div class="text-xs text-slate-500">Total Pesanan</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="bi bi-star"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $pointBalance }}</div>
                    <div class="text-xs text-slate-500">Total Poin</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ $user->created_at->format('d M Y') }}</div>
                    <div class="text-xs text-slate-500">Bergabung</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-700">Informasi Profile</h3>
        </div>
        <div class="p-6">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="py-3 pr-8 text-slate-500 w-40">Nama</td>
                        <td class="py-3 text-slate-700 font-medium">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-8 text-slate-500">Email</td>
                        <td class="py-3 text-slate-700">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-8 text-slate-500">Phone</td>
                        <td class="py-3 text-slate-700">{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-8 text-slate-500">Email Verified</td>
                        <td class="py-3 text-slate-700">
                            @if ($user->email_verified_at)
                                {{ $user->email_verified_at->format('d M Y H:i') }}
                            @else
                                <span class="text-slate-400">Belum verifikasi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-8 text-slate-500">Bergabung</td>
                        <td class="py-3 text-slate-700">{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-8 text-slate-500">Terakhir Update</td>
                        <td class="py-3 text-slate-700">{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
