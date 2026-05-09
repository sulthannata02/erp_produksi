@extends('layouts.app')
@section('title', 'Tambah Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Tambah data packing baru')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Form Tambah Packing</div>

    <form action="{{ route('packings.store') }}" method="POST" id="form-create-packing">
        @csrf

        <div class="form-group">
            <label class="form-label">Data QC (hasil Good) <span style="color:var(--ng)">*</span></label>
            <select name="qc_id" class="form-select-full" id="input-qc-id" required onchange="loadQcInfo(this)">
                <option value="">-- Pilih Data QC --</option>
                @foreach($qcs as $qc)
                    <option value="{{ $qc->id }}"
                            data-customer="{{ optional(optional($qc->production)->material)->nama_customer }}"
                            data-material="{{ optional(optional($qc->production)->material)->nama_material }}"
                            data-kode="{{ optional($qc->production)->kode_produksi }}"
                            data-qty="{{ $qc->qty_qc }}"
                            data-satuan="{{ optional(optional($qc->production)->material)->satuan }}"
                            {{ old('qc_id') == $qc->id ? 'selected' : '' }}>
                        {{ optional($qc->production)->kode_produksi ?? 'ID-'.$qc->id }} — {{ optional(optional($qc->production)->material)->nama_material }} ({{ number_format($qc->qty_qc ?? 0) }} {{ optional(optional($qc->production)->material)->satuan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div id="info-qc" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
            Customer: <strong id="info-qc-customer">-</strong> &nbsp;|&nbsp;
            Material: <strong id="info-qc-material">-</strong> &nbsp;|&nbsp;
            Qty QC: <strong id="info-qc-qty" style="color:var(--primary)">-</strong>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Total Finish Good (FG) <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah_fg" class="form-control" id="input-jumlah-fg"
                       value="{{ old('jumlah_fg') }}" min="0" placeholder="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Total Not Good (NG) <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah_ng" class="form-control" id="input-jumlah-ng"
                       value="{{ old('jumlah_ng') }}" min="0" placeholder="0" required>
            </div>
        </div>



        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" id="input-keterangan-packing"
                      rows="3" placeholder="Catatan packing...">{{ old('keterangan') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-warning" id="btn-submit-packing">
                <i class="ph ph-floppy-disk"></i> Simpan Packing
            </button>
            <a href="{{ route('packings.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function loadQcInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-qc').style.display = 'block';
        document.getElementById('info-qc-customer').textContent = opt.dataset.customer || '-';
        document.getElementById('info-qc-material').textContent = opt.dataset.material || '-';
        document.getElementById('info-qc-qty').textContent      = parseInt(opt.dataset.qty || 0).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
    } else {
        document.getElementById('info-qc').style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-qc-id');
    if (sel.value) loadQcInfo(sel);
});
</script>
@endpush