@extends('layouts.auth-layout')
@section('content')
    <div class="p-2">
        <h2 class="text-3xl font-bold mb-6">Manajemen Users</h2>

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

        <div class="flex justify-end items-center">
            <a href="{{ route('users.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md mb-4 shadow-sm hover:bg-blue-700">+ Tambah User</a>
        </div>

        <div class="overflow-x-auto rounded-lg bg-white shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium uppercase">Nama</th>
                        <th class="py-3 px-6 text-left text-xs font-medium uppercase">Username</th>
                        <th class="py-3 px-6 text-left text-xs font-medium uppercase">Email</th>
                        <th class="py-3 px-6 text-left text-xs font-medium uppercase">Role</th>
                        <th class="py-3 px-6 text-center text-xs font-medium uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="border-b border-gray-300 py-3 px-5 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="border-b border-gray-300 py-3 px-5 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="border-b border-gray-300 py-3 px-5 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="border-b border-gray-300 py-3 px-5 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                            <td class="border-b border-gray-300 py-3 px-5 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2 justify-center">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="flex items-center px-1 py-0.5 text-yellow-600 hover:text-yellow-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('users.delete', $user) }}" method="POST"
                                        onsubmit="openConfirmModal(this,event);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center px-1 py-0.5 text-red-500 cursor-pointer hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <div id="confirmModal"
        class="fixed hidden inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center transition-opacity duration-300">
        <div id="modalBox"
            class="bg-white p-6 rounded-2xl shadow-xl max-w-md w-full scale-90 opacity-0 transition-all duration-300">
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
                Menghapus user akan mengubah laporan penjualan.
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
