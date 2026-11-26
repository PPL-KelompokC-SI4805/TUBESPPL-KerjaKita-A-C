<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Middleware RBAC — membatasi akses endpoint berdasarkan peran pengguna.
     *
     * Penggunaan pada route:
     *   ->middleware('role:mahasiswa')
     *   ->middleware('role:penyedia')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles   Daftar peran yang diizinkan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.',
            ], 403);
        }

        return $next($request);
    }
}
