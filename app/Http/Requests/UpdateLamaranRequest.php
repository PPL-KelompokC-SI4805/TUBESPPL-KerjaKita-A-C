<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLamaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'surat_lamaran' => ['sometimes', 'nullable', 'string', 'max:3000'],
            'perbarui_cv'   => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'surat_lamaran.max'   => 'Surat lamaran maksimal 3000 karakter.',
            'perbarui_cv.boolean' => 'Nilai perbarui_cv harus true atau false.',
        ];
    }
}
