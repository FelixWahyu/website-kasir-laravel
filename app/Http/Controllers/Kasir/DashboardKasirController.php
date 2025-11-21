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

        $todayTransactions = Transaction::whereDate('created_at', $today);

        $summary = [
            'total_transactions' => $todayTransactions->count(),
            'total_revenue' => $todayTransactions->sum('total_amount'),
            'total_members' => Customer::count(),
        ];

        $lowStockProducts = $this->getLowStockProducts();

        $recentTransactions = Transaction::with('user', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('kasir.dashboard', compact(
            'summary',
            'lowStockProducts',
            'recentTransactions'
        ), ['title' => 'Dashboard']);
    }

    private function getLowStockProducts()
    {
        return Product::where('stock', '<=', self::STOCK_THRESHOLD)
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($product) {
                $status = $product->stock == 0 ? 'Habis' : 'Akan Habis';
                return (object) [
                    'product_name' => $product->product_name,
                    'stock' => $product->stock,
                    'status' => $status
                ];
            });
    }
}
