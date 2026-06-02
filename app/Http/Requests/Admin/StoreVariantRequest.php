<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01',
            'sku' => 'required|string|max:45|unique:product_variants,sku',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Label varian wajib diisi.',
            'label.max' => 'Label varian maksimal 100 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga harus lebih dari 0.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.max' => 'SKU maksimal 45 karakter.',
            'sku.unique' => 'SKU sudah digunakan.',
            'is_active.required' => 'Status aktif wajib dipilih.',
        ];
    }
}
