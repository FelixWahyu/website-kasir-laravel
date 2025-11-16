@extends('layouts.auth-layout')
@section('content')
    <div class="h-full p-2 overflow-y-auto sm:px-2 lg:px-2">
        <h1 class="text-3xl font-bold mb-8">Dashboard Analytics</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-600">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_transactions']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-600">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_revenue']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-emerald-600">
                <p class="text-sm text-gray-500">Total Laba Kotor</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_gross_profit']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-600">
                <p class="text-sm text-gray-500">Total Member</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_members']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-pink-600">
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_users']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-600">
                <p class="text-sm text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_products']) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Grafik Penjualan (7 Hari Terakhir)</h2>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Produk Terlaris (Unit)</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-xs">Produk</th>
                                <th class="px-3 py-2 text-xs">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topSellingProducts as $item)
                                <tr>
                                    <td class="text-sm">{{ $item->product_name }}</td>
                                    <td class="text-sm font-semibold text-center">{{ number_format($item->total_sold) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Notifikasi Stok</h2>
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
                                    <td class="text-left text-sm">{{ $item->product_name }}</td>
                                    <td class="text-center text-sm">{{ $item->stock }}</td>
                                    <td
                                        class="text-left text-sm p-1 rounded-lg {{ $item->status === 'Akan Habis' ? 'text-yellow-500 bg-yellow-50' : 'text-red-500 bg-red-50' }} font-semibold">
                                        {{ $item->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500">Stok Aman!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.parsed.y !== null) {
                                    return 'Pendapatan: Rp ' + context.parsed.y.toString().replace(
                                        /\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                                return '';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
