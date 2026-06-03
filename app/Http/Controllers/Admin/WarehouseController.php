<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWarehouseRequest;
use App\Http\Requests\Admin\UpdateWarehouseRequest;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Warehouse::query();

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $warehouses = $query->latest()->paginate(15)->withQueryString();

        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('admin.warehouses.form', ['warehouse' => null]);
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        Warehouse::create($request->validated());

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil dibuat.');
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('admin.warehouses.form', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validated());

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $hasStock = $warehouse->warehouseStocks()->sum('quantity') > 0;
        if ($hasStock) {
            return redirect()->route('admin.warehouses.index')->withErrors(
                'Gudang tidak bisa dihapus karena masih memiliki stok.'
            );
        }

        $hasOrders = Order::where('warehouse_id', $warehouse->id)->whereNull('deleted_at')->exists();
        if ($hasOrders) {
            return redirect()->route('admin.warehouses.index')->withErrors(
                'Gudang tidak bisa dihapus karena masih memiliki pesanan aktif.'
            );
        }

        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')->with('success', 'Gudang berhasil dihapus.');
    }
}
