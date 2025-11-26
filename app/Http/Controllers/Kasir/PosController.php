<?php

namespace App\Http\Controllers\Kasir;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->get();

        $customers = Customer::select('*')->get();
        $activeDiscounts = Discount::where('is_active', true)->get();

        $categories = Category::all();
        // $customers = Customer::all();

        $productsJson = $products->toJson();
        $customersJson = $customers->toJson();
        $discountsJson = $activeDiscounts->toJson();

        return view('kasir.pos.index', compact('productsJson', 'categories', 'customersJson', 'discountsJson'));
    }

    /**
     * Menampilkan view cetak struk (resit) untuk printer termal.
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['details.product', 'user', 'customer']);

        if ($transaction->details->isEmpty()) {
            abort(404, 'Detail transaksi tidak ditemukan.');
        }

        return view('kasir.receipts.print', compact('transaction'));
    }
}
