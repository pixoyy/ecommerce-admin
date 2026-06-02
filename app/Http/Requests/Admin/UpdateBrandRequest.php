<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('brands', 'name')->ignore($this->route('brand')),
            ],
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
