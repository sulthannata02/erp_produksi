@extends('layouts.app')
@section('title', 'Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Kelola data packing')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('packings.create') }}" class="btn btn-warning" id="btn-tambah-packing">
        <i class="ph ph-plus"></i> Tambah Packing
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('packings.index') }}" id="form-filter-packing">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-packing">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-packing" value="{{ request('tanggal') }}">

            <select name="status" class="form-select" id="filter-status-packing">
                <option value="">Semua Status</option>
                <option value="proses"  {{ request('status') == 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-packing">
                <i class="ph ph-funnel"></i> Filter
            </button>

            <div class="search-box">
                <input type="text" name="search" id="search-packing"
                       placeholder="Cari packing..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            @if(request()->hasAny(['customer','tanggal','status','search']))
                <a href="{{ route('packings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-packing">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Packing</th>
                    <th>Material</th>
                    <th>Total Finish Good</th>
                    <th>Total Not Good</th>
                    <th>Keterangan</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packings as $i => $packing)
                <tr>
                    <td>{{ $packings->firstItem() + $i }}</td>
                    <td>{{ $packing->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional(optional(optional($packing->qc)->production)->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $packing->kode_packing ?? '-' }}</code></td>
                    <td>{{ optional(optional(optional($packing->qc)->production)->material)->nama_material ?? '-' }}</td>
                    <td><span style="font-weight:600;color:var(--fg-ok)">{{ number_format($packing->jumlah_fg) }}</span></td>
                    <td><span style="font-weight:600;color:var(--ng)">{{ number_format($packing->jumlah_ng) }}</span></td>
                    <td style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $packing->keterangan }}">
                        {{ $packing->keterangan ?? '-' }}
                    </td>
                    <td>{{ $packing->operator ?? '-' }}</td>
                    <td>
                        @if($packing->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('packings.edit', $packing->id) }}" class="btn-edit" title="Edit" id="btn-edit-packing-{{ $packing->id }}">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('packings.destroy', $packing->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus data packing ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" id="btn-del-packing-{{ $packing->id }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-archive-box" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data packing
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($packings->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($packings->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $packings->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($packings->getUrlRange(1, $packings->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $packings->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($packings->hasMorePages())
            <a href="{{ $packings->nextPageUrl() }}" class="page-btn">»</a>
        @else
            <span class="page-btn" style="opacity:.4">»</span>
        @endif
    </div>
    @endif
</div>
@endsection