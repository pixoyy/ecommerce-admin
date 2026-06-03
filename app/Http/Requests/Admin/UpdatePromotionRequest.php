<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promotionId = $this->route('promotion');

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('promotions', 'name')->ignore($promotionId)],
            'is_active' => 'required|boolean',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama promosi wajib diisi.',
            'name.max' => 'Nama promosi maksimal 100 karakter.',
            'name.unique' => 'Nama promosi sudah digunakan.',
            'is_active.required' => 'Status wajib dipilih.',
            'start_at.required' => 'Tanggal mulai wajib diisi.',
            'start_at.date' => 'Format tanggal mulai tidak valid.',
            'end_at.required' => 'Tanggal selesai wajib diisi.',
            'end_at.date' => 'Format tanggal selesai tidak valid.',
            'end_at.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ];
    }
}
