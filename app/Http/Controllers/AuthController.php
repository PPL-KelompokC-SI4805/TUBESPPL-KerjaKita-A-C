<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\PenyediaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login (PBI-02) -> Langsung ke Halaman Utama
    public function login(Request $request)
    {
        $credentials =$request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {$request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali di KerjaKita!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Registrasi (PBI-01) -> Langsung ke Halaman Utama
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mahasiswa,penyedia',
            'nama_lengkap' => 'required_if:role,mahasiswa|nullable|string|max:255',
            'nim' => 'nullable|string|max:50',
            'universitas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nama_organisasi' => 'required_if:role,penyedia|nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
        ]);

        $user = DB::transaction(function () use ($request) {$newUser = User::create([
                'name' => $request->role === 'mahasiswa' ? $request->nama_lengkap :$request->nama_organisasi,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'mahasiswa') {
                MahasiswaProfile::create([
                    'user_id' => $newUser->id,
                    'nama_lengkap' => $request->nama_lengkap,
                    'nim' => $request->nim,
                    'universitas' => $request->universitas,
                    'program_studi' => $request->program_studi,
                    'semester' => 1,
                ]);
            } else {
                PenyediaProfile::create([
                    'user_id' => $newUser->id,
                    'nama_organisasi' => $request->nama_organisasi,
                    'bidang_usaha' => $request->bidang_usaha,
                ]);
            }

            return $newUser;
        });

        Auth::login($user);
        return redirect('/dashboard')->with('success', 'Registrasi akun berhasil!');
    }

    // Tampilkan Halaman Lupa / Ganti Password (PBI-03)
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Proses Reset / Ganti Password dari Halaman Login (PBI-03)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'email.exists' => 'Email tersebut tidak terdaftar di sistem KerjaKita.',
        ]);

        $user = User::where('email', $request->email)->first();$user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();$request->session()->regenerateToken();
        return redirect('/login');
    }
}