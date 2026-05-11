@extends('layouts.app')
@section('title', 'Input Hasil Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Input data pengemasan barang jadi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Laporan Packing</h3>
        <a href="{{ route('packings.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('packings.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Pilih Data QC <span style="color:var(--ng)">*</span></label>
            <select name="qc_id" class="form-select-full @error('qc_id') is-invalid @enderror" id="select-qc-packing" required onchange="loadQcInfo(this)">
                <option value="">-- Pilih Data QC --</option>
                @foreach($qcs as $qc)
                    <option value="{{ $qc->id }}" 
                            data-qty="{{ $qc->qty_qc }}"
                            data-material="{{ optional($qc->production->material)->nama_material }}"
                            data-qty-box="{{ optional($qc->production->material)->qty_per_box }}"
                            {{ (request('qc_id') == $qc->id) ? 'selected' : '' }}>
                        {{ optional($qc->production)->kode_produksi }} — {{ optional($qc->production->material)->nama_material }} (QC: {{ number_format($qc->qty_qc) }} Pcs)
                    </option>
                @endforeach
            </select>
            @error('qc_id') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>

        <div id="info-qc" style="display:none; background:#F0F9FF; border:1px solid #BAE6FD; border-radius:12px; padding:16px; margin-bottom:24px">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong id="info-material-name">-</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:#0369A1; display:block; font-size:10px; text-transform:uppercase">Qty Dari QC</small>
                    <strong id="info-qty-qc" style="font-size:18px">0 Pcs</strong>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px">
            <div class="form-group">
                <label class="form-label">Jumlah FG (Good) <span style="color:var(--ng)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_fg" id="input-jumlah-fg" class="form-control @error('jumlah_fg') is-invalid @enderror" value="{{ old('jumlah_fg') }}" required min="0" oninput="syncPacking('fg')" style="font-size:24px; font-weight:800; color:var(--selesai); text-align:center; padding:15px">
                    <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
                </div>
                @error('jumlah_fg') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah NG (Rusak) <span style="color:var(--ng)">*</span></label>
                <div style="position:relative">
                    <input type="number" name="jumlah_ng" id="input-jumlah-ng" class="form-control @error('jumlah_ng') is-invalid @enderror" value="{{ old('jumlah_ng', 0) }}" required min="0" oninput="syncPacking('ng')" style="font-size:24px; font-weight:800; color:var(--ng); text-align:center; padding:15px">
                    <span style="position:absolute; right:20px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
                </div>
                @error('jumlah_ng') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>
        <small style="color:var(--text-muted); text-align:center; display:block; margin-top:-15px; margin-bottom:24px">Pastikan jumlah barang sesuai dengan fisik yang akan masuk Box.</small>

        <div style="background:var(--body-bg); border-radius:12px; padding:15px; margin-bottom:24px; border:1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
            <div>
                <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Estimasi Jumlah Box</small>
                <strong id="display-box" style="font-size:24px; color:var(--primary)">0 Box</strong>
            </div>
            <div style="text-align:right">
                <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Status Awal</small>
                <span class="badge badge-proses">Sedang Proses</span>
            </div>
        </div>

        <input type="hidden" name="status" value="proses">

        <div class="form-group">
            <label class="form-label">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Contoh: Mulai packing batch A...">{{ old('keterangan') }}</textarea>
            @error('keterangan') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('packings.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-secondary" style="background:var(--secondary); color:#fff; border:none; padding:12px 30px"><i class="ph ph-archive-box"></i> Simpan & Mulai Packing</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function loadQcInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    const infoBox = document.getElementById('info-qc');
    const inputFg = document.getElementById('input-jumlah-fg');
    const inputNg = document.getElementById('input-jumlah-ng');

    if(opt.value) {
        infoBox.style.display = 'block';
        document.getElementById('info-material-name').textContent = opt.dataset.material;
        document.getElementById('info-qty-qc').textContent = parseInt(opt.dataset.qty).toLocaleString('id') + ' Pcs';
        
        // Otomatis isi FG full dan NG 0
        inputFg.value = opt.dataset.qty;
        inputNg.value = 0;
        
        calculateBox();
    } else {
        infoBox.style.display = 'none';
    }
}

function syncPacking(source) {
    const sel = document.getElementById('select-qc-packing');
    const opt = sel.options[sel.selectedIndex];
    const total = parseInt(opt.dataset.qty) || 0;
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

function calculateBox() {
    const sel = document.getElementById('select-qc-packing');
    const opt = sel.options[sel.selectedIndex];
    const qtyFg = parseInt(document.getElementById('input-jumlah-fg').value) || 0;
    
    if (opt && opt.value) {
        // Ambil dari attribute langsung biar aman
        const qtyPerBox = parseInt(opt.getAttribute('data-qty-box')) || 1;
        const totalBox = Math.ceil(qtyFg / qtyPerBox);
        document.getElementById('display-box').textContent = totalBox.toLocaleString('id') + ' Box';
    } else {
        document.getElementById('display-box').textContent = '0 Box';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('select-qc-packing');
    if(sel && sel.value) loadQcInfo(sel);
});
</script>
@endpush
@endsection
