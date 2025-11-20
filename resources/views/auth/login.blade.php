@extends('layouts.app')
@section('content')
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md border border-gray-300">
            <h1 class="text-2xl font-bold text-center text-gray-800">Login Sistem Kasir</h1>

            @if (session('status'))
                <div class="px-4 py-3 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-6">
                @csrf <div>
                    <label for="username" class="block text-sm text-gray-800 font-medium">Username</label>
                    <input type="text" name="username" id="username" required autofocus
                        class="w-full px-3 py-2 mt-1 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('username') }}">
                    @error('username')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm text-gray-800 font-medium">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 mt-1 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" name="remember" id="remember"
                            class="rounded dark:bg-gray-700 border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-800">Ingat saya</span>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white cursor-pointer bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Login
                    </button>
                </div>
            </form>

            <p class="text-sm text-center">
                Belum punya akun?
                <a href="/register" class="font-medium text-blue-500 hover:underline">Register di sini</a>
            </p>
        </div>
    </div>
@endsection
