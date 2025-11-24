<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|regex:/^[\pL\s\.\'\-]+$/u',
            'username' => 'required|string|max:100|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,kasir'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:admin,kasir']
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role']
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diupdate!');
    }

    public function delete(User $user)
    {
        if ($user->transactions()->exists()) {
            return redirect()->route('users.index')->with('error', 'User tidak dapat dihapus karena memiliki riwayat transaksi!');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda dapat menghapus akun anda sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data user berhasil dihapus!');
    }
}
