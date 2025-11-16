<?php

namespace App\Http\Controllers\Kasir;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        // Data yang dibutuhkan untuk tampilan POS:
        $products = Product::with('category')
            ->latest()
            ->get();

        $categories = Category::all();
        $customers = Customer::all();

        // Convert ke JSON untuk digunakan oleh Alpine.js
        $productsJson = $products->toJson();
        $customersJson = $customers->toJson();

        return view('kasir.pos.index', compact('productsJson', 'categories', 'customersJson'));
    }

    /**
     * Menampilkan view cetak struk (resit) untuk printer termal.
     */
    public function receipt(Transaction $transaction)
    {
        // Eager load details, product, user (kasir), dan customer (member)
        $transaction->load(['details.product', 'user', 'customer']);

        // Pastikan transaksi memiliki details sebelum mencetak
        if ($transaction->details->isEmpty()) {
            abort(404, 'Detail transaksi tidak ditemukan.');
        }

        return view('kasir.receipts.print', compact('transaction'));
    }
}
