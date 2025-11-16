<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman/form registrasi.
     */
    public function create()
    {
        return view('auth.register', ['title' => 'Register - POS']);
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\.\'\-]+$/u'],
            'username' => ['required', 'string', 'max:100', 'unique:users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            // 'password_confirmation' akan dicek oleh 'confirmed'
        ]);

        // 2. Buat User Baru
        // Kita belum set 'role' di sini. 
        // Secara default, migrasi kita akan mengisinya sebagai 'kasir'. Ini aman.
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'kasir'
            // 'password' akan di-hash otomatis oleh Casts di Model User
        ]);

        // 3. Login-kan User
        // Auth::login($user);

        // 4. Redirect ke Halaman Utama
        // Kita akan atur halaman ini nanti (misal: /dashboard)
        return redirect()->route('login');
    }
}
