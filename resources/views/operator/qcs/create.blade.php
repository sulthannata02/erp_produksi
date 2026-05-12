@extends('layouts.app')
@section('title', 'Input Hasil QC')
@section('page-title', 'Quality Control')
@section('page-sub', 'Input hasil pengecekan kualitas barang produksi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Laporan QC</h3>
        <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('qcs.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Pilih Produksi (Batch) <span style="color:var(--ng)">*</span></label>
            <select name="production_id" class="form-select-full @error('production_id') is-invalid @enderror" id="select-production-qc" required onchange="loadProdInfo(this)">
                <option value="">-- Pilih Batch Produksi --</option>
                @foreach($productions as $prod)
                    <option value="{{ $prod->id }}" 
                            data-kode="{{ $prod->kode_produksi }}"
                            data-material="{{ optional($prod->material)->nama_material }}"
                            data-qty="{{ $prod->jumlah_produksi }}"
                            data-satuan="{{ optional($prod->material)->satuan }}"
                            {{ (request('production_id') == $prod->id) ? 'selected' : '' }}>
                        {{ $prod->kode_produksi }} — {{ optional($prod->material)->nama_material }} ({{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }})
                    </option>
                @endforeach
            </select>
            @error('production_id') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>

        <div id="info-batch" style="display:none; background:#F0F9FF; border:1px solid #BAE6FD; border-radius:12px; padding:16px; margin-bottom:24px">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Total Hasil Produksi</small>
                    <strong id="info-qty-total" style="font-size:18px">0 Pcs</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong id="info-material-name">-</strong>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah Pcs yang Akan Dicek (QC) <span style="color:var(--ng)">*</span></label>
            <div style="position:relative">
                <input type="number" name="qty_qc" id="input-qty-qc" class="form-control @error('qty_qc') is-invalid @enderror" required min="1" style="font-size:18px; font-weight:700">
                <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
            </div>
            @error('qty_qc') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            <small style="color:var(--text-muted)">Input jumlah barang yang akan diperiksa. Data digunakan untuk pencatatan hasil pengecekan ketebalan coating produksi.</small>
        </div>

        <div class="form-group" style="display:flex; gap:15px; margin-bottom: 20px;">
            <div style="flex:1">
                <label class="form-label">Thickness Atas (μm)</label>
                <input type="text" name="thickness_atas" class="form-control @error('thickness_atas') is-invalid @enderror" value="{{ old('thickness_atas') }}" placeholder="Contoh: 18">
                @error('thickness_atas') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div style="flex:1">
                <label class="form-label">Thickness Bawah (μm)</label>
                <input type="text" name="thickness_bawah" class="form-control @error('thickness_bawah') is-invalid @enderror" value="{{ old('thickness_bawah') }}" placeholder="Contoh: 20">
                @error('thickness_bawah') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <input type="hidden" name="status" value="proses">

        <div class="form-group">
            <label class="form-label">Keterangan Awal (Opsional)</label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Contoh: Mulai pengecekan shift pagi...">{{ old('keterangan') }}</textarea>
            @error('keterangan') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('qcs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 30px"><i class="ph ph-shield-check"></i> Simpan Laporan QC</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function loadProdInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if(opt.value) {
        document.getElementById('info-batch').style.display = 'block';
        document.getElementById('info-qty-total').textContent = opt.dataset.qty + ' ' + opt.dataset.satuan;
        document.getElementById('info-material-name').textContent = opt.dataset.material;
        document.getElementById('input-qty-qc').value = opt.dataset.qty;
    } else {
        document.getElementById('info-batch').style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('select-production-qc');
    if(sel && sel.value) loadProdInfo(sel);
});
</script>
@endpush
@endsection
