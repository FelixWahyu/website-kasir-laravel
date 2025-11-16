<?php

namespace App\Http\Controllers\Kasir;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionStoreController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        // 1. Validasi Data Masuk
        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'payment_method' => ['required', 'string', 'in:tunai,debit,e_wallet'],
            'discount_amount' => ['required', 'integer', 'min:0'],
            'subtotal' => ['required', 'integer', 'min:0'],
            'total_amount' => ['required', 'integer', 'min:0'],
            'amount_paid' => ['required', 'integer', 'min:0'],
            'change_due' => ['required', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.selling_price' => ['required', 'integer', 'min:0'],
        ]);

        // Cek Keamanan Tambahan: Pastikan Total Amount dihitung dengan benar di backend
        if ($validated['total_amount'] !== ($validated['subtotal'] - $validated['discount_amount'])) {
            return response()->json(['error' => 'Validasi total gagal. Hitungan Subtotal dan Diskon tidak sesuai.'], 422);
        }

        // Cek Stok (Mencegah Race Condition dengan DB Transaction)
        $productIds = collect($validated['items'])->pluck('product_id')->all();
        $productsInStock = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($validated['items'] as $item) {
            $product = $productsInStock->get($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                return response()->json(['error' => 'Gagal: Stok untuk produk ' . $product->product_name . ' tidak cukup.'], 422);
            }
        }


        // 2. Jalankan Database Transaction
        // Ini memastikan semua query (simpan transaksi, simpan detail, update stok) 
        // harus sukses semua, jika satu gagal, semua dibatalkan (rollback).
        DB::beginTransaction();
        try {
            // 2a. Buat Nomor Invoice
            $invoiceNumber = $this->generateInvoiceNumber();

            // 2b. Simpan Transaksi Utama
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => Auth::id(), // Kasir yang bertugas
                'customer_id' => $validated['customer_id'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount_amount'],
                'total_amount' => $validated['total_amount'],
                'amount_paid' => $validated['amount_paid'],
                'change_due' => $validated['change_due'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            // 2c. Simpan Detail Transaksi & Update Stok
            $transactionDetails = [];
            foreach ($validated['items'] as $item) {
                // Simpan detail
                $transactionDetails[] = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'selling_price' => $item['selling_price'],
                    'subtotal' => $item['quantity'] * $item['selling_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Update Stok Produk
                $product = $productsInStock->get($item['product_id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Gunakan insert massal untuk detail transaksi
            $transaction->details()->insert($transactionDetails);

            DB::commit(); // Semua sukses, konfirmasi ke database

            // 3. Respon Sukses
            return response()->json([
                'message' => 'Transaksi berhasil diproses!',
                'transaction_id' => $transaction->id,
                'invoice_number' => $invoiceNumber,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Ada yang gagal, batalkan semua
            // Log::error($e->getMessage()); // Jika menggunakan logging
            return response()->json(['error' => 'Transaksi gagal diproses di server. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper untuk membuat nomor invoice unik.
     */
    private function generateInvoiceNumber(): string
    {
        // Format: INV/YYYYMMDD/XXXXX (contoh: INV/20251113/00001)
        $date = now()->format('Ymd');

        // Cari transaksi terakhir hari ini
        $lastTransaction = Transaction::whereDate('created_at', today())
            ->latest()
            ->first();

        if ($lastTransaction) {
            // Ambil nomor urut terakhir dan tambahkan 1
            $lastNumber = (int) substr($lastTransaction->invoice_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'INV/' . $date . '/' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
