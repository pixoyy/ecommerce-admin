<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromotionItemRequest;
use App\Models\Promotion;
use App\Models\PromotionItem;
use Illuminate\Http\RedirectResponse;

class PromotionItemController extends Controller
{
    public function store(StorePromotionItemRequest $request, Promotion $promotion): RedirectResponse
    {
        $promotion->promotionItems()->create($request->validated());

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Item promosi berhasil ditambahkan.');
    }

    public function destroy(PromotionItem $item): RedirectResponse
    {
        $promotionId = $item->promotion_id;
        $item->delete();

        return redirect()->route('admin.promotions.show', $promotionId)
            ->with('success', 'Item promosi berhasil dihapus.');
    }
}
