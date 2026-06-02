<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'gender' => 'required|in:1,2,3',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'brand_id.required' => 'Brand wajib dipilih.',
            'brand_id.exists' => 'Brand tidak valid.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 100 karakter.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat jpeg, png, jpg, atau webp.',
            'thumbnail.max' => 'Thumbnail maksimal 2MB.',
            'gender.required' => 'Gender wajib dipilih.',
            'gender.in' => 'Gender tidak valid.',
            'is_active.required' => 'Status aktif wajib dipilih.',
        ];
    }
}
