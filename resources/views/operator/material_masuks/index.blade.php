@extends('layouts.app')
@section('title', 'Barang Datang')
@section('page-title', 'Barang Datang (Inbound)')
@section('page-sub', 'Riwayat material masuk dari supplier/customer')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('material-masuks.create') }}" class="btn btn-success">
        <i class="ph ph-plus"></i> Input Barang Datang
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('material-masuks.index') }}" id="form-filter-dn">
        <div class="filter-bar">
            <input type="date" name="tanggal" class="form-select" value="{{ request('tanggal') }}">

            <div class="search-box">
                <input type="text" name="search" placeholder="Cari No. DN atau Material..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm"><i class="ph ph-funnel"></i> Filter</button>
            @if(request('tanggal') || request('search'))
                <a href="{{ route('material-masuks.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-dn">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nomor DN</th>
                    <th>Material</th>
                    <th>Qty Masuk</th>
                    <th>Operator</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materialMasuks as $i => $mm)
                <tr>
                    <td>{{ $materialMasuks->firstItem() + $i }}</td>
                    <td>{{ \Carbon\Carbon::parse($mm->tanggal)->format('d/m/Y') }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $mm->no_dn }}</code></td>
                    <td>
                        <strong>{{ optional($mm->material)->nama_material }}</strong><br>
                        <small style="color:var(--text-muted)">{{ optional($mm->material)->nama_customer }}</small>
                    </td>
                    <td><span class="badge badge-selesai">{{ number_format($mm->qty_masuk) }} {{ optional($mm->material)->satuan }}</span></td>
                    <td>{{ $mm->operator ?? '-' }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('material-masuks.edit', $mm->id) }}" class="btn-edit" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('material-masuks.destroy', $mm->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete(this.form)">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-truck" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data barang datang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($materialMasuks->hasPages())
    <div class="pagination" style="margin-top:16px">
        {{ $materialMasuks->links() }}
    </div>
    @endif
</div>
@endsection
