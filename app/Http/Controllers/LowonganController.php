<?php

namespace App\Http\Controllers;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use App\Services\LowonganService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    // Menampilkan daftar lowongan milik penyedia
    public function index()
    {
    $penyedia = auth()->user()->penyediaProfile;

    $lowongan = $penyedia->lowongan()->latest()->get();

    return view('penyedia.lowongan-index', compact('lowongan'));
    }

    // Menampilkan detail lowongan
    public function show($id)
    {
    $penyedia = auth()->user()->penyediaProfile;

    $lowongan = $penyedia->lowongan()
        ->with('kategori')
        ->findOrFail($id);

    return view('penyedia.lowongan-detail', compact('lowongan'));
    }

    // Menampilkan formulir tambah lowongan
    public function create()
    {
        $kategori = KategoriLowongan::all();

        return view('penyedia.lowongan-create', compact('kategori'));
    }

    // Menyimpan lowongan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_posisi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_lowongan,id',
            'jenis_pekerjaan' => 'required|in:magang,freelance,paruh_waktu',
            'tipe_lokasi' => 'required|in:onsite,remote,hybrid',
            'lokasi_kota' => 'nullable|string|max:255',
            'waktu_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|string',
            'kompensasi' => 'nullable|string|max:255',
        ]);

        $penyedia = auth()->user()->penyediaProfile;

        if (!$penyedia) {
            return back()->with('error', 'Profil penyedia tidak ditemukan.');
        }

        $penyedia->lowongan()->create($validated + [
            'status_aktif' => true,
        ]);


        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', 'Lowongan berhasil ditambahkan.');
    }

    // Menampilkan form edit lowongan
    public function edit($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);
        $kategori = KategoriLowongan::all();

        return view('penyedia.lowongan-edit', compact('lowongan', 'kategori'));
    }

    // Menyimpan perubahan lowongan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul_posisi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_lowongan,id',
            'jenis_pekerjaan' => 'required|in:magang,freelance,paruh_waktu',
            'tipe_lokasi' => 'required|in:onsite,remote,hybrid',
            'lokasi_kota' => 'nullable|string|max:255',
            'waktu_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|string',
            'kompensasi' => 'nullable|string|max:255',
        ]);

        $penyedia = auth()->user()->penyediaProfile;
        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->update($validated);

        return redirect()
            ->route('penyedia.lowongan.show', $lowongan->id)
            ->with('success', 'Lowongan berhasil diperbarui.');
    }
    // Mengubah status lowongan
    public function toggleStatus($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->update([
            'status_aktif' => !$lowongan->status_aktif,
        ]);

        $pesan = $lowongan->status_aktif
            ? 'Lowongan berhasil diaktifkan kembali.'
            : 'Lowongan berhasil ditutup.';

        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', $pesan);
    }
    // Menghapus lowongan
    public function destroy($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->delete();

        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
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
