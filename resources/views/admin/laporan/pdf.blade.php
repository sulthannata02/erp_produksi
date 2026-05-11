<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Produksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1A202C;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #1E6FD9;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 700;
            color: #1E6FD9;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: 600;
            color: #1A202C;
            margin-top: 4px;
        }

        .header p {
            font-size: 10px;
            color: #718096;
            margin-top: 3px;
        }

        /* Info filter */
        .info-bar {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            background: #EBF3FF;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .info-item {
            display: inline-block;
            margin-right: 24px;
            font-size: 10px;
            color: #4A5568;
        }

        .info-item strong { color: #1E6FD9; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead tr {
            background: #1E6FD9;
            color: #fff;
        }

        thead th {
            padding: 8px 10px;
            text-align: center;
            font-weight: 600;
            font-size: 10px;
            letter-spacing: .3px;
            border: 1px solid #1558B0;
        }

        thead th.left { text-align: left; }

        tbody tr:nth-child(even) { background: #F8FAFC; }
        tbody tr:nth-child(odd)  { background: #fff; }

        tbody td {
            padding: 7px 10px;
            border: 1px solid #E5E9F0;
            vertical-align: middle;
        }

        tbody td.center { text-align: center; }
        tbody td.right  { text-align: right; }

        .td-fg { color: #059669; font-weight: 600; text-align: center; }
        .td-ng { color: #DC2626; font-weight: 600; text-align: center; }

        /* Total row */
        tfoot tr {
            background: #1A202C;
            color: #fff;
        }

        tfoot td {
            padding: 8px 10px;
            border: 1px solid #2D3748;
            font-weight: 700;
        }

        tfoot td.center { text-align: center; }
        tfoot td.fg     { color: #6EE7B7; text-align: center; font-weight: 700; }
        tfoot td.ng     { color: #FCA5A5; text-align: center; font-weight: 700; }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 20px;
            color: #A0AEC0;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            font-size: 9px;
            color: #A0AEC0;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 10px;
            color: #4A5568;
        }

        .sign-box {
            display: inline-block;
            text-align: center;
            font-size: 10px;
        }

        .sign-line {
            margin-top: 50px;
            border-top: 1px solid #1A202C;
            width: 140px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>ERP PRODUKSI — PT. ACTMETAL INDONESIA</h1>
        <h2>LAPORAN PRODUKSI & PACKING</h2>
        <p>
            Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            @if($selectedCust) &nbsp;|&nbsp; Customer: {{ $selectedCust }} @endif
        </p>
    </div>

    {{-- Info bar --}}
    <div class="info-bar">
        <span class="info-item">Dicetak: <strong>{{ now()->locale('id')->translatedFormat('d M Y, H:i') }} WIB</strong></span>
        <span class="info-item">Total Data: <strong>{{ $productions->count() }} record</strong></span>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align:middle;width:30px">No</th>
                <th rowspan="2" class="left" style="vertical-align:middle">Nama Customer</th>
                <th rowspan="2" style="vertical-align:middle">Total Produksi</th>
                <th rowspan="2" style="background:#1558B0;vertical-align:middle">Total QC</th>
                <th colspan="2" style="background:#0EA5E9">Total Packing</th>
                <th rowspan="2" class="left" style="vertical-align:middle">Keterangan</th>
            </tr>
            <tr>
                <th style="background:#0EA5E9">Finish Good</th>
                <th style="background:#0EA5E9">Not Good</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $i => $prod)
            @php
                $qcTotal = optional($prod->qc)->qty_qc ?? 0;
                $packFg = optional(optional($prod->qc)->packing)->jumlah_fg ?? 0;
                $packNg = optional(optional($prod->qc)->packing)->jumlah_ng ?? 0;
                $ket    = optional(optional($prod->qc)->packing)->keterangan ?? optional($prod->qc)->keterangan ?? '-';
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                <td class="center">{{ number_format($prod->jumlah_produksi) }}</td>
                <td class="center">{{ number_format($qcTotal) }}</td>
                <td class="td-fg">{{ number_format($packFg) }}</td>
                <td class="td-ng">{{ number_format($packNg) }}</td>
                <td>{{ $ket }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty">Tidak ada data untuk periode yang dipilih</td>
            </tr>
            @endforelse
        </tbody>
        @if($productions->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right">TOTAL</td>
                <td class="center">{{ number_format($totalProduksi) }}</td>
                <td class="center">{{ number_format($totalQc) }}</td>
                <td class="fg">{{ number_format($totalPackFg) }}</td>
                <td class="ng">{{ number_format($totalPackNg) }}</td>
                <td>—</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">
            Dokumen ini digenerate secara otomatis oleh Sistem ERP Produksi PT. Actmetal Indonesia.
        </div>
        <div class="footer-right">
            <div class="sign-box">
                Mengetahui,<br>
                <div class="sign-line"></div>
                Kepala Produksi
            </div>
        </div>
    </div>

</body>
</html>
