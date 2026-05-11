@extends('layouts.app')
@section('title', 'Riwayat QC')
@section('page-title', 'Quality Control')
@section('page-sub', 'Riwayat pengecekan kualitas barang')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('qcs.create') }}" class="btn btn-success">
        <i class="ph ph-plus"></i> Input QC Baru
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('qcs.index') }}" id="form-filter-qc">
        <div class="filter-bar">
            <select name="customer" class="form-select">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" value="{{ request('tanggal') }}">

            <select name="status" class="form-select">
                <option value="">Pilih Status</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <div class="search-box">
                <input type="text" name="search" placeholder="Cari batch..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm"><i class="ph ph-funnel"></i> Filter</button>
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
                    <th>Kode Produksi</th>
                    <th>Material</th>
                    <th>Qty QC</th>
                    <th>Thick Atas</th>
                    <th>Thick Bawah</th>
                    <th>Status QC</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $i => $qc)
                <tr>
                    <td>{{ $qcs->firstItem() + $i }}</td>
                    <td>{{ $qc->created_at->format('d/m/Y') }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ optional($qc->production)->kode_produksi }}</code></td>
                    <td>{{ optional($qc->production->material)->nama_material }}</td>
                    <td>{{ number_format($qc->qty_qc) }}</td>
                    <td>{{ $qc->thickness_atas ?? '-' }}</td>
                    <td>{{ $qc->thickness_bawah ?? '-' }}</td>
                    <td>
                        @if($qc->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            @if($qc->status === 'proses')
                                <a href="{{ route('qcs.edit', $qc->id) }}" class="btn btn-primary btn-sm" style="display:flex; align-items:center; gap:5px; white-space:nowrap">
                                    <i class="ph ph-shield-check"></i> Selesaikan QC
                                </a>
                            @else
                                <a href="{{ route('qcs.edit', $qc->id) }}" class="btn-edit" title="Edit Data">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                            @endif
                            <form action="{{ route('qcs.destroy', $qc->id) }}" method="POST" style="display:inline" id="form-delete-qc-{{ $qc->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete('form-delete-qc-{{ $qc->id }}')">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--text-muted);padding:32px">
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
        {{ $qcs->links() }}
    </div>
    @endif
</div>
@endsection