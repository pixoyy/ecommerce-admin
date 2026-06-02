@extends('admin.layouts.app')

@section('title', 'Otorisasi — ' . $role->name . ' — EssenseLuxe Admin')
@section('page_title', 'Otorisasi: ' . $role->name)

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Atur hak akses module untuk role <strong>{{ $role->name }}</strong>.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    {{-- Role Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 px-6 py-4 mb-5">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="bi bi-shield-lock text-lg"></i>
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-700">{{ $role->name }}</div>
                <div class="flex items-center gap-2 mt-0.5">
                    @if ($role->status)
                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-medium px-2 py-0.5 rounded-full border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-medium px-2 py-0.5 rounded-full border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Inactive
                        </span>
                    @endif
                    <span class="text-xs text-slate-400">{{ $groups->count() }} module group</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Authorization Form --}}
    <form action="{{ route('admin.roles.authorizations.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="text-left px-5 py-4 font-semibold text-slate-600 min-w-[200px] sticky left-0 bg-slate-50/50 z-10">Module</th>
                            @foreach ($authTypes as $authType)
                                <th class="text-center px-4 py-4 font-semibold text-slate-600 capitalize min-w-[90px]">{{ $authType->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($groups as $group)
                            {{-- Group Header --}}
                            <tr class="bg-slate-50/30">
                                <td colspan="{{ $authTypes->count() + 1 }}" class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $group->name }}</span>
                                        <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded font-medium">{{ $group->modules->count() }} module</span>
                                    </div>
                                </td>
                            </tr>

                            {{-- Module Rows --}}
                            @foreach ($group->modules as $module)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3.5 text-slate-700 font-medium sticky left-0 bg-white hover:bg-slate-50/50 z-10">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                                                <i class="bi {{ $module->icon ?: 'bi-dot' }}" style="font-size:12px"></i>
                                            </span>
                                            {{ $module->name }}
                                        </div>
                                    </td>
                                    @foreach ($authTypes as $authType)
                                        @php
                                            $checked = isset($currentAuths[$module->id]) && in_array($authType->id, $currentAuths[$module->id]);
                                        @endphp
                                        <td class="text-center px-4 py-3.5">
                                            <label class="inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 cursor-pointer transition-all duration-150
                                                {{ $checked ? 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700' : 'border-slate-300 hover:border-slate-400 bg-white' }}">
                                                <input type="checkbox"
                                                       name="auth[{{ $module->id }}][]"
                                                       value="{{ $authType->id }}"
                                                       {{ $checked ? 'checked' : '' }}
                                                       class="sr-only peer">
                                                @if ($checked)
                                                    <i class="bi bi-check-lg text-white text-sm"></i>
                                                @endif
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="{{ $authTypes->count() + 1 }}" class="px-5 py-12 text-center">
                                    <i class="bi bi-grid-3x3-gap text-4xl text-slate-300 block mb-3"></i>
                                    <p class="text-slate-400 text-sm">Belum ada module yang tersedia.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @error('auth')
            <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl">
                {{ $message }}
            </div>
        @enderror

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                <i class="bi bi-check2 mr-1.5"></i>
                Save Authorizations
            </button>
            <a href="{{ route('admin.roles.index') }}"
               class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                Back
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="checkbox"][name^="auth["]').forEach(function (checkbox) {
                var label = checkbox.closest('label');
                if (!label) return;

                function syncVisual() {
                    var isChecked = checkbox.checked;
                    label.className = 'inline-flex items-center justify-center w-9 h-9 rounded-lg border-2 cursor-pointer transition-all duration-150 ' +
                        (isChecked
                            ? 'bg-indigo-600 border-indigo-600 hover:bg-indigo-700'
                            : 'border-slate-300 hover:border-slate-400 bg-white');

                    var icon = label.querySelector('.bi-check-lg');
                    if (isChecked) {
                        if (!icon) {
                            var newIcon = document.createElement('i');
                            newIcon.className = 'bi bi-check-lg text-white text-sm';
                            label.appendChild(newIcon);
                        }
                    } else {
                        if (icon) icon.remove();
                    }
                }

                checkbox.addEventListener('change', syncVisual);
            });
        });
    </script>
@endsection
