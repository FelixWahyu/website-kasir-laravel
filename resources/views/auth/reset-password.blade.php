@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-6 bg-white border border-gray-300 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center">Buat Password Baru</h1>

            @if (session('status'))
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" id="email" required readonly
                        class="w-full px-3 py-2 mt-1 border rounded-md bg-gray-100 cursor-not-allowed"
                        value="{{ $email }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium">Password Baru</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 mt-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3 py-2 mt-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Rubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
