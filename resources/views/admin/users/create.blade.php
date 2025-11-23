@extends('layouts.auth-layout')
@section('content')
    <div>
        <h2>Tambah User baru</h2>
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="name" id="name" required autofocus
                    class="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium">Username</label>
                <input type="text" name="username" id="username" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('username') }}">
                @error('username')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Role</label>
                <select name="role" class="border p-2 w-full mb-3">
                    <option value="">--Pilih Role--</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <button type="submit"
                    class="w-full px-4 py-2 font-semibold text-white cursor-pointer bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
