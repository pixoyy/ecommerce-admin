<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:brands,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama merek wajib diisi.',
            'name.unique' => 'Nama merek sudah digunakan.',
            'name.max' => 'Nama merek maksimal 100 karakter.',
        ];
    }
}
