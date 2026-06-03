@extends('admin.layouts.app')

@section('title', 'Ulasan — EssenseLuxe Admin')
@section('page_title', 'Ulasan')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Moderasi ulasan produk dari pelanggan.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-5">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="w-56">
                <label for="product_id" class="block text-xs font-medium text-slate-500 mb-1">Produk</label>
                <select name="product_id" id="product_id"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <label for="rating" class="block text-xs font-medium text-slate-500 mb-1">Rating</label>
                <select name="rating" id="rating"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua Rating</option>
                    @foreach (range(5, 1) as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                    @endforeach
                </select>
            </div>

            <div class="w-44">
                <label for="is_visible" class="block text-xs font-medium text-slate-500 mb-1">Visibilitas</label>
                <select name="is_visible" id="is_visible"
                        class="block w-full px-3 py-2 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    <option value="">Semua</option>
                    <option value="1" {{ request('is_visible') === '1' ? 'selected' : '' }}>Tampil</option>
                    <option value="0" {{ request('is_visible') === '0' ? 'selected' : '' }}>Tersembunyi</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                    <i class="bi bi-funnel mr-1"></i>
                    Filter
                </button>
                @if (request()->anyFilled(['product_id', 'rating', 'is_visible']))
                    <a href="{{ route('admin.reviews.index') }}"
                       class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Reset
                    </a>
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
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Pelanggan</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Produk</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600">Rating</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Ulasan</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $reviews->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $review->user->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600 max-w-[180px] truncate">{{ $review->product->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-amber-500 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 max-w-xs">
                                <p class="truncate">{{ $review->reason ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($review->is_visible)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tampil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Tersembunyi
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium {{ $review->is_visible ? 'text-amber-600 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-emerald-600 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }} border rounded-lg transition-colors">
                                            <i class="bi {{ $review->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            {{ $review->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="bi bi-chat-square-text text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada ulasan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
