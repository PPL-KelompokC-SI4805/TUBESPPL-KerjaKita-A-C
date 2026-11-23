<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatatanSimpananRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki izin untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    /**
     * Aturan validasi untuk memperbarui catatan pribadi lowongan tersimpan (F-15).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'catatan_pribadi' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Pesan kustom validasi dalam Bahasa Indonesia (NFR-04).
     */
    public function messages(): array
    {
        return [
            'catatan_pribadi.max' => 'Catatan pribadi maksimal 2.000 karakter.',
        ];
    }
}
