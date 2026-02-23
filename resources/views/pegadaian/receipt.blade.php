<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Gadai </title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .receipt-wrapper {
            max-width: 800px;
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

        .print-bar h2 { font-size: 14px; font-weight: 600; }

        .btn-group { display: flex; gap: 8px; }

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

        .btn-print { background: #4f46e5; color: white; }
        .btn-print:hover { background: #4338ca; }
        .btn-back { background: transparent; color: #94a3b8; border: 1px solid #334155; }
        .btn-back:hover { background: #1a2744; color: white; }

        .receipt {
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #fbbf24;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .logo {
            font-size: 32px;
            font-weight: 900;
            color: #fbbf24;
            margin-bottom: 8px;
        }

        .store-name {
            font-size: 20px;
            font-weight: 900;
            color: #1e293b;
        }

        .store-info {
            font-size: 11px;
            color: #64748b;
            margin-top: 8px;
        }

        .section {
            margin: 20px 0;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 4px 0;
        }

        .label {
            color: #64748b;
            font-size: 12px;
        }

        .value {
            font-weight: 700;
            color: #1e293b;
        }

        .highlight-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }

        .highlight-box .row {
            border-bottom: 1px dashed #fbbf24;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .highlight-box .row:last-child {
            border-bottom: none;
            font-size: 18px;
            font-weight: 900;
            color: #92400e;
            padding-top: 8px;
        }

        .terms {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }

        .terms h4 {
            font-size: 12px;
            font-weight: 800;
            color: #991b1b;
            margin-bottom: 8px;
        }

        .terms ul {
            list-style: none;
            font-size: 11px;
            color: #7f1d1d;
        }

        .terms li {
            padding: 4px 0;
            padding-left: 16px;
            position: relative;
        }

        .terms li:before {
            content: "•";
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 2px dashed #cbd5e1;
            font-size: 11px;
            color: #64748b;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin: 32px 0;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box p {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-box .name {
            border-top: 2px solid #1e293b;
            padding-top: 8px;
            font-weight: 700;
            color: #1e293b;
        }

        @media print {
            body { background: white; padding: 0; }
            .receipt-wrapper { box-shadow: none; border-radius: 0; max-width: 100%; }
            .print-bar { display: none !important; }
            .receipt { padding: 20px; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body>

@php
    $pawn = \App\Models\PawnTransaction::with(['outlet', 'user'])
        ->findOrFail($pawnId);
@endphp

<div class="receipt-wrapper">
    
    <!-- Print Bar -->
    <div class="print-bar">
        <h2>Bukti Gadai</h2>
        <div class="btn-group">
            <a href="{{ route('pegadaian.detail', $pawn->id) }}" class="btn btn-back">
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
            <div class="logo">⚜️ GADAI</div>
            <div class="store-name">MEKSIKO GROUP</div>
            <div class="store-info">
                {{ $pawn->outlet->name }}<br>
                {{ $pawn->outlet->address }}, {{ $pawn->outlet->city }}<br>
                @if($pawn->outlet->phone)Telp: {{ $pawn->outlet->phone }}@endif
            </div>
        </div>

        <!-- Pawn Number -->
        <div style="text-align: center; margin: 24px 0;">
            <p style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Nomor Bukti Gadai</p>
            <p style="font-size: 24px; font-weight: 900; font-family: monospace; color: #1e293b;">
                {{ $pawn->pawn_number }}
            </p>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">📋 Data Nasabah</div>
            <div class="row">
                <span class="label">Nama Lengkap</span>
                <span class="value">{{ $pawn->customer_name }}</span>
            </div>
            <div class="row">
                <span class="label">No. KTP</span>
                <span class="value">{{ $pawn->customer_id_number }}</span>
            </div>
            <div class="row">
                <span class="label">No. HP</span>
                <span class="value">{{ $pawn->customer_phone }}</span>
            </div>
            @if($pawn->customer_address)
            <div class="row">
                <span class="label">Alamat</span>
                <span class="value">{{ $pawn->customer_address }}</span>
            </div>
            @endif
        </div>

        <!-- Item Info -->
        <div class="section">
            <div class="section-title">💎 Barang Jaminan</div>
            <div class="row">
                <span class="label">Nama Barang</span>
                <span class="value">{{ $pawn->item_name }}</span>
            </div>
            <div class="row">
                <span class="label">Kategori</span>
                <span class="value">{{ ucfirst($pawn->item_category) }}</span>
            </div>
            @if($pawn->item_weight)
            <div class="row">
                <span class="label">Berat</span>
                <span class="value">{{ $pawn->item_weight }} gram</span>
            </div>
            @endif
            @if($pawn->item_description)
            <div class="row">
                <span class="label">Deskripsi</span>
                <span class="value">{{ $pawn->item_description }}</span>
            </div>
            @endif
        </div>

        <!-- Transaction Details -->
        <div class="highlight-box">
            <div class="row">
                <span>Nilai Taksir</span>
                <span>Rp {{ number_format($pawn->appraisal_value, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>Pinjaman Pokok</span>
                <span>Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>Biaya Admin</span>
                <span>Rp {{ number_format($pawn->admin_fee, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span>UANG DITERIMA</span>
                <span>Rp {{ number_format($pawn->loan_amount - $pawn->admin_fee, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Dates & Interest -->
        <div class="section">
            <div class="section-title">📅 Informasi Pinjaman</div>
            <div class="row">
                <span class="label">Tanggal Gadai</span>
                <span class="value">{{ $pawn->start_date->format('d F Y') }}</span>
            </div>
            <div class="row">
                <span class="label">Jatuh Tempo</span>
                <span class="value" style="color: #dc2626; font-weight: 900;">
                    {{ $pawn->due_date->format('d F Y') }}
                </span>
            </div>
            <div class="row">
                <span class="label">Tenor</span>
                <span class="value">{{ $pawn->loan_period_days }} Hari</span>
            </div>
            <div class="row">
                <span class="label">Bunga per Bulan</span>
                <span class="value">{{ $pawn->interest_rate }}%</span>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms">
            <h4>⚠️ SYARAT & KETENTUAN</h4>
            <ul>
                <li>Barang jaminan harus ditebus paling lambat tanggal jatuh tempo yang tertera.</li>
                <li>Bunga dihitung per bulan (30 hari) dan akan dikenakan setiap bulannya.</li>
                <li>Nasabah dapat memperpanjang gadai dengan membayar bunga sesuai tenor perpanjangan.</li>
                <li>Jika lewat jatuh tempo lebih dari 7 hari tanpa perpanjangan, barang akan masuk proses lelang.</li>
                <li>Barang jaminan tidak dapat diambil tanpa pelunasan penuh (pokok + bunga).</li>
                <li>Bukti gadai ini harus dibawa saat pelunasan/perpanjangan.</li>
                <li>Kehilangan bukti gadai wajib lapor polisi dan dikenakan biaya administrasi.</li>
            </ul>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Nasabah</p>
                <div class="name">{{ $pawn->customer_name }}</div>
            </div>
            <div class="signature-box">
                <p>Petugas</p>
                <div class="name">{{ $pawn->user->name }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                SIMPAN BUKTI INI DENGAN BAIK
            </p>
            <p>
                Dicetak pada: {{ now()->format('d F Y, H:i:s') }}<br>
                Dokumen ini sah sebagai bukti gadai resmi <strong>Meksiko Group</strong>
            </p>
        </div>

    </div>
</div>

</body>
</html>