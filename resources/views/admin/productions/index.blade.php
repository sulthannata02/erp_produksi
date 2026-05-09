@extends('layouts.app')
@section('title', 'Produksi')
@section('page-title', 'Produksi')
@section('page-sub', 'Kelola data produksi')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('productions.create') }}" class="btn btn-success" id="btn-tambah-produksi">
        <i class="ph ph-plus"></i> Tambah Produksi
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('productions.index') }}" id="form-filter-produksi">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-produksi">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-produksi"
                   value="{{ request('tanggal') }}">

            <div class="search-box">
                <input type="text" name="search" id="search-produksi"
                       placeholder="Cari produksi..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-produksi">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request()->hasAny(['customer','tanggal','search']))
                <a href="{{ route('productions.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-produksi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Produksi</th>
                    <th>Material</th>
                    <th>Qty Produksi</th>
                    <th>Satuan</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $i => $prod)
                <tr>
                    <td>{{ $productions->firstItem() + $i }}</td>
                    <td>{{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d/m/Y') : '-' }}</td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $prod->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }}</td>
                    <td>{{ optional($prod->material)->satuan ?? '-' }}</td>
                    <td>{{ $prod->operator ?? '-' }}</td>
                    <td>
                        @if($prod->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('productions.edit', $prod->id) }}" class="btn-edit" title="Edit" id="btn-edit-prod-{{ $prod->id }}">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('productions.destroy', $prod->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus data produksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" id="btn-del-prod-{{ $prod->id }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-factory" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data produksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
</div>
@endsection