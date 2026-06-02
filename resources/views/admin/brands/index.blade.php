@extends('admin.layouts.app')

@section('title', 'Merek — EssenseLuxe Admin')
@section('page_title', 'Merek')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Kelola merek produk.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Create Brand
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Nama</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Slug</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($brands as $brand)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $brands->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $brand->name }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ $brand->slug }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.brands.edit', $brand) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus merek {{ $brand->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <i class="bi bi-bookmark text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada merek. Buat merek pertama Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($brands->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
@endsection
