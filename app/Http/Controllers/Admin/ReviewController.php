<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'product', 'orderItem']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }

        $reviews = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    public function toggle(Review $review): RedirectResponse
    {
        $review->update(['is_visible' => !$review->is_visible]);

        $status = $review->fresh()->is_visible ? 'ditampilkan' : 'disembunyikan';

        return redirect()->route('admin.reviews.index')
            ->with('success', "Ulasan berhasil {$status}.");
    }
}
