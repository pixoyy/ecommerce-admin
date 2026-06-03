@extends('admin.layouts.app')

@section('title', 'Akun Pembayaran — EssenseLuxe Admin')
@section('page_title', 'Akun Pembayaran')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <div>
            <p class="text-sm text-slate-500">Kelola akun pembayaran bank.</p>
        </div>
        <a href="{{ route('admin.payment-accounts.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
            <i class="bi bi-plus-lg"></i>
            Buat Akun
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left px-5 py-4 font-semibold text-slate-600 w-14">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Bank</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">No. Rekening</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Atas Nama</th>
                        <th class="text-left px-5 py-4 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-4 font-semibold text-slate-600 w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-500">{{ $accounts->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $account->bank_name }}</td>
                            <td class="px-5 py-4 text-slate-600 font-mono text-xs">{{ $account->account_number }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $account->account_name }}</td>
                            <td class="px-5 py-4">
                                @if ($account->is_active)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.payment-accounts.edit', $account) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.payment-accounts.destroy', $account) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $account->bank_name }} - {{ $account->account_number }}?')">
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
                                <i class="bi bi-credit-card text-4xl text-slate-300 block mb-3"></i>
                                <p class="text-slate-400 text-sm">Belum ada akun pembayaran. Buat akun pertama Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($accounts->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>
@endsection
