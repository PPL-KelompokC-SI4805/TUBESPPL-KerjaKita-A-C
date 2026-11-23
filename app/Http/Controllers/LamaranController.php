<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjukanLamaranRequest;
use App\Http\Requests\UpdateLamaranRequest;
use App\Services\LamaranService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Sisi mahasiswa: ajukan, riwayat, ubah, batal lamaran.
 * Cocokkan label PBI-16/17/18 dengan pembagian dari PM.
 */
class LamaranController extends Controller
{
    public function __construct(protected LamaranService $service) {}

    // GET /api/mahasiswa/lamaran?status=Terkirim
    public function index(Request $request): JsonResponse
    {
        $data = $this->service->riwayat($request->user(), $request->query('status'));

        return $this->ok('Riwayat lamaran berhasil diambil.', $data, ['total' => $data->count()]);
    }

    // POST /api/mahasiswa/lamaran
    public function store(AjukanLamaranRequest $request): JsonResponse
    {
        $lamaran = $this->service->ajukan(
            $request->user(),
            (int) $request->input('lowongan_id'),
            $request->input('surat_lamaran')
        );

        return $this->ok('Lamaran berhasil dikirim.', $lamaran, [], 201);
    }

    // GET /api/mahasiswa/lamaran/{id}
    public function show(Request $request, int $id): JsonResponse
    {
        return $this->ok('Detail lamaran berhasil diambil.', $this->service->detail($request->user(), $id));
    }

    // PUT /api/mahasiswa/lamaran/{id}
    public function update(UpdateLamaranRequest $request, int $id): JsonResponse
    {
        $lamaran = $this->service->ubah($request->user(), $id, $request->validated());

        return $this->ok('Lamaran berhasil diperbarui.', $lamaran);
    }

    // DELETE /api/mahasiswa/lamaran/{id}  (status jadi Dibatalkan)
    public function destroy(Request $request, int $id): JsonResponse
    {
        $lamaran = $this->service->batal($request->user(), $id);

        return $this->ok('Lamaran berhasil dibatalkan.', $lamaran);
    }

    private function ok(string $message, mixed $data, array $extra = [], int $code = 200): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data] + $extra, $code);
    }
}
