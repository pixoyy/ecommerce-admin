<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;

class StorePromotionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promotionId = $this->route('promotion');

        return [
            'product_variant_id' => [
                'required',
                'exists:product_variants,id',
                "unique:promotion_items,product_variant_id,NULL,id,promotion_id,{$promotionId}",
            ],
            'override_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $variant = ProductVariant::find($this->product_variant_id);
                    if ($variant && $value >= $variant->price) {
                        $fail('Harga override harus kurang dari harga normal (' . number_format($variant->price, 0, ',', '.') . ').');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_variant_id.required' => 'Varian produk wajib dipilih.',
            'product_variant_id.exists' => 'Varian produk tidak ditemukan.',
            'product_variant_id.unique' => 'Varian ini sudah ada dalam promosi.',
            'override_price.required' => 'Harga override wajib diisi.',
            'override_price.numeric' => 'Harga override harus berupa angka.',
            'override_price.min' => 'Harga override tidak boleh negatif.',
        ];
    }
}
