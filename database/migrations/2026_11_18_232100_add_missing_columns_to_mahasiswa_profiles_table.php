<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang belum ada pada tabel mahasiswa_profiles
     * sesuai spesifikasi ERD (bagian 4.3 PRD).
     *
     * Kolom baru: fakultas, jenjang, bio, transkrip_file_path, portfolio_url
     */
    public function up(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->string('fakultas')->nullable()->after('universitas');
            $table->string('jenjang', 20)->nullable()->after('program_studi');
            $table->text('bio')->nullable()->after('ketersediaan_waktu');
            $table->string('transkrip_file_path')->nullable()->after('cv_file_path');
            $table->string('portfolio_url')->nullable()->after('transkrip_file_path');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'fakultas',
                'jenjang',
                'bio',
                'transkrip_file_path',
                'portfolio_url',
            ]);
        });
    }
};
