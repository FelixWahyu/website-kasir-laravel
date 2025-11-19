@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Edit Kategori: {{ $category->name }}</h1>

        <div class="max-w-3xl bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                        Kategori</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 mt-1 border border-gray-500 rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('name', $category->name) }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('categories.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 font-semibold cursor-pointer text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
