<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LowonganTersimpan extends Model
{
    use HasFactory;

    protected $table = 'lowongan_tersimpan';

    protected $fillable = [
        'mahasiswa_id',
        'lowongan_id',
        'catatan_pribadi',
    ];

    /**
     * Relasi ke profil mahasiswa (N:1)
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke lowongan (N:1)
     */
    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }
}
