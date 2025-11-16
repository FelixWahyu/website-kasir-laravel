@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 mx-auto max-w-7xl sm:px-2 lg:px-4">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Manajemen Produk</h1>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div id="alert-success" class="alert-message p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="alert-error" class="alert-message p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between mb-4">
            {{-- Anda bisa tambahkan filter atau pencarian di sini --}}
            <div class="w-1/3">
                {{--  --}}
            </div>
            <a href="{{ route('products.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Produk Baru
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Nama (SKU)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Harga Jual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-10 h-10 object-cover rounded-full">
                                @else
                                    <span class="text-gray-400 text-xs">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp
                                {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="font-bold {{ $product->stock <= $product->stock_minimum ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $product->stock }}
                                </span>
                                <span class="text-xs text-gray-500"> (Min: {{ $product->stock_minimum }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Hati-hati! Menghapus produk akan mempengaruhi laporan. Lanjutkan?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
@push('script')
    <script>
        document.querySelectorAll(".alert-message").forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    alert.style.transition = 'opacity 0.4s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 400);
                }
            }, 2500);
        });
    </script>
@endpush
