@extends('layouts.app')
@section('title', 'Monitoring')
@section('page-title', 'Monitoring')
@section('page-sub', 'Status keseluruhan proses produksi')

@push('styles')
<style>
/* Pipeline tracker */
.pipeline {
    display: flex;
    align-items: center;
    gap: 0;
    font-size: 12px;
}

.pipe-step {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 11px;
    white-space: nowrap;
}

.pipe-step.done    { background: var(--selesai-bg); color: var(--selesai); }
.pipe-step.pending { background: var(--proses-bg);  color: var(--proses); }
.pipe-step.waiting { background: var(--body-bg);    color: var(--text-muted); border: 1px dashed var(--border); }

.pipe-arrow {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0 2px;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

<div class="card">
    {{-- Filter --}}
    <form method="GET" action="{{ route('monitoring.index') }}" id="form-filter-monitoring">
        <div class="filter-bar">
            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-monitoring"
                   value="{{ request('tanggal') }}">

            <div class="search-box">
                <input type="text" name="search" id="search-monitoring"
                       placeholder="Cari kode, material, customer..."
                       value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-primary btn-sm" id="btn-filter-monitoring">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request()->hasAny(['search','tanggal']))
                <a href="{{ route('monitoring.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-monitoring">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produksi</th>
                    <th>Customer</th>
                    <th>Material</th>
                    <th>Qty</th>
                    <th>Tanggal</th>
                    <th style="min-width:380px">Status Pipeline</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $i => $prod)
                @php
                    $qc      = $prod->qc;
                    $packing = $qc?->packing;

                    // Step 1: Material → selalu done karena produksi sudah ada
                    // Step 2: Produksi
                    $prodDone = true;
                    // Step 3: QC
                    $qcDone   = !is_null($qc);
                    $qcHasil  = $qc?->hasil;
                    // Step 4: Packing
                    $packDone = !is_null($packing);
                @endphp
                <tr>
                    <td>{{ $productions->firstItem() + $i }}</td>
                    <td>
                        <code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">
                            {{ $prod->kode_produksi ?? '-' }}
                        </code>
                    </td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }}</td>
                    <td>{{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <div class="pipeline">
                            {{-- Material --}}
                            <span class="pipe-step done">
                                <i class="ph ph-check-circle"></i> Material
                            </span>
                            <span class="pipe-arrow">→</span>

                            {{-- Produksi --}}
                            <span class="pipe-step done">
                                <i class="ph ph-check-circle"></i> Produksi
                            </span>
                            <span class="pipe-arrow">→</span>

                            {{-- QC --}}
                            @if($qcDone)
                                @if($qcHasil === 'good')
                                    <span class="pipe-step done">
                                        <i class="ph ph-check-circle"></i> QC (FG)
                                    </span>
                                @else
                                    <span class="pipe-step" style="background:var(--ng-bg);color:var(--ng);font-weight:600">
                                        <i class="ph ph-x-circle"></i> QC (NG)
                                    </span>
                                @endif
                            @else
                                <span class="pipe-step pending">
                                    <i class="ph ph-clock"></i> QC
                                </span>
                            @endif
                            <span class="pipe-arrow">→</span>

                            {{-- Packing --}}
                            @if($packDone)
                                <span class="pipe-step done">
                                    <i class="ph ph-check-circle"></i> Packing
                                    @if($packing->kode_packing)
                                        <span style="font-size:10px;opacity:.8">({{ $packing->kode_packing }})</span>
                                    @endif
                                </span>
                            @elseif($qcDone && $qcHasil === 'good')
                                <span class="pipe-step pending">
                                    <i class="ph ph-clock"></i> Packing
                                </span>
                            @else
                                <span class="pipe-step waiting">
                                    <i class="ph ph-minus"></i> Packing
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-magnifying-glass" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data produksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($productions->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($productions->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $productions->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($productions->getUrlRange(1, $productions->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $productions->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($productions->hasMorePages())
            <a href="{{ $productions->nextPageUrl() }}" class="page-btn">»</a>
        @else
            <span class="page-btn" style="opacity:.4">»</span>
        @endif
    </div>
    @endif

    {{-- Legend --}}
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:16px;font-size:12px;flex-wrap:wrap">
        <span style="color:var(--text-muted);font-weight:600">Keterangan:</span>
        <span class="pipe-step done" style="font-size:11px"><i class="ph ph-check-circle"></i> Selesai</span>
        <span class="pipe-step pending" style="font-size:11px"><i class="ph ph-clock"></i> Menunggu</span>
        <span class="pipe-step waiting" style="font-size:11px"><i class="ph ph-minus"></i> Belum dapat diproses</span>
        <span class="pipe-step" style="background:var(--ng-bg);color:var(--ng);font-size:11px"><i class="ph ph-x-circle"></i> QC Tidak Lolos (NG)</span>
    </div>
</div>

@endsection
