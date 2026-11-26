<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Lamaran extends Model
{
    use HasFactory;

    public const STATUS_TERKIRIM   = 'Terkirim';
    public const STATUS_DIBATALKAN = 'Dibatalkan';

    protected $table = 'lamaran';

    protected $fillable = [
        'lowongan_id',
        'mahasiswa_id',
        'surat_lamaran',
        'cv_snapshot_path',
        'status_lamaran',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(MahasiswaProfile::class, 'mahasiswa_id');
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    /** Lamaran hanya boleh diubah/dibatalkan selama masih berstatus Terkirim. */
    public function bisaDiubah(): bool
    {
        return $this->status_lamaran === self::STATUS_TERKIRIM;
    }

    public function scopeMilik(Builder $query, int $mahasiswaId): Builder
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function cvUrl(): ?string
    {
        return $this->cv_snapshot_path
            ? Storage::disk('public')->url($this->cv_snapshot_path)
            : null;
    }
}
