<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLowonganRequest;
use App\Http\Requests\UpdateCatatanSimpananRequest;
use App\Services\LowonganService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LowonganTersimpanController extends Controller
{
    protected LowonganService $service;

    public function __construct(LowonganService $service)
    {
        $this->service = $service;
    }

    /**
     * F-15: Mengambil daftar lowongan yang disimpan oleh mahasiswa.
     * GET /api/mahasiswa/lowongan-tersimpan
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $savedList = $this->service->getSavedLowonganList($mahasiswa, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Daftar lowongan tersimpan berhasil diambil.',
            'data'    => $savedList,
            'total'   => $savedList->count(),
        ], 200);
    }

    /**
     * F-15: Menyimpan lowongan ke daftar simpanan mahasiswa (Bookmark).
     * POST /api/mahasiswa/lowongan-tersimpan
     */
    public function store(SaveLowonganRequest $request): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $saved = $this->service->saveLowongan(
            $mahasiswa,
            (int) $request->input('lowongan_id'),
            $request->input('catatan_pribadi')
        );

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil disimpan ke daftar minat karir Anda.',
            'data'    => $saved,
        ], 201);
    }

    /**
     * F-15: Mengubah catatan pribadi pada lowongan tersimpan.
     * PUT /api/mahasiswa/lowongan-tersimpan/{id}
     */
    public function update(UpdateCatatanSimpananRequest $request, int $id): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $saved = $this->service->updateCatatanSimpanan(
            $mahasiswa,
            $id,
            $request->input('catatan_pribadi')
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan pribadi berhasil diperbarui.',
            'data'    => $saved,
        ], 200);
    }

    /**
     * F-15: Menghapus lowongan dari daftar tersimpan.
     * DELETE /api/mahasiswa/lowongan-tersimpan/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $this->service->deleteSavedLowongan($mahasiswa, $id);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil dihapus dari daftar tersimpan.',
        ], 200);
    }
}
