<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa_profiles')->onDelete('cascade');
            $table->text('surat_lamaran')->nullable();
            $table->string('cv_snapshot_path')->nullable();
            $table->string('status_lamaran', 50)->default('Terkirim');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'lowongan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamaran');
    }
};