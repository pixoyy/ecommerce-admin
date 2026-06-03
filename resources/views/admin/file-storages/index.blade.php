@extends('admin.layouts.app')

@section('title', 'File Storage — EssenseLuxe Admin')
@section('page_title', 'File Storage')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Semua file yang telah diupload ke sistem. Read-only.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">ID</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Link</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($files as $file)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $files->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $file->id }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ $file->link }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                    {{ $file->link }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $file->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <i class="bi bi-folder text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada file.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($files->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $files->links() }}
            </div>
        @endif
    </div>
@endsection
