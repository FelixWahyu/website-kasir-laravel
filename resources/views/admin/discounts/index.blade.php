@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-6">Manajemen Diskon Member</h1>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('discounts.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                + Tambah Aturan Diskon
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Diskon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persyaratan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diskon (%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
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
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $discount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $discount->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3">
                                    <a href="{{ route('discounts.edit', $discount) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('discounts.toggle', $discount) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-{{ $discount->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $discount->is_active ? 'yellow' : 'green' }}-900">
                                            {{ $discount->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('discounts.destroy', $discount) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus diskon ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
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
