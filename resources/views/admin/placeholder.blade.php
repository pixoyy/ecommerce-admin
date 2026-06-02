@extends('admin.layouts.app')

@section('page_title', $pageTitle)
@section('title', $pageTitle . ' - EssenseLuxe Admin')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-cone-striped text-xl"></i>
            </div>
            <div class="min-w-0">
                <h2 class="text-xl font-semibold text-slate-800">{{ $pageTitle }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Halaman ini masih placeholder dan sudah disiapkan dari data sidebar.
                </p>
                <div class="mt-5">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
