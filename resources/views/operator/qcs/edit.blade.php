@extends('layouts.app')
@section('title', 'Edit Laporan QC')
@section('page-title', 'Edit QC')
@section('page-sub', 'Perbarui data hasil pengecekan kualitas')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Edit Laporan QC: {{ optional($qc->production)->kode_produksi }}</h3>
        <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('qcs.update', $qc->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="background:#F8FAFC; border-radius:12px; padding:16px; margin-bottom:24px; border:1px solid var(--border)">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong>{{ optional($qc->production->material)->nama_material }}</strong>
                </div>
                <div style="text-align:right">
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Total Produksi</small>
                    <strong id="info-qty-total" data-qty="{{ $qc->qty_qc }}">{{ number_format($qc->qty_qc) }} Pcs</strong>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Thickness Atas (μm)</label>
                <input type="text" name="thickness_atas" class="form-control" value="{{ old('thickness_atas', $qc->thickness_atas) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Thickness Bawah (μm)</label>
                <input type="text" name="thickness_bawah" class="form-control" value="{{ old('thickness_bawah', $qc->thickness_bawah) }}">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px">
            <div class="form-group">
                <label class="form-label">Status QC</label>
                <select name="status" class="form-select-full" required>
                    <option value="proses" {{ old('status', $qc->status) == 'proses' ? 'selected' : '' }}>Masih Dicek (Proses)</option>
                    <option value="selesai" {{ old('status', $qc->status) == 'selesai' || $qc->status == 'proses' ? 'selected' : '' }}>Selesai Dicek</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $qc->keterangan) }}</textarea>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('qcs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 30px"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
@endpush
@endsection
