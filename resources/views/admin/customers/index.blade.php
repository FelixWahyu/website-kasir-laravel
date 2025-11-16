@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Manajemen Pelanggan (Member)</h1>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('customers.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Pelanggan Baru
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Nomor Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->phone_number }}</td>
                            <td class="px-6 py-4">{{ Str::limit($customer->address, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $customers->links() }}</div>
    </div>
@endsection
