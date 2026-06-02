@extends('admin.layouts.app')

@section('title', 'Pelanggan — EssenseLuxe Admin')
@section('page_title', 'Pelanggan')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Daftar customer terdaftar.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Create Customer
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-slate-500 mb-1">Cari Nama / Email</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="search"
                           class="flex-1 px-3 py-2 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           value="{{ request('search') }}"
                           placeholder="Ketik nama atau email...">
                </div>
            </div>

            <div class="w-44">
                <label for="trashed" class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="trashed" id="trashed"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Aktif</option>
                    <option value="1" {{ request('trashed') === '1' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    <i class="bi bi-funnel mr-1"></i>
                    Filter
                </button>
                @if (request()->anyFilled(['search', 'trashed']))
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Name</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Email</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Phone</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Bergabung</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-cyan-500 flex items-center justify-center text-xs font-semibold text-white flex-shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $user->phone ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @if ($user->trashed())
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Nonaktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sky-600 bg-sky-50 border border-sky-200 rounded-lg hover:bg-sky-100 transition-colors">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>
                                    @if (!$user->trashed())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                              onsubmit="return confirm('Nonaktifkan customer {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                                <i class="bi bi-person-x"></i>
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="bi bi-people text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">
                                    @if (request()->anyFilled(['search', 'trashed']))
                                        Tidak ada customer yang sesuai.
                                    @else
                                        Belum ada customer terdaftar.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
