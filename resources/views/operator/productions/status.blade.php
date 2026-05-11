@extends('layouts.app')
@section('title', 'Update Status Produksi')
@section('page-title', 'Update Status')
@section('page-sub', 'Ganti status pengerjaan dan validasi hanger')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Status SPK: {{ $production->kode_produksi }}</h3>
        <a href="{{ route('productions.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('productions.updateStatus', $production->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="background:#F8FAFC; border-radius:12px; padding:20px; margin-bottom:24px; border:1px solid var(--border)">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; font-size:14px">
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong>{{ optional($production->material)->nama_material }}</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Target (Plan)</small>
                    <strong style="color:var(--primary)">{{ number_format($production->target_hanger) }} Hanger</strong>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Pilih Status Baru <span style="color:var(--ng)">*</span></label>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px">
                <label class="status-option">
                    <input type="radio" name="status" value="proses" {{ $production->status === 'proses' ? 'checked' : '' }} required>
                    <div class="status-box">
                        <i class="ph ph-clock"></i>
                        <span>Sedang Proses</span>
                    </div>
                </label>
                <label class="status-option">
                    <input type="radio" name="status" value="selesai" {{ $production->status === 'selesai' ? 'checked' : '' }} required>
                    <div class="status-box box-selesai">
                        <i class="ph ph-check-circle"></i>
                        <span>Selesai Produksi</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top:24px">
            <label class="form-label">Actual Hanger Lapangan <span style="color:var(--ng)">*</span></label>
            <div style="position:relative">
                <input type="number" name="jumlah_hanger" id="input-hanger" class="form-control" value="{{ old('jumlah_hanger', $production->jumlah_hanger ?: $production->target_hanger) }}" required min="1" oninput="calculateTotal()" style="font-size:24px; font-weight:800; text-align:center; padding:15px">
                <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Hanger</span>
            </div>
        </div>

        <div style="background:var(--body-bg); border-radius:16px; padding:20px; text-align:center; margin-bottom:24px; border:2px dashed var(--border)">
            <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px">Total Hasil (Estimasi)</div>
            <div style="font-size:32px; font-weight:800; color:var(--secondary)">
                <span id="display-total">0</span>
                <span style="font-size:14px; font-weight:600">Pcs</span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 2fr; gap:12px">
            <a href="{{ route('productions.index') }}" class="btn btn-secondary" style="padding:15px; font-weight:700">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:15px; font-weight:700; background:#10B981; border:none">
                <i class="ph ph-floppy-disk"></i> Simpan Status Baru
            </button>
        </div>
    </form>
</div>

{{-- Hidden Data --}}
<div id="mat-info" data-qty-hanger="{{ optional($production->material)->qty_per_hanger }}" style="display:none"></div>

@push('styles')
<style>
.status-option { cursor: pointer; }
.status-option input { display: none; }
.status-box {
    border: 2px solid var(--border);
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: all 0.3s ease;
    background: #fff;
}
.status-box i { font-size: 24px; color: var(--text-muted); }
.status-box span { font-size: 13px; font-weight: 600; color: var(--text-dark); }

.status-option input:checked + .status-box {
    border-color: var(--primary);
    background: #EFF6FF;
}
.status-option input:checked + .status-box i { color: var(--primary); }

.status-option input:checked + .status-box.box-selesai {
    border-color: #10B981;
    background: #F0FDF4;
}
.status-option input:checked + .status-box.box-selesai i { color: #10B981; }
</style>
@endpush

@push('scripts')
<script>
function calculateTotal() {
    const qtyPerHanger = parseInt(document.getElementById('mat-info').dataset.qtyHanger) || 0;
    const hanger = parseInt(document.getElementById('input-hanger').value) || 0;
    document.getElementById('display-total').textContent = (hanger * qtyPerHanger).toLocaleString('id');
}
document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endpush
@endsection
