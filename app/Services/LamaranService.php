<?php

namespace App\Services;

use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LamaranService
{
    public function getMahasiswaProfile(User $user): MahasiswaProfile
    {
        return MahasiswaProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['nama_lengkap' => $user->name]
        );
    }

    // ───────────── Pengajuan lamaran ─────────────
    public function ajukan(User $user, int $lowonganId, ?string $surat): Lamaran
    {
        $mhs      = $this->getMahasiswaProfile($user);
        $lowongan = Lowongan::findOrFail($lowonganId);

        if (!$lowongan->isAktif()) {
            $this->gagal('lowongan_id', 'Lowongan ini sudah ditutup.');
        }

        $this->pastikanCvAda($mhs);

        $existing = Lamaran::milik($mhs->id)->where('lowongan_id', $lowongan->id)->first();

        if ($existing && $existing->status_lamaran !== Lamaran::STATUS_DIBATALKAN) {
            $this->gagal('lowongan_id', 'Anda sudah melamar ke lowongan ini.');
        }

        return DB::transaction(function () use ($mhs, $lowongan, $surat, $existing) {
            $data = [
                'surat_lamaran'    => $surat,
                'cv_snapshot_path' => $this->buatSnapshotCv($mhs, $lowongan),
                'status_lamaran'   => Lamaran::STATUS_TERKIRIM,
            ];

            // Tabel punya unique(mahasiswa_id, lowongan_id): lamaran yang pernah
            // dibatalkan "dihidupkan lagi", bukan dibuat baris baru.
            if ($existing) {
                $existing->update($data);
                $lamaran = $existing;
            } else {
                $lamaran = Lamaran::create($data + [
                    'mahasiswa_id' => $mhs->id,
                    'lowongan_id'  => $lowongan->id,
                ]);
            }

            return $lamaran->load('lowongan.penyedia');
        });
    }

    // ───────────── Riwayat lamaran ─────────────
    public function riwayat(User $user, ?string $status = null): Collection
    {
        $mhs = $this->getMahasiswaProfile($user);

        return Lamaran::with('lowongan.penyedia')
            ->milik($mhs->id)
            ->when($status, fn ($q) => $q->where('status_lamaran', $status))
            ->latest()
            ->get();
    }

    public function detail(User $user, int $id): Lamaran
    {
        $mhs = $this->getMahasiswaProfile($user);

        return Lamaran::with('lowongan.penyedia')->milik($mhs->id)->findOrFail($id);
    }

    // ───────────── Ubah & batal lamaran ─────────────
    public function ubah(User $user, int $id, array $data): Lamaran
    {
        $lamaran = $this->detail($user, $id);
        $this->pastikanBisaDiubah($lamaran);

        if (array_key_exists('surat_lamaran', $data)) {
            $lamaran->surat_lamaran = $data['surat_lamaran'];
        }

        if (!empty($data['perbarui_cv'])) {
            $mhs = $this->getMahasiswaProfile($user);
            $this->pastikanCvAda($mhs);

            if ($lamaran->cv_snapshot_path) {
                Storage::disk('public')->delete($lamaran->cv_snapshot_path);
            }
            $lamaran->cv_snapshot_path = $this->buatSnapshotCv($mhs, $lamaran->lowongan);
        }

        $lamaran->save();

        return $lamaran->fresh('lowongan.penyedia');
    }

    public function batal(User $user, int $id): Lamaran
    {
        $lamaran = $this->detail($user, $id);
        $this->pastikanBisaDiubah($lamaran);

        if ($lamaran->cv_snapshot_path) {
            Storage::disk('public')->delete($lamaran->cv_snapshot_path);
        }

        // Soft-cancel: baris tetap ada sebagai riwayat, hanya statusnya yang berubah.
        $lamaran->update([
            'status_lamaran'   => Lamaran::STATUS_DIBATALKAN,
            'cv_snapshot_path' => null,
        ]);

        return $lamaran->fresh('lowongan.penyedia');
    }

    // ───────────── Daftar pelamar (sisi penyedia) ─────────────
    /** @return array{lowongan: Lowongan, pelamar: \Illuminate\Support\Collection} */
    public function daftarPelamar(User $penyediaUser, int $lowonganId, ?string $status = null): array
    {
        $penyedia = PenyediaProfile::where('user_id', $penyediaUser->id)->firstOrFail();

        // 404 kalau lowongan bukan milik penyedia ini.
        $lowongan = Lowongan::where('penyedia_id', $penyedia->id)->findOrFail($lowonganId);

        $pelamar = Lamaran::with('mahasiswa')
            ->where('lowongan_id', $lowongan->id)
            ->where('status_lamaran', '!=', Lamaran::STATUS_DIBATALKAN)
            ->when($status, fn ($q) => $q->where('status_lamaran', $status))
            ->latest()
            ->get()
            ->map(fn (Lamaran $l) => [
                'id'             => $l->id,
                'status_lamaran' => $l->status_lamaran,
                'surat_lamaran'  => $l->surat_lamaran,
                'cv_url'         => $l->cvUrl(),
                'tanggal_lamar'  => $l->created_at,
                'mahasiswa'      => [
                    'nama_lengkap'  => $l->mahasiswa->nama_lengkap,
                    'nim'           => $l->mahasiswa->nim,
                    'universitas'   => $l->mahasiswa->universitas,
                    'program_studi' => $l->mahasiswa->program_studi,
                    'semester'      => $l->mahasiswa->semester,
                    'keahlian'      => $l->mahasiswa->keahlian,
                ],
            ]);

        return ['lowongan' => $lowongan, 'pelamar' => $pelamar];
    }

    // ───────────── Helper ─────────────
    private function pastikanCvAda(MahasiswaProfile $mhs): void
    {
        if (!$mhs->cv_file_path || !Storage::disk('public')->exists($mhs->cv_file_path)) {
            $this->gagal('cv', 'Unggah CV terlebih dahulu di halaman profil sebelum melamar.');
        }
    }

    private function pastikanBisaDiubah(Lamaran $lamaran): void
    {
        if (!$lamaran->bisaDiubah()) {
            $this->gagal('status_lamaran', 'Lamaran tidak dapat diubah atau dibatalkan karena statusnya sudah ' . $lamaran->status_lamaran . '.');
        }
    }

    /** Salin CV saat ini sebagai snapshot, supaya CV yang dilihat penyedia tidak berubah diam-diam. */
    private function buatSnapshotCv(MahasiswaProfile $mhs, Lowongan $lowongan): string
    {
        $path = 'lamaran_cv/lamaran_' . $mhs->id . '_' . $lowongan->id . '_' . time() . '.pdf';
        Storage::disk('public')->copy($mhs->cv_file_path, $path);

        return $path;
    }

    private function gagal(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => [$message]]);
    }
}
