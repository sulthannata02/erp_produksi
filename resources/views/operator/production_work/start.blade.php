@extends('layouts.app')
@section('title', 'Mulai Produksi')
@section('page-title', 'Validasi SPK')
@section('page-sub', 'Konfirmasi dan mulai pengerjaan produksi')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Mulai Kerja: {{ $production->kode_produksi }}</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('production-work.start', $production->id) }}" method="POST">
        @csrf
        <div style="background:#F0F9FF; border:1px solid #BAE6FD; border-radius:12px; padding:20px; margin-bottom:24px">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; font-size:14px">
                <div>
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong>{{ optional($production->material)->nama_material }}</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Customer</small>
                    <strong>{{ optional($production->material)->nama_customer }}</strong>
                </div>
                <div style="margin-top:10px">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Target Admin</small>
                    <strong style="color:var(--primary)">{{ number_format($production->target_hanger) }} Hanger</strong>
                </div>
                <div style="margin-top:10px; text-align:right">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Stok Tersedia</small>
                    <strong>{{ number_format(optional($production->material)->aktual_stok) }} Pcs</strong>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Input Jumlah Hanger Aktual <span style="color:var(--ng)">*</span></label>
            <div style="position:relative">
                <input type="number" name="jumlah_hanger" id="input-actual-hanger" class="form-control" value="{{ old('jumlah_hanger', $production->target_hanger) }}" required min="1" oninput="calculateTotal()" style="font-size:24px; font-weight:800; text-align:center; padding:15px">
                <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Hanger</span>
            </div>
            <small style="color:var(--text-muted); display:block; margin-top:8px; text-align:center">Sesuaikan jika jumlah hanger riil berbeda dengan rencana admin.</small>
        </div>

        <div style="background:var(--body-bg); border-radius:16px; padding:24px; text-align:center; margin-bottom:24px; border:2px dashed var(--border)">
            <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:5px">Total Stok Akan Dipotong</div>
            <div style="font-size:36px; font-weight:800; color:var(--primary)">
                <span id="display-total-pcs">0</span>
                <span style="font-size:16px; font-weight:600">Pcs</span>
            </div>
            <small id="warning-stok" style="color:var(--ng); font-weight:700; display:none"><i class="ph ph-warning"></i> Stok tidak mencukupi!</small>
        </div>

        <div style="display:flex; justify-content:center; gap:12px">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="flex:1; padding:15px; font-size:16px; font-weight:700">Batal</a>
            <button type="submit" id="btn-submit" class="btn btn-primary" style="flex:2; padding:15px; font-size:16px; font-weight:700">
                <i class="ph ph-check-square"></i> Validasi & Mulai Produksi
            </button>
        </div>
            <p style="font-size:11px; color:var(--text-muted); text-align:center">Dengan mengeklik tombol di atas, Anda mengonfirmasi bahwa material telah diambil dari gudang.</p>
        </div>
    </form>
</div>

{{-- Hidden Material Info --}}
<div id="mat-info" 
     data-qty-hanger="{{ optional($production->material)->qty_per_hanger }}" 
     data-stok="{{ optional($production->material)->aktual_stok }}"
     style="display:none"></div>

@push('scripts')
<script>
function calculateTotal() {
    const info = document.getElementById('mat-info').dataset;
    const qtyPerHanger = parseInt(info.qtyHanger) || 0;
    const stokTersedia = parseInt(info.stok) || 0;
    const hanger = parseInt(document.getElementById('input-actual-hanger').value) || 0;
    
    const totalPcs = hanger * qtyPerHanger;
    document.getElementById('display-total-pcs').textContent = totalPcs.toLocaleString('id');
    
    const warning = document.getElementById('warning-stok');
    const btn = document.getElementById('btn-submit');
    
    if (totalPcs > stokTersedia) {
        warning.style.display = 'block';
        btn.disabled = true;
        btn.style.opacity = '0.5';
    } else {
        warning.style.display = 'none';
        btn.disabled = false;
        btn.style.opacity = '1';
    }
}
document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
@endsection
