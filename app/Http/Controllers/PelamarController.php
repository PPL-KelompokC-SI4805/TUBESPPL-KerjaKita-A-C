<?php

namespace App\Http\Controllers;

use App\Services\LamaranService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** Sisi penyedia: daftar pelamar per lowongan. */
class PelamarController extends Controller
{
    public function __construct(protected LamaranService $service) {}

    // GET /api/penyedia/lowongan/{id}/pelamar?status=Terkirim
    public function index(Request $request, int $id): JsonResponse
    {
        $hasil = $this->service->daftarPelamar($request->user(), $id, $request->query('status'));

        return response()->json([
            'success'  => true,
            'message'  => 'Daftar pelamar berhasil diambil.',
            'lowongan' => [
                'id'           => $hasil['lowongan']->id,
                'judul_posisi' => $hasil['lowongan']->judul_posisi,
            ],
            'data'     => $hasil['pelamar'],
            'total'    => $hasil['pelamar']->count(),
        ]);
    }
}
