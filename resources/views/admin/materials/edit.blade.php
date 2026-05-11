@extends('layouts.app')
@section('title', 'Edit Material')
@section('page-title', 'Edit Material')
@section('page-sub', 'Perbarui data master material')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Edit Material: {{ $material->nama_material }}</h3>
        <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_customer" class="form-control" value="{{ old('nama_customer', $material->nama_customer) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                <input type="text" name="nama_material" class="form-control" value="{{ old('nama_material', $material->nama_material) }}" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Kode Material / Part</label>
                <input type="text" name="kode_part" class="form-control @error('kode_part') is-invalid @enderror" value="{{ old('kode_part', $material->kode_part) }}">
                @error('kode_part') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                <select name="satuan" class="form-select-full @error('satuan') is-invalid @enderror" required>
                    <option value="Pcs" {{ old('satuan', $material->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="Box" {{ old('satuan', $material->satuan) == 'Box' ? 'selected' : '' }}>Box</option>
                    <option value="Pallet" {{ old('satuan', $material->satuan) == 'Pallet' ? 'selected' : '' }}>Pallet</option>
                </select>
                @error('satuan') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Target Stok (Blueprint)</label>
                <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $material->jumlah) }}" min="0">
                @error('jumlah') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Konversi: Qty / Hanger <span style="color:var(--ng)">*</span></label>
                <input type="number" name="qty_per_hanger" class="form-control @error('qty_per_hanger') is-invalid @enderror" value="{{ old('qty_per_hanger', $material->qty_per_hanger) }}" min="1" required>
                @error('qty_per_hanger') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Konversi: Qty / Box <span style="color:var(--ng)">*</span></label>
                <input type="number" name="qty_per_box" class="form-control @error('qty_per_box') is-invalid @enderror" value="{{ old('qty_per_box', $material->qty_per_box) }}" min="1" required>
                @error('qty_per_box') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Tanggal Masuk Master <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $material->tanggal_masuk) }}" required>
                @error('tanggal_masuk') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div style="display:grid; grid-template-columns: 60px 1fr; gap:15px; align-items:center">
                @if($material->gambar)
                    <img src="{{ asset('storage/'.$material->gambar) }}" style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                @else
                    <div style="width:60px; height:60px; background:var(--body-bg); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:10px">No Img</div>
                @endif
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Update Foto</label>
                    <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                    @error('gambar') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px; margin-top:20px">
            <a href="{{ route('materials.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 30px"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
