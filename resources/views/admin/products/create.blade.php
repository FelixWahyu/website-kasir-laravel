@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Tambah Produk Baru</h1>

        <div class="bg-white p-8 max-w-5xl rounded-lg shadow-md">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        {{-- Nama Produk --}}
                        <div>
                            <label for="product_name" class="block text-sm font-medium text-gray-700">Nama Produk *</label>
                            <input type="text" name="product_name" id="product_name" required
                                value="{{ old('product_name') }}"
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('product_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori *</label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Beli --}}
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli (Modal)
                                *</label>
                            <input type="number" name="purchase_price" id="purchase_price" required
                                value="{{ old('purchase_price') }}"
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('purchase_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">Harga Jual *</label>
                            <input type="number" name="selling_price" id="selling_price" required
                                value="{{ old('selling_price') }}"
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('selling_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        {{-- SKU/Barcode --}}
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU / Barcode</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Stok Saat Ini --}}
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stok Saat Ini *</label>
                            <input type="number" name="stock" id="stock" required value="{{ old('stock', 0) }}"
                                min="0" class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Stok Minimum --}}
                        <div>
                            <label for="stock_minimum" class="block text-sm font-medium text-gray-700">Stok Minimum
                                (Notifikasi) *</label>
                            <input type="number" name="stock_minimum" id="stock_minimum" required
                                value="{{ old('stock_minimum', 5) }}" min="0"
                                class="mt-1 block w-full border p-2 border-gray-500 rounded-lg shadow-sm">
                            @error('stock_minimum')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gambar Produk --}}
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Gambar Produk (Max
                                2MB)</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="mt-1 block w-full text-sm border border-gray-500 rounded-lg text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('products.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 font-semibold cursor-pointer text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
