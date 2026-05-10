@extends('layouts.app')
@section('title', 'Riwayat Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Riwayat pengemasan barang jadi')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('packings.create') }}" class="btn btn-secondary" style="background:var(--secondary); color:#fff; border:none">
        <i class="ph ph-plus"></i> Input Packing Baru
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('packings.index') }}" id="form-filter-packing">
        <div class="filter-bar">
            <select name="customer" class="form-select">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" value="{{ request('tanggal') }}">

            <div class="search-box">
                <input type="text" name="search" placeholder="Cari kode packing..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm"><i class="ph ph-funnel"></i> Filter</button>
            @if(request()->hasAny(['customer','tanggal','search']))
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
                    <th>Kode Packing</th>
                    <th>Material</th>
                    <th>Jumlah FG</th>
                    <th>Jumlah Box</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packings as $i => $pkg)
                <tr>
                    <td>{{ $packings->firstItem() + $i }}</td>
                    <td>{{ $pkg->created_at->format('d/m/Y') }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $pkg->kode_packing }}</code></td>
                    <td>{{ optional($pkg->qc->production->material)->nama_material }}</td>
                    <td>{{ number_format($pkg->jumlah_fg) }} Pcs</td>
                    <td><span class="badge badge-proses">{{ number_format($pkg->jumlah_box) }} Box</span></td>
                    <td>
                        @if($pkg->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('packings.print', $pkg->id) }}" target="_blank" class="btn-edit" title="Print Kanban" style="background:#F0F9FF; color:#0369A1">
                                <i class="ph ph-printer"></i>
                            </a>
                            @if(auth()->user()->role === 'operator')
                                @if($pkg->status === 'proses')
                                    <a href="{{ route('packings.edit', $pkg->id) }}" class="btn btn-secondary btn-sm" style="display:flex; align-items:center; gap:5px; white-space:nowrap; background:var(--secondary); color:#fff; border:none">
                                        <i class="ph ph-check-circle"></i> Selesaikan
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('packings.edit', $pkg->id) }}" class="btn-edit" title="Edit">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('packings.destroy', $pkg->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete(this.form)">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-muted);padding:32px">
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
        {{ $packings->links() }}
    </div>
    @endif
</div>
@endsection