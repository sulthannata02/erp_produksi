@extends('layouts.app')
@section('title', 'Produksi')
@section('page-title', 'Produksi')
@section('page-sub', 'Kelola data dan SPK produksi')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('productions.create') }}" class="btn btn-success">
        <i class="ph ph-plus"></i> Tambah SPK Baru
    </a>
</div>

{{-- ═══ SUMMARY CARDS ═══ --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue">📅</div>
        <div class="stat-info">
            <div class="label">Total SPK (Rencana)</div>
            <div class="value" style="color:var(--primary)">{{ $productions->where('status', 'rencana')->count() }}</div>
            <div class="sub">Menunggu Validasi Operator</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">⚙️</div>
        <div class="stat-info">
            <div class="label">Produksi Berjalan</div>
            <div class="value" style="color:var(--proses)">{{ $productions->where('status', 'proses')->count() }}</div>
            <div class="sub">Sedang dikerjakan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-info">
            <div class="label">Produksi Selesai</div>
            <div class="value" style="color:var(--selesai)">{{ $productions->where('status', 'selesai')->count() }}</div>
            <div class="sub">Total batch selesai</div>
        </div>
    </div>
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

        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-produksi">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Detail SPK</th>
                    <th>Material</th>
                    <th>Progress / Qty</th>
                    <th>Status</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $i => $prod)
                <tr>
                    <td>{{ $productions->firstItem() + $i }}</td>
                    <td>
                        <div style="line-height:1.4">
                            <code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px;display:inline-block;margin-bottom:4px">{{ $prod->kode_produksi ?? '-' }}</code><br>
                            <span style="font-size:11px;color:var(--text-muted)">
                                📅 {{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d/m/Y') : '-' }}<br>
                                🏢 {{ optional($prod->material)->nama_customer ?? '-' }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <strong>{{ optional($prod->material)->nama_material ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted)">Part: {{ optional($prod->material)->kode_part ?? '-' }}</small>
                    </td>
                    <td>
                        <div style="line-height:1.4">
                            <span class="badge" style="background:#EFF6FF;color:#2563EB;margin-bottom:4px">
                                {{ number_format($prod->jumlah_hanger) }} / {{ number_format($prod->target_hanger) }} Hanger
                            </span><br>
                            <strong>
                                @php
                                    $currentQty = $prod->status === 'rencana' 
                                        ? ($prod->target_hanger * (optional($prod->material)->qty_per_hanger ?? 0))
                                        : $prod->jumlah_produksi;
                                    $qtyPerBox = optional($prod->material)->qty_per_box ?: 1;
                                @endphp
                                {{ number_format($currentQty) }} Pcs
                            </strong>
                            <span style="color:var(--text-muted);font-size:11px">({{ ceil($currentQty / $qtyPerBox) }} Box)</span>
                        </div>
                    </td>
                    <td>
                        @if($prod->status === 'rencana')
                            <span class="badge" style="background:#E0F2FE; color:#0369A1; border: 1px solid #BAE6FD">Rencana</span>
                        @elseif($prod->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            @if(auth()->user()->role === 'operator')
                                <a href="{{ route('productions.status', $prod->id) }}" class="btn btn-primary btn-sm" 
                                        style="display:flex; align-items:center; gap:5px; white-space:nowrap; background:#10B981; border:none">
                                    <i class="ph ph-arrow-circle-right"></i> Status
                                </a>
                            @endif
                            
                            <a href="{{ route('productions.edit', $prod->id) }}" class="btn-edit" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>

                            <form action="{{ route('productions.destroy', $prod->id) }}" method="POST" style="display:inline" id="form-delete-{{ $prod->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete('form-delete-{{ $prod->id }}')">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);padding:32px">
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
        {{ $productions->links() }}
    </div>
    @endif
</div>

@endsection