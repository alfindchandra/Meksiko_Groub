<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $transfer->transfer_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Font standar nota agar mudah dibaca */
            font-size: 11px;
            color: #000;
            background: #fff;
            line-height: 1.3;
            margin: 0;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        /* Header */
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .company-info h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-info {
            text-align: right;
        }
        .doc-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Detail Alamat */
        .address-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .address-box {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }
        .address-box strong {
            text-decoration: underline;
            display: block;
            margin-bottom: 5px;
        }

        /* Info Pengiriman */
        .shipping-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .shipping-info table {
            width: 100%;
        }

        /* Tabel Barang */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .item-table th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        .item-table td {
            padding: 5px;
            border-bottom: 0.5px solid #ccc;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Tanda Tangan */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .signature-wrapper {
            display: table-cell;
            width: 33%;
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }

        /* Tombol Cetak (Hanya layar) */
        @media print {
            .no-print { display: none; }
        }
        .no-print {
            background: #444;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            padding: 5px 15px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">CETAK SURAT JALAN</button>
        <p style="font-size: 10px; margin-top: 5px;">Gunakan kertas A4 atau rangkap (NCR)</p>
    </div>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>MEKSIKO GROUP</h1>
                <p>Distributor Management System<br>
                Telp: 0813 8217 6161 | Bojonegoro</p>
            </div>
            <div class="doc-info">
                <div class="doc-title">SURAT JALAN</div>
                <p>No: <strong>{{ $transfer->transfer_number }}</strong><br>
                Tgl: {{ $transfer->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="address-section">
            <div class="address-box">
                <strong>PENGIRIM:</strong>
                {{ $transfer->fromOutlet->name }}<br>
                {{ $transfer->fromOutlet->address }}<br>
                {{ $transfer->fromOutlet->city }}
            </div>
            
            <div class="address-box">
                <strong>PENERIMA:</strong>
                {{ $transfer->toOutlet->name }}<br>
                {{ $transfer->toOutlet->address }}<br>
                {{ $transfer->toOutlet->city }}
            </div>
        </div>

        <div class="shipping-info">
            <table>
                <tr>
                    <td width="15%">Kurir/Ekspedisi</td>
                    <td width="35%">: {{ $transfer->shipment->courier_name ?? '-' }}</td>
                    <td width="15%">No. Kendaraan</td>
                    <td width="35%">: {{ $transfer->shipment->vehicle_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tgl Kirim</td>
                    <td>: {{ $transfer->sent_at ? $transfer->sent_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>Status</td>
                    <td>: {{ strtoupper($transfer->status) }}</td>
                </tr>
            </table>
        </div>

        <table class="item-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="45%">NAMA BARANG / SKU</th>
                    <th class="text-center" width="15%">PERMINTAAN</th>
                    <th class="text-center" width="15%">DIKIRIM</th>
                    <th class="text-center" width="20%">CATATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfer->items as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>
                        {{ $item->product->name }}<br>
                        <small>{{ $item->product->sku }}</small>
                    </td>
                    <td class="text-center">{{ number_format($item->quantity_requested) }} {{ $item->product->unit }}</td>
                    <td class="text-center">{{ number_format($item->quantity_sent ?? $item->quantity_requested) }} {{ $item->product->unit }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($transfer->notes)
        <div style="border: 1px solid #000; padding: 5px; margin-bottom: 20px;">
            <strong>Catatan:</strong><br>
            {{ $transfer->notes }}
        </div>
        @endif

        <div class="signature-section">
            <div class="signature-wrapper">
                <p>Pengirim/Gudang,</p>
                <div class="signature-space"></div>
                <p>( ____________________ )</p>
            </div>
            <div class="signature-wrapper">
                <p>Sopir/Kurir,</p>
                <div class="signature-space"></div>
                <p>( ____________________ )</p>
            </div>
            <div class="signature-wrapper">
                <p>Penerima,</p>
                <div class="signature-space"></div>
                <p>( ____________________ )</p>
                <small>Stempel & Nama Terang</small>
            </div>
        </div>

        <div style="margin-top: 30px; font-size: 9px; border-top: 1px solid #000; padding-top: 5px;">
            <p>Putih: Kantor | Merah: Penerima | Kuning: Arsip Gudang</p>
            <p>Dicetak otomatis oleh DMS Meksiko Group pada {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

</body>
</html>