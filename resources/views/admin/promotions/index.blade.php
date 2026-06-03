@extends('admin.layouts.app')

@section('title', 'Promosi — EssenseLuxe Admin')
@section('page_title', 'Promosi')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Kelola promosi dan flash sale.</p>
        </div>
        <a href="{{ route('admin.promotions.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Buat Promosi
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Nama</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Periode</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600">Item</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($promotions as $promotion)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $promotions->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $promotion->name }}</td>
                            <td class="px-5 py-4 text-slate-500 text-xs">
                                {{ $promotion->start_at->format('d M Y H:i') }} —
                                {{ $promotion->end_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-4 text-center text-slate-600">{{ $promotion->promotion_items_count }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $now = now();
                                    $isActive = $promotion->is_active && $now >= $promotion->start_at && $now <= $promotion->end_at;
                                @endphp
                                @if ($isActive)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @elseif ($now > $promotion->end_at)
                                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Scheduled
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.promotions.show', $promotion) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>
                                    <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus promosi {{ $promotion->name }}?')">
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
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="bi bi-megaphone text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada promosi. Buat promosi pertama Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($promotions->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $promotions->links() }}
            </div>
        @endif
    </div>
@endsection
