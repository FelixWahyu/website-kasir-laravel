<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $transaction->invoice_number }}</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #000;
        }

        @media print {

            @page {
                margin: 0;
                size: 58mm 297mm;
            }

            body {
                width: 58mm;
                margin: 0;
                padding: 0;
                font-size: 8pt;
            }

            .receipt-container {
                width: 100%;
                padding: 5px;
                box-sizing: border-box;
            }

            header,
            footer,
            table {
                margin: 5px 0;
            }

            .header-text,
            .footer-text {
                text-align: center;
                line-height: 1.2;
            }

            .item-list td {
                padding: 1px 0;
            }
        }

        .receipt-container {
            max-width: 58mm;
            margin: 20px auto;
            background: #fff;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .item-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .item-list td {
            vertical-align: top;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        <header>
            <h3 class="header-text" style="margin: 0; font-size: 10pt;">{{ $settings['app_name'] ?? 'Kasir Toko ABCD' }}
            </h3>
            <p class="header-text" style="font-size: 7pt;">Jl. Jend. Sudirman No. 123, Jakarta</p>
        </header>

        <div class="separator"></div>

        <div style="font-size: 7pt;">
            <p style="margin: 0;">No. Transaksi: {{ $transaction->invoice_number }}</p>
            <p style="margin: 0;">Tanggal: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
            <p style="margin: 0;">Kasir: {{ $transaction->user->name }}</p>
            <p style="margin: 0;">Member: {{ $transaction->customer ? $transaction->customer->name : 'Umum' }}</p>
        </div>

        <div class="separator"></div>

        <table class="item-list">
            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td colspan="2" style="font-weight: bold;">{{ $detail->product->product_name }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">{{ $detail->quantity }} x</td>
                        <td style="text-align: right;">@ {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="separator"></div>

        <table style="width: 100%; font-size: 8pt;">
            <tr>
                <td>Subtotal</td>
                <td style="text-align: right;">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Diskon</td>
                <td style="text-align: right;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">TOTAL BAYAR</td>
                <td style="text-align: right; font-weight: bold;">Rp
                    {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="separator"></div>

        <table style="width: 100%; font-size: 8pt;">
            <tr>
                <td>Bayar ({{ $transaction->payment_method }})</td>
                <td style="text-align: right;">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td style="text-align: right;">Rp {{ number_format($transaction->change_due, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="separator"></div>

        <footer>
            <p class="footer-text" style="font-size: 7pt; margin-top: 5px;">
                TERIMA KASIH ATAS KUNJUNGAN ANDA!
            </p>
        </footer>
    </div>

    <script>
        window.onload = function() {
            window.print();

            // Opsional: Tutup jendela setelah cetak (tergantung preferensi browser)
            // window.onafterprint = function() {
            //     window.close();
            // };
        }
    </script>

</body>

</html>
