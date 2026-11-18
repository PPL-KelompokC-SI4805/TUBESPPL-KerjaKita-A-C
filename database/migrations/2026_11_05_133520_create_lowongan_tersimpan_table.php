<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan_tersimpan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->text('catatan_pribadi')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'lowongan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan_tersimpan');
    }
};