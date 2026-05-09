@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-sub', 'Selamat datang, Admin! 👋')

@push('styles')
<style>
.priority-link { 
    font-size: 13px; 
    font-weight: 600; 
    color: var(--primary); 
    background: rgba(30,111,217, 0.1); /* light primary bg */
    display: inline-flex; 
    align-items: center; 
    gap: 6px; 
    padding: 6px 16px;
    border-radius: 20px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(30,111,217, 0.2);
}
.priority-link:hover { 
    background: var(--primary); 
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30,111,217, 0.25);
    border-color: var(--primary);
}
.priority-link i {
    transition: transform 0.3s ease;
}
.priority-link:hover i {
    transform: translateX(4px);
}
</style>
@endpush

@section('content')

{{-- ═══ MENU FITUR ═══ --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-title">Menu Fitur</div>
    <div class="feature-grid" style="grid-template-columns:repeat(5,1fr)">
        <div class="feature-card">
            <span class="feat-icon">📦</span>
            <h3>Material</h3>
            <p>Kelola data material</p>
            <a href="{{ route('materials.index') }}" class="btn btn-feat-blue btn-sm">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">🏭</span>
            <h3>Produksi</h3>
            <p>Kelola data produksi</p>
            <a href="{{ route('productions.index') }}" class="btn btn-feat-green btn-sm">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">📊</span>
            <h3>Monitoring</h3>
            <p>Pantau seluruh proses</p>
            <a href="{{ route('monitoring.index') }}" class="btn btn-feat-orange btn-sm">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">📋</span>
            <h3>Laporan</h3>
            <p>Lihat laporan produksi</p>
            <a href="{{ route('laporan.index') }}" class="btn btn-feat-pink btn-sm">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">👥</span>
            <h3>Pengguna</h3>
            <p>Kelola data akses sistem</p>
            <a href="{{ route('users.index') }}" class="btn btn-feat-blue btn-sm" style="background:#14B8A6">Masuk</a>
        </div>
    </div>
</div>

{{-- ═══ PRIORITAS PRODUKSI ═══ --}}
<div class="card" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="card-title" style="margin-bottom:0">Prioritas Produksi (Delivery Note)</div>
        <a href="{{ route('productions.index') }}" class="priority-link">Lihat Semua DN <i class="ph ph-arrow-right"></i></a>
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
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prioritas as $i => $prod)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }}</td>
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
                        @if($prod->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @elseif($prod->status === 'proses')
                            <span class="badge badge-proses">Proses</span>
                        @else
                            <span class="badge badge-belum">Belum Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:24px">Belum ada data produksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══ BOTTOM: RINGKASAN + GRAFIK ═══ --}}
<div class="dash-bottom">
    {{-- Ringkasan Data --}}
    <div class="card">
        <div class="card-title">Ringkasan Data</div>
        <div class="stat-grid" style="grid-template-columns:repeat(2,1fr);gap:12px;">
            <div class="stat-card">
                <div class="stat-icon blue">📦</div>
                <div class="stat-info">
                    <div class="label">Total Material</div>
                    <div class="value">{{ number_format($totalMaterial) }}</div>
                    <div class="sub">Jenis Material</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">🏭</div>
                <div class="stat-info">
                    <div class="label">Total Produksi</div>
                    <div class="value">{{ number_format($totalProduksi) }}</div>
                    <div class="sub">Data Produksi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">🛡️</div>
                <div class="stat-info">
                    <div class="label">Total QC</div>
                    <div class="value">{{ number_format($totalQc) }}</div>
                    <div class="sub">Data QC</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">📬</div>
                <div class="stat-info">
                    <div class="label">Total Packing</div>
                    <div class="value">{{ number_format($totalPacking) }}</div>
                    <div class="sub">Data Packing</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Produksi --}}
    <div class="card">
        <div class="card-title">Grafik Produksi (30 Hari Terakhir)</div>
        <div class="chart-container">
            <canvas id="chartProduksi"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartProduksi').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($grafikLabel),
        datasets: [{
            label: 'Jumlah Produksi',
            data: @json($grafikData),
            borderColor: '#1E6FD9',
            backgroundColor: 'rgba(30,111,217,.1)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#1E6FD9',
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F0F0F0' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
</script>
@endpush
