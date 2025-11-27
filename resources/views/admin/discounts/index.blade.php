@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Manajemen Diskon Member</h1>

        @if (session('success'))
            <div class="alert-message p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-message p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('discounts.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Aturan Diskon
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase border-b border-gray-400">
                            Nama Diskon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase border-b border-gray-400">
                            Persyaratan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase border-b border-gray-400">
                            Diskon (%)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase border-b border-gray-400">
                            Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-center text-white uppercase border-b border-gray-400">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($discounts as $discount)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-medium">{{ $discount->name }}</p>
                                <p class="text-xs text-gray-500">{{ $discount->description }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                Total Min: Rp {{ number_format($discount->min_total_transaction) }} <br>
                                Max Transaksi: {{ $discount->max_transactions_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">
                                {{ number_format($discount->percentage, 0) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold items-center rounded-full 
                                            {{ $discount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $discount->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <form action="{{ route('discounts.toggle', $discount) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" class="sr-only peer"
                                                onchange="this.form.submit()" {{ $discount->is_active ? 'checked' : '' }}>

                                            <div
                                                class="w-12 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-blue-500 transition-all duration-300">
                                            </div>

                                            <div
                                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-6 shadow">
                                            </div>
                                        </label>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('discounts.edit', $discount) }}"
                                        class="text-yellow-600 flex items-center px-1 py-0.5 hover:text-yellow-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('discounts.destroy', $discount) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus diskon ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 cursor-pointer px-1 py-0.5 flex items-center hover:text-red-900">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada aturan diskon.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $discounts->links() }}
        </div>
    </div>
@endsection
