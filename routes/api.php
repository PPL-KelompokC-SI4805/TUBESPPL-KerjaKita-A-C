<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaProfileController;
use App\Http\Controllers\PenyediaProfileController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\LowonganTersimpanController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\PelamarController;

// ─── Route yang memerlukan autentikasi (Sanctum) ─────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // ─── Profil Mahasiswa (M2: F-04, F-05, F-06) ─────────────────
    // Hanya pengguna dengan role 'mahasiswa' yang dapat mengakses.
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {

        // F-04: Melihat profil mahasiswa
        Route::get('/profile', [MahasiswaProfileController::class, 'show']);

        // F-05: Mengubah profil mahasiswa
        Route::put('/profile', [MahasiswaProfileController::class, 'update']);

        // F-06: Unggah / ganti CV
        Route::post('/profile/cv', [MahasiswaProfileController::class, 'uploadCv']);

        // F-06: Hapus CV
        Route::delete('/profile/cv', [MahasiswaProfileController::class, 'deleteCv']);

        // F-19 (PBI-19): Unggah / ganti & hapus Transkrip Nilai
        Route::post('/profile/transkrip', [MahasiswaProfileController::class, 'uploadTranskrip']);
        Route::delete('/profile/transkrip', [MahasiswaProfileController::class, 'deleteTranskrip']);

        // ─── Pencarian Lowongan (M5: F-13, F-14) ─────────────────
        Route::get('/lowongan', [LowonganController::class, 'index']);
        Route::get('/lowongan/categories', [LowonganController::class, 'categories']);
        Route::get('/lowongan/{id}', [LowonganController::class, 'show']);

        // ─── Lowongan Tersimpan (M5: F-15) ──────────────────────
        Route::get('/lowongan-tersimpan', [LowonganTersimpanController::class, 'index']);
        Route::post('/lowongan-tersimpan', [LowonganTersimpanController::class, 'store']);
        Route::put('/lowongan-tersimpan/{id}', [LowonganTersimpanController::class, 'update']);
        Route::delete('/lowongan-tersimpan/{id}', [LowonganTersimpanController::class, 'destroy']);

        // ─── Lamaran Mahasiswa (M6) ─────────────────────────────
        Route::get('/lamaran', [LamaranController::class, 'index']);
        Route::post('/lamaran', [LamaranController::class, 'store']);
        Route::get('/lamaran/{id}', [LamaranController::class, 'show']);
        Route::put('/lamaran/{id}', [LamaranController::class, 'update']);
        Route::delete('/lamaran/{id}', [LamaranController::class, 'destroy']);
    });

    // ─── Profil Penyedia (M3: F-07, F-08, F-09) ──────────────────
    // Hanya pengguna dengan role 'penyedia' yang dapat mengakses.
    Route::middleware('role:penyedia')->prefix('penyedia')->group(function () {

        // F-07: Melihat profil organisasi
        Route::get('/profile', [PenyediaProfileController::class, 'show']);

        // F-08: Mengubah profil organisasi
        Route::put('/profile', [PenyediaProfileController::class, 'update']);

        // F-09: Unggah / ganti logo
        Route::post('/profile/logo', [PenyediaProfileController::class, 'uploadLogo']);

        // F-09: Hapus logo
        Route::delete('/profile/logo', [PenyediaProfileController::class, 'deleteLogo']);

        // ─── Daftar Pelamar Lowongan (M6) ───────────────────────
        Route::get('/lowongan/{id}/pelamar', [PelamarController::class, 'index']);
    });

});