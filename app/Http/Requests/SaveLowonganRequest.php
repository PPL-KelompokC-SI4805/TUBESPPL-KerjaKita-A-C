<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveLowonganRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki izin untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    /**
     * Aturan validasi untuk menyimpan lowongan (F-15).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lowongan_id'     => ['required', 'integer', 'exists:lowongan,id'],
            'catatan_pribadi' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Pesan kustom validasi dalam Bahasa Indonesia (NFR-04).
     */
    public function messages(): array
    {
        return [
            'lowongan_id.required' => 'ID Lowongan wajib disertakan.',
            'lowongan_id.integer'  => 'ID Lowongan harus berupa angka.',
            'lowongan_id.exists'   => 'Lowongan yang dipilih tidak ditemukan dalam sistem.',
            'catatan_pribadi.max'  => 'Catatan pribadi maksimal 2.000 karakter.',
        ];
    }
}
