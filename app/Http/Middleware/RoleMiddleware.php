<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Cek apakah role pengguna cocok dengan role yang disyaratkan
        // Menggunakan strtolower agar perbandingan tidak case-sensitive
        if (!in_array($user->role, $roles)) {
            // Jika role tidak cocok, kembalikan ke home dengan pesan error
            return redirect('/')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
