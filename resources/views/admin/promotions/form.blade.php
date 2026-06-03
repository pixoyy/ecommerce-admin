@extends('admin.layouts.app')

@section('title', $promotion ? 'Edit Promosi — EssenseLuxe Admin' : 'Buat Promosi — EssenseLuxe Admin')
@section('page_title', $promotion ? 'Edit Promosi' : 'Buat Promosi')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $promotion ? 'Edit Promosi' : 'Buat Promosi Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $promotion ? 'Ubah data promosi.' : 'Buat promosi atau flash sale baru.' }}</p>
            </div>

            <form action="{{ $promotion ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($promotion)
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Promosi</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $promotion->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Mis: Flash Sale Akhir Bulan"
                           required autofocus>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="start_at" class="block text-sm font-medium text-slate-700 mb-1.5">Mulai</label>
                        <input type="datetime-local"
                               name="start_at"
                               id="start_at"
                               value="{{ old('start_at', $promotion->start_at ? $promotion->start_at->format('Y-m-d\TH:i') : '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('start_at') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('start_at')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_at" class="block text-sm font-medium text-slate-700 mb-1.5">Selesai</label>
                        <input type="datetime-local"
                               name="end_at"
                               id="end_at"
                               value="{{ old('end_at', $promotion->end_at ? $promotion->end_at->format('Y-m-d\TH:i') : '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('end_at') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('end_at')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="is_active"
                            id="is_active"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('is_active') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="1" {{ old('is_active', $promotion->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $promotion->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $promotion ? 'Update Promosi' : 'Save Promosi' }}
                    </button>
                    <a href="{{ $promotion ? route('admin.promotions.show', $promotion) : route('admin.promotions.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
