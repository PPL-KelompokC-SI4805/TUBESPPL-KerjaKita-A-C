<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Lowongan extends Model
{
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

    public function penyedia(): BelongsTo
    {
        return $this->belongsTo(PenyediaProfile::class, 'penyedia_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLowongan::class, 'kategori_id');
    }
}
