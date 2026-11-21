<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriLowongan extends Model
{
    protected $table = 'kategori_lowongan';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'kategori_id');
    }
}
