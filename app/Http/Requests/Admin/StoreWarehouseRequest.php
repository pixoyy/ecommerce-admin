<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'is_active' => 'required|boolean',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama gudang wajib diisi.',
            'name.max' => 'Nama gudang maksimal 100 karakter.',
            'is_active.required' => 'Status wajib dipilih.',
            'city.max' => 'Kota maksimal 100 karakter.',
            'province.max' => 'Provinsi maksimal 100 karakter.',
            'postal_code.max' => 'Kode pos maksimal 10 karakter.',
        ];
    }
}
