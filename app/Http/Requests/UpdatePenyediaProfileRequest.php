<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePenyediaProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'penyedia';
    }

    /**
     * Aturan validasi untuk memperbarui profil organisasi (F-08).
     */
    public function rules(): array
    {
        return [
            'nama_organisasi' => ['sometimes', 'required', 'string', 'max:255'],
            'bidang_usaha'    => ['sometimes', 'nullable', 'string', 'max:255'],
            'deskripsi'       => ['sometimes', 'nullable', 'string', 'max:2000'],
            'alamat'          => ['sometimes', 'nullable', 'string', 'max:1000'],
            'kota'            => ['sometimes', 'nullable', 'string', 'max:100'],
            'website'         => ['sometimes', 'nullable', 'url', 'max:255'],
            'kontak'          => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_organisasi.required' => 'Nama organisasi wajib diisi.',
            'nama_organisasi.string'   => 'Nama organisasi harus berupa teks.',
            'nama_organisasi.max'      => 'Nama organisasi maksimal 255 karakter.',
            'bidang_usaha.string'      => 'Bidang usaha harus berupa teks.',
            'bidang_usaha.max'         => 'Bidang usaha maksimal 255 karakter.',
            'deskripsi.string'         => 'Deskripsi harus berupa teks.',
            'deskripsi.max'            => 'Deskripsi maksimal 2000 karakter.',
            'alamat.string'            => 'Alamat harus berupa teks.',
            'alamat.max'               => 'Alamat maksimal 1000 karakter.',
            'kota.string'              => 'Kota harus berupa teks.',
            'kota.max'                 => 'Kota maksimal 100 karakter.',
            'website.url'              => 'Website harus berupa URL yang valid (contoh: https://perusahaan.com).',
            'website.max'              => 'Website maksimal 255 karakter.',
            'kontak.string'            => 'Kontak harus berupa teks.',
            'kontak.max'               => 'Kontak maksimal 100 karakter.',
        ];
    }
}