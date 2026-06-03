<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromotionRequest;
use App\Http\Requests\Admin\UpdatePromotionRequest;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Promotion::withCount('promotionItems');

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $promotions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        return view('admin.promotions.form', ['promotion' => null]);
    }

    public function store(StorePromotionRequest $request): RedirectResponse
    {
        $promotion = Promotion::create($request->validated());

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promosi berhasil dibuat.');
    }

    public function show(Promotion $promotion): View
    {
        $promotion->load(['promotionItems.productVariant.product']);

        $products = Product::with('productVariants')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.promotions.show', compact('promotion', 'products'));
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.form', compact('promotion'));
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $promotion->update($request->validated());

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promosi berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promosi berhasil dihapus.');
    }
}
