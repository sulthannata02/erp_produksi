<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #1e293b; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
        .badge-proses { background-color: #fef3c7; color: #92400e; }
        .badge-selesai { background-color: #dcfce7; color: #166534; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #94a3b8; }
        .timestamp { font-size: 8px; color: #64748b; margin-top: 2px; display: block; }
        
        .text-primary { color: #2563eb; }
        .text-success { color: #16a34a; }
        .text-danger { color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PT. ACTMETAL INDONESIA</h1>
        <p>{{ $title }}</p>
        <span style="font-size: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">SPK & Material</th>
                <th width="15%">1. Planning (SPK)</th>
                <th width="20%">2. Produksi (Actual)</th>
                <th width="15%">3. Quality Control</th>
                <th width="15%">4. Packing</th>
                <th width="20%">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productions as $p)
            <tr>
                <td>
                    <strong class="text-primary">{{ $p->kode_produksi }}</strong><br>
                    {{ optional($p->material)->nama_material }}<br>
                    <small>Cust: {{ optional($p->material)->nama_customer }}</small>
                </td>
                <td>
                    Tgl: {{ $p->created_at->format('d/m/Y') }}<br>
                    Jam: {{ $p->created_at->format('H:i') }}<br>
                    Target: {{ number_format($p->target_hanger) }} H
                </td>
                <td>
                    @if($p->status !== 'rencana')
                        <span class="text-primary">Mulai: {{ $p->updated_at->format('d/m/Y H:i') }}</span><br>
                        @if($p->status === 'selesai')
                            <span class="text-success">Selesai: {{ $p->updated_at->format('d/m/Y H:i') }}</span><br>
                            Hasil: {{ number_format($p->jumlah_produksi) }} Pcs
                        @else
                            <span class="badge badge-proses">Sedang Proses</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($p->qc)
                        Tgl: {{ $p->qc->created_at->format('d/m/Y') }}<br>
                        FG: {{ number_format($p->qc->jumlah_fg) }}<br>
                        NG: {{ number_format($p->qc->jumlah_ng) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($p->qc && $p->qc->packing)
                        Tgl: {{ $p->qc->packing->created_at->format('d/m/Y') }}<br>
                        <strong>{{ number_format($p->qc->packing->jumlah_box) }} Box</strong><br>
                        FG: {{ number_format($p->qc->packing->jumlah_fg) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($p->status === 'rencana')
                        Rencana (Belum Jalan)
                    @elseif($p->qc && $p->qc->packing && $p->qc->packing->status === 'selesai')
                        SELESAI (MASUK GUDANG FG)
                    @elseif($p->qc && $p->qc->packing)
                        Sedang Packing
                    @elseif($p->qc)
                        Selesai QC (Antri Packing)
                    @else
                        Proses Produksi
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Halaman 1 dari 1 — PT. Actmetal Indonesia ERP System
    </div>
</body>
</html>
