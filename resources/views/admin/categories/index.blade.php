@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 mx-auto max-w-7xl sm:px-4 lg:px-6">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Manajemen Kategori</h1>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.400ms x-init="setTimeout(() => show = false, 2500)"
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.400ms x-init="setTimeout(() => show = false, 2500)"
                class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('categories.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Kategori Baru
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-800 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider dark:text-gray-300">
                            No</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider dark:text-gray-300">
                            Nama Kategori</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider dark:text-gray-300">
                            Dibuat Pada</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider dark:text-gray-300">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $category->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('categories.edit', $category) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>

                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data kategori. Silakan tambahkan satu!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
