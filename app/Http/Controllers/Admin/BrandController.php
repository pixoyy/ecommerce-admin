<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::orderBy('name')->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.form', ['brand' => null]);
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Brand::create($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil dibuat.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $brand->update($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->whereNull('deleted_at')->count() > 0) {
            return redirect()->route('admin.brands.index')->withErrors(
                'Merek masih memiliki produk aktif.'
            );
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil dihapus.');
    }
}
