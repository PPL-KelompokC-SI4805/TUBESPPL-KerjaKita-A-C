<?php

namespace App\Services;

use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MahasiswaProfileService
{
    /**
     * Ambil profil mahasiswa yang sedang login (F-04).
     * Jika belum ada record profil, buat otomatis dengan firstOrCreate.
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\MahasiswaProfile
     */
    public function getProfile(User $user): MahasiswaProfile
    {
        $profile = MahasiswaProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['nama_lengkap' => $user->name]
        );

        return $profile;
    }

    /**
     * Perbarui profil mahasiswa (F-05).
     *
     * @param  \App\Models\User  $user
     * @param  array  $data  Data yang sudah tervalidasi
     * @return \App\Models\MahasiswaProfile
     */
    public function updateProfile(User $user, array $data): MahasiswaProfile
    {
        $profile = $this->getProfile($user);
        $profile->update($data);

        return $profile->fresh();
    }

    /**
     * Unggah atau ganti file CV (F-06).
     *
     * Jika CV lama sudah ada, hapus file fisiknya dari storage
     * sebelum menyimpan file baru. File disimpan di disk 'public' pada folder 'cv'.
     * NFR-11: validasi tipe PDF & maks 2 MB dilakukan di UploadCvRequest.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return array  ['profile' => MahasiswaProfile, 'cv_url' => string]
     */
    public function uploadCv(User $user, UploadedFile $file): array
    {
        $profile = $this->getProfile($user);

        // Hapus CV lama jika ada
        if ($profile->cv_file_path) {
            Storage::disk('public')->delete($profile->cv_file_path);
        }

        // Simpan file baru ke storage/app/public/cv
        // Nama file: cv_{userId}_{timestamp}.pdf agar unik
        $filename = 'cv_' . $user->id . '_' . time() . '.pdf';
        $path = $file->storeAs('cv', $filename, 'public');

        // Update kolom cv_file_path di database
        $profile->update(['cv_file_path' => $path]);

        return [
            'profile' => $profile->fresh(),
            'cv_url'  => Storage::disk('public')->url($path),
        ];
    }

    /**
     * Hapus file CV dari storage dan kosongkan kolom cv_file_path (F-06).
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\MahasiswaProfile
     *
     * @throws \Exception  jika mahasiswa belum memiliki CV
     */
    public function deleteCv(User $user): MahasiswaProfile
    {
        $profile = $this->getProfile($user);

        if (!$profile->cv_file_path) {
            throw new \Exception('Anda belum memiliki file CV yang dapat dihapus.');
        }

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($profile->cv_file_path);

        // Kosongkan kolom cv_file_path
        $profile->update(['cv_file_path' => null]);

        return $profile->fresh();
    }

    /**
     * Unggah atau ganti file Transkrip Nilai PDF (PBI-19 / F-19).
     */
    public function uploadTranskrip(User $user, UploadedFile $file): array
    {
        $profile = $this->getProfile($user);

        if ($profile->transkrip_file_path) {
            Storage::disk('public')->delete($profile->transkrip_file_path);
        }

        $filename = 'transkrip_' . $user->id . '_' . time() . '.pdf';
        $path = $file->storeAs('transkrip', $filename, 'public');

        $profile->update(['transkrip_file_path' => $path]);

        return [
            'profile'       => $profile->fresh(),
            'transkrip_url' => Storage::disk('public')->url($path),
        ];
    }

    /**
     * Hapus file Transkrip Nilai dari storage (PBI-19 / F-19).
     */
    public function deleteTranskrip(User $user): MahasiswaProfile
    {
        $profile = $this->getProfile($user);

        if (!$profile->transkrip_file_path) {
            throw new \Exception('Anda belum memiliki file Transkrip Nilai yang dapat dihapus.');
        }

        Storage::disk('public')->delete($profile->transkrip_file_path);
        $profile->update(['transkrip_file_path' => null]);

        return $profile->fresh();
    }
}
