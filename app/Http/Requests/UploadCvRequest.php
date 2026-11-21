<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCvRequest extends FormRequest
{
    /**
     * Hanya pengguna terautentikasi dengan role mahasiswa yang boleh mengirim request ini.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'mahasiswa';
    }

    /**
     * Aturan validasi untuk mengunggah CV (F-06).
     * NFR-11: File CV wajib PDF, maksimal 2 MB, divalidasi di sisi server.
     */
    public function rules(): array
    {
        return [
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'cv.required' => 'File CV wajib diunggah.',
            'cv.file'     => 'CV harus berupa file yang valid.',
            'cv.mimes'    => 'File CV harus berformat PDF.',
            'cv.max'      => 'Ukuran file CV maksimal 2 MB.',
        ];
    }
}
