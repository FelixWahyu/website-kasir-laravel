@extends('layouts.auth-layout')
@section('content')
    <div class="container max-w-3xl">
        <h2 class="text-3xl font-bold mb-6">Edit User : {{ $user->name }}</h2>
        <div class="bg-white p-4 shadow-md border border-gray-300 rounded-md">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="text-sm block font-medium">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        class="border border-gray-400 shadow-sm rounded-lg p-2 w-full mt-1">
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                        class="border border-gray-400 mt-1 shadow-sm rounded-lg p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="border border-gray-400 mt-1 shadow-sm p-2 w-full rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium">Role</label>
                    <select name="role" id="role"
                        class="border border-gray-400 shadow-sm mt-1 p-2 w-full rounded-lg">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                </div>
                <h2 class="text-gray-500 text-xs font-medium mt-3 mb-1">Kosongkan password jika tidak ingin diubah</h2>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" name="password"
                        class="border border-gray-400 shadow-sm rounded-lg mt-1 p-2 w-full">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="border border-gray-400 shadow-sm rounded-lg mt-1 p-2 w-full">
                </div>

                <div class="flex justify-end gap-4 items-center">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 bg-gray-100 border border-gray-300 shadow-sm rounded-md hover:bg-gray-200">Batal</a>
                    <button
                        class="px-4 py-2 bg-blue-600 cursor-pointer text-white rounded-md shadow-sm hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
