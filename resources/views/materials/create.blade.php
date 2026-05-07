@extends('layouts.app')
@section('title', 'Tambah Material')
@section('page-title', 'Material')
@section('page-sub', 'Tambah data material baru')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Form Tambah Material</div>

    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" id="form-create-material">
        @csrf

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_customer" class="form-control" id="input-nama-customer"
                       value="{{ old('nama_customer') }}" placeholder="Contoh: Fujiseat" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_material" class="form-control" id="input-nama-material"
                       value="{{ old('nama_material') }}" placeholder="Contoh: Kain MB Tech" required>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Kode Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="kode_part" class="form-control" id="input-kode-material"
                       value="{{ old('kode_part') }}" placeholder="Contoh: MAT-001" required>
            </div>
            <div class="form-group">
                <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                <select name="satuan" class="form-select-full" id="input-satuan" required>
                    <option value="">-- Pilih Satuan --</option>
                    @foreach(['Pcs','Roll','Meter','Kg','Liter','Set','Box'] as $s)
                        <option value="{{ $s }}" {{ old('satuan') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Jumlah / Stok <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah" class="form-control" id="input-jumlah"
                       value="{{ old('jumlah') }}" min="0" placeholder="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Masuk <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_masuk" class="form-control" id="input-tanggal-masuk"
                       value="{{ old('tanggal_masuk', now()->toDateString()) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Material <span style="color:var(--text-muted);font-weight:400">(opsional, max 2MB)</span></label>
            <input type="file" name="gambar" class="form-control" id="input-gambar" accept="image/*">
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-primary" id="btn-submit-material">
                <i class="ph ph-floppy-disk"></i> Simpan Material
            </button>
            <a href="{{ route('materials.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection