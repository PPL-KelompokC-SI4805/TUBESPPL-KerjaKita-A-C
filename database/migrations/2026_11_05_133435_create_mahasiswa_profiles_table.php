<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nim', 50)->nullable();
            $table->string('universitas')->nullable();
            $table->string('program_studi')->nullable();
            $table->integer('semester')->unsigned()->nullable();
            $table->string('domisili', 100)->nullable();
            $table->text('keahlian')->nullable();
            $table->string('ketersediaan_waktu')->nullable();
            $table->string('cv_file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_profiles');
    }
};