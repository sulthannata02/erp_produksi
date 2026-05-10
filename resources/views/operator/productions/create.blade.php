@extends('layouts.app')
@section('title', 'Tambah SPK Produksi')
@section('page-title', 'Tambah SPK Baru')
@section('page-sub', 'Buat blueprint rencana produksi (SPK)')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Perencanaan Produksi</h3>
        <a href="{{ route('productions.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('productions.store') }}" method="POST">
        @csrf
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:24px">
            <div class="form-group">
                <label class="form-label">Tanggal Rencana <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_produksi" class="form-control @error('tanggal_produksi') is-invalid @enderror" value="{{ old('tanggal_produksi', now()->toDateString()) }}" required>
                @error('tanggal_produksi') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Customer</label>
                <input type="text" id="display-customer" class="form-control" readonly placeholder="Otomatis..." style="background:var(--body-bg)">
            </div>
            <div class="form-group">
                <label class="form-label">Pilih Material <span style="color:var(--ng)">*</span></label>
                <select name="material_id" class="form-select-full @error('material_id') is-invalid @enderror" id="input-material-id" required onchange="loadMaterialInfo(this)">
                    <option value="">-- Pilih Material --</option>
                    @foreach($materialsList as $mat)
                        <option value="{{ $mat->id }}"
                                data-customer="{{ $mat->nama_customer }}"
                                data-kode="{{ $mat->kode_part }}"
                                data-nama="{{ $mat->nama_material }}"
                                data-qty-hanger="{{ $mat->qty_per_hanger }}"
                                data-qty-box="{{ $mat->qty_per_box }}"
                                {{ old('material_id') == $mat->id ? 'selected' : '' }}>
                            {{ $mat->kode_part }} — {{ $mat->nama_material }}
                        </option>
                    @endforeach
                </select>
                @error('material_id') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Material Info Display --}}
        <div id="info-material" style="display:none; background:var(--low-bg); border:1px solid #A7F3D0; border-radius:12px; padding:16px; margin-bottom:24px">
            <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:15px">
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Kode Part</small>
                    <strong id="info-kode" style="color:var(--text-dark)">-</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Nama Material</small>
                    <strong id="info-nama" style="color:var(--text-dark)">-</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Qty / Hanger</small>
                    <strong id="info-qty-hanger" style="color:var(--primary)">-</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Qty / Box</small>
                    <strong id="info-qty-box" style="color:var(--secondary)">-</strong>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; margin-bottom:24px">
            {{-- Left Side: Inputs --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Target Hanger (Blueprint) <span style="color:var(--ng)">*</span></label>
                    <div style="position:relative">
                        <input type="number" name="target_hanger" id="input-target-hanger" class="form-control @error('target_hanger') is-invalid @enderror" value="{{ old('target_hanger') }}" min="1" required oninput="calculateProduction()" style="padding-right:60px; font-size:18px; font-weight:700">
                        <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Hanger</span>
                    </div>
                    @error('target_hanger') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
                </div>

                @if(auth()->user()->role !== 'operator')
                <div class="form-group">
                    <label class="form-label">Ditugaskan Kepada (Operator)</label>
                    <select name="operator" class="form-select-full @error('operator') is-invalid @enderror" required>
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operatorsList as $op)
                            <option value="{{ $op->name }}" {{ old('operator') == $op->name ? 'selected' : '' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                    @error('operator') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Tambahkan catatan jika ada...">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Right Side: Result Preview --}}
            <div style="border:2px solid var(--primary); border-radius:16px; padding:24px; background:var(--primary-light); display:flex; flex-direction:column; justify-content:center; text-align:center">
                <div style="margin-bottom:20px">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--primary); margin-bottom:4px">Estimasi Total Produksi</div>
                    <div style="font-size:32px; font-weight:800; color:var(--primary)">
                        <span id="display-total-pcs">0</span>
                        <span style="font-size:14px; font-weight:600">Pcs</span>
                    </div>
                </div>
                
                <div style="padding-top:20px; border-top:1px dashed rgba(30,111,217,0.3)">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px">Kebutuhan Kemasan</div>
                    <div style="font-size:24px; font-weight:700; color:var(--text-dark)">
                        <span id="display-total-box">0</span>
                        <span style="font-size:12px; font-weight:600">Box</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('productions.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 30px"><i class="ph ph-floppy-disk"></i> Simpan SPK Produksi</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function loadMaterialInfo(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('display-customer').value = opt.dataset.customer || '';
        document.getElementById('info-material').style.display = 'block';
        document.getElementById('info-kode').textContent = opt.dataset.kode || '-';
        document.getElementById('info-nama').textContent = opt.dataset.nama || '-';
        document.getElementById('info-qty-hanger').textContent = opt.dataset.qtyHanger + ' Pcs / Hanger';
        document.getElementById('info-qty-box').textContent = opt.dataset.qtyBox + ' Pcs / Box';
        calculateProduction();
    } else {
        document.getElementById('display-customer').value = '';
        document.getElementById('info-material').style.display = 'none';
        document.getElementById('display-total-pcs').textContent = '0';
        document.getElementById('display-total-box').textContent = '0';
    }
}

function calculateProduction() {
    const sel = document.getElementById('input-material-id');
    const opt = sel.options[sel.selectedIndex];
    const targetHanger = parseInt(document.getElementById('input-target-hanger').value) || 0;
    
    if (opt.value && targetHanger > 0) {
        const qtyPerHanger = parseInt(opt.dataset.qtyHanger) || 0;
        const qtyPerBox = parseInt(opt.dataset.qtyBox) || 1;
        
        const totalPcs = targetHanger * qtyPerHanger;
        const totalBox = Math.ceil(totalPcs / qtyPerBox);
        
        document.getElementById('display-total-pcs').textContent = totalPcs.toLocaleString('id');
        document.getElementById('display-total-box').textContent = totalBox.toLocaleString('id');
    } else {
        document.getElementById('display-total-pcs').textContent = '0';
        document.getElementById('display-total-box').textContent = '0';
    }
}

// Inisialisasi jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-material-id');
    if (sel && sel.value) loadMaterialInfo(sel);
});
</script>
@endpush
@endsection
