<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\WarehouseStock;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $ordersToday = Order::whereDate('created_at', today())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $totalRevenue = Order::where('status', 5)->sum('total');
        $totalCustomers = User::count();
        $activeProducts = Product::where('is_active', 1)->count();

        $lowStock = WarehouseStock::with('productVariant.product')
            ->where('quantity', '<', 5)
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $orderStatusLabels = [
            1 => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-600'],
            2 => ['label' => 'Paid', 'class' => 'bg-blue-50 text-blue-600'],
            3 => ['label' => 'Processing', 'class' => 'bg-indigo-50 text-indigo-600'],
            4 => ['label' => 'Shipped', 'class' => 'bg-purple-50 text-purple-600'],
            5 => ['label' => 'Delivered', 'class' => 'bg-emerald-50 text-emerald-600'],
            6 => ['label' => 'Cancelled', 'class' => 'bg-red-50 text-red-600'],
        ];

        return view('admin.dashboard', compact(
            'ordersToday',
            'totalRevenue',
            'totalCustomers',
            'activeProducts',
            'lowStock',
            'recentOrders',
            'orderStatusLabels',
        ));
    }
}
