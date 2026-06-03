<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_variant_id' => [
                'required',
                'exists:product_variants,id',
                Rule::unique('warehouse_stocks', 'product_variant_id')
                    ->where('warehouse_id', $this->warehouse_id),
            ],
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'Gudang wajib dipilih.',
            'warehouse_id.exists' => 'Gudang tidak valid.',
            'product_variant_id.required' => 'Varian produk wajib dipilih.',
            'product_variant_id.exists' => 'Varian produk tidak valid.',
            'product_variant_id.unique' => 'Stok untuk varian ini di gudang tersebut sudah ada.',
            'quantity.required' => 'Jumlah stok wajib diisi.',
            'quantity.integer' => 'Jumlah stok harus berupa angka.',
            'quantity.min' => 'Jumlah stok tidak boleh negatif.',
        ];
    }
}
