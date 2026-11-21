<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    protected $table = 'mahasiswa_profiles';

    /**
     * Seluruh kolom pada tabel mahasiswa_profiles yang boleh diisi massal.
     * Sesuai skema ERD pada bagian 4.3 PRD.
     */
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nim',
        'universitas',
        'fakultas',
        'program_studi',
        'jenjang',
        'semester',
        'domisili',
        'keahlian',
        'ketersediaan_waktu',
        'bio',
        'cv_file_path',
        'transkrip_file_path',
        'portfolio_url',
    ];

    /**
     * Relasi belongsTo ke User (1:1).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
