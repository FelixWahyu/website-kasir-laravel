<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginSessionController extends Controller
{
    /**
     * Menampilkan halaman/form login.
     */
    public function create()
    {
        return view('auth.login', ['title' => 'Login - POS']);
    }

    /**
     * Memproses upaya login.
     */
    public function store(Request $request,)
    {
        // Validasi input dasar
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required', 'string'],
        ]);

        // Buat key unik untuk setiap username + IP address
        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip();

        // Cek apakah user terlalu sering mencoba login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik.",
            ]);
        }

        // Coba autentikasi user
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Tambah 1 hit ke limiter jika gagal
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ])->redirectTo(route('login'));
        }

        // Jika login berhasil, reset percobaan
        RateLimiter::clear($throttleKey);

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // Arahkan user sesuai role
        return match (Auth::user()->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'kasir' => redirect()->intended(route('kasir.dashboard')),
            default => redirect('/'),
        };
    }

    /**
     * Memproses logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate(); // Matikan session

        $request->session()->regenerateToken(); // Buat token CSRF baru

        return redirect()->route('login'); // Arahkan ke halaman login
    }
}
