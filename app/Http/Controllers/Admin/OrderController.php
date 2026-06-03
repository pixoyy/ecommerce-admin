<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\UserPoint;
use App\Models\WarehouseStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'orderItems', 'payments', 'shipments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($search = $request->get('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load([
            'user.userPoints',
            'warehouse',
            'orderItems.productVariant',
            'payments.paymentAccount',
            'payments.approvedBy',
            'shipments.warehouse',
            'shipments.trackingLogs',
            'pointTransactions',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        if (!in_array($order->status, [1, 3])) {
            return redirect()->route('admin.orders.show', $order)
                ->withErrors('Pesanan tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 6]);

            foreach ($order->orderItems as $item) {
                WarehouseStock::where('warehouse_id', $order->warehouse_id)
                    ->where('product_variant_id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->increment('quantity', $item->quantity);
            }

            if ($order->point_redeemed > 0) {
                $userPoint = UserPoint::firstOrCreate(
                    ['user_id' => $order->user_id],
                    ['balance' => 0]
                );
                $userPoint->increment('balance', $order->point_redeemed);

                PointTransaction::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'type' => 1,
                    'amount' => $order->point_redeemed,
                    'description' => 'Pengembalian poin karena pembatalan pesanan ' . $order->order_number,
                ]);
            }
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
