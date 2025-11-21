@extends('layouts.auth-layout')
@section('content')
    <div class="container p-2 mx-auto max-w-7xl sm:px-4 lg:px-6">
        <h1 class="text-3xl font-bold mb-8">Dashboard Kasir</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-600">
                <p class="text-sm text-gray-500">Total Transaksi Hari ini</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_transactions']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-600">
                <p class="text-sm text-gray-500">Total Pendapatan Hari ini</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_revenue']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-600">
                <p class="text-sm text-gray-500">Total Pelanggan Member</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_members']) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="bg-white p-6 rounded-lg border border-gray-300 shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Notifikasi Stok</h2>
                <table class="min-w-full divide-y divide-gray-200 border border-collapse border-gray-400">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">Produk</th>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">Stok</th>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $item)
                            <tr>
                                <td class="text-sm text-left px-2 py-1 border-b border-gray-400">{{ $item->product_name }}
                                </td>
                                <td class="text-sm text-center px-2 py-1 border-b border-gray-400">{{ $item->stock }}</td>
                                <td
                                    class="text-sm text-left px-2 py-1 rounded-lg border-b border-gray-400 {{ $item->status === 'Akan Habis' ? 'text-yellow-500 bg-yellow-50' : 'text-red-500 bg-red-50' }} font-semibold">
                                    {{ $item->status }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center px-2 py-1 text-gray-500">Stok Aman!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white col-span-2 p-6 border border-gray-300 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">History Transaksi</h2>
                <table class="min-w-full divide-y text-center divide-gray-200 border border-gray-400 border-collapse">
                    <thead class="bg-gray-700 border-b border-gray-400">
                        <tr>
                            <th class="px-3 py-2 text-xs text-white">Invoice</th>
                            <th class="px-3 py-2 text-xs text-white">Kasir</th>
                            <th class="px-3 py-2 text-xs text-white">Pelanggan</th>
                            <th class="px-3 py-2 text-xs text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="text-sm font-medium px-2 py-1 border-b border-gray-400">
                                    {{ $transaction->invoice_number }}</td>
                                <td class="text-sm px-2 py-1 border-b border-gray-400">{{ $transaction->user->name }}</td>
                                <td class="text-sm px-2 py-1 border-b border-gray-400">
                                    {{ $transaction->customer->name ?? 'Umum' }}</td>
                                <td class="text-sm font-semibold px-2 py-1 border-b border-gray-400">Rp
                                    {{ number_format($transaction->total_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center px-2 py-1 text-gray-500">Belum ada transaksi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
