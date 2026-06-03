<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentAccountRequest;
use App\Http\Requests\Admin\UpdatePaymentAccountRequest;
use App\Models\PaymentAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentAccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = PaymentAccount::query();

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        $accounts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payment-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('admin.payment-accounts.form', ['account' => null]);
    }

    public function store(StorePaymentAccountRequest $request): RedirectResponse
    {
        PaymentAccount::create($request->validated());

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil dibuat.');
    }

    public function edit(PaymentAccount $paymentAccount): View
    {
        return view('admin.payment-accounts.form', ['account' => $paymentAccount]);
    }

    public function update(UpdatePaymentAccountRequest $request, PaymentAccount $paymentAccount): RedirectResponse
    {
        $paymentAccount->update($request->validated());

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentAccount $paymentAccount): RedirectResponse
    {
        if ($paymentAccount->payments()->whereNull('deleted_at')->count() > 0) {
            return redirect()->route('admin.payment-accounts.index')->withErrors(
                'Akun pembayaran masih memiliki data pembayaran.'
            );
        }

        $paymentAccount->delete();

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil dihapus.');
    }
}
