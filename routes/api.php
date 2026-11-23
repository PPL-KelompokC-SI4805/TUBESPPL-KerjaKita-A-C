<?php
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\PelamarController;

// Di DALAM grup Route::middleware('role:mahasiswa')->prefix('mahasiswa'):
Route::get('/lamaran',         [LamaranController::class, 'index']);    // riwayat
Route::post('/lamaran',        [LamaranController::class, 'store']);    // ajukan
Route::get('/lamaran/{id}',    [LamaranController::class, 'show']);     // detail
Route::put('/lamaran/{id}',    [LamaranController::class, 'update']);   // ubah
Route::delete('/lamaran/{id}', [LamaranController::class, 'destroy']);  // batal

// Grup BARU (atau gabung ke grup penyedia milik Natasya kalau sudah ada):
Route::middleware('role:penyedia')->prefix('penyedia')->group(function () {
    Route::get('/lowongan/{id}/pelamar', [PelamarController::class, 'index']);
});
