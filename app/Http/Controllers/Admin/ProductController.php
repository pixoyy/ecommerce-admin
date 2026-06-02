<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FileStorage;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'category', 'thumbnailImage']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', 1)->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.form', ['product' => null, 'brands' => $brands, 'categories' => $categories]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use ($request, $validated) {
            $fileStorage = $this->uploadFile($request->file('thumbnail'), 'products');
            $validated['thumbnail'] = $fileStorage->id;
            $validated['slug'] = $this->generateSlug($validated['name']);

            return Product::create($validated);
        });

        return redirect()->route('admin.products.show', $result)
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Product $product): View
    {
        $product->load(['brand', 'category', 'thumbnailImage', 'productImages.fileStorage', 'productVariants' => function ($q) {
            $q->withCount('orderItems');
        }]);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'brands', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $product, &$validated) {
            if ($request->hasFile('thumbnail')) {
                $fileStorage = $this->uploadFile($request->file('thumbnail'), 'products');
                $validated['thumbnail'] = $fileStorage->id;
            } else {
                unset($validated['thumbnail']);
            }

            $validated['slug'] = $this->generateSlug($validated['name'], $product->id);

            $product->update($validated);
        });

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $hasOrders = OrderItem::whereHas('productVariant', function ($q) use ($product) {
            $q->where('product_id', $product->id);
        })->exists();

        if ($hasOrders) {
            return redirect()->route('admin.products.index')
                ->withErrors('Produk tidak dapat dihapus karena sudah memiliki pesanan.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function uploadFile($file, string $subdirectory): FileStorage
    {
        $path = $file->store($subdirectory, 'public');

        return FileStorage::create(['link' => $path]);
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Product::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
