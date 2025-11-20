@extends('layouts.app')
@section('content')
    <div class="container mx-auto mt-16 max-w-lg px-6 py-4 rounded-md bg-white border border-gray-300 shadow-md">
        <h2 class="text-xl text-center font-semibold">Email Rubah Password</h2>
        <p class="text-center mb-4">Masukan alamat email yang terdaftar diakun anda untuk dapat melanjutkan rubah password.
        </p>
        @if (session('status'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->has('email'))
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ $errors->first('email') }}
            </div>
        @endif
        <form action="{{ route('send.email') }}" method="post" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-3 py-2 mt-1 border border-gray-300 shadow-sm rounded-md focus:ring-1 focus:outline-0 focus:border-2 focus:border-blue-600"
                    value="{{ old('email') }}">
            </div>
            <div>
                <button type="submit"
                    class="w-full px-4 py-2 font-semibold cursor-pointer text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Kirim Link Reset
                </button>
            </div>
        </form>
        <p class="mt-4 text-center">
            <a href="{{ route('login') }}" class="font-medium text-sm text-blue-500 hover:underline">Kembali ke Login</a>
        </p>
    </div>
@endsection
