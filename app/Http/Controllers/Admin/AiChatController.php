<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AiChatController extends Controller
{
    /**
     * Menangani query pengguna, menjalankan query database, dan menyiapkan konteks untuk AI Gemini.
     */
    public function handleQuery(Request $request)
    {
        $query = strtolower($request->input('query'));
        $data = [];
        $analysisContext = '';

        try {

            if (str_contains($query, 'laba') && str_contains($query, 'hari ini')) {
                $data = $this->getDailyGrossProfit(Carbon::today());
                $analysisContext = 'laba kotor (gross profit) untuk hari ini.';
            } elseif (str_contains($query, 'laba')) {
                $data = $this->getTotalGrossProfit();
                $analysisContext = 'total laba kotor akumulatif.';
            } elseif (str_contains($query, 'produk') && (str_contains($query, 'habis') || str_contains($query, 'akan habis'))) {
                $data = $this->getLowStockProducts(10)->toArray();
                $analysisContext = 'daftar produk dengan stok rendah (dibawah 10) atau habis.';
            } elseif (str_contains($query, 'laris') || str_contains($query, 'terjual')) {
                $data = $this->getTopSellingProducts(5)->toArray();
                $analysisContext = '5 produk terlaris berdasarkan kuantitas terjual.';
            } elseif (str_contains($query, 'transaksi')) {
                $data = $this->getDailyTransaction(Carbon::today());
                $analysisContext = 'total transaksi kasir hari ini.';
            } else {
                $data = [];
                $analysisContext = 'tidak ada data spesifik yang diminta, jawablah pertanyaan ini secara umum.';
            }

            $systemPrompt = "Anda adalah Analis POS profesional. Tugas Anda adalah menganalisis data toko kasir yang disediakan. Jawablah pertanyaan pengguna ('$query') dalam Bahasa Indonesia yang ramah, ringkas, dan fokus pada data yang relevan. Data yang Anda dapatkan adalah: " . json_encode($data);

            return response()->json([
                'prompt' => $query,
                'system_instruction' => $systemPrompt,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['answer' => 'Terjadi kesalahan server saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    private function getDailyGrossProfit(Carbon $date)
    {
        $transactions = Transaction::with('details.product')
            ->whereDate('created_at', $date)
            ->get();

        $profit = $transactions->sum(function ($transaction) {
            return $transaction->details->sum(function ($detail) {
                $cost = $detail->product->purchase_price ?? 0;
                $revenue = $detail->selling_price;
                $quantity = $detail->quantity;
                return ($revenue - $cost) * $quantity;
            });
        });

        return ['date' => $date->format('Y-m-d'), 'gross_profit' => $profit];
    }

    private function getTotalGrossProfit()
    {
        $transactions = Transaction::with('details.product')->get();

        $profit = $transactions->sum(function ($transaction) {
            return $transaction->details->sum(function ($detail) {
                $cost = $detail->product->purchase_price ?? 0;
                $revenue = $detail->selling_price;
                $quantity = $detail->quantity;
                return ($revenue - $cost) * $quantity;
            });
        });

        return ['total_gross_profit' => $profit];
    }

    private function getDailyTransaction(Carbon $date)
    {
        $todaDate = $date->format('Y-m-d');
        $transactionCount = Transaction::whereDate('created_at', $todaDate)->count();

        return ['transaction_today' => $transactionCount];
    }

    private function getLowStockProducts($threshold)
    {
        return Product::where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($product) {
                return (object) [
                    'name' => $product->product_name,
                    'stock' => $product->stock,
                    'min_stock' => $product->stock_minimum
                ];
            });
    }

    private function getTopSellingProducts($limit)
    {
        $today = Carbon::today();
        return DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(DB::raw('product_id, SUM(quantity) as total_quantity'))
            // ->whereDate('transactions.created_at', $today)
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return (object) [
                    'name' => $product->product_name ?? 'Produk Dihapus',
                    'total_sold' => $item->total_quantity,
                ];
            });
    }
}
