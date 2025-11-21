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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = Transaction::with(['user', 'customer', 'details.product'])
            ->latest();

        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
        }

        $aggregateData = (clone $transactions)->get();

        $totalSales = $aggregateData->sum('total_amount');
        $totalDiscount = $aggregateData->sum('discount');
        $totalGrossProfit = $this->calculateGrossProfit($aggregateData);

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

        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
        }

        $transactions = $transactions->get();

        $totalSales = $transactions->sum('total_amount');
        $totalDiscount = $transactions->sum('discount');
        $totalGrossProfit = $this->calculateGrossProfit($transactions);

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'transactions',
            'totalSales',
            'totalDiscount',
            'totalGrossProfit',
            'startDate',
            'endDate'
        ));

        $filename = 'Laporan_Penjualan_' . ($startDate ?? 'All') . '_to_' . ($endDate ?? 'All') . '.pdf';
        return $pdf->stream($filename);
    }

    private function calculateGrossProfit($transactions)
    {
        return $transactions->sum(function ($transaction) {
            return $transaction->details->sum(function ($detail) {
                $cost = $detail->product->purchase_price ?? 0;
                $revenue = $detail->selling_price;
                $quantity = $detail->quantity;

                if ($detail->product === null) return 0;

                return ($revenue - $cost) * $quantity;
            });
        });
    }
}
