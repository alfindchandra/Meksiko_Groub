<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 20px;
        }

        .receipt-wrapper {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        .print-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #1e293b;
            color: white;
        }

        .print-bar h2 {
            font-size: 14px;
            font-weight: 600;
        }

        .btn-group {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print {
            background: #4f46e5;
            color: white;
        }

        .btn-print:hover {
            background: #4338ca;
        }

        .btn-back {
            background: transparent;
            color: #94a3b8;
            border: 1px solid #334155;
        }

        .btn-back:hover {
            background: #1a2744;
            color: white;
        }

        .receipt {
            padding: 32px 24px;
            font-size: 13px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #ddd;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .store-name {
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .store-info {
            font-size: 11px;
            color: #666;
        }

        .section {
            margin: 16px 0;
            padding-bottom: 12px;
            border-bottom: 1px dashed #ddd;
        }

        .section:last-child {
            border-bottom: none;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .label {
            color: #666;
            font-size: 12px;
        }

        .value {
            font-weight: 700;
        }

        .items-table {
            width: 100%;
        }

        .items-table th {
            text-align: left;
            font-size: 11px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: 700;
        }

        .items-table td {
            padding: 8px 0;
            font-size: 12px;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 2px solid #333;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 14px;
        }

        .total-row.grand {
            font-size: 18px;
            font-weight: 900;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #ddd;
        }

        .payment-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin: 16px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 2px dashed #ddd;
            font-size: 11px;
            color: #666;
        }

        .barcode {
            text-align: center;
            margin: 16px 0;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-wrapper {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .print-bar {
                display: none !important;
            }

            .receipt {
                padding: 12px;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>

@php
    $sale = \App\Models\Sale::with(['outlet', 'user', 'items.product'])
        ->findOrFail($saleId);
@endphp

<div class="receipt-wrapper">
    
    <!-- Print Bar (Screen Only) -->
    <div class="print-bar">
        <h2>Struk Pembayaran</h2>
        <div class="btn-group">
            <a href="{{ route('pos') }}" class="btn btn-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Receipt Content -->
    <div class="receipt">
        
        <!-- Header -->
        <div class="header">
            <div class="store-name">MEKSIKO GROUP</div>
            <div class="store-info">
                {{ $sale->outlet->name }}<br>
                {{ $sale->outlet->address }}<br>
                {{ $sale->outlet->city }}<br>
                @if($sale->outlet->phone)Telp: {{ $sale->outlet->phone }}@endif
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="section">
            <div class="row">
                <span class="label">No. Transaksi</span>
                <span class="value">{{ $sale->sale_number }}</span>
            </div>
            <div class="row">
                <span class="label">Tanggal</span>
                <span class="value">{{ $sale->sale_date->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="label">Kasir</span>
                <span class="value">{{ $sale->user->name }}</span>
            </div>
            @if($sale->customer_name)
            <div class="row">
                <span class="label">Pelanggan</span>
                <span class="value">{{ $sale->customer_name }}</span>
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td>
                            {{ $item->product->name }}<br>
                            <span style="font-size:10px;color:#666;">{{ $item->product->sku }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 0) }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->discount > 0)
            <div class="total-row">
                <span>Diskon</span>
                <span>- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($sale->tax > 0)
            <div class="total-row">
                <span>Pajak</span>
                <span>Rp {{ number_format($sale->tax, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL</span>
                <span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="row">
                <span class="label">Metode Pembayaran</span>
                <span class="value">{{ strtoupper($sale->payment_method) }}</span>
            </div>
            @if($sale->payment_method === 'cash')
            <div class="row">
                <span class="label">Bayar</span>
                <span class="value">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span class="label">Kembali</span>
                <span class="value">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <!-- Barcode -->
        <div class="barcode">
            {{ $sale->sale_number }}
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight:700;margin-bottom:8px;">TERIMA KASIH</p>
            <p>Barang yang sudah dibeli tidak dapat<br>dikembalikan kecuali ada perjanjian</p>
            <p style="margin-top:12px;font-size:10px;">
                Dicetak: {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>

    </div>
</div>

</body>
</html>