@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('page-title', 'Dashboard')
@section('page-sub', 'Daftar pekerjaan hari ini 👷')

@section('content')

{{-- ═══ STAT CARDS ═══ --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="label">Barang Datang</div>
            <div class="value" style="color:var(--primary)">{{ $totalBarangDatang }}</div>
            <div class="sub">Total Material Masuk</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🏭</div>
        <div class="stat-info">
            <div class="label">Total Produksi</div>
            <div class="value" style="color:var(--selesai)">{{ $totalProduksi }}</div>
            <div class="sub">Data Produksi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <img src="{{ asset('images/quality-control.png') }}" alt="QC" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stat-info">
            <div class="label">Total QC</div>
            <div class="value" style="color:#7C3AED">{{ $totalQc }}</div>
            <div class="sub">Data QC Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <img src="{{ asset('images/icon packing.png') }}" alt="Packing" style="width: 24px; height: 24px; object-fit: contain;">
        </div>
        <div class="stat-info">
            <div class="label">Total Packing</div>
            <div class="value" style="color:var(--proses)">{{ $totalPacking }}</div>
            <div class="sub">Data Packing Selesai</div>
        </div>
    </div>
</div>

{{-- ═══ MENU FITUR OPERATOR ═══ --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-title">Menu Fitur</div>
    <div class="feature-grid" style="grid-template-columns:repeat(5,1fr)">
        <div class="feature-card">
            <span class="feat-icon">📦</span>
            <h3>Barang Datang</h3>
            <p>Input material baru datang</p>
            <a href="{{ route('material-masuks.index') }}" class="btn btn-feat-blue btn-sm" style="background:#14B8A6">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">🏭</span>
            <h3>Produksi</h3>
            <p>Input hasil kerja produksi</p>
            <a href="{{ route('productions.index') }}" class="btn btn-feat-green btn-sm">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">
                <img src="{{ asset('images/quality-control.png') }}" alt="QC" style="width: 40px; height: 40px; object-fit: contain; margin: 0 auto;">
            </span>
            <h3>Quality Control</h3>
            <p>Input hasil QC produksi</p>
            <a href="{{ route('qcs.index') }}" class="btn btn-feat-purple btn-sm" id="btn-go-qc">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">
                <img src="{{ asset('images/icon packing.png') }}" alt="Packing" style="width: 40px; height: 40px; object-fit: contain; margin: 0 auto;">
            </span>
            <h3>Packing</h3>
            <p>Input data pengemasan</p>
            <a href="{{ route('packings.index') }}" class="btn btn-feat-orange btn-sm" id="btn-go-packing">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">🔍</span>
            <h3>Tracking</h3>
            <p>Pantau status seluruh proses</p>
            <a href="{{ route('tracking.index') }}" class="btn btn-feat-blue btn-sm" id="btn-go-tracking">Masuk</a>
        </div>
    </div>
</div>

{{-- ═══ PRIORITAS PRODUKSI (DELIVERY NOTE) ═══ --}}
<div class="card" style="margin-bottom:20px; border-left: 5px solid var(--primary)">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="card-title" style="margin-bottom:0">
            📋 Prioritas Produksi (Delivery Note)
        </div>
        <a href="{{ route('productions.index') }}" class="priority-link">Lihat Semua Data <i class="ph ph-arrow-right"></i></a>
    </div>
    <div class="table-wrapper">
        <table class="table" id="table-prioritas">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Material</th>
                    <th>Jumlah (Qty)</th>
                    <th>Deadline</th>
                    <th>Prioritas</th>
                    <th>Status / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prioritas as $i => $prod)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>
                        <strong>{{ optional($prod->material)->nama_material ?? '-' }}</strong><br>
                        <small style="color:var(--text-muted)">{{ $prod->kode_produksi }}</small>
                    </td>
                    <td>
                        <strong>{{ number_format($prod->target_hanger * (optional($prod->material)->qty_per_hanger ?? 0)) }}</strong>
                        <small style="color:var(--text-muted)">Pcs</small>
                    </td>
                    <td>{{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d M Y') : '-' }}</td>
                    <td>
                        @php
                            $days = $prod->tanggal_produksi ? now()->diffInDays($prod->tanggal_produksi, false) : null;
                            if ($days === null) $p = 'normal';
                            elseif ($days <= 2)  $p = 'urgent';
                            elseif ($days <= 5)  $p = 'normal';
                            else                 $p = 'low';
                        @endphp
                        <span class="badge badge-{{ $p }}">{{ ucfirst($p) }}</span>
                    </td>
                    <td>
                        @if($prod->status !== 'selesai')
                            <a href="{{ route('production-work.showStart', $prod->id) }}" class="btn btn-primary btn-sm" style="background:var(--primary); border:none">
                                <i class="ph ph-play-circle"></i> Mulai Kerja
                            </a>
                        @else
                            <span class="badge badge-selesai">Selesai</span>
                            <br><small style="color:var(--text-muted); font-size:10px">{{ $prod->operator }}</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:24px">
                        <i class="ph ph-calendar-blank" style="font-size:28px;display:block;margin-bottom:6px"></i>
                        Belum ada data prioritas produksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
// Modal logic removed as requested - using dedicated pages now
</script>
@endpush
@endsection
