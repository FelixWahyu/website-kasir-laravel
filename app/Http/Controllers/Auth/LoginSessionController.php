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
     * Memproses login.
     */
    public function store(Request $request,)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($request->input('username')) . '|' . $request->ip() . '|' . substr(hash('sha256', $request->userAgent()), 0, 10);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 30);

            throw ValidationException::withMessages([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

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
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
