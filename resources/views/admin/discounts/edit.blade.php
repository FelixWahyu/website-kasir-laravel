@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 max-w-7xl sm:px-4 lg:px-6">
        <h1 class="text-3xl font-bold mb-6">Buat Aturan Diskon Baru</h1>

        <div class="bg-white p-6 border border-gray-300 rounded-lg shadow-md">
            <form action="{{ route('discounts.update', $discount) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Diskon</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $discount->name) }}"
                        class="mt-1 block w-full p-2 border border-gray-400 rounded-lg shadow-sm">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="2"
                        class="mt-1 block w-full p-2 border border-gray-400 rounded-lg shadow-sm">{{ old('description', $discount->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="text-md font-semibold pt-4">Persyaratan Member</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="min_total_transaction" class="block text-sm font-medium text-gray-700">Min. Total
                            Belanja (Rp)</label>
                        <input type="number" name="min_total_transaction" id="min_total_transaction" required
                            value="{{ old('min_total_transaction', $discount->min_total_transaction) }}" min="0"
                            class="mt-1 block w-full p-2 border border-gray-400 rounded-lg shadow-sm">
                        @error('min_total_transaction')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Total belanja akumulatif pelanggan.</p>
                    </div>
                    <div>
                        <label for="max_transactions_count" class="block text-sm font-medium text-gray-700">Max. Jumlah
                            Transaksi</label>
                        <input type="number" name="max_transactions_count" id="max_transactions_count" required
                            value="{{ old('max_transactions_count', $discount->max_transactions_count) }}" min="0"
                            class="mt-1 block w-full p-2 border border-gray-400 rounded-lg shadow-sm">
                        @error('max_transactions_count')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal transaksi member untuk mendapatkan diskon ini.</p>
                    </div>
                </div>

                <h3 class="text-md font-semibold pt-4">Nilai Diskon</h3>
                <div>
                    <label for="percentage" class="block text-sm font-medium text-gray-700">Persentase Diskon (%)</label>
                    <input type="number" name="percentage" id="percentage" required
                        value="{{ old('percentage', $discount->percentage) }}" min="0" max="100" step="0.01"
                        class="mt-1 block w-full p-2 border border-gray-400 rounded-lg shadow-sm">
                    @error('percentage')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-4 gap-4">
                    <a href="{{ route('discounts.index') }}"
                        class="px-4 py-2 bg-gray-100 border border-gray-300 shadow-sm rounded-md hover:bg-gray-200">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 font-semibold text-white cursor-pointer bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Diskon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
