<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    const STOCK_THRESHOLD = 10;
    public function index()
    {
        // 1. Total Ringkasan
        $summary = $this->getSummaryData();

        // 2. Notifikasi Stok
        $lowStockProducts = $this->getLowStockProducts();

        // 3. Produk Terlaris (Top 5)
        $topSellingProducts = $this->getTopSellingProducts(5);

        // 4. Data Grafik Penjualan (7 Hari Terakhir)
        $chartData = $this->getSalesChartData(7);

        return view('admin.dashboard', compact(
            'summary',
            'lowStockProducts',
            'topSellingProducts',
            'chartData'
        ), ['title' => 'Dashboard Admin']);
    }

    // --- Metode Pengumpulan Data ---

    private function getSummaryData()
    {
        $totalTransactions = Transaction::all();
        $allTransactions = Transaction::with('details.product')->get(); // Load relasi untuk hitung laba

        // Helper untuk menghitung Gross Profit dari semua transaksi
        $totalGrossProfit = $allTransactions->sum(function ($transaction) {
            return $transaction->details->sum(function ($detail) {
                $cost = $detail->product->purchase_price ?? 0;
                $revenue = $detail->selling_price;
                $quantity = $detail->quantity;
                return ($revenue - $cost) * $quantity;
            });
        });

        return [
            'total_transactions' => $totalTransactions->count(), // Transaksi Hari Ini
            'total_revenue' => $totalTransactions->sum('total_amount'), // Pendapatan Hari Ini
            'total_gross_profit' => $totalGrossProfit, // Laba Kotor Total
            'total_members' => Customer::count(),
            'total_users' => User::count(),
            'total_products' => Product::count(),
        ];
    }

    private function getLowStockProducts()
    {
        return Product::where('stock', '<=', self::STOCK_THRESHOLD)
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($product) {
                $status = 'Akan Habis';
                if ($product->stock == 0) {
                    $status = 'Habis';
                }
                return (object) [
                    'product_name' => $product->product_name,
                    'stock' => $product->stock,
                    'status' => $status
                ];
            });
    }

    private function getTopSellingProducts($limit = 5)
    {
        return DB::table('transaction_details')
            ->select(DB::raw('product_id, SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return (object) [
                    'product_name' => $product->product_name ?? 'Produk Dihapus',
                    'total_sold' => $item->total_quantity,
                ];
            });
    }

    private function getSalesChartData($days = 7)
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D, d M'); // e.g., Sat, 15 Nov

            $dailyRevenue = Transaction::whereDate('created_at', $date)
                ->sum('total_amount');
            $data[] = $dailyRevenue;
        }

        return (object) [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
