<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    protected $fillable = [
        'penyedia_id',
        'kategori_id',
        'judul_posisi',
        'jenis_pekerjaan',
        'tipe_lokasi',
        'lokasi_kota',
        'waktu_kerja',
        'deskripsi',
        'persyaratan',
        'kompensasi',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    /**
     * Cek apakah lowongan aktif.
     */
    public function isAktif(): bool
    {
        return (bool) $this->status_aktif;
    }

    /**
     * Tutup lowongan (F-12).
     */
    public function tutup(): bool
    {
        $this->status_aktif = false;
        return $this->save();
    }

    /**
     * Scope untuk mengambil lowongan aktif saja.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Scope pencarian kata kunci pada judul posisi atau nama perusahaan (F-14 / FR-09).
     */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        $term = '%' . trim($keyword) . '%';

        return $query->where(function (Builder $q) use ($term) {
            $q->where('judul_posisi', 'LIKE', $term)
              ->orWhereHas('penyedia', function (Builder $p) use ($term) {
                  $p->where('nama_organisasi', 'LIKE', $term);
              });
        });
    }

    /**
     * Scope filter multi-kriteria: kategori, jenis pekerjaan, tipe lokasi, lokasi kota (F-14 / FR-10).
     */
    public function scopeFilter(Builder $query, array $params = []): Builder
    {
        // Filter kategori berdasarkan ID
        if (!empty($params['kategori_id'])) {
            $query->where('kategori_id', $params['kategori_id']);
        }

        // Filter kategori berdasarkan Slug
        if (!empty($params['kategori_slug']) && $params['kategori_slug'] !== 'semua') {
            $query->whereHas('kategori', function (Builder $q) use ($params) {
                $q->where('slug', $params['kategori_slug']);
            });
        }

        // Filter jenis pekerjaan (magang, freelance, paruh_waktu)
        if (!empty($params['jenis_pekerjaan']) && $params['jenis_pekerjaan'] !== 'semua') {
            $query->where('jenis_pekerjaan', $params['jenis_pekerjaan']);
        }

        // Filter tipe lokasi (onsite, remote, hybrid)
        if (!empty($params['tipe_lokasi']) && $params['tipe_lokasi'] !== 'semua') {
            $query->where('tipe_lokasi', $params['tipe_lokasi']);
        }

        // Filter lokasi kota
        if (!empty($params['lokasi_kota'])) {
            $query->where('lokasi_kota', 'LIKE', '%' . trim($params['lokasi_kota']) . '%');
        }

        // Filter waktu kerja / komitmen
        if (!empty($params['waktu_kerja'])) {
            $query->where('waktu_kerja', 'LIKE', '%' . trim($params['waktu_kerja']) . '%');
        }

        return $query;
    }

    /**
     * Cek apakah lowongan sudah disimpan oleh mahasiswa tertentu.
     */
    public function sudahDisimpan(int $mahasiswaId): bool
    {
        return $this->lowonganTersimpan()->where('mahasiswa_id', $mahasiswaId)->exists();
    }

    /**
     * Cek apakah lowongan sudah pernah dilamar oleh mahasiswa tertentu (F-16).
     */
    public function sudahDilamar(int $mahasiswaId): bool
    {
        return $this->lamaran()->where('mahasiswa_id', $mahasiswaId)->exists();
    }

    /**
     * Relasi ke profil penyedia (N:1)
     */
    public function penyedia(): BelongsTo
    {
        return $this->belongsTo(PenyediaProfile::class, 'penyedia_id');
    }

    /**
     * Relasi ke kategori lowongan (N:1)
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLowongan::class, 'kategori_id');
    }

    /**
     * Relasi ke lowongan tersimpan (1:N)
     */
    public function lowonganTersimpan(): HasMany
    {
        return $this->hasMany(LowonganTersimpan::class, 'lowongan_id');
    }

    /**
     * Relasi ke lamaran (1:N)
     */
    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }
}
