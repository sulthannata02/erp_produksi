@extends('layouts.app')
@section('title', 'Edit Material')
@section('page-title', 'Material')
@section('page-sub', 'Edit data material')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-title">Form Edit Material</div>

    <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" id="form-edit-material">
        @csrf @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_customer" class="form-control" id="edit-nama-customer"
                       value="{{ old('nama_customer', $material->nama_customer) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_material" class="form-control" id="edit-nama-material"
                       value="{{ old('nama_material', $material->nama_material) }}" required>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Kode Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="kode_part" class="form-control" id="edit-kode-material"
                       value="{{ old('kode_part', $material->kode_part) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                <select name="satuan" class="form-select-full" id="edit-satuan" required>
                    @foreach(['Pcs','Roll','Meter','Kg','Liter','Set','Box'] as $s)
                        <option value="{{ $s }}" {{ old('satuan', $material->satuan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Jumlah / Stok <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah" class="form-control" id="edit-jumlah"
                       value="{{ old('jumlah', $material->jumlah) }}" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Masuk <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_masuk" class="form-control" id="edit-tanggal-masuk"
                       value="{{ old('tanggal_masuk', $material->tanggal_masuk) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Material <span style="color:var(--text-muted);font-weight:400">(kosongkan jika tidak diganti)</span></label>
            @if($material->gambar)
                <div style="margin-bottom:8px">
                    <img src="{{ asset('storage/'.$material->gambar) }}" style="height:60px;border-radius:6px;border:1px solid var(--border)">
                </div>
            @endif
            <input type="file" name="gambar" class="form-control" id="edit-gambar" accept="image/*">
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-primary" id="btn-update-material">
                <i class="ph ph-floppy-disk"></i> Update Material
            </button>
            <a href="{{ route('materials.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection