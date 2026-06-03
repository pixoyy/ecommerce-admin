@extends('admin.layouts.app')

@section('title', $account ? 'Edit Akun Pembayaran — EssenseLuxe Admin' : 'Buat Akun Pembayaran — EssenseLuxe Admin')
@section('page_title', $account ? 'Edit Akun Pembayaran' : 'Buat Akun Pembayaran')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">{{ $account ? 'Edit Akun Pembayaran' : 'Buat Akun Pembayaran Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $account ? 'Ubah data akun pembayaran.' : 'Tambah akun bank baru.' }}</p>
            </div>

            <form action="{{ $account ? route('admin.payment-accounts.update', $account) : route('admin.payment-accounts.store') }}"
                  method="POST"
                  class="p-6 space-y-5">
                @csrf
                @if ($account)
                    @method('PUT')
                @endif

                <div>
                    <label for="bank_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Bank</label>
                    <input type="text"
                           name="bank_name"
                           id="bank_name"
                           value="{{ old('bank_name', $account->bank_name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('bank_name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Mis: BCA, Mandiri, BRI"
                           required autofocus>
                    @error('bank_name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-medium text-slate-700 mb-1.5">Nomor Rekening</label>
                    <input type="text"
                           name="account_number"
                           id="account_number"
                           value="{{ old('account_number', $account->account_number ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('account_number') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Nomor rekening"
                           required>
                    @error('account_number')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_name" class="block text-sm font-medium text-slate-700 mb-1.5">Atas Nama</label>
                    <input type="text"
                           name="account_name"
                           id="account_name"
                           value="{{ old('account_name', $account->account_name ?? '') }}"
                           class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 @error('account_name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                           placeholder="Nama pemilik rekening"
                           required>
                    @error('account_name')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="is_active"
                            id="is_active"
                            class="block w-full px-3.5 py-2.5 text-sm text-slate-700 border border-slate-300 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all @error('is_active') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                        <option value="1" {{ old('is_active', $account->is_active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $account->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        {{ $account ? 'Update Akun' : 'Save Akun' }}
                    </button>
                    <a href="{{ route('admin.payment-accounts.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
