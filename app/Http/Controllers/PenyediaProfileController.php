<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePenyediaProfileRequest;
use App\Http\Requests\UploadLogoRequest;
use App\Services\PenyediaProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenyediaProfileController extends Controller
{
    /**
     * @var \App\Services\PenyediaProfileService
     */
    protected PenyediaProfileService $service;

    public function __construct(PenyediaProfileService $service)
    {
        $this->service = $service;
    }

    // ─────────────────────────────────────────────────────────────
    //  F-07 : Melihat Profil Organisasi
    //  GET /api/penyedia/profile
    // ─────────────────────────────────────────────────────────────
    public function show(Request $request): JsonResponse
    {
        $user    = $request->user();
        $profile = $this->service->getProfile($user);

        return response()->json([
            'success' => true,
            'message' => 'Data profil organisasi berhasil diambil.',
            'data'    => [
                'email'    => $user->email,
                'logo_url' => $this->service->logoUrl($profile),
                ...$profile->toArray(),
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-08 : Mengubah Profil Organisasi
    //  PUT /api/penyedia/profile
    // ─────────────────────────────────────────────────────────────
    public function update(UpdatePenyediaProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $profile   = $this->service->updateProfile($request->user(), $validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil organisasi berhasil diperbarui.',
            'data'    => [
                'email'    => $request->user()->email,
                'logo_url' => $this->service->logoUrl($profile),
                ...$profile->toArray(),
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-09 : Unggah / Ganti Logo
    //  POST /api/penyedia/profile/logo
    // ─────────────────────────────────────────────────────────────
    public function uploadLogo(UploadLogoRequest $request): JsonResponse
    {
        $result = $this->service->uploadLogo($request->user(), $request->file('logo'));

        return response()->json([
            'success' => true,
            'message' => 'Logo organisasi berhasil diunggah.',
            'data'    => [
                'logo_path' => $result['profile']->logo_path,
                'logo_url'  => $result['logo_url'],
            ],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  F-09 : Hapus Logo
    //  DELETE /api/penyedia/profile/logo
    // ─────────────────────────────────────────────────────────────
    public function deleteLogo(Request $request): JsonResponse
    {
        try {
            $profile = $this->service->deleteLogo($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Logo organisasi berhasil dihapus.',
                'data'    => [
                    'logo_path' => $profile->logo_path,
                    'logo_url'  => null,
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