@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 mx-auto max-w-7xl sm:px-2 lg:px-4">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-800">Manajemen Produk</h1>

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

        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center w-1/3 gap-1">
                <form action="{{ route('products.index') }}" method="GET"
                    class="flex bg-white w-full border border-gray-400 items-center shadow-sm rounded-lg">
                    <input type="text" id="search" name="search" value="{{ $keyword }}"
                        class="px-3 py-1 text-gray-400 border-none outline-0 w-full" placeholder="Cari produk...">
                    <button type="submit"
                        class="bg-blue-600 py-2 px-3 border border-blue-600 rounded-r-lg cursor-pointer text-white font-bold hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </form>
                @if ($keyword)
                    <a href="{{ route('products.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow-md hover:bg-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                @endif
            </div>
            <a href="{{ route('products.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Produk Baru
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
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
                                        class="text-indigo-600 flex items-center px-1 py-0.5 hover:text-indigo-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                        onsubmit="openConfirmModal(event,this);">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 flex items-center px-1 py-0.5 cursor-pointer hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Hapus
                                        </button>
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
    <div id="confirmModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity duration-300">
        <div id="modalBox"
            class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md scale-90 opacity-0 transition-all duration-300">

            <div class="flex items-center space-x-3 mb-4">
                <div class="bg-red-100 text-red-600 w-10 h-10 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v4.5m0 3h.01M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h2>
            </div>

            <p class="text-gray-600 mb-6 leading-relaxed">
                Menghapus produk akan mengubah stok dan laporan penjualan.
                Apakah Anda yakin ingin melanjutkan?
            </p>

            <div class="flex justify-end space-x-2">
                <button id="cancelBtn"
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 cursor-pointer hover:bg-gray-300 transition">
                    Batal
                </button>

                <button id="confirmBtn"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white cursor-pointer hover:bg-red-700 transition">
                    Hapus
                </button>
            </div>
        </div>
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

        let formToSubmit = null;
        const modal = document.getElementById('confirmModal');
        const modalBox = document.getElementById('modalBox');

        function openConfirmModal(form) {
            event.preventDefault();
            formToSubmit = form;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modalBox.classList.remove('scale-90', 'opacity-0');
                modalBox.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        document.getElementById('cancelBtn').addEventListener('click', function() {
            closeModal();
        });

        document.getElementById('confirmBtn').addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        function closeModal() {
            modalBox.classList.add('scale-90', 'opacity-0');
            modalBox.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);

            formToSubmit = null;
        }
    </script>
@endpush
