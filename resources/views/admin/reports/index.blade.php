@extends('layouts.auth-layout')
@section('content')
    <div class="container px-2 mx-auto max-w-7xl sm:px-4 lg:px-6">
        <h1 class="text-3xl font-bold mb-6">Laporan Penjualan</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <form method="GET" action="{{ route('admin.reports') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $startDate) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $endDate) }}"
                            class="mt-1 block w-full p-1 border border-gray-400 rounded-md shadow-sm">
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-md cursor-pointer hover:bg-blue-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.reports') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow-md hover:bg-gray-400">
                            Reset
                        </a>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('admin.reports.pdf', request()->query()) }}"
                            class="px-4 py-2 bg-red-600 text-white rounded-md shadow-md hover:bg-red-700 {{ $transactions->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            target="_blank">
                            Export ke PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-600">
                <p class="text-sm font-medium text-gray-500">Total Transaksi (Unit)</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($transactions->total(), 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                <p class="text-sm font-medium text-gray-500">Total Penjualan Kotor (Net)</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-600">
                <p class="text-sm font-medium text-gray-500">Total Diskon Diberikan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
            </div>

            <div class="bg-emerald-50 p-4 rounded-lg border-l-4 border-emerald-600">
                <p class="text-sm font-medium text-gray-500">Total Laba Kotor</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalGrossProfit, 0, ',', '.') }}</p>
            </div>
        </div>


        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase">Total Net</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase">Laba Kotor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Kasir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        @php
                            // Hitung Laba Kotor per Transaksi
                            $transactionGrossProfit = $transaction->details->sum(function ($detail) {
                                $cost = $detail->product->purchase_price ?? 0;
                                $revenue = $detail->selling_price;
                                $quantity = $detail->quantity;
                                return ($revenue - $cost) * $quantity;
                            });
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $transaction->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right">Rp
                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-emerald-600">Rp
                                {{ number_format($transactionGrossProfit, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->user->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada transaksi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>

    </div>
@endsection
