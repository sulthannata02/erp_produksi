@extends('layouts.app')
@section('title', 'Detail QC')
@section('page-title', 'QC (Quality Control)')
@section('page-sub', 'Detail data QC')

@section('content')
<div class="card" style="max-width:700px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <div class="card-title" style="margin-bottom:0">Detail QC</div>
        <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <table style="width:100%;font-size:13px;border-collapse:collapse" id="table-detail-qc">
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted);width:40%">Kode Produksi</td>
            <td style="padding:10px;font-weight:600">{{ optional($qc->production)->kode_produksi ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Customer</td>
            <td style="padding:10px">{{ optional(optional($qc->production)->material)->nama_customer ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Material</td>
            <td style="padding:10px">{{ optional(optional($qc->production)->material)->nama_material ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Qty QC</td>
            <td style="padding:10px">{{ number_format($qc->qty_qc ?? 0) }} {{ optional(optional($qc->production)->material)->satuan }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Thickness Atas</td>
            <td style="padding:10px;font-weight:600">{{ $qc->thickness_atas ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Thickness Bawah</td>
            <td style="padding:10px;font-weight:600">{{ $qc->thickness_bawah ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Keterangan</td>
            <td style="padding:10px">{{ $qc->keterangan ?? '-' }}</td>
        </tr>
        <tr style="border-bottom:1px solid var(--border)">
            <td style="padding:10px;color:var(--text-muted)">Status</td>
            <td style="padding:10px">
                @if($qc->status === 'selesai')
                    <span class="badge badge-selesai">Selesai</span>
                @else
                    <span class="badge badge-proses">Proses</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding:10px;color:var(--text-muted)">Tanggal Input</td>
            <td style="padding:10px">{{ $qc->created_at->format('d M Y, H:i') }} WIB</td>
        </tr>
    </table>

    @if($qc->packing)
    <div style="margin-top:20px;padding-top:20px;border-top:2px solid var(--border)">
        <div class="card-title">Data Packing Terkait</div>
        <table style="width:100%;font-size:13px;border-collapse:collapse">
            <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:10px;color:var(--text-muted);width:40%">Kode Packing</td>
                <td style="padding:10px;font-weight:600">{{ $qc->packing->kode_packing }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:10px;color:var(--text-muted)">Finish Good (FG)</td>
                <td style="padding:10px;color:var(--fg-ok);font-weight:600">{{ number_format($qc->packing->jumlah_fg) }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--border)">
                <td style="padding:10px;color:var(--text-muted)">Not Good (NG)</td>
                <td style="padding:10px;color:var(--ng);font-weight:600">{{ number_format($qc->packing->jumlah_ng) }}</td>
            </tr>
            <tr>
                <td style="padding:10px;color:var(--text-muted)">Operator</td>
                <td style="padding:10px">{{ $qc->packing->operator }}</td>
            </tr>
        </table>
    </div>
    @endif
</div>
@endsection
