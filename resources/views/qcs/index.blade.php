@extends('layouts.app')
@section('title', 'QC (Quality Control)')
@section('page-title', 'QC (Quality Control)')
@section('page-sub', 'Kelola data quality control')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('qcs.create') }}" class="btn btn-purple" id="btn-tambah-qc">
        <i class="ph ph-plus"></i> Tambah QC
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('qcs.index') }}" id="form-filter-qc">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-qc">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-qc" value="{{ request('tanggal') }}">

            <select name="status" class="form-select" id="filter-status-qc">
                <option value="">Semua Status</option>
                <option value="proses"  {{ request('status') == 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-qc">
                <i class="ph ph-funnel"></i> Filter
            </button>

            <div class="search-box">
                <input type="text" name="search" id="search-qc"
                       placeholder="Cari QC..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            @if(request()->hasAny(['customer','tanggal','status','search']))
                <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-qc">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Produksi</th>
                    <th>Material</th>
                    <th>Qty QC</th>
                    <th>Hasil</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $i => $qc)
                <tr>
                    <td>{{ $qcs->firstItem() + $i }}</td>
                    <td>{{ $qc->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional(optional($qc->production)->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ optional($qc->production)->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional(optional($qc->production)->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($qc->qty_qc ?? 0) }}</td>
                    <td>
                        @if($qc->hasil === 'good')
                            <span class="badge badge-fg">FG (OK)</span>
                        @else
                            <span class="badge badge-ng">NG</span>
                        @endif
                    </td>
                    <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $qc->keterangan }}">
                        {{ $qc->keterangan ?? '-' }}
                    </td>
                    <td>
                        @if($qc->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('qcs.show', $qc->id) }}" class="btn-view" title="Lihat Detail" id="btn-view-qc-{{ $qc->id }}">
                                <i class="ph ph-eye"></i>
                            </a>
                            <a href="{{ route('qcs.edit', $qc->id) }}" class="btn-edit" title="Edit" id="btn-edit-qc-{{ $qc->id }}">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-shield-check" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data QC
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qcs->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($qcs->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $qcs->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($qcs->getUrlRange(1, $qcs->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $qcs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($qcs->hasMorePages())
            <a href="{{ $qcs->nextPageUrl() }}" class="page-btn">»</a>
        @else
            <span class="page-btn" style="opacity:.4">»</span>
        @endif
    </div>
    @endif
</div>
@endsection