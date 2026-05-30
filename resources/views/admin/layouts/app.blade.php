<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EssenseLuxe Admin')</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @endif
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <h1 class="text-lg font-bold text-gray-800">EssenseLuxe</h1>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </nav>

            {{-- Sidebar footer --}}
            <div class="p-4 border-t border-gray-200">
                <p class="text-xs text-gray-400">EssenseLuxe v1.0</p>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Navbar --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
                <h2 class="text-lg font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        {{ Auth::guard('admin')->user()->name }}
                    </span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/js/app.js')
    @endif
    @stack('scripts')
</body>
</html>
