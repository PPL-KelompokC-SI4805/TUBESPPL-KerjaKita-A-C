<?php

namespace Database\Seeders;

use App\Models\KategoriLowongan;
use Illuminate\Database\Seeder;

class KategoriLowonganSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Teknologi Informasi',
            'Desain',
            'Bisnis dan Manajemen',
            'Pemasaran',
            'Keuangan',
            'Pendidikan',
            'Administrasi',
            'Komunikasi',
        ];

        foreach ($kategori as $nama) {
            KategoriLowongan::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($nama)],
                ['nama_kategori' => $nama]
            );
        }
    }
}
