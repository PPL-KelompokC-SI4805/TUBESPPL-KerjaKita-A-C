<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMahasiswaProfileRequest;
use App\Http\Requests\UploadCvRequest;
use App\Services\MahasiswaProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaProfileController extends Controller
{
    /**
     * @var \App\Services\MahasiswaProfileService
     */
    protected MahasiswaProfileService $service;

    public function __construct(MahasiswaProfileService $service)
    {
        $this->service = $service;
    }

    // ─────────────────────────────────────────────────────────────
    //  F-04 : Melihat Profil Mahasiswa
    //  GET /api/mahasiswa/profile
    // ─────────────────────────────────────────────────────────────
    /**
     * Mengambil data profil lengkap mahasiswa yang sedang login,
     * beserta email dari tabel users.
     * Jika profil belum ada, otomatis dibuat record kosong (firstOrCreate).
     */
    public function show(Request $request): JsonResponse
    {
        $user    = $request->user();
        $profile = $this->service->getProfile($user);

        return response()->json([
            'success' => true,
            'message' => 'Data profil mahasiswa berhasil diambil.',
            'data'    => [
                'email' => $user->email,
                ...$profile->toArray(),
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-05 : Mengubah Profil Mahasiswa
    //  PUT /api/mahasiswa/profile
    // ─────────────────────────────────────────────────────────────
    /**
     * Memperbarui data profil mahasiswa menggunakan Form Request Validation.
     */
    public function update(UpdateMahasiswaProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $profile   = $this->service->updateProfile($request->user(), $validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil mahasiswa berhasil diperbarui.',
            'data'    => [
                'email' => $request->user()->email,
                ...$profile->toArray(),
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-06 : Unggah / Ganti CV
    //  POST /api/mahasiswa/profile/cv
    // ─────────────────────────────────────────────────────────────
    /**
     * Mengunggah atau mengganti berkas CV.
     * Validasi sisi server: wajib PDF, maks 2 MB.
     * Jika CV lama ada, file fisik dihapus sebelum disimpan ulang.
     * Mengembalikan URL publik file CV yang baru.
     */
    public function uploadCv(UploadCvRequest $request): JsonResponse
    {
        $result = $this->service->uploadCv($request->user(), $request->file('cv'));

        return response()->json([
            'success' => true,
            'message' => 'File CV berhasil diunggah.',
            'data'    => [
                'cv_file_path' => $result['profile']->cv_file_path,
                'cv_url'       => $result['cv_url'],
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-06 : Hapus CV
    //  DELETE /api/mahasiswa/profile/cv
    // ─────────────────────────────────────────────────────────────
    /**
     * Menghapus file fisik CV dari storage dan mengosongkan kolom cv_file_path.
     */
    public function deleteCv(Request $request): JsonResponse
    {
        try {
            $profile = $this->service->deleteCv($request->user());

            return response()->json([
                'success' => true,
                'message' => 'File CV berhasil dihapus.',
                'data'    => [
                    'cv_file_path' => $profile->cv_file_path,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
