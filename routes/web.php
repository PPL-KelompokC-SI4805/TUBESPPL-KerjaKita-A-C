<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LowonganController;

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


        // M4: Manajemen Lowongan (F-10, F-11, F-12)
        Route::middleware('role:penyedia')->prefix('penyedia')->name('penyedia.')->group(function () {

            Route::get('/lowongan', [LowonganController::class, 'index'])
                ->name('lowongan.index');

            Route::get('/lowongan/tambah', [LowonganController::class, 'create'])
                ->name('lowongan.create');

            Route::post('/lowongan', [LowonganController::class, 'store'])
                ->name('lowongan.store');

            Route::get('/lowongan/{id}', [LowonganController::class, 'show'])
                ->name('lowongan.show');

            Route::get('/lowongan/{id}/edit', [LowonganController::class, 'edit'])
                ->name('lowongan.edit');

            Route::put('/lowongan/{id}', [LowonganController::class, 'update'])
                ->name('lowongan.update');
            
            Route::patch('/lowongan/{id}/status', [LowonganController::class, 'toggleStatus'])
                ->name('lowongan.toggle-status');

            Route::delete('/lowongan/{id}', [LowonganController::class, 'destroy'])
              ->name('lowongan.destroy');
        });


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

    // M5: Pencarian & Detail Lowongan Mahasiswa (F-13, F-14)
    Route::get('/mahasiswa/lowongan', function () {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini khusus untuk peran mahasiswa.');
        }

        $token = $user->createToken('web-session-' . $user->id)->plainTextToken;

        return view('mahasiswa.lowongan', [
            'apiToken' => $token,
            'user' => $user,
        ]);
    })->name('mahasiswa.lowongan');

    // M5: Lowongan Tersimpan (F-15)
    Route::get('/mahasiswa/lowongan-tersimpan', function () {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini khusus untuk peran mahasiswa.');
        }

        $token = $user->createToken('web-session-' . $user->id)->plainTextToken;

        return view('mahasiswa.lowongan_tersimpan', [
            'apiToken' => $token,
            'user' => $user,
        ]);
    })->name('mahasiswa.lowongan_tersimpan');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});