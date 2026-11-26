<?php

namespace App\Services;

use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PenyediaProfileService
{
    /**
     * Ambil profil organisasi penyedia yang sedang login (F-07).
     * Jika belum ada record profil, buat otomatis dengan firstOrCreate.
     */
    public function getProfile(User $user): PenyediaProfile
    {
        return PenyediaProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['nama_organisasi' => $user->name]
        );
    }

    /**
     * Perbarui profil organisasi penyedia (F-08).
     * Jika nama organisasi berubah, nama pada tabel users ikut disinkronkan
     * (konsisten dengan AuthController::register yang memakai nama organisasi sebagai name).
     */
    public function updateProfile(User $user, array $data): PenyediaProfile
    {
        $profile = $this->getProfile($user);
        $profile->update($data);

        if (!empty($data['nama_organisasi']) && $user->name !== $data['nama_organisasi']) {
            $user->update(['name' => $data['nama_organisasi']]);
        }

        return $profile->fresh();
    }

    /**
     * Unggah atau ganti logo organisasi (F-09).
     * Logo lama (jika ada) dihapus dari storage sebelum file baru disimpan.
     * File disimpan di disk 'public' pada folder 'logo'.
     *
     * @return array  ['profile' => PenyediaProfile, 'logo_url' => string]
     */
    public function uploadLogo(User $user, UploadedFile $file): array
    {
        $profile = $this->getProfile($user);

        if ($profile->logo_path) {
            Storage::disk('public')->delete($profile->logo_path);
        }

        // Nama file: logo_{userId}_{timestamp}.{ext} agar unik
        $filename = 'logo_' . $user->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('logo', $filename, 'public');

        $profile->update(['logo_path' => $path]);

        return [
            'profile'  => $profile->fresh(),
            'logo_url' => asset('storage/' . $path),
        ];
    }

    /**
     * Hapus logo dari storage dan kosongkan kolom logo_path (F-09).
     *
     * @throws \Exception  jika penyedia belum memiliki logo
     */
    public function deleteLogo(User $user): PenyediaProfile
    {
        $profile = $this->getProfile($user);

        if (!$profile->logo_path) {
            throw new \Exception('Anda belum memiliki logo organisasi yang dapat dihapus.');
        }

        Storage::disk('public')->delete($profile->logo_path);
        $profile->update(['logo_path' => null]);

        return $profile->fresh();
    }

    /**
     * Helper: URL publik logo (null jika belum ada).
     */
    public function logoUrl(PenyediaProfile $profile): ?string
    {
        return $profile->logo_path
            ? asset('storage/' . $profile->logo_path)
            : null;
    }
}