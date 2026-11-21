<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaProfileController;
use App\Http\Controllers\PenyediaProfileController;

/*
|--------------------------------------------------------------------------
| API Routes — KerjaKita
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis mendapat prefix /api.
| Autentikasi menggunakan Laravel Sanctum (token-based).
|
*/

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
    });

});



