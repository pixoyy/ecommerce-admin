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
                <div
                    class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-sky-400 rounded-xl flex items-center justify-center text-lg font-bold text-white shadow-md shadow-indigo-200">
                    E
                </div>
                <div>
                    <div class="text-base font-bold text-slate-800 leading-tight">EssenseLuxe</div>
                    <div class="text-[11px] text-slate-400">Panel Admin</div>
                </div>
            </div>

            @php
                $currentRouteName = Route::currentRouteName() ?? '';

                $resolveSidebarRoute = function (?string $route): ?string {
                    if (!$route) {
                        return null;
                    }

                    if (Route::has($route)) {
                        return $route;
                    }

                    if (Route::has($route . '.index')) {
                        return $route . '.index';
                    }

                    return null;
                };

                $normalizeIcon = function (?string $icon, string $default = 'folder2-open'): string {
                    if (!$icon) {
                        return 'bi-' . $default;
                    }

                    return str_starts_with($icon, 'bi-') ? $icon : 'bi-' . $icon;
                };

                $allModules = $sidebarGroups->flatMap(fn($group) => $group->modules);

                $activeModule = $allModules->first(function ($module) use ($currentRouteName) {
                    return $currentRouteName === $module->route ||
                        $currentRouteName === $module->route . '.index' ||
                        ($module->route !== '' && str($currentRouteName)->startsWith($module->route . '.'));
                });

                $activeGroup = $activeModule ? $sidebarGroups->firstWhere('id', $activeModule->module_group_id) : null;

                $pageTitle = $activeModule?->name ?? 'Dashboard';
                $pageIcon = $normalizeIcon($activeModule?->icon ?? ($activeGroup?->icon ?? 'grid-1x2-fill'));
            @endphp

            <nav id="sidebarNav" class="flex-1 overflow-y-auto py-2 px-2 space-y-0.5">
                @foreach ($sidebarGroups as $key => $group)
                    @php
                        $modules = $group->modules->filter(fn($module) => $module->is_shown)->values();

                        $firstRoutableModule = $modules->first(function ($module) use ($resolveSidebarRoute) {
                            return $resolveSidebarRoute($module->route) !== null;
                        });

                        $groupRoute = $firstRoutableModule
                            ? route($resolveSidebarRoute($firstRoutableModule->route))
                            : null;

                        $groupHasActiveModule = $modules->contains(function ($module) use ($currentRouteName) {
                            return $currentRouteName === $module->route ||
                                $currentRouteName === $module->route . '.index' ||
                                ($module->route !== '' && str($currentRouteName)->startsWith($module->route . '.'));
                        });

                        $groupIcon = $normalizeIcon($group->icon);
                        $panelId = 'sidebar-group-' . $key;
                        $isSingleModule = $modules->count() === 1;
                        $singleModule = $modules->first();
                        $singleRoute = $singleModule ? $resolveSidebarRoute($singleModule->route) : null;
                    @endphp

                    @continue($modules->isEmpty())

                    @if ($isSingleModule && $singleRoute)
                        <a href="{{ route($singleRoute) }}" @class([
                            'sidebar-module-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm relative',
                            'active bg-indigo-600 text-white shadow-md shadow-indigo-200' => $groupHasActiveModule,
                            'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' => !$groupHasActiveModule,
                        ])>
                            <span @class([
                                'w-6 h-6 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                                'bg-indigo-500 text-white' => $groupHasActiveModule,
                                'bg-slate-100 text-slate-500' => !$groupHasActiveModule,
                            ])>
                                <i class="bi {{ $normalizeIcon($singleModule->icon ?? $group->icon) }}"></i>
                            </span>

                            <span class="font-medium">{{ $singleModule->name }}</span>
                        </a>
                    @else
                        <button type="button"
                            @class([
                                'sidebar-group-btn flex items-center gap-3 w-[calc(100%-8px)] px-3.5 py-2.5 rounded-xl text-sm text-left cursor-pointer relative',
                                'bg-indigo-700 text-white' => $groupHasActiveModule,
                                'text-slate-500 hover:bg-indigo-50 hover:text-indigo-700' => !$groupHasActiveModule,
                            ])
                            data-sidebar-toggle data-sidebar-target="{{ $panelId }}" aria-expanded="false"
                            aria-controls="{{ $panelId }}"
                            @if ($groupRoute) data-sidebar-route="{{ $groupRoute }}" @endif>

                            <span @class([
                                'w-6 h-6 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                                'bg-indigo-500 text-white' => $groupHasActiveModule,
                                'bg-slate-100 text-slate-500' => !$groupHasActiveModule,
                            ])>
                                <i class="bi {{ $groupIcon }}"></i>
                            </span>

                            <span class="flex-1 min-w-0">{{ $group->name }}</span>
                            <i class="bi bi-chevron-down text-xs chevron-icon text-slate-400"></i>
                            </i>
                        </button>


                        <div id="{{ $panelId }}" class="sidebar-group-panel" hidden>
                            <div class="ml-5 mr-2 mt-1 mb-2 space-y-1">
                                @foreach ($modules as $module)
                                    @php
                                        $resolvedRoute = $resolveSidebarRoute($module->route);
                                        $routeExists = $resolvedRoute !== null;
                                        $moduleIcon = $normalizeIcon($module->icon);
                                        $moduleIsActive =
                                            $currentRouteName === $module->route ||
                                            $currentRouteName === $module->route . '.index' ||
                                            ($module->route !== '' &&
                                                str($currentRouteName)->startsWith($module->route . '.'));
                                    @endphp

                                    @if ($routeExists)
                                        <a href="{{ route($resolvedRoute) }}" @class([
                                            'sidebar-module-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm',
                                            'active bg-indigo-50 text-indigo-700 font-medium shadow-sm shadow-indigo-200' => $moduleIsActive,
                                            'text-slate-600' => !$moduleIsActive,
                                        ])>
                                        @else
                                            <span
                                                class="sidebar-module-link sidebar-module-disabled flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-400">
                                    @endif

                                    <span @class([
                                        'w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0',
                                        'bg-indigo-50' => $moduleIsActive,
                                        'text-slate-500' => !$moduleIsActive,
                                    ])>
                                        <i class="bi {{ $moduleIcon }} {{ $moduleIsActive ? ' text-indigo-700' : '' }}"
                                            style="font-size:12px"></i>
                                    </span>

                                    <span class="flex-1 min-w-0">{{ $module->name }}</span>

                                    @if (!$routeExists)
                                        <span
                                            class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded font-medium">
                                            Coming Soon
                                        </span>
                                    @endif

                                    @if ($routeExists)
                                        </a>
                                    @else
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>

            {{-- User Profile --}}
            <div class="flex items-center gap-3 px-5 py-4 border-t border-slate-100 mt-auto">
                <div
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-sm font-semibold text-white flex-shrink-0 shadow-sm">
                    {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-700 truncate">{{ Auth::guard('admin')->user()->name }}
                    </div>
                    <div class="text-[11px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email }}</div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            {{-- Topbar --}}

            <header
                class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shadow-sm">

                <h1 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="bi {{ $pageIcon }}"></i>
                    </span>
                    {{ $pageTitle }}
                </h1>

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
                    <div
                        class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-5">
                        <i class="bi bi-check-circle-fill text-emerald-500"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebarNav');
            if (!sidebar) return;

            var storageKey = 'admin.sidebar.open-panel';

            function setPanelState(panel, btn, isOpen) {
                if (isOpen) {
                    panel.hidden = false;
                    btn.classList.add('open');
                    btn.setAttribute('aria-expanded', 'true');
                } else {
                    panel.hidden = true;
                    btn.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }

            sidebar.querySelectorAll('.sidebar-group-panel').forEach(function(panel) {
                var btn = sidebar.querySelector('[data-sidebar-target="' + panel.id + '"]');
                if (!btn) return;

                setPanelState(panel, btn, false);
            });

            var savedPanelId = window.localStorage.getItem(storageKey);
            if (savedPanelId) {
                var savedPanel = document.getElementById(savedPanelId);
                var savedBtn = savedPanel && sidebar.querySelector('[data-sidebar-target="' + savedPanelId + '"]');
                if (savedPanel && savedBtn) {
                    setPanelState(savedPanel, savedBtn, true);
                }
            }

            sidebar.addEventListener('click', function(e) {
                var btn = e.target.closest('[data-sidebar-toggle]');
                if (!btn) return;

                var panel = document.getElementById(btn.getAttribute('data-sidebar-target'));
                if (!panel) return;

                var wasOpen = !panel.hidden;

                // Close all other panels first so the accordion stays stable.
                sidebar.querySelectorAll('.sidebar-group-panel').forEach(function(otherPanel) {
                    if (otherPanel === panel) return;

                    var otherBtn = sidebar.querySelector('[data-sidebar-target="' + otherPanel.id +
                        '"]');
                    if (!otherBtn) return;

                    setPanelState(otherPanel, otherBtn, false);
                });

                if (!wasOpen) {
                    setPanelState(panel, btn, true);
                    window.localStorage.setItem(storageKey, panel.id);
                } else {
                    setPanelState(panel, btn, false);
                    window.localStorage.removeItem(storageKey);
                }
            });
        });
    </script>
</body>

</html>
