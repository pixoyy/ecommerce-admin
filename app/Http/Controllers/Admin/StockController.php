<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $query = WarehouseStock::with(['warehouse', 'productVariant.product']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($search = $request->get('search')) {
            $query->whereHas('productVariant.product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('productVariant', function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%");
            });
        }

        $stocks = $query->latest()->paginate(15)->withQueryString();
        $warehouses = Warehouse::where('is_active', 1)->orderBy('name')->get();

        return view('admin.stocks.index', compact('stocks', 'warehouses'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', 1)->orderBy('name')->get();
        $products = Product::with('productVariants')->where('is_active', 1)->orderBy('name')->get();

        return view('admin.stocks.form', compact('warehouses', 'products'));
    }

    public function store(StockRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            WarehouseStock::updateOrCreate(
                [
                    'warehouse_id' => $request->warehouse_id,
                    'product_variant_id' => $request->product_variant_id,
                ],
                ['quantity' => $request->quantity]
            );
        });

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }
}
