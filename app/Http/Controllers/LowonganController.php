<?php

namespace App\Http\Controllers;

use App\Services\LowonganService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    protected LowonganService $service;

    public function __construct(LowonganService $service)
    {
        $this->service = $service;
    }

    /**
     * F-13 & F-14: Mengambil daftar lowongan aktif untuk mahasiswa
     * dengan pencarian kata kunci dan filter.
     * GET /api/mahasiswa/lowongan
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $params = $request->only([
            'search',
            'keyword',
            'kategori_id',
            'kategori_slug',
            'jenis_pekerjaan',
            'tipe_lokasi',
            'lokasi_kota',
            'waktu_kerja',
        ]);

        $perPage = (int) $request->input('per_page', 8);
        $paginator = $this->service->getLowonganList($mahasiswa, $params, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar lowongan berhasil diambil.',
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ], 200);
    }

    /**
     * Mengambil daftar master kategori lowongan.
     * GET /api/mahasiswa/lowongan/categories
     */
    public function categories(): JsonResponse
    {
        $categories = $this->service->getCategories();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data'    => $categories,
        ], 200);
    }

    /**
     * F-13 / FR-11: Menampilkan detail lengkap lowongan tertentu.
     * GET /api/mahasiswa/lowongan/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $mahasiswa = $this->service->getMahasiswaProfile($user);

        $lowongan = $this->service->getLowonganDetail($id, $mahasiswa);

        return response()->json([
            'success' => true,
            'message' => 'Detail lowongan berhasil diambil.',
            'data'    => $lowongan,
        ], 200);
    }
}
