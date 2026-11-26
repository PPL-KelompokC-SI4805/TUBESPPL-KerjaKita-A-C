<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadLogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'penyedia';
    }

    /**
     * Aturan validasi untuk mengunggah logo organisasi (F-09).
     * Logo wajib gambar JPG/PNG, maksimal 2 MB.
     */
    public function rules(): array
    {
        return [
            'logo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'logo.required' => 'File logo wajib diunggah.',
            'logo.file'     => 'Logo harus berupa file yang valid.',
            'logo.image'    => 'Logo harus berupa gambar.',
            'logo.mimes'    => 'Logo harus berformat JPG, JPEG, atau PNG.',
            'logo.max'      => 'Ukuran file logo maksimal 2 MB.',
        ];
    }
}