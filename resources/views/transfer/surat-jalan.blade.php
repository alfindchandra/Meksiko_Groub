<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $transfer->transfer_number }}</title>
    <style>
        /* ============================================
           GLOBAL RESET & BASE
        ============================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #f5f5f5;
            line-height: 1.5;
        }

        /* ============================================
           SCREEN PREVIEW - Wrapper
        ============================================ */
        .page-wrapper {
            max-width: 794px;
            margin: 30px auto;
            background: white;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            border-radius: 8px;
            overflow: hidden;
        }

        /* ============================================
           SCREEN-ONLY: Print Button Bar
        ============================================ */
        .print-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #1e293b;
            color: white;
        }
        .print-bar h2 { font-size: 14px; font-weight: 600; }
        .print-bar .btn-group { display: flex; gap: 10px; }
        .btn-print {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px;
            background: #4f46e5; color: white;
            border: none; border-radius: 8px;
            font-size: 13px; font-weight: 700;
            cursor: pointer; text-decoration: none;
        }
        .btn-print:hover { background: #4338ca; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px;
            background: transparent; color: #94a3b8;
            border: 1px solid #334155; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none;
        }
        .btn-back:hover { background: #1a2744; color: white; }

        /* ============================================
           DOCUMENT BODY
        ============================================ */
        .document {
            padding: 32px 40px 40px;
        }

        /* ---- Header Perusahaan ---- */
        .company-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 3px solid #1e293b;
            margin-bottom: 20px;
        }
        .company-logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-box {
            width: 52px; height: 52px;
            background: #4f46e5;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 900; color: white;
            letter-spacing: -1px;
        }
        .company-name { font-size: 20px; font-weight: 900; color: #1e293b; }
        .company-sub  { font-size: 11px; color: #64748b; margin-top: 2px; }
        .company-contact { font-size: 10px; color: #94a3b8; margin-top: 4px; }

        .doc-title-box { text-align: right; }
        .doc-title {
            font-size: 22px; font-weight: 900;
            color: #4f46e5; letter-spacing: 1px;
            text-transform: uppercase;
        }
        .doc-number {
            font-family: monospace; font-size: 13px;
            color: #334155; background: #f1f5f9;
            padding: 4px 10px; border-radius: 6px;
            display: inline-block; margin-top: 6px;
        }
        .doc-date { font-size: 11px; color: #64748b; margin-top: 4px; }

        /* ---- Status Badge ---- */
        .status-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .status-in-transit { background: #eef2ff; color: #4f46e5; border: 1.5px solid #c7d2fe; }
        .status-delivered   { background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0; }
        .status-pending     { background: #fffbeb; color: #d97706; border: 1.5px solid #fde68a; }
        .status-approved    { background: #eff6ff; color: #2563eb; border: 1.5px solid #bfdbfe; }
        .status-received    { background: #f0fdf4; color: #16a34a; border: 1.5px solid #bbf7d0; }
        .status-rejected    { background: #fff1f2; color: #e11d48; border: 1.5px solid #fecdd3; }
        .status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: currentColor;
            display: inline-block;
        }

        /* ---- Info Grid ---- */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 50px 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .info-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }
        .info-card.highlight {
            background: #eef2ff;
            border-color: #c7d2fe;
        }
        .info-label {
            font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: #94a3b8; margin-bottom: 8px;
        }
        .info-name  { font-size: 14px; font-weight: 800; color: #1e293b; }
        .info-code  { font-family: monospace; font-size: 11px; color: #4f46e5; margin-top: 2px; }
        .info-addr  { font-size: 11px; color: #64748b; margin-top: 4px; line-height: 1.4; }
        .info-phone { font-size: 11px; color: #94a3b8; margin-top: 2px; }

        .arrow-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .arrow-circle {
            width: 36px; height: 36px;
            background: #4f46e5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .arrow-circle svg { width: 18px; height: 18px; }

        /* ---- Kurir Info ---- */
        .courier-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
        }
        .courier-item-label {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.1em; color: #94a3b8; margin-bottom: 3px;
        }
        .courier-item-value {
            font-size: 13px; font-weight: 700; color: #1e293b;
        }
        .courier-item-empty { color: #cbd5e1; font-style: italic; font-weight: 400; }

        /* ---- Table ---- */
        .section-title {
            font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #475569; margin-bottom: 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .section-title::after {
            content: '';
            flex: 1; height: 1.5px;
            background: #e2e8f0;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.items-table thead tr {
            background: #1e293b;
        }
        table.items-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px; font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        table.items-table thead th:last-child,
        table.items-table thead th.text-center { text-align: center; }
        table.items-table thead th.text-right  { text-align: right; }

        table.items-table tbody tr { border-bottom: 1px solid #f1f5f9; }
        table.items-table tbody tr:nth-child(even) { background: #f8fafc; }
        table.items-table tbody tr:last-child { border-bottom: 2px solid #1e293b; }

        table.items-table tbody td {
            padding: 10px 12px;
            vertical-align: top;
        }
        table.items-table tbody td.text-center { text-align: center; }
        table.items-table tbody td.text-right  { text-align: right; }

        .product-name { font-weight: 700; color: #1e293b; font-size: 12px; }
        .product-sku  { font-family: monospace; font-size: 10px; color: #94a3b8; margin-top: 1px; }
        .product-cat  { font-size: 10px; color: #64748b; margin-top: 1px; }

        .qty-box {
            display: inline-flex; flex-direction: column;
            align-items: center;
            background: #eef2ff; color: #4f46e5;
            padding: 4px 10px; border-radius: 8px;
            font-weight: 800; font-size: 13px;
        }
        .qty-unit { font-size: 9px; font-weight: 600; color: #818cf8; }

        .tfoot-total td {
            padding: 12px;
            background: #f1f5f9;
            font-weight: 800;
        }

        /* ---- Notes ---- */
        .notes-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }
        .notes-box-label { font-size: 10px; font-weight: 800; color: #d97706; margin-bottom: 4px; }
        .notes-box-text  { font-size: 12px; color: #92400e; line-height: 1.5; }

        /* ---- Signature Grid ---- */
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 8px;
        }
        .signature-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }
        .sig-role {
            font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: #64748b; margin-bottom: 2px;
        }
        .sig-name {
            font-size: 11px; color: #94a3b8;
            margin-bottom: 56px;
        }
        .sig-line {
            border-top: 1.5px solid #cbd5e1;
            padding-top: 6px;
            font-size: 11px; font-weight: 700;
            color: #334155; min-height: 22px;
        }
        .sig-date { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        /* ---- Footer ---- */
        .doc-footer {
            margin-top: 28px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-left  { font-size: 10px; color: #94a3b8; }
        .footer-right { font-size: 10px; color: #94a3b8; text-align: right; }
        .footer-brand { font-weight: 800; color: #4f46e5; }

        /* ============================================
           PRINT STYLES
        ============================================ */
        @media print {
            body { background: white; font-size: 11px; }
            .page-wrapper {
                margin: 0; max-width: 100%;
                box-shadow: none; border-radius: 0;
            }
            .print-bar { display: none !important; }
            .document  { padding: 20px 28px 28px; }
            .company-name { font-size: 18px; }
            .doc-title    { font-size: 18px; }

            table.items-table thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .qty-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .info-card.highlight { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            @page {
                size: A4 portrait;
                margin: 12mm 14mm;
            }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    {{-- ============ TOP BAR (Screen Only) ============ --}}
    <div class="print-bar">
        <h2>Preview Surat Jalan</h2>
        <div class="btn-group">
            <a href="{{ route('transfer.detail', $transfer->id) }}" class="btn-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="btn-print">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Dokumen
            </button>
        </div>
    </div>

    {{-- ============ DOCUMENT ============ --}}
    <div class="document">

        {{-- Header Perusahaan --}}
        <div class="company-header">
            <div class="company-logo">
                <div class="logo-box">MX</div>
                <div>
                    <div class="company-name">Meksiko Group</div>
                    <div class="company-sub">Distribution Management System</div>
                    <div class="company-contact">info@meksiko.com  •  021-XXXXXXX</div>
                </div>
            </div>
            <div class="doc-title-box">
                <div class="doc-title">Surat Jalan</div>
                <div class="doc-number">{{ $transfer->transfer_number }}</div>
                <div class="doc-date">
                    Tanggal cetak: {{ now()->format('d F Y, H:i') }}
                </div>
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="status-row">
            @php
                $statusClass = match($transfer->status) {
                    'in_transit' => 'status-in-transit',
                    'delivered', 'received' => 'status-delivered',
                    'approved'   => 'status-approved',
                    'rejected'   => 'status-rejected',
                    default      => 'status-pending',
                };
                $statusLabel = match($transfer->status) {
                    'pending'    => 'Pending',
                    'approved'   => 'Disetujui',
                    'in_transit' => 'Dalam Perjalanan',
                    'received'   => 'Diterima',
                    'rejected'   => 'Ditolak',
                    default      => ucfirst($transfer->status),
                };
            @endphp
            <span class="status-badge {{ $statusClass }}">
                <span class="status-dot"></span>
                {{ $statusLabel }}
            </span>
            @if($transfer->shipment)
            <span class="status-badge" style="background:#f8fafc;color:#64748b;border-color:#e2e8f0;">
                No. Pengiriman: {{ $transfer->shipment->shipment_number }}
            </span>
            @endif
        </div>

        {{-- Rute Pengiriman --}}
        <p class="section-title">Rute Pengiriman</p>
        <div class="info-grid">
            {{-- Asal --}}
            <div class="info-card">
                <div class="info-label">📦  Dikirim Dari</div>
                <div class="info-name">{{ $transfer->fromOutlet->name }}</div>
                <div class="info-code">{{ $transfer->fromOutlet->code }}</div>
                <div class="info-addr">
                    {{ $transfer->fromOutlet->address }}<br>
                    {{ $transfer->fromOutlet->city }}
                </div>
                @if($transfer->fromOutlet->phone)
                <div class="info-phone">☎ {{ $transfer->fromOutlet->phone }}</div>
                @endif
            </div>

            {{-- Arrow --}}
            <div class="arrow-box">
                <div class="arrow-circle">
                    <svg fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </div>

            {{-- Tujuan --}}
            <div class="info-card highlight">
                <div class="info-label">🏪  Dikirim Ke</div>
                <div class="info-name">{{ $transfer->toOutlet->name }}</div>
                <div class="info-code">{{ $transfer->toOutlet->code }}</div>
                <div class="info-addr">
                    {{ $transfer->toOutlet->address }}<br>
                    {{ $transfer->toOutlet->city }}
                </div>
                @if($transfer->toOutlet->phone)
                <div class="info-phone">☎ {{ $transfer->toOutlet->phone }}</div>
                @endif
            </div>
        </div>

        {{-- Info Kurir & Tanggal --}}
        <div class="courier-row">
            <div>
                <div class="courier-item-label">Pengirim / Kurir</div>
                <div class="courier-item-value">
                    @if($transfer->shipment?->courier_name)
                        {{ $transfer->shipment->courier_name }}
                    @else
                        <span class="courier-item-empty">—</span>
                    @endif
                </div>
            </div>
            <div>
                <div class="courier-item-label">Nomor Kendaraan</div>
                <div class="courier-item-value">
                    @if($transfer->shipment?->vehicle_number)
                        {{ $transfer->shipment->vehicle_number }}
                    @else
                        <span class="courier-item-empty">—</span>
                    @endif
                </div>
            </div>
            <div>
                <div class="courier-item-label">Tanggal Kirim</div>
                <div class="courier-item-value">
                    @if($transfer->sent_at)
                        {{ $transfer->sent_at->format('d M Y') }}
                    @elseif($transfer->approved_at)
                        <span class="courier-item-empty">Belum dikirim</span>
                    @else
                        <span class="courier-item-empty">—</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabel Barang --}}
        <p class="section-title">Rincian Barang</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:32px;">#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Qty Diminta</th>
                    @if($transfer->sent_at)
                    <th class="text-center">Qty Kirim</th>
                    @endif
                    @if($transfer->received_at)
                    <th class="text-center">Qty Terima</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($transfer->items as $i => $item)
                <tr>
                    <td style="color:#94a3b8;font-weight:700;">{{ $i + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product->name }}</div>
                        <div class="product-sku">{{ $item->product->sku }}</div>
                    </td>
                    <td>
                        <div class="product-cat">{{ $item->product->category->name ?? '-' }}</div>
                    </td>
                    <td class="text-center">
                        <div class="qty-box">
                            {{ number_format($item->quantity_requested) }}
                            <span class="qty-unit">{{ $item->product->unit }}</span>
                        </div>
                    </td>
                    @if($transfer->sent_at)
                    <td class="text-center">
                        <span style="font-weight:700; color:#4f46e5;">
                            {{ number_format($item->quantity_sent ?? $item->quantity_requested) }}
                            <span style="font-size:10px;color:#818cf8;"> {{ $item->product->unit }}</span>
                        </span>
                    </td>
                    @endif
                    @if($transfer->received_at)
                    <td class="text-center">
                        <span style="font-weight:700;
                            color: {{ $item->quantity_received != ($item->quantity_sent ?? $item->quantity_requested) ? '#e11d48' : '#16a34a' }}">
                            {{ number_format($item->quantity_received) }}
                            <span style="font-size:10px;"> {{ $item->product->unit }}</span>
                        </span>
                        @if($item->quantity_received != ($item->quantity_sent ?? $item->quantity_requested))
                        <div style="font-size:9px;color:#e11d48;font-weight:800;">SELISIH</div>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-total">
                    <td colspan="3" style="text-align:right;color:#64748b;font-size:11px;">
                        Total {{ $transfer->items->count() }} jenis barang
                    </td>
                    <td class="text-center" style="color:#4f46e5;">
                        {{ number_format($transfer->items->sum('quantity_requested')) }}
                    </td>
                    @if($transfer->sent_at)
                    <td class="text-center" style="color:#4f46e5;">
                        {{ number_format($transfer->items->sum('quantity_sent')) }}
                    </td>
                    @endif
                    @if($transfer->received_at)
                    <td class="text-center" style="color:#16a34a;">
                        {{ number_format($transfer->items->sum('quantity_received')) }}
                    </td>
                    @endif
                </tr>
            </tfoot>
        </table>

        {{-- Catatan --}}
        @if($transfer->notes)
        <div class="notes-box">
            <div class="notes-box-label">📝 Catatan</div>
            <div class="notes-box-text">{{ $transfer->notes }}</div>
        </div>
        @endif

        {{-- Tanda Tangan --}}
        <p class="section-title">Tanda Tangan</p>
        <div class="signature-grid">
            {{-- Pembuat --}}
            <div class="signature-box">
                <div class="sig-role">Dibuat Oleh</div>
                <div class="sig-name">{{ $transfer->requestedBy->name }}</div>
                <div class="sig-line">
                    @if($transfer->requestedBy) {{ $transfer->requestedBy->name }} @endif
                </div>
                <div class="sig-date">{{ $transfer->created_at->format('d M Y') }}</div>
            </div>

            {{-- Pengirim --}}
            <div class="signature-box">
                <div class="sig-role">Pengirim</div>
                <div class="sig-name">
                    {{ $transfer->sentBy ? $transfer->sentBy->name : $transfer->fromOutlet->name }}
                </div>
                <div class="sig-line">
                    {{ $transfer->sentBy ? $transfer->sentBy->name : '' }}
                </div>
                <div class="sig-date">
                    {{ $transfer->sent_at ? $transfer->sent_at->format('d M Y') : '___/___/______' }}
                </div>
            </div>

            {{-- Penerima --}}
            <div class="signature-box">
                <div class="sig-role">Penerima</div>
                <div class="sig-name">{{ $transfer->toOutlet->name }}</div>
                <div class="sig-line">
                    {{ $transfer->receivedBy ? $transfer->receivedBy->name : '' }}
                </div>
                <div class="sig-date">
                    {{ $transfer->received_at ? $transfer->received_at->format('d M Y') : '___/___/______' }}
                </div>
            </div>
        </div>

        {{-- Footer Dokumen --}}
        <div class="doc-footer">
            <div class="footer-left">
                Dicetak oleh: <strong>{{ auth()->user()->name }}</strong> pada {{ now()->format('d M Y, H:i') }}<br>
                Dokumen ini sah sebagai surat jalan resmi <span class="footer-brand">Meksiko Group</span>
            </div>
            <div class="footer-right">
                <span class="footer-brand">Meksiko Group</span> — DMS<br>
                {{ $transfer->transfer_number }}
            </div>
        </div>

    </div>{{-- end .document --}}
</div>{{-- end .page-wrapper --}}

</body>
</html>