@extends('layouts.auth-layout')
@section('content')
    <div class="container px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Dashboard Kasir Hari Ini</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-600">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_transactions']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-600">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_revenue']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-600">
                <p class="text-sm text-gray-500">Total Pelanggan Member</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_members']) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-red-600">🚨 Notifikasi Stok</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-xs">Produk</th>
                            <th class="px-3 py-2 text-xs">Stok</th>
                            <th class="px-3 py-2 text-xs">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $item)
                            <tr>
                                <td class="text-sm">{{ $item->name }}</td>
                                <td class="text-sm">{{ $item->stock }}</td>
                                <td class="text-sm text-red-500 font-semibold">{{ $item->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">Stok Aman!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">History Transaksi (10 Terakhir Hari Ini)</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-xs">Invoice</th>
                            <th class="px-3 py-2 text-xs">Pelanggan</th>
                            <th class="px-3 py-2 text-xs">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="text-sm font-medium">{{ $transaction->invoice_number }}</td>
                                <td class="text-sm">{{ $transaction->customer->name ?? 'Umum' }}</td>
                                <td class="text-sm font-semibold">Rp {{ number_format($transaction->total_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">Belum ada transaksi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
