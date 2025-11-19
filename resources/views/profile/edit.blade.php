@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Manajemen Profil Pengguna</h1>

        @if (session('success'))
            <div id="alert-success" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">

            <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl">
                <h2 class="text-xl font-semibold mb-4 pb-2">Update Data Diri</h2>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username (Untuk Login)</label>
                        <input type="text" name="username" id="username" required
                            value="{{ old('username', $user->username) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md cursor-pointer hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md max-w-3xl">
                <h2 class="text-xl font-semibold mb-4 pb-2">Rubah Password</h2>

                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-lg shadow-sm">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md cursor-pointer hover:bg-blue-700">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        const alert = document.getElementById('alert-success');
        setTimeout(() => {
            if (alert) {
                alert.style.transition = 'opacity 0.4s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 400);
            }
        }, 2500);
    </script>
@endpush
