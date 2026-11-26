<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriLowongan extends Model
{
    use HasFactory;

    protected $table = 'kategori_lowongan';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    /**
     * Relasi ke Lowongan (1:N)
     */
    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'kategori_id');
    }
}
