<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Kanban - {{ $packing->kode_packing }}</title>
    <style>
        @page {
            size: A6;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 10mm;
            color: #333;
            background: #fff;
        }
        .kanban-card {
            border: 2px solid #000;
            padding: 5mm;
            height: calc(148mm - 20mm - 4px); /* A6 height minus padding and border */
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 3mm;
            margin-bottom: 5mm;
        }
        .logo {
            height: 12mm;
        }
        .title {
            font-size: 20pt;
            font-weight: bold;
            text-align: right;
        }
        .content {
            flex-grow: 1;
        }
        .info-row {
            display: flex;
            margin-bottom: 3mm;
            border-bottom: 1px solid #ddd;
            padding-bottom: 1mm;
        }
        .label {
            width: 35mm;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            color: #666;
        }
        .value {
            flex-grow: 1;
            font-size: 12pt;
            font-weight: bold;
        }
        .qty-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5mm;
            margin-top: 5mm;
        }
        .qty-box {
            border: 1px solid #000;
            padding: 2mm;
            text-align: center;
        }
        .qty-label {
            font-size: 9pt;
            margin-bottom: 1mm;
        }
        .qty-value {
            font-size: 18pt;
            font-weight: 900;
        }
        .footer {
            margin-top: 5mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 8pt;
        }
        .barcode {
            font-family: 'Libre Barcode 39', cursive; /* Fallback to text if not available */
            font-size: 30pt;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #14B8A6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Sekarang</button>

    <div class="kanban-card">
        <div class="header">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
            <div class="title">KANBAN</div>
        </div>

        <div class="content">
            <div class="info-row">
                <div class="label">Customer</div>
                <div class="value">{{ optional(optional(optional($packing->qc)->production)->material)->nama_customer ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Material</div>
                <div class="value">{{ optional(optional(optional($packing->qc)->production)->material)->nama_material ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">Kode Material</div>
                <div class="value">{{ optional(optional(optional($packing->qc)->production)->material)->kode_part ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="label">No. Packing</div>
                <div class="value">{{ $packing->kode_packing }}</div>
            </div>
            <div class="info-row">
                <div class="label">Tgl Packing</div>
                <div class="value">{{ $packing->created_at->format('d F Y') }}</div>
            </div>

            <div class="qty-section">
                <div class="qty-box">
                    <div class="qty-label">QTY / BOX</div>
                    <div class="qty-value">{{ number_format(optional(optional(optional($packing->qc)->production)->material)->qty_per_box ?? 0) }}</div>
                </div>
                <div class="qty-box">
                    <div class="qty-label">JUMLAH BOX</div>
                    <div class="qty-value">{{ number_format($packing->jumlah_box) }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div>
                <div>Operator: {{ $packing->operator }}</div>
                <div style="margin-top: 2mm; font-size: 10pt; font-weight: bold;">{{ $packing->kode_packing }}</div>
            </div>
            <div style="text-align: right">
                <div style="font-size: 7pt">Status</div>
                <div style="font-size: 10pt; font-weight: bold; color: green;">PASSED</div>
            </div>
        </div>
    </div>

    <script>
        // window.print(); // Uncomment to auto-print on load
    </script>
</body>
</html>
