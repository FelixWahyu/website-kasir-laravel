@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Tambah Kategori Baru</h1>

        <div class="max-w-3xl bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-800">Nama
                        Kategori</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 mt-1 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('categories.index') }}"
                        class="px-4 py-2 text-gray-800 bg-gray-200 rounded-md hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 font-semibold cursor-pointer text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
