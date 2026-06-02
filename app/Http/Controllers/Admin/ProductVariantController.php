<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVariantRequest;
use App\Http\Requests\Admin\UpdateVariantRequest;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        $variants = $product->productVariants()->withCount('orderItems')->get();

        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product): View
    {
        return view('admin.variants.form', ['product' => $product, 'variant' => null]);
    }

    public function store(StoreVariantRequest $request, Product $product): RedirectResponse
    {
        $product->productVariants()->create($request->validated());

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil ditambahkan.');
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        return view('admin.variants.form', compact('product', 'variant'));
    }

    public function update(UpdateVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        $variant->update($request->validated());

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil diperbarui.');
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        $hasOrders = OrderItem::where('product_variant_id', $variant->id)->exists();

        if ($hasOrders) {
            return redirect()->route('admin.products.show', $product)
                ->withErrors('Varian tidak dapat dihapus karena sudah memiliki pesanan.');
        }

        $variant->delete();

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Varian berhasil dihapus.');
    }
}
