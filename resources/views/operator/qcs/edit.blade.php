@extends('layouts.app')
@section('title', 'Edit Laporan QC')
@section('page-title', 'Edit QC')
@section('page-sub', 'Perbarui data hasil pengecekan kualitas')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Edit Laporan QC: {{ optional($qc->production)->kode_produksi }}</h3>
        <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('qcs.update', $qc->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="background:#F8FAFC; border-radius:12px; padding:16px; margin-bottom:24px; border:1px solid var(--border)">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong>{{ optional($qc->production->material)->nama_material }}</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Total Produksi</small>
                    <strong id="info-qty-total" data-qty="{{ $qc->qty_qc }}">{{ number_format($qc->qty_qc) }} Pcs</strong>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:24px">
            <div class="form-group">
                <label class="form-label">Status QC</label>
                <select name="status" class="form-select-full" required>
                    <option value="proses" {{ old('status', $qc->status) == 'proses' ? 'selected' : '' }}>Masih Dicek (Proses)</option>
                    <option value="selesai" {{ old('status', $qc->status) == 'selesai' || $qc->status == 'proses' ? 'selected' : '' }}>Selesai Dicek</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah FG (Good/Bagus) <span style="color:var(--selesai)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_fg" id="input-jumlah-fg" class="form-control" value="{{ old('jumlah_fg', ($qc->jumlah_fg == 0 ? $qc->qty_qc : $qc->jumlah_fg)) }}" required min="0" oninput="syncQC('fg')" style="font-size:18px; font-weight:700; color:var(--selesai)">
                    <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--selesai)">FG</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah NG (Bad/Rusak) <span style="color:var(--ng)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_ng" id="input-jumlah-ng" class="form-control" value="{{ old('jumlah_ng', $qc->jumlah_ng) }}" required min="0" oninput="syncQC('ng')" style="font-size:18px; font-weight:700; color:var(--ng)">
                    <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--ng)">NG</span>
                </div>
            </div>
        </div>
        <small style="color:var(--text-muted); display:block; margin-top:-15px; margin-bottom:20px">Total FG + NG harus sama dengan Total QC ({{ number_format($qc->qty_qc) }} Pcs).</small>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $qc->keterangan) }}</textarea>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('qcs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 30px"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function syncQC(source) {
    const total = parseInt(document.getElementById('info-qty-total').dataset.qty) || 0;
    const fg = document.getElementById('input-jumlah-fg');
    const ng = document.getElementById('input-jumlah-ng');
    
    if(source === 'fg') {
        const val = parseInt(fg.value) || 0;
        ng.value = Math.max(0, total - val);
    } else {
        const val = parseInt(ng.value) || 0;
        fg.value = Math.max(0, total - val);
    }
}
</script>
@endpush
@endsection
