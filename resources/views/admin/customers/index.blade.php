@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 mx-auto max-w-7xl sm:px-4 lg:px-6">
        <h1 class="text-3xl font-bold mb-6 dark:text-gray-100">Manajemen Pelanggan (Member)</h1>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div id="alert-success" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}
            </div>
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
                                        class="text-indigo-600 flex items-center px-1 py-0.5 hover:text-indigo-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $customers->links() }}</div>
    </div>
@endsection
@push('script')
    <script>
        const alert = document.getElementById('alert-success');
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 2500);
    </script>
@endpush
