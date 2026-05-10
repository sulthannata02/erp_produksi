@extends('layouts.app')
@section('title', 'Laporan Traceability')
@section('page-title', 'Laporan Produksi')
@section('page-sub', 'Ketertelusuran proses dari Material hingga Packing')

@section('content')
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title">Filter Laporan</h3>
        <div style="display:flex; gap:10px">
            <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-primary" style="background:#EF4444; border:none">
                <i class="ph ph-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('laporan.index') }}" style="margin-bottom:24px">
        <div style="display:grid; grid-template-columns: 1.5fr 1fr 1fr; gap:24px; background:#F8FAFC; padding:24px; border-radius:12px; border:1px solid var(--border)">
            <div class="form-group">
                <label class="form-label">Customer</label>
                <select name="customer" class="form-select" style="margin-bottom:12px">
                    <option value="">Semua Customer</option>
                    @foreach($customers as $c)
                        <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <div style="display:flex; gap:10px">
                    <button type="submit" class="btn btn-primary" style="padding:8px 24px"><i class="ph ph-funnel"></i> Terapkan Filter</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary" style="padding:8px 16px">Reset</a>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" style="font-size:12px">
            <thead>
                <tr style="background:#F1F5F9">
                    <th>SPK / Material</th>
                    <th>1. Planning (SPK)</th>
                    <th>2. Produksi (Start/Finish)</th>
                    <th>3. Quality Control</th>
                    <th>4. Packing</th>
                    <th>Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $p)
                <tr>
                    <td style="min-width:180px">
                        <strong style="color:var(--primary)">{{ $p->kode_produksi }}</strong><br>
                        <small>{{ optional($p->material)->nama_material }}</small><br>
                        <span class="badge" style="font-size:10px; background:#E0F2FE; color:#0369A1">{{ optional($p->material)->nama_customer }}</span>
                    </td>
                    <td>
                        <div style="line-height:1.4">
                            📅 {{ $p->created_at->format('d/m/Y') }}<br>
                            ⏰ {{ $p->created_at->format('H:i') }}<br>
                            <small style="color:var(--text-muted)">Target: {{ number_format($p->target_hanger) }} Hanger</small>
                        </div>
                    </td>
                    <td>
                        @if($p->status !== 'rencana')
                            <div style="line-height:1.4">
                                <span style="color:var(--proses)">Start: {{ $p->updated_at->format('d/m/Y H:i') }}</span><br>
                                @if($p->status === 'selesai')
                                    <span style="color:var(--selesai)">Finish: {{ $p->updated_at->format('d/m/Y H:i') }}</span><br>
                                    <small>Hasil: {{ number_format($p->jumlah_produksi) }} Pcs</small>
                                @else
                                    <span class="badge badge-proses">Sedang Jalan</span>
                                @endif
                            </div>
                        @else
                            <span style="color:var(--text-muted)">- Belum Mulai -</span>
                        @endif
                    </td>
                    <td>
                        @if($p->qc)
                            <div style="line-height:1.4">
                                📅 {{ $p->qc->created_at->format('d/m/Y H:i') }}<br>
                                <span style="color:var(--selesai)">FG: {{ number_format($p->qc->jumlah_fg) }}</span> / 
                                <span style="color:var(--ng)">NG: {{ number_format($p->qc->jumlah_ng) }}</span>
                            </div>
                        @else
                            <span style="color:var(--text-muted)">- Antri QC -</span>
                        @endif
                    </td>
                    <td>
                        @if($p->qc && $p->qc->packing)
                            <div style="line-height:1.4">
                                📅 {{ $p->qc->packing->created_at->format('d/m/Y H:i') }}<br>
                                <strong style="color:var(--secondary)">{{ number_format($p->qc->packing->jumlah_box) }} Box</strong><br>
                                <small>FG: {{ number_format($p->qc->packing->jumlah_fg) }}</small>
                            </div>
                        @else
                            <span style="color:var(--text-muted)">- Antri Packing -</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'rencana')
                            <span class="badge badge-normal">Rencana</span>
                        @elseif($p->qc && $p->qc->packing && $p->qc->packing->status === 'selesai')
                            <span class="badge badge-selesai">Gudang Finish Good</span>
                        @elseif($p->qc && $p->qc->packing)
                             <span class="badge badge-proses">Proses Packing</span>
                        @elseif($p->qc)
                             <span class="badge badge-proses">Selesai QC</span>
                        @else
                             <span class="badge badge-proses">Proses Produksi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted)">
                        <i class="ph ph-file-search" style="font-size:40px; display:block; margin-bottom:10px"></i>
                        Data tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px">
        {{ $productions->links() }}
    </div>
</div>
@endsection
