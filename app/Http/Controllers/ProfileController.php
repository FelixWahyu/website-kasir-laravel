<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form Edit Profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi data diri pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email harus unik, kecuali jika emailnya adalah email user saat ini
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Username harus unik, kecuali jika username-nya adalah username user saat ini
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:15'],
        ]);

        // 2. Update Data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Password
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // 2. Verifikasi Password Lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama Anda salah.']);
        }

        // 3. Update Password
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // Sign Out semua sesi kecuali sesi saat ini (opsional)
        Auth::logoutOtherDevices($request->current_password);

        return redirect()->route('profile.edit')
            ->with('success', 'Password berhasil diubah!');
    }
}
