@extends('admin.layouts.app')

@section('title', $warehouse ? 'Edit Gudang — EssenseLuxe Admin' : 'Create Gudang — EssenseLuxe Admin')
@section('page_title', $warehouse ? 'Edit Gudang' : 'Create Gudang')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $warehouse ? 'Edit Gudang' : 'Buat Gudang Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $warehouse ? 'Ubah data gudang.' : 'Tambah gudang penyimpanan.' }}</p>
            </div>
            <form action="{{ $warehouse ? route('admin.warehouses.update', $warehouse) : route('admin.warehouses.store') }}"
                  method="POST" class="p-6 space-y-5">
                @csrf
                @if ($warehouse) @method('PUT') @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Gudang</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('name') border-red-400 @enderror"
                           placeholder="Nama gudang" required autofocus>
                    @error('name')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 mb-1.5">Kota</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $warehouse->city ?? '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('city') border-red-400 @enderror"
                               placeholder="Kota">
                        @error('city')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-medium text-slate-700 mb-1.5">Provinsi</label>
                        <input type="text" name="province" id="province" value="{{ old('province', $warehouse->province ?? '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('province') border-red-400 @enderror"
                               placeholder="Provinsi">
                        @error('province')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $warehouse->postal_code ?? '') }}"
                               class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('postal_code') border-red-400 @enderror"
                               placeholder="Kode pos">
                        @error('postal_code')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                        <select name="is_active" id="is_active"
                                class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('is_active') border-red-400 @enderror">
                            <option value="1" {{ old('is_active', $warehouse->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $warehouse->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')<p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $warehouse ? 'Update Warehouse' : 'Save Warehouse' }}
                    </button>
                    <a href="{{ route('admin.warehouses.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
