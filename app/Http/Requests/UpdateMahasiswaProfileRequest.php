<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaProfileRequest extends FormRequest
{
    /**
     * Hanya pengguna terautentikasi dengan role mahasiswa yang boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    /**
     * Aturan validasi untuk memperbarui profil mahasiswa (F-05).
     */
    public function rules(): array
    {
        return [
            'nama_lengkap'      => ['sometimes', 'required', 'string', 'max:255'],
            'nim'               => ['sometimes', 'nullable', 'string', 'max:50'],
            'universitas'       => ['sometimes', 'nullable', 'string', 'max:255'],
            'fakultas'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'program_studi'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'jenjang'           => ['sometimes', 'nullable', 'string', 'in:D3,D4,S1,S2,S3'],
            'semester'          => ['sometimes', 'nullable', 'integer', 'min:1', 'max:14'],
            'domisili'          => ['sometimes', 'nullable', 'string', 'max:100'],
            'keahlian'          => ['sometimes', 'nullable', 'string', 'max:1000'],
            'ketersediaan_waktu'=> ['sometimes', 'nullable', 'string', 'max:255'],
            'bio'               => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required'     => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string'       => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max'          => 'Nama lengkap maksimal 255 karakter.',
            'nim.string'                => 'NIM harus berupa teks.',
            'nim.max'                   => 'NIM maksimal 50 karakter.',
            'universitas.string'        => 'Universitas harus berupa teks.',
            'universitas.max'           => 'Universitas maksimal 255 karakter.',
            'fakultas.string'           => 'Fakultas harus berupa teks.',
            'fakultas.max'              => 'Fakultas maksimal 255 karakter.',
            'program_studi.string'      => 'Program studi harus berupa teks.',
            'program_studi.max'         => 'Program studi maksimal 255 karakter.',
            'jenjang.string'            => 'Jenjang harus berupa teks.',
            'jenjang.in'                => 'Jenjang harus salah satu dari: D3, D4, S1, S2, S3.',
            'semester.integer'          => 'Semester harus berupa angka.',
            'semester.min'              => 'Semester minimal 1.',
            'semester.max'              => 'Semester maksimal 14.',
            'domisili.string'           => 'Domisili harus berupa teks.',
            'domisili.max'              => 'Domisili maksimal 100 karakter.',
            'keahlian.string'           => 'Keahlian harus berupa teks.',
            'keahlian.max'              => 'Keahlian maksimal 1000 karakter.',
            'ketersediaan_waktu.string'  => 'Ketersediaan waktu harus berupa teks.',
            'ketersediaan_waktu.max'     => 'Ketersediaan waktu maksimal 255 karakter.',
            'bio.string'               => 'Bio harus berupa teks.',
            'bio.max'                  => 'Bio maksimal 2000 karakter.',
        ];
    }
}
