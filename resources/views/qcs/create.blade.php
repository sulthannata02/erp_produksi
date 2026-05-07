@extends('layouts.app')
@section('title', 'Tambah QC')
@section('page-title', 'QC (Quality Control)')
@section('page-sub', 'Tambah data QC baru')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Form Tambah QC</div>

    <form action="{{ route('qcs.store') }}" method="POST" id="form-create-qc">
        @csrf

        <div class="form-group">
            <label class="form-label">Data Produksi <span style="color:var(--ng)">*</span></label>
            <select name="production_id" class="form-select-full" id="input-production-id" required onchange="loadProdInfo(this)">
                <option value="">-- Pilih Data Produksi --</option>
                @foreach($productions as $prod)
                    <option value="{{ $prod->id }}"
                            data-customer="{{ optional($prod->material)->nama_customer }}"
                            data-material="{{ optional($prod->material)->nama_material }}"
                            data-qty="{{ $prod->jumlah_produksi }}"
                            data-satuan="{{ optional($prod->material)->satuan }}"
                            {{ old('production_id') == $prod->id ? 'selected' : '' }}>
                        {{ $prod->kode_produksi ?? 'ID-'.$prod->id }} — {{ optional($prod->material)->nama_material }} ({{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div id="info-produksi" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
            Customer: <strong id="info-prod-customer">-</strong> &nbsp;|&nbsp;
            Material: <strong id="info-prod-material">-</strong> &nbsp;|&nbsp;
            Qty: <strong id="info-prod-qty" style="color:var(--primary)">-</strong>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Qty QC <span style="color:var(--ng)">*</span></label>
                <input type="number" name="qty_qc" class="form-control" id="input-qty-qc"
                       value="{{ old('qty_qc') }}" min="1" placeholder="Jumlah yang di-QC" required>
            </div>
            <div class="form-group">
                <label class="form-label">Hasil <span style="color:var(--ng)">*</span></label>
                <select name="hasil" class="form-select-full" id="input-hasil-qc" required>
                    <option value="">-- Pilih Hasil --</option>
                    <option value="good"     {{ old('hasil') === 'good'     ? 'selected' : '' }}>FG (OK) — Sesuai Standar</option>
                    <option value="not_good" {{ old('hasil') === 'not_good' ? 'selected' : '' }}>NG — Tidak Sesuai</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" id="input-keterangan-qc"
                      rows="3" placeholder="Catatan hasil QC...">{{ old('keterangan') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-purple" id="btn-submit-qc">
                <i class="ph ph-floppy-disk"></i> Simpan QC
            </button>
            <a href="{{ route('qcs.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function loadProdInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-produksi').style.display = 'block';
        document.getElementById('info-prod-customer').textContent = opt.dataset.customer || '-';
        document.getElementById('info-prod-material').textContent = opt.dataset.material || '-';
        document.getElementById('info-prod-qty').textContent      = parseInt(opt.dataset.qty).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
        document.getElementById('input-qty-qc').max = opt.dataset.qty;
    } else {
        document.getElementById('info-produksi').style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-production-id');
    if (sel.value) loadProdInfo(sel);
});
</script>
@endpush