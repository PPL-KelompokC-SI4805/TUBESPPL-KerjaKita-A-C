<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

// Guest Routes (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.reset.custom');
});

// Authenticated Routes (Sudah Login -> Halaman Utama)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // M2: Profil Mahasiswa & Kelola CV (F-04, F-05, F-06)
    Route::get('/mahasiswa/profil', function () {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini khusus untuk peran mahasiswa.');
        }

        // Buat atau ambil API token Sanctum untuk integrasi REST API
        $token = $user->createToken('web-session-' . $user->id)->plainTextToken;

        return view('mahasiswa.profil', [
            'apiToken' => $token,
            'user' => $user,
        ]);
    })->name('mahasiswa.profil');

     // M3: Profil Penyedia & Kelola Logo (F-07, F-08, F-09)
    Route::get('/penyedia/profil', function () {
        $user = Auth::user();
        if ($user->role !== 'penyedia') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini khusus untuk peran penyedia.');
        }

        // Buat API token Sanctum untuk integrasi REST API
        $token = $user->createToken('web-session-' . $user->id)->plainTextToken;

        return view('penyedia.profil', [
            'apiToken' => $token,
            'user' => $user,
        ]);
    })->name('penyedia.profil');

    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});