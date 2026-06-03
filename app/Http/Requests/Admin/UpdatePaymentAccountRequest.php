<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'bank_name.max' => 'Nama bank maksimal 100 karakter.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_number.max' => 'Nomor rekening maksimal 100 karakter.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'account_name.max' => 'Nama pemilik rekening maksimal 100 karakter.',
            'is_active.required' => 'Status wajib dipilih.',
        ];
    }
}
