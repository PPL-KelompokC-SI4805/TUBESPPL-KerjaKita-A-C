<?php

namespace App\Services;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use App\Models\LowonganTersimpan;
use App\Models\MahasiswaProfile;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class LowonganService
{
    /**
     * Mengambil atau membuat profil mahasiswa untuk user yang sedang aktif.
     */
    public function getMahasiswaProfile(User $user): MahasiswaProfile
    {
        return MahasiswaProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['nama_lengkap' => $user->name]
        );
    }

    /**
     * Mengambil seluruh kategori lowongan beserta jumlah lowongan aktif (F-13, F-14).
     */
    public function getCategories(): Collection
    {
        return KategoriLowongan::withCount([
            'lowongan' => fn($q) => $q->where('status_aktif', true),
        ])->get();
    }

    /**
     * Mengambil daftar lowongan aktif dengan fitur pencarian kata kunci,
     * filter kategori/jenis/lokasi, pagination, dan indikator simpanan mahasiswa (F-13, F-14).
     *
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @param  array  $params
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLowonganList(MahasiswaProfile $mahasiswa, array $params = [], int $perPage = 10): LengthAwarePaginator
    {
        $keyword = $params['search'] ?? $params['keyword'] ?? null;

        $query = Lowongan::query()
            ->with(['penyedia', 'kategori'])
            ->aktif()
            ->cari($keyword)
            ->filter($params)
            ->latest();

        $paginator = $query->paginate($perPage);

        // Ambil peta id lowongan yang sudah disimpan oleh mahasiswa ini
        $savedMap = LowonganTersimpan::where('mahasiswa_id', $mahasiswa->id)
            ->get(['id', 'lowongan_id', 'catatan_pribadi'])
            ->keyBy('lowongan_id');

        // Tambahkan metadata status simpan ke setiap item
        $paginator->getCollection()->transform(function (Lowongan $job) use ($savedMap, $mahasiswa) {
            $isSaved = $savedMap->has($job->id);
            $savedRecord = $isSaved ? $savedMap->get($job->id) : null;

            $job->is_saved = $isSaved;
            $job->saved_id = $savedRecord?->id;
            $job->catatan_pribadi = $savedRecord?->catatan_pribadi;
            $job->sudah_dilamar = $job->sudahDilamar($mahasiswa->id);

            return $job;
        });

        return $paginator;
    }

    /**
     * Mengambil detail lengkap satu lowongan aktif (F-13 / FR-11).
     *
     * @param  int  $lowonganId
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @return \App\Models\Lowongan
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getLowonganDetail(int $lowonganId, MahasiswaProfile $mahasiswa): Lowongan
    {
        $lowongan = Lowongan::with(['penyedia', 'kategori'])
            ->aktif()
            ->find($lowonganId);

        if (!$lowongan) {
            throw ValidationException::withMessages([
                'lowongan' => ['Lowongan tidak ditemukan atau sudah tidak aktif.'],
            ]);
        }

        $saved = LowonganTersimpan::where('mahasiswa_id', $mahasiswa->id)
            ->where('lowongan_id', $lowonganId)
            ->first();

        $lowongan->is_saved = (bool) $saved;
        $lowongan->saved_id = $saved?->id;
        $lowongan->catatan_pribadi = $saved?->catatan_pribadi;
        $lowongan->sudah_dilamar = $lowongan->sudahDilamar($mahasiswa->id);

        return $lowongan;
    }

    /**
     * Mengambil daftar lowongan yang disimpan oleh mahasiswa (F-15).
     *
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @param  array  $params  Filter opsional seperti jenis_pekerjaan
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSavedLowonganList(MahasiswaProfile $mahasiswa, array $params = []): Collection
    {
        $query = LowonganTersimpan::query()
            ->with(['lowongan' => fn($q) => $q->with(['penyedia', 'kategori'])])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest();

        $savedList = $query->get();

        // Filter di koleksi berdasarkan jenis pekerjaan jika diberikan
        if (!empty($params['jenis_pekerjaan']) && $params['jenis_pekerjaan'] !== 'semua') {
            $savedList = $savedList->filter(function ($item) use ($params) {
                return $item->lowongan && $item->lowongan->jenis_pekerjaan === $params['jenis_pekerjaan'];
            })->values();
        }

        return $savedList;
    }

    /**
     * Menyimpan lowongan ke daftar favorit / arsip minat karir mahasiswa (F-15).
     *
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @param  int  $lowonganId
     * @param  string|null  $catatanPribadi
     * @return \App\Models\LowonganTersimpan
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveLowongan(MahasiswaProfile $mahasiswa, int $lowonganId, ?string $catatanPribadi = null): LowonganTersimpan
    {
        $lowongan = Lowongan::find($lowonganId);

        if (!$lowongan || !$lowongan->isAktif()) {
            throw ValidationException::withMessages([
                'lowongan_id' => ['Lowongan tidak ditemukan atau sudah tidak aktif.'],
            ]);
        }

        $saved = LowonganTersimpan::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'lowongan_id'  => $lowonganId,
            ],
            [
                'catatan_pribadi' => $catatanPribadi,
            ]
        );

        return $saved->load(['lowongan.penyedia', 'lowongan.kategori']);
    }

    /**
     * Mengubah catatan pribadi pada lowongan tersimpan (F-15).
     *
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @param  int  $lowonganId  Bisa ID lowongan atau ID record tersimpan
     * @param  string|null  $catatanPribadi
     * @return \App\Models\LowonganTersimpan
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateCatatanSimpanan(MahasiswaProfile $mahasiswa, int $lowonganId, ?string $catatanPribadi): LowonganTersimpan
    {
        // Cari berdasarkan lowongan_id terlebih dahulu, atau berdasarkan PK id
        $saved = LowonganTersimpan::where('mahasiswa_id', $mahasiswa->id)
            ->where(function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId)
                  ->orWhere('id', $lowonganId);
            })
            ->first();

        if (!$saved) {
            throw ValidationException::withMessages([
                'saved' => ['Data lowongan tersimpan tidak ditemukan.'],
            ]);
        }

        $saved->update([
            'catatan_pribadi' => $catatanPribadi,
        ]);

        return $saved->fresh()->load(['lowongan.penyedia', 'lowongan.kategori']);
    }

    /**
     * Menghapus lowongan dari daftar tersimpan (F-15).
     *
     * @param  \App\Models\MahasiswaProfile  $mahasiswa
     * @param  int  $lowonganId  Bisa ID lowongan atau ID record tersimpan
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteSavedLowongan(MahasiswaProfile $mahasiswa, int $lowonganId): bool
    {
        $saved = LowonganTersimpan::where('mahasiswa_id', $mahasiswa->id)
            ->where(function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId)
                  ->orWhere('id', $lowonganId);
            })
            ->first();

        if (!$saved) {
            throw ValidationException::withMessages([
                'saved' => ['Data lowongan tersimpan tidak ditemukan.'],
            ]);
        }

        return (bool) $saved->delete();
    }
}
