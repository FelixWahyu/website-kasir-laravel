<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .summary-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }

        h1 {
            font-size: 14pt;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 12pt;
            margin-top: 0;
        }
    </style>
</head>

<body>

    <h1>Laporan Penjualan</h1>
    <h2>Periode: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Akhir' }}</h2>

    <div class="summary-box">
        <p>Total Transaksi: {{ count($transactions) }}</p>
        <p>Total Penjualan Bersih: Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        <p>Total Diskon: Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
        <p style="font-weight: bold; color: green;">Total Laba Kotor: Rp
            {{ number_format($totalGrossProfit, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Invoice</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 15%;">Kasir</th>
                <th style="width: 15%;">Pelanggan</th>
                <th style="width: 10%;" class="text-right">Diskon</th>
                <th style="width: 15%;" class="text-right">Total Akhir</th>
                <th style="width: 15%;" class="text-right">Laba Kotor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                @php
                    $transactionGrossProfit = $transaction->details->sum(function ($detail) {
                        $cost = $detail->product->purchase_price ?? 0;
                        $revenue = $detail->selling_price;
                        $quantity = $detail->quantity;
                        return ($revenue - $cost) * $quantity;
                    });
                @endphp
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->customer->name ?? 'Umum' }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transactionGrossProfit, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->details->isNotEmpty())
                    <tr>
                        <td colspan="7" style="padding: 0; border: none; background-color: #f9f9f9;">
                            <ul style="list-style: none; margin: 0; padding: 5px 0 5px 20px; font-size: 9pt;">
                                @foreach ($transaction->details as $detail)
                                    <li>
                                        <span style="font-weight: bold;">{{ $detail->product->name }}</span>
                                        ({{ $detail->quantity }}x Rp
                                        {{ number_format($detail->selling_price, 0, ',', '.') }})
                                        ({{ number_format((($detail->selling_price ?? 0) - ($detail->product->purchase_price ?? 0)) * $detail->quantity, 0, ',', '.') }}
                                        Laba)
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

</body>

</html>
