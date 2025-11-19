@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 max-w-3xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Pengaturan Website</h1>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('POST')

                <div class="max-w-md">
                    <label for="app_name" class="block text-sm font-medium text-gray-700">Nama Website</label>
                    <input type="text" name="app_name" id="app_name"
                        value="{{ old('app_name', $settings['app_name'] ?? '') }}"
                        class="block w-full mt-1 p-1 border border-gray-500 rounded-lg shadow-sm">
                </div>

                <div class="max-w-md">
                    <label for="app_logo" class="block text-sm font-medium text-gray-700">Logo Website</label>
                    <input type="file" name="app_logo" id="app_logo"
                        class="block w-full border border-gray-500 rounded-lg p-1 mt-1 text-sm text-gray-500 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah logo.</p>
                    @error('app_logo')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror

                    @if (isset($settings['app_logo']) && $settings['app_logo'])
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700">Logo Saat Ini:</p>
                            <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo"
                                class="h-16 mt-2 rounded-md shadow-md bg-gray-100 p-2">
                        </div>
                    @else
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700">Logo Saat Ini:</p>
                            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                class="h-16 mt-2 rounded-md shadow-md bg-gray-100 p-2">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-4 py-2 font-semibold cursor-pointer text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
