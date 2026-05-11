@extends('layouts.app')
@section('title', 'Tambah Material Baru')
@section('page-title', 'Tambah Material')
@section('page-sub', 'Daftarkan material master baru ke sistem')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Material Master</h3>
        <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_customer" class="form-control" value="{{ old('nama_customer') }}" required placeholder="Contoh: PT. ABC">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_material" class="form-control" value="{{ old('nama_material') }}" required placeholder="Contoh: Plate A1">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Kode Material / Part</label>
                <input type="text" name="kode_part" class="form-control @error('kode_part') is-invalid @enderror" value="{{ old('kode_part') }}" placeholder="Contoh: MAT-001">
                @error('kode_part') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                <select name="satuan" class="form-select-full @error('satuan') is-invalid @enderror" required>
                    <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                    <option value="Pallet" {{ old('satuan') == 'Pallet' ? 'selected' : '' }}>Pallet</option>
                </select>
                @error('satuan') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Target Stok (Blueprint)</label>
                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 0) }}" min="0">
                @error('jumlah') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Konversi: Qty / Hanger <span style="color:var(--ng)">*</span></label>
                <input type="number" name="qty_per_hanger" class="form-control @error('qty_per_hanger') is-invalid @enderror" value="{{ old('qty_per_hanger', 1) }}" min="1" required>
                @error('qty_per_hanger') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Konversi: Qty / Box <span style="color:var(--ng)">*</span></label>
                <input type="number" name="qty_per_box" class="form-control @error('qty_per_box') is-invalid @enderror" value="{{ old('qty_per_box', 1) }}" min="1" required>
                @error('qty_per_box') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Tanggal Masuk Master <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                @error('tanggal_masuk') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Foto Material</label>
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                <small style="color:var(--text-muted)">Format: JPG, PNG. Maksimal 2MB.</small>
                @error('gambar') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px; margin-top:20px">
            <a href="{{ route('materials.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 30px"><i class="ph ph-floppy-disk"></i> Simpan Material</button>
        </div>
    </form>
</div>
@endsection
