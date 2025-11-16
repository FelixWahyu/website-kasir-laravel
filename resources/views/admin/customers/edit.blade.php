@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-4xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Edit Pelanggan: {{ $customer->name }}</h1>

        <div class="bg-white p-8 rounded-lg shadow-md">
            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Pelanggan --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Pelanggan *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $customer->name) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon (Unik) --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon (WA) *</label>
                    <input type="text" name="phone" id="phone" required
                        value="{{ old('phone', $customer->phone) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('customers.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 font-semibold text-white cursor-pointer bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
