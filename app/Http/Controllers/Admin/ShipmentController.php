<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\Shipment;
use App\Models\ShipmentTrackingLog;
use App\Models\UserPoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    private const PROGRESSION = [1 => 2, 2 => 3, 3 => 4];

    public function index(Request $request): View
    {
        $query = Shipment::with(['order', 'warehouse']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%");
            })->orWhere('courier_name', 'like', "%{$search}%")
              ->orWhere('tracking_number', 'like', "%{$search}%");
        }

        $shipments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.shipments.index', compact('shipments'));
    }

    public function create(Order $order): View|RedirectResponse
    {
        if (!in_array($order->status, [2, 3])) {
            return redirect()->route('admin.orders.show', $order)
                ->withErrors('Pesanan harus sudah dibayar untuk membuat pengiriman.');
        }

        if ($order->shipments()->whereNull('deleted_at')->exists()) {
            return redirect()->route('admin.orders.show', $order)
                ->withErrors('Pesanan ini sudah memiliki pengiriman.');
        }

        return view('admin.shipments.form', compact('order'));
    }

    public function store(Request $request, Order $order): RedirectResponse
    {
        if (!in_array($order->status, [2, 3])) {
            return redirect()->route('admin.orders.show', $order)
                ->withErrors('Pesanan harus sudah dibayar untuk membuat pengiriman.');
        }

        if ($order->shipments()->whereNull('deleted_at')->exists()) {
            return redirect()->route('admin.orders.show', $order)
                ->withErrors('Pesanan ini sudah memiliki pengiriman.');
        }

        $validated = $request->validate([
            'courier_name' => 'required|string|max:100',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $shipment = DB::transaction(function () use ($order, $validated) {
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'warehouse_id' => $order->warehouse_id,
                'shipping_cost' => $validated['shipping_cost'],
                'status' => 1,
                'courier_name' => $validated['courier_name'],
            ]);

            ShipmentTrackingLog::create([
                'shipment_id' => $shipment->id,
                'updated_by' => auth('admin')->id(),
                'status' => 1,
                'note' => 'Pengiriman dibuat.',
            ]);

            return $shipment;
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function show(Shipment $shipment): View
    {
        $shipment->load([
            'order.user',
            'warehouse',
            'trackingLogs.updatedBy',
        ]);

        return view('admin.shipments.show', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment): RedirectResponse
    {
        $currentStatus = $shipment->status;
        $newStatus = (int) $request->status;

        if (!isset(self::PROGRESSION[$currentStatus]) || self::PROGRESSION[$currentStatus] !== $newStatus) {
            return redirect()->route('admin.shipments.show', $shipment)
                ->withErrors('Perubahan status tidak valid.');
        }

        if ($newStatus === 2) {
            $validated = $request->validate([
                'tracking_number' => 'required|string|max:100',
            ]);
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($shipment, $newStatus, $validated, $request) {
            $updateData = ['status' => $newStatus];

            if ($newStatus === 2) {
                $updateData['tracking_number'] = $request->tracking_number;
            }

            if ($newStatus === 4) {
                $updateData['delivered_at'] = now();
            }

            $shipment->update($updateData);

            ShipmentTrackingLog::create([
                'shipment_id' => $shipment->id,
                'updated_by' => auth('admin')->id(),
                'status' => $newStatus,
                'note' => $validated['note'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);

            if ($newStatus === 4) {
                $order = $shipment->order;
                $order->update(['status' => 5]);

                if ($order->point_earned > 0) {
                    $userPoint = UserPoint::firstOrCreate(
                        ['user_id' => $order->user_id],
                        ['balance' => 0]
                    );
                    $userPoint->increment('balance', $order->point_earned);

                    PointTransaction::create([
                        'user_id' => $order->user_id,
                        'order_id' => $order->id,
                        'type' => 1,
                        'amount' => $order->point_earned,
                        'description' => 'Poin earned dari pesanan ' . $order->order_number,
                    ]);
                }
            }
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
