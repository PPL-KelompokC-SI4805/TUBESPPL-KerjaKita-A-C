<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyedia_id')->constrained('penyedia_profiles')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_lowongan')->onDelete('cascade');
            $table->string('judul_posisi');
            $table->enum('jenis_pekerjaan', ['magang', 'freelance', 'paruh_waktu']);
            $table->enum('tipe_lokasi', ['onsite', 'remote', 'hybrid']);
            $table->string('lokasi_kota', 100)->nullable();
            $table->string('waktu_kerja')->nullable();
            $table->text('deskripsi');
            $table->text('persyaratan')->nullable();
            $table->string('kompensasi')->nullable();
            $table->boolean('status_aktif')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};