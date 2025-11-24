@extends('layouts.auth-layout')
@section('content')
    <div>
        <h2 class="text-xl font-bold mb-4">Edit User</h2>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border p-2 w-full mb-3">

            <label>Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="border p-2 w-full mb-3">

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border p-2 w-full mb-3">

            <label>Role</label>
            <select name="role" class="border p-2 w-full mb-3">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
            </select>

            <label>Password (kosongkan jika tidak ganti)</label>
            <input type="password" name="password" class="border p-2 w-full mb-3">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="border p-2 w-full mb-3">

            <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </form>
    </div>
@endsection
