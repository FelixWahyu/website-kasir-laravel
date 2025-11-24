@extends('layouts.auth-layout')
@section('content')
    <h2 class="text-3xl font-semibold mb-6">Tambah User baru</h2>
    <div class="container max-w-3xl bg-white shadow-md rounded-lg border border-gray-300 p-2">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6 p-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="name" id="name" required autofocus
                    class="w-full px-3 py-2 mt-1 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium">Username</label>
                <input type="text" name="username" id="username" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('username') }}">
                @error('username')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium">Role</label>
                <select id="role" name="role" class="border border-gray-400 px-4 py-2 mt-1 w-full rounded-md mb-3">
                    <option value="">--Pilih Role--</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="off"
                    class="w-full px-3 py-2 mt-1 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 font-semibold text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-md shadow-sm">Batal</a>
                <button type="submit"
                    class="px-4 py-2 font-semibold text-white cursor-pointer bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
