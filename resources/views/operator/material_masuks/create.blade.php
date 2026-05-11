@extends('layouts.app')
@section('title', 'Input Barang Datang')
@section('page-title', 'Barang Datang')
@section('page-sub', 'Catat material baru yang masuk ke gudang')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Input Barang Datang</h3>
        <a href="{{ route('material-masuks.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('material-masuks.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Pilih Material <span style="color:var(--ng)">*</span></label>
            <select name="material_id" class="form-select-full @error('material_id') is-invalid @enderror" required>
                <option value="">-- Pilih Material --</option>
                @foreach($materialsList as $mat)
                    <option value="{{ $mat->id }}" {{ old('material_id') == $mat->id ? 'selected' : '' }}>
                        {{ $mat->nama_customer }} — {{ $mat->nama_material }} ({{ $mat->kode_part }})
                    </option>
                @endforeach
            </select>
            @error('material_id') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Nomor DN / Surat Jalan <span style="color:var(--ng)">*</span></label>
                <input type="text" name="no_dn" class="form-control @error('no_dn') is-invalid @enderror" value="{{ old('no_dn') }}" required placeholder="Contoh: DN/2026/001">
                @error('no_dn') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Datang <span style="color:var(--ng)">*</span></label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', now()->toDateString()) }}" required>
                @error('tanggal') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Quantity Masuk (Pcs) <span style="color:var(--ng)">*</span></label>
            <div style="position:relative">
                <input type="number" name="qty_masuk" class="form-control @error('qty_masuk') is-invalid @enderror" value="{{ old('qty_masuk') }}" required min="1" placeholder="0" style="font-size:18px; font-weight:700">
                <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Pcs</span>
            </div>
            @error('qty_masuk') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            <small style="color:var(--text-muted)">Stok aktual material akan bertambah otomatis setelah disimpan.</small>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px; margin-top:20px">
            <a href="{{ route('material-masuks.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 30px"><i class="ph ph-floppy-disk"></i> Simpan Data Datang</button>
        </div>
    </form>
</div>
@endsection
