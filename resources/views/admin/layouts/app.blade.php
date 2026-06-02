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
<body class="font-sans antialiased text-slate-700">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside id="sidebar"
               class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-slate-200 flex flex-col transition-all duration-300 shadow-sm">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-100">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-sky-400 rounded-xl flex items-center justify-center text-lg font-bold text-white shadow-md shadow-indigo-200">
                    E
                </div>
                <div>
                    <div class="text-base font-bold text-slate-800 leading-tight">EssenseLuxe</div>
                    <div class="text-[11px] text-slate-400">Panel Admin</div>
                </div>
            </div>

            @php
                $currentRouteName = Route::currentRouteName() ?? '';
                $navigasiGroup = $sidebarGroups->firstWhere('name', 'Navigasi');
                $groupIcons = [
                    'Navigasi' => 'bi-speedometer2',
                    'Akses' => 'bi-shield-lock',
                    'Pengguna' => 'bi-people',
                    'Kategori' => 'bi-tags',
                    'Merek' => 'bi-bookmark',
                    'Produk' => 'bi-box-seam',
                    'Inventaris' => 'bi-building',
                    'Promosi & Pembayaran' => 'bi-credit-card',
                    'Pesanan' => 'bi-cart-check',
                    'Lainnya' => 'bi-grid-3x3-gap',
                ];
                $moduleIcons = [
                    'admin.dashboard' => 'bi-grid-1x2-fill',
                    'admin.roles' => 'bi-shield-lock',
                    'admin.admins' => 'bi-person-badge',
                    'admin.categories' => 'bi-tags',
                    'admin.brands' => 'bi-bookmark',
                    'admin.products' => 'bi-box',
                    'admin.warehouses' => 'bi-building',
                    'admin.stocks' => 'bi-boxes',
                    'admin.promotions' => 'bi-megaphone',
                    'admin.payment-accounts' => 'bi-credit-card',
                    'admin.orders' => 'bi-cart',
                    'admin.payments' => 'bi-cash-stack',
                    'admin.shipments' => 'bi-truck',
                    'admin.users' => 'bi-people',
                    'admin.points' => 'bi-star',
                    'admin.reviews' => 'bi-chat-square-text',
                    'admin.file-storages' => 'bi-folder',
                ];
            @endphp

            <nav id="sidebarNav" class="flex-1 overflow-y-auto py-2 px-2 space-y-0.5">
                @if ($navigasiGroup)
                    @php
                        $dashboardModule = $navigasiGroup->modules->firstWhere('route', 'admin.dashboard');
                        $dashboardIsActive = $currentRouteName === 'admin.dashboard';
                    @endphp

                    @if ($dashboardModule)
                        <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-400 px-3 pt-4 pb-1.5">Navigasi</div>
                        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
                           @class(['sidebar-module-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm relative',
                                   'active bg-indigo-600 text-white shadow-md shadow-indigo-200' => $dashboardIsActive,
                                   'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' => !$dashboardIsActive])>
                            <span @class(['w-6 h-6 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                                         'bg-white/20' => $dashboardIsActive,
                                         'bg-slate-100 text-slate-500' => !$dashboardIsActive])>
                                <i class="bi bi-grid-1x2-fill"></i>
                            </span>
                            <span class="font-medium">{{ $dashboardModule->name }}</span>
                        </a>
                    @endif
                @endif

                @foreach ($sidebarGroups->reject(fn ($group) => $group->name === 'Navigasi') as $key => $group)
                    @php
                        $modules = $group->modules->filter(fn ($module) => $module->is_shown)->values();
                        $groupIsActive = $modules->contains(function ($module) use ($currentRouteName) {
                            if ($currentRouteName === $module->route) return true;
                            $routePrefix = (string) str($module->route)->beforeLast('.');
                            return $routePrefix !== '' && str($currentRouteName)->startsWith($routePrefix . '.');
                        });
                        $groupIcon = $group->icon ?: ($groupIcons[$group->name] ?? 'bi-folder2-open');
                        $collapseId = 'sidebar-group-' . $key;
                    @endphp

                    @continue($modules->isEmpty())

                    <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 px-3 pt-4 pb-1.5">{{ $group->name }}</div>

                    <button type="button"
                       class="sidebar-group-btn flex items-center gap-3 w-[calc(100%-8px)] mx-1 px-3.5 py-2.5 rounded-xl text-sm text-left cursor-pointer relative {{ $groupIsActive ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-700' }} {{ !$groupIsActive ? 'collapsed' : '' }}"
                       data-bs-toggle="collapse"
                       data-bs-target="#{{ $collapseId }}"
                       aria-expanded="{{ $groupIsActive ? 'true' : 'false' }}"
                       aria-controls="{{ $collapseId }}">
                        <span @class(['w-6 h-6 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                                     'bg-indigo-100 text-indigo-600' => $groupIsActive,
                                     'bg-slate-100 text-slate-500' => !$groupIsActive])>
                            <i class="bi {{ $groupIcon }}"></i>
                        </span>
                        <span class="flex-1 min-w-0">{{ $group->name }}</span>
                        <i class="bi bi-chevron-down text-xs chevron-icon {{ $groupIsActive ? 'text-indigo-400' : 'text-slate-400' }}"></i>
                    </button>

                    <div id="{{ $collapseId }}" class="collapse {{ $groupIsActive ? 'show' : '' }}" data-bs-parent="#sidebarNav">
                        <div class="ml-5 mr-2 mb-2 p-1.5 rounded-xl bg-white border border-slate-100 shadow-sm sidebar-submenu-card">
                            @foreach ($modules as $module)
                                @php
                                    $routeExists = Route::has($module->route);
                                    $moduleIcon = $module->icon ?: ($moduleIcons[$module->route] ?? '');
                                    $moduleRoutePrefix = (string) str($module->route)->beforeLast('.');
                                    $moduleIsActive = $currentRouteName === $module->route || ($moduleRoutePrefix !== '' && str($currentRouteName)->startsWith($moduleRoutePrefix . '.'));
                                @endphp

                                <a href="{{ $routeExists ? route($module->route) : '#' }}"
                                   @class(['sidebar-module-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm',
                                           'active bg-indigo-600 text-white font-medium shadow-sm shadow-indigo-200' => $moduleIsActive,
                                           'text-slate-600' => !$moduleIsActive])>
                                    <span @class(['w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0',
                                                 'bg-white/20' => $moduleIsActive,
                                                 'bg-slate-100 text-slate-500' => !$moduleIsActive])>
                                        @if ($moduleIcon)
                                            <i class="bi {{ $moduleIcon }}" style="font-size:12px"></i>
                                        @else
                                            <i class="bi bi-dot"></i>
                                        @endif
                                    </span>
                                    <span class="flex-1 min-w-0">{{ $module->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            {{-- User Profile --}}
            <div class="flex items-center gap-3 px-5 py-4 border-t border-slate-100 mt-auto">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-sm font-semibold text-white flex-shrink-0 shadow-sm">
                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-700 truncate">{{ Auth::guard('admin')->user()->name }}</div>
                    <div class="text-[11px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email }}</div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shadow-sm">
                <h1 class="text-lg font-semibold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-slate-500 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-slate-700 transition-all duration-200 cursor-pointer">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </header>

            {{-- Content --}}
            <div class="flex-1 p-6 bg-slate-50/50">
                @if (session('success'))
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-5">
                        <i class="bi bi-check-circle-fill text-emerald-500"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5">
                        <i class="bi bi-exclamation-circle-fill text-red-500"></i>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/js/app.js')
    @endif
    @stack('scripts')
</body>
</html>
