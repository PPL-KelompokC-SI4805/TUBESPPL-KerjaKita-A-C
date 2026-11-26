<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyediaProfile extends Model
{
    protected $table = 'penyedia_profiles';

    protected $fillable = [
        'user_id',
        'nama_organisasi',
        'bidang_usaha',
        'deskripsi',
        'alamat',
        'kota',
        'website',
        'kontak',
        'logo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lowongan()
    {
        return $this->hasMany(Lowongan::class, 'penyedia_id');
    }
}