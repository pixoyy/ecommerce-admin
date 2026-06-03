@extends('admin.layouts.app')

@section('title', 'Gudang — EssenseLuxe Admin')
@section('page_title', 'Gudang')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Kelola gudang penyimpanan.</p>
        </div>
        <a href="{{ route('admin.warehouses.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Create Warehouse
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.warehouses.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-slate-500 mb-1">Cari Nama / Kota</label>
                <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-50 text-slate-400 border-r border-slate-200">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" name="search" id="search"
                           class="flex-1 px-3 py-2 text-sm text-slate-700 outline-none bg-transparent placeholder:text-slate-400"
                           value="{{ request('search') }}" placeholder="Cari nama atau kota...">
                </div>
            </div>
            <div class="w-36">
                <label for="is_active" class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="is_active" id="is_active"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    <i class="bi bi-funnel mr-1"></i> Filter
                </button>
                @if (request()->anyFilled(['search', 'is_active']))
                    <a href="{{ route('admin.warehouses.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">Reset</a>
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
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Nama</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Kota</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Provinsi</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Kode Pos</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($warehouses as $warehouse)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $warehouses->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $warehouse->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $warehouse->city ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $warehouse->province ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $warehouse->postal_code ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @if ($warehouse->is_active)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.warehouses.edit', $warehouse) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" method="POST"
                                          onsubmit="return confirm('Hapus gudang {{ $warehouse->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="bi bi-building text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada gudang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($warehouses->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $warehouses->links() }}</div>
        @endif
    </div>
@endsection
