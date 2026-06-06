<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\FileStorage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Models\PointTransaction;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShipmentTrackingLog;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id');
        $warehouses = Warehouse::pluck('id');
        $variants = ProductVariant::with('product')->get();
        $paymentAccounts = PaymentAccount::pluck('id');
        $images = FileStorage::pluck('id');
        $admin = Admin::first();

        $orderData = [
            // Order 1: Delivered
            [
                'user_idx' => 0,
                'warehouse_idx' => 0,
                'status' => 5,
                'subtotal' => 300000,
                'shipping_cost' => 15000,
                'point_redeemed' => 100,
                'point_earned' => 300,
                'buyer_name' => 'Rina Amelia',
                'buyer_email' => 'rina@example.com',
                'buyer_phone' => '081234567890',
                'shipping_address' => 'Jl. Merdeka No. 10, RT 05 RW 03, Kel. Menteng, Kec. Menteng, Jakarta Pusat',
                'shipping_note' => 'Sebelum jam 5 sore ya kak',
                'paid_at' => now()->subDays(10),
                'items' => [
                    ['variant_idx' => 0, 'qty' => 2],
                    ['variant_idx' => 1, 'qty' => 1],
                ],
            ],
            // Order 2: Shipped
            [
                'user_idx' => 1,
                'warehouse_idx' => 1,
                'status' => 4,
                'subtotal' => 1800000,
                'shipping_cost' => 25000,
                'point_redeemed' => 0,
                'point_earned' => 1800,
                'buyer_name' => 'Budi Santoso',
                'buyer_email' => 'budi@example.com',
                'buyer_phone' => '081234567891',
                'shipping_address' => 'Jl. Raya Darmo Permai No. 45, Surabaya',
                'shipping_note' => null,
                'paid_at' => now()->subDays(5),
                'items' => [
                    ['variant_idx' => 16, 'qty' => 1],
                ],
            ],
            // Order 3: Processing
            [
                'user_idx' => 2,
                'warehouse_idx' => 2,
                'status' => 3,
                'subtotal' => 355000,
                'shipping_cost' => 12000,
                'point_redeemed' => 50,
                'point_earned' => 355,
                'buyer_name' => 'Siti Nurhaliza',
                'buyer_email' => 'siti@example.com',
                'buyer_phone' => '081234567892',
                'shipping_address' => 'Jl. Setiabudi No. 200, Bandung',
                'shipping_note' => 'Tolong dicek dulu barangnya sebelum dikirim',
                'paid_at' => now()->subDays(2),
                'items' => [
                    ['variant_idx' => 6, 'qty' => 1],
                    ['variant_idx' => 8, 'qty' => 2],
                    ['variant_idx' => 22, 'qty' => 1],
                ],
            ],
            // Order 4: Paid (pending shipment)
            [
                'user_idx' => 3,
                'warehouse_idx' => 0,
                'status' => 2,
                'subtotal' => 650000,
                'shipping_cost' => 18000,
                'point_redeemed' => 0,
                'point_earned' => 650,
                'buyer_name' => 'Doni Prasetyo',
                'buyer_email' => 'doni@example.com',
                'buyer_phone' => '081234567893',
                'shipping_address' => 'Jl. Sudirman No. 88, Jakarta Selatan',
                'shipping_note' => null,
                'paid_at' => now()->subHours(6),
                'items' => [
                    ['variant_idx' => 19, 'qty' => 1],
                ],
            ],
            // Order 5: Pending Payment
            [
                'user_idx' => 4,
                'warehouse_idx' => 1,
                'status' => 1,
                'subtotal' => 260000,
                'shipping_cost' => 10000,
                'point_redeemed' => 0,
                'point_earned' => 260,
                'buyer_name' => 'Maya Indah',
                'buyer_email' => 'maya@example.com',
                'buyer_phone' => '081234567894',
                'shipping_address' => 'Jl. Tunjungan No. 15, Surabaya',
                'shipping_note' => 'Kode intercom: #1234',
                'paid_at' => null,
                'items' => [
                    ['variant_idx' => 31, 'qty' => 2],
                    ['variant_idx' => 33, 'qty' => 1],
                ],
            ],
        ];

        foreach ($orderData as $i => $oData) {
            $orderNumber = 'ORD-' . now()->format('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id' => $users->get($oData['user_idx']),
                'warehouse_id' => $warehouses->get($oData['warehouse_idx']),
                'order_number' => $orderNumber,
                'status' => $oData['status'],
                'subtotal' => $oData['subtotal'],
                'shipping_cost' => $oData['shipping_cost'],
                'point_redeemed' => $oData['point_redeemed'],
                'point_earned' => $oData['point_earned'],
                'total' => $oData['subtotal'] + $oData['shipping_cost'] - $oData['point_redeemed'],
                'buyer_name' => $oData['buyer_name'],
                'buyer_email' => $oData['buyer_email'],
                'buyer_phone' => $oData['buyer_phone'],
                'shipping_address' => $oData['shipping_address'],
                'shipping_note' => $oData['shipping_note'],
                'note' => null,
                'paid_at' => $oData['paid_at'],
            ]);

            foreach ($oData['items'] as $item) {
                $variant = $variants->get($item['variant_idx']);
                if (!$variant) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_label' => $variant->label,
                    'unit_price' => $variant->price,
                    'quantity' => $item['qty'],
                    'subtotal' => $variant->price * $item['qty'],
                ]);
            }

            if ($oData['status'] >= 4) {
                $shipmentStatuses = [5, 4];
                $shipStatus = $shipmentStatuses[$i % 2];

                $shipment = Shipment::create([
                    'order_id' => $order->id,
                    'warehouse_id' => $warehouses->get($oData['warehouse_idx']),
                    'shipping_cost' => $oData['shipping_cost'],
                    'status' => $shipStatus,
                    'courier_name' => fake()->randomElement(['JNE', 'J&T', 'SiCepat', 'GoSend']),
                    'tracking_number' => strtoupper(fake()->bothify('#########')),
                    'delivered_at' => $shipStatus === 5 ? $oData['paid_at']->addDays(3) : null,
                ]);

                $logs = [
                    ['status' => 1, 'note' => 'Pesanan telah diterima di gudang', 'location' => $shipment->warehouse->city],
                    ['status' => 2, 'note' => 'Paket telah di pickup kurir', 'location' => $shipment->warehouse->city],
                    ['status' => 3, 'note' => 'Paket dalam perjalanan menuju kota tujuan', 'location' => 'Dalam perjalanan'],
                    ['status' => 4, 'note' => 'Paket sedang diantar kurir ke alamat tujuan', 'location' => $order->shipping_address],
                ];

                if ($shipStatus === 5) {
                    $logs[] = ['status' => 5, 'note' => 'Paket telah diterima oleh pembeli', 'location' => $order->shipping_address];
                }

                foreach ($logs as $log) {
                    ShipmentTrackingLog::create([
                        'shipment_id' => $shipment->id,
                        'updated_by' => $admin->id,
                        'status' => $log['status'],
                        'note' => $log['note'],
                        'location' => $log['location'],
                    ]);
                }
            }

            $isPaid = $oData['status'] >= 2;

            $paymentData = [
                'order_id' => $order->id,
                'payment_account_id' => $paymentAccounts->get($i % $paymentAccounts->count()),
                'amount' => $oData['subtotal'] + $oData['shipping_cost'],
                'status' => $isPaid ? 2 : 1,
            ];

            if ($isPaid) {
                $paymentData['approved_at'] = $oData['paid_at'];
                $paymentData['proof_path'] = $images->get(($i + 1) * 2);

                if ($oData['status'] >= 3) {
                    $paymentData['approved_by'] = $admin->id;
                }
            }

            Payment::create($paymentData);

            if ($oData['point_earned'] > 0 && $oData['paid_at']) {
                PointTransaction::create([
                    'user_id' => $users->get($oData['user_idx']),
                    'order_id' => $order->id,
                    'type' => 1,
                    'amount' => $oData['point_earned'],
                    'description' => 'Poin dari pesanan #' . $orderNumber,
                ]);

                $userPoint = UserPoint::where('user_id', $users->get($oData['user_idx']))->first();
                if ($userPoint) {
                    $userPoint->increment('balance', $oData['point_earned']);
                }
            }

            if ($oData['point_redeemed'] > 0) {
                PointTransaction::create([
                    'user_id' => $users->get($oData['user_idx']),
                    'order_id' => $order->id,
                    'type' => 2,
                    'amount' => $oData['point_redeemed'],
                    'description' => 'Penukaran poin untuk pesanan #' . $orderNumber,
                ]);

                $userPoint = UserPoint::where('user_id', $users->get($oData['user_idx']))->first();
                if ($userPoint) {
                    $userPoint->decrement('balance', $oData['point_redeemed']);
                }
            }
        }
    }
}
