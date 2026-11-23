<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjukanLamaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'lowongan_id'   => ['required', 'integer', 'exists:lowongan,id'],
            'surat_lamaran' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'lowongan_id.required' => 'Lowongan wajib dipilih.',
            'lowongan_id.exists'   => 'Lowongan tidak ditemukan.',
            'surat_lamaran.max'    => 'Surat lamaran maksimal 3000 karakter.',
        ];
    }
}
