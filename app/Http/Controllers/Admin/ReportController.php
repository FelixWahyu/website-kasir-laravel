<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Menampilkan Indeks Laporan Penjualan dengan Filter.
     */
    public function reportIndex(Request $request)
    {
        // 1. Ambil data filter dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 2. Query Transaksi
        $transactions = Transaction::with(['user', 'customer', 'details.product'])
            ->latest();

        // 3. Aplikasikan Filter Tanggal
        if ($startDate) {
            // WHERE created_at >= start_date (awal hari)
            $transactions->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            // WHERE created_at <= end_date (akhir hari)
            $transactions->whereDate('created_at', '<=', $endDate);
        }

        // Clone query untuk agregasi (tanpa paginasi)
        $aggregateData = (clone $transactions)->get();

        // 3. Hitung Ringkasan Termasuk Laba Kotor
        $totalSales = $aggregateData->sum('total_amount');
        $totalDiscount = $aggregateData->sum('discount');
        $totalGrossProfit = $this->calculateGrossProfit($aggregateData);

        // Paginasi
        $transactions = $transactions->paginate(20)->withQueryString();

        return view('admin.reports.index', compact(
            'transactions',
            'totalSales',
            'totalDiscount',
            'totalGrossProfit',
            'startDate',
            'endDate'
        ));
    }

    public function reportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = Transaction::with(['user', 'customer', 'details.product'])
            ->latest();

        // Aplikasikan Filter Tanggal (Sama seperti index)
        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
        }

        // Ambil SEMUA data (tanpa paginasi) untuk PDF
        $transactions = $transactions->get();

        // 2. Hitung Ringkasan Termasuk Laba Kotor
        $totalSales = $transactions->sum('total_amount');
        $totalDiscount = $transactions->sum('discount');
        $totalGrossProfit = $this->calculateGrossProfit($transactions);

        // 3. Load dan Tampilkan View PDF
        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'transactions',
            'totalSales',
            'totalDiscount',
            'totalGrossProfit',
            'startDate',
            'endDate'
        ));

        // 2. Download File
        $filename = 'Laporan_Penjualan_' . ($startDate ?? 'All') . '_to_' . ($endDate ?? 'All') . '.pdf';
        return $pdf->stream($filename);
    }

    // Fungsi Helper untuk Menghitung Laba Kotor
    private function calculateGrossProfit($transactions)
    {
        return $transactions->sum(function ($transaction) {
            // Laba Kotor per transaksi = SUM((Harga Jual - Harga Modal) * Quantity)
            return $transaction->details->sum(function ($detail) {
                $cost = $detail->product->purchase_price ?? 0;
                $revenue = $detail->selling_price;
                $quantity = $detail->quantity;

                // Pastikan tidak ada data yang null
                if ($detail->product === null) return 0;

                return ($revenue - $cost) * $quantity;
            });
        });
    }
}
