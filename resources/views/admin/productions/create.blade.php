@extends('layouts.app')
@section('title', 'Tambah Produksi')
@section('page-title', 'Produksi')
@section('page-sub', 'Tambah data produksi baru')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Form Tambah Produksi</div>

    <form action="{{ route('productions.store') }}" method="POST" id="form-create-produksi">
        @csrf

        <div class="form-group">
            <label class="form-label">Material <span style="color:var(--ng)">*</span></label>
            <select name="material_id" class="form-select-full" id="input-material-id" required onchange="loadMaterialInfo(this)">
                <option value="">-- Pilih Material --</option>
                @foreach($materials as $mat)
                    <option value="{{ $mat->id }}"
                            data-customer="{{ $mat->nama_customer }}"
                            data-satuan="{{ $mat->satuan }}"
                            data-stok="{{ $mat->jumlah }}"
                            {{ old('material_id') == $mat->id ? 'selected' : '' }}>
                        {{ $mat->nama_material }} ({{ $mat->kode_part }}) — Stok: {{ number_format($mat->jumlah) }} {{ $mat->satuan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="info-material" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
            Customer: <strong id="info-customer">-</strong> &nbsp;|&nbsp;
            Satuan: <strong id="info-satuan">-</strong> &nbsp;|&nbsp;
            Stok tersedia: <strong id="info-stok" style="color:var(--primary)">-</strong>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Jumlah Produksi <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah_produksi" class="form-control" id="input-jumlah-produksi"
                       value="{{ old('jumlah_produksi') }}" min="1" placeholder="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Produksi <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_produksi" class="form-control" id="input-tanggal-produksi"
                       value="{{ old('tanggal_produksi', now()->toDateString()) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Operator <span style="color:var(--ng)">*</span></label>
            <select name="operator" class="form-select-full" id="input-operator" required>
                <option value="">-- Pilih Operator --</option>
                @foreach($operators as $op)
                    <option value="{{ $op->name }}" {{ old('operator') == $op->name ? 'selected' : '' }}>
                        {{ $op->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-success" id="btn-submit-produksi">
                <i class="ph ph-floppy-disk"></i> Simpan Produksi
            </button>
            <a href="{{ route('productions.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function loadMaterialInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-material').style.display = 'block';
        document.getElementById('info-customer').textContent = opt.dataset.customer || '-';
        document.getElementById('info-satuan').textContent   = opt.dataset.satuan   || '-';
        document.getElementById('info-stok').textContent     = parseInt(opt.dataset.stok).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
    } else {
        document.getElementById('info-material').style.display = 'none';
    }
}
// Trigger on page load if old value
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-material-id');
    if (sel.value) loadMaterialInfo(sel);
});
</script>
@endpush