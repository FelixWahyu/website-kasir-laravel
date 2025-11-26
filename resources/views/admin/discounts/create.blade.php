@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-2xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Buat Aturan Diskon Baru</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('discounts.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Diskon</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="2"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="text-md font-semibold pt-4 border-t">Persyaratan Member</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="min_total_transaction" class="block text-sm font-medium text-gray-700">Min. Total
                            Belanja (Rp)</label>
                        <input type="number" name="min_total_transaction" id="min_total_transaction" required
                            value="{{ old('min_total_transaction', 0) }}" min="0"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('min_total_transaction')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Total belanja akumulatif pelanggan.</p>
                    </div>
                    <div>
                        <label for="max_transactions_count" class="block text-sm font-medium text-gray-700">Max. Jumlah
                            Transaksi</label>
                        <input type="number" name="max_transactions_count" id="max_transactions_count" required
                            value="{{ old('max_transactions_count', 9999) }}" min="0"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('max_transactions_count')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal transaksi member untuk mendapatkan diskon ini.</p>
                    </div>
                </div>

                <h3 class="text-md font-semibold pt-4 border-t">Nilai Diskon</h3>
                <div>
                    <label for="percentage" class="block text-sm font-medium text-gray-700">Persentase Diskon (%)</label>
                    <input type="number" name="percentage" id="percentage" required value="{{ old('percentage') }}"
                        min="0" max="100" step="0.01"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('percentage')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Diskon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
