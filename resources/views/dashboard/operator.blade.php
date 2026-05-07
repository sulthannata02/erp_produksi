@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('page-title', 'Dashboard')
@section('page-sub', 'Daftar pekerjaan hari ini 👷')

@section('content')

{{-- ═══ STAT CARDS ═══ --}}
<div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div class="stat-info">
            <div class="label">Menunggu QC</div>
            <div class="value" style="color:var(--proses)">{{ $totalBelumQc }}</div>
            <div class="sub">Produksi belum di-QC</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">📬</div>
        <div class="stat-info">
            <div class="label">Menunggu Packing</div>
            <div class="value" style="color:#7C3AED">{{ $totalBelumPacking }}</div>
            <div class="sub">Siap dikemas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🛡️</div>
        <div class="stat-info">
            <div class="label">QC Selesai</div>
            <div class="value" style="color:var(--selesai)">{{ $totalQcSelesai }}</div>
            <div class="sub">Total QC selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">✅</div>
        <div class="stat-info">
            <div class="label">Packing Selesai</div>
            <div class="value" style="color:var(--primary)">{{ $totalPackSelesai }}</div>
            <div class="sub">Total packing selesai</div>
        </div>
    </div>
</div>

{{-- ═══ MENU FITUR OPERATOR ═══ --}}
<div class="card" style="margin-bottom:20px">
    <div class="card-title">Menu Fitur</div>
    <div class="feature-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="feature-card">
            <span class="feat-icon">🛡️</span>
            <h3>Quality Control</h3>
            <p>Input hasil QC produksi</p>
            <a href="{{ route('qcs.index') }}" class="btn btn-feat-purple btn-sm" id="btn-go-qc">Masuk</a>
        </div>
        <div class="feature-card">
            <span class="feat-icon">📬</span>
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

{{-- ═══ PRODUKSI MENUNGGU QC ═══ --}}
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="card-title" style="margin-bottom:0">
            ⏳ Produksi Menunggu QC
            @if($totalBelumQc > 0)
                <span class="badge badge-proses" style="margin-left:8px">{{ $totalBelumQc }} item</span>
            @endif
        </div>
        <a href="{{ route('qcs.create') }}" class="btn btn-purple btn-sm" id="btn-tambah-qc-dash">
            <i class="ph ph-plus"></i> Input QC
        </a>
    </div>
    <div class="table-wrapper">
        <table class="table" id="table-belum-qc">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produksi</th>
                    <th>Customer</th>
                    <th>Material</th>
                    <th>Qty Produksi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beluQc as $i => $prod)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $prod->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }}</td>
                    <td>{{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d/m/Y') : '-' }}</td>
                    <td><span class="badge badge-belum">Belum QC</span></td>
                    <td>
                        <a href="{{ route('qcs.create') }}?production={{ $prod->id }}"
                           class="btn btn-purple btn-sm" id="btn-qc-prod-{{ $prod->id }}">
                            <i class="ph ph-shield-check"></i> QC Sekarang
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-muted);padding:24px">
                        <i class="ph ph-check-circle" style="font-size:28px;display:block;margin-bottom:6px;color:var(--selesai)"></i>
                        Semua produksi sudah di-QC 🎉
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══ SIAP PACKING ═══ --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="card-title" style="margin-bottom:0">
            📬 Siap Packing (QC Lolos)
            @if($totalBelumPacking > 0)
                <span class="badge badge-fg" style="margin-left:8px">{{ $totalBelumPacking }} item</span>
            @endif
        </div>
        <a href="{{ route('packings.create') }}" class="btn btn-warning btn-sm" id="btn-tambah-packing-dash">
            <i class="ph ph-plus"></i> Input Packing
        </a>
    </div>
    <div class="table-wrapper">
        <table class="table" id="table-siap-packing">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produksi</th>
                    <th>Customer</th>
                    <th>Material</th>
                    <th>Qty QC</th>
                    <th>Hasil QC</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($belumPacking as $i => $prod)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $prod->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format(optional($prod->qc)->qty_qc ?? 0) }} {{ optional($prod->material)->satuan }}</td>
                    <td><span class="badge badge-fg">FG (OK)</span></td>
                    <td>
                        <a href="{{ route('packings.create') }}?qc={{ optional($prod->qc)->id }}"
                           class="btn btn-warning btn-sm" id="btn-pack-prod-{{ $prod->id }}">
                            <i class="ph ph-archive-box"></i> Packing Sekarang
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:24px">
                        <i class="ph ph-check-circle" style="font-size:28px;display:block;margin-bottom:6px;color:var(--selesai)"></i>
                        Tidak ada produksi yang siap packing saat ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
