<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['order', 'paymentAccount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order.user',
            'paymentAccount',
            'approvedBy',
            'rejectedBy',
            'proofPath',
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    public function approve(Payment $payment): RedirectResponse
    {
        if ($payment->status !== 1) {
            return redirect()->route('admin.payments.show', $payment)
                ->withErrors('Pembayaran tidak dapat disetujui pada status ini.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 2,
                'approved_by' => auth('admin')->id(),
                'approved_at' => now(),
            ]);

            $payment->order->update([
                'status' => 3,
                'paid_at' => now(),
            ]);
        });

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        if ($payment->status !== 1) {
            return redirect()->route('admin.payments.show', $payment)
                ->withErrors('Pembayaran tidak dapat ditolak pada status ini.');
        }

        $validated = $request->validate([
            'rejected_reason' => 'required|string|min:10',
        ]);

        $payment->update([
            'status' => 3,
            'rejected_by' => auth('admin')->id(),
            'rejected_at' => now(),
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran ditolak.');
    }
}
