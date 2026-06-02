<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileStorage;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'images.required' => 'Pilih minimal satu gambar.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau webp.',
            'images.*.max' => 'Gambar maksimal 2MB.',
        ]);

        DB::transaction(function () use ($request, $product) {
            $maxSort = $product->productImages()->max('sort_order') ?? 0;

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products/gallery', 'public');
                $fileStorage = FileStorage::create(['link' => $path]);

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $fileStorage->id,
                    'sort_order' => $maxSort + $index + 1,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Gambar berhasil diupload.');
    }

    public function destroy(ProductImage $image): RedirectResponse
    {
        $image->delete();

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }
}
