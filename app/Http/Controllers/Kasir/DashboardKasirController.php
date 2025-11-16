<?php

namespace App\Http\Controllers\Kasir;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardKasirController extends Controller
{
    const STOCK_THRESHOLD = 10;
    public function index()
    {
        $today = now()->today();

        // 1. Total Ringkasan (Hari Ini)
        $todayTransactions = Transaction::whereDate('created_at', $today);

        $summary = [
            'total_transactions' => $todayTransactions->count(),
            'total_revenue' => $todayTransactions->sum('total_amount'),
            'total_members' => Customer::where('is_member', true)->count(), // Total member aktif
        ];

        // 2. Notifikasi Stok
        $lowStockProducts = $this->getLowStockProducts();

        // 3. History Transaksi (10 Transaksi Terakhir Hari Ini)
        $recentTransactions = Transaction::with('customer')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('kasir.dashboard', compact(
            'summary',
            'lowStockProducts',
            'recentTransactions'
        ), ['title' => 'Sistem Kasir']);
    }

    private function getLowStockProducts()
    {
        return Product::where('stock', '<=', self::STOCK_THRESHOLD)
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($product) {
                $status = $product->stock == 0 ? 'Habis' : 'Akan Habis';
                return (object) [
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'status' => $status
                ];
            });
    }
}
