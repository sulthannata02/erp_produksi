@extends('layouts.app')
@section('title', 'Selesaikan Packing')
@section('page-title', 'Selesaikan Packing')
@section('page-sub', 'Perbarui status dan finalisasi data pengemasan')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Finalisasi Packing: {{ $packing->kode_packing }}</h3>
        <a href="{{ route('packings.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('packings.update', $packing->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="background:#F0F9FF; border-radius:12px; padding:16px; margin-bottom:24px; border:1px solid #BAE6FD">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong>{{ optional($packing->qc->production->material)->nama_material }}</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Qty dari QC</small>
                    <strong id="info-qty-qc" data-qty-box="{{ optional($packing->qc->production->material)->qty_per_box }}" data-qty-qc="{{ $packing->qc->qty_qc }}">{{ number_format($packing->qc->qty_qc) }} Pcs</strong>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:24px">
            <label class="form-label">Status Packing</label>
            <select name="status" class="form-select-full" required>
                <option value="proses" {{ old('status', $packing->status) == 'proses' ? 'selected' : '' }}>Masih Proses</option>
                <option value="selesai" {{ old('status', $packing->status) == 'selesai' || $packing->status == 'proses' ? 'selected' : '' }}>Selesai (Siap Kirim)</option>
            </select>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px">
            <div class="form-group">
                <label class="form-label">Jumlah FG (Good) <span style="color:var(--ng)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_fg" id="input-jumlah-fg" class="form-control" value="{{ old('jumlah_fg', $packing->jumlah_fg) }}" required min="0" oninput="syncPacking('fg')" style="font-size:24px; font-weight:800; color:var(--selesai); text-align:center; padding:15px">
                    <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah NG (Rusak) <span style="color:var(--ng)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_ng" id="input-jumlah-ng" class="form-control" value="{{ old('jumlah_ng', $packing->jumlah_ng) }}" required min="0" oninput="syncPacking('ng')" style="font-size:24px; font-weight:800; color:var(--ng); text-align:center; padding:15px">
                    <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
                </div>
            </div>
        </div>

        {{-- Auto Calculation Display --}}
        <div style="border:2px solid var(--secondary); border-radius:16px; padding:20px; background:rgba(245,158,11,0.05); text-align:center; margin-bottom:24px">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px">Jumlah Kemasan Terhitung</div>
            <div style="font-size:32px; font-weight:800; color:var(--secondary)">
                <span id="display-total-box">0</span>
                <span style="font-size:14px; font-weight:600">Box</span>
            </div>
            <small style="color:var(--text-muted)">Dihitung otomatis: <b>{{ optional($packing->qc->production->material)->qty_per_box }} Pcs / Box</b></small>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $packing->keterangan) }}</textarea>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('packings.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-secondary" style="background:var(--secondary); color:#fff; border:none; padding:12px 30px"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function calculateBox() {
    const qtyFg = parseInt(document.getElementById('input-jumlah-fg').value) || 0;
    const infoQc = document.getElementById('info-qty-qc');
    const qtyPerBox = parseInt(infoQc.getAttribute('data-qty-box')) || 1;
    const totalBox = Math.ceil(qtyFg / qtyPerBox);
    document.getElementById('display-total-box').textContent = totalBox.toLocaleString('id');
}

function syncPacking(source) {
    const infoQc = document.getElementById('info-qty-qc');
    const total = parseInt(infoQc.getAttribute('data-qty-qc')) || 0;
    const fg = document.getElementById('input-jumlah-fg');
    const ng = document.getElementById('input-jumlah-ng');
    
    if(source === 'fg') {
        const val = parseInt(fg.value) || 0;
        ng.value = Math.max(0, total - val);
    } else {
        const val = parseInt(ng.value) || 0;
        fg.value = Math.max(0, total - val);
    }
    calculateBox();
}
document.addEventListener('DOMContentLoaded', calculateBox);
</script>
@endpush
@endsection
