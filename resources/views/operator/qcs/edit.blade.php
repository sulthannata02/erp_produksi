@extends('layouts.app')
@section('title', 'Edit QC')
@section('page-title', 'QC (Quality Control)')
@section('page-sub', 'Edit data QC')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-title">Form Edit QC</div>

    {{-- Info read-only --}}
    <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ optional($qc->production)->kode_produksi ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Material</span><br><strong>{{ optional(optional($qc->production)->material)->nama_material ?? '-' }}</strong></div>
        </div>
    </div>

    <form action="{{ route('qcs.update', $qc->id) }}" method="POST" id="form-edit-qc">
        @csrf @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Hasil <span style="color:var(--ng)">*</span></label>
                <select name="hasil" class="form-select-full" id="edit-hasil-qc" required>
                    <option value="good"     {{ old('hasil', $qc->hasil) === 'good'     ? 'selected' : '' }}>FG (OK)</option>
                    <option value="not_good" {{ old('hasil', $qc->hasil) === 'not_good' ? 'selected' : '' }}>NG</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
                <select name="status" class="form-select-full" id="edit-status-qc" required>
                    <option value="proses"  {{ old('status', $qc->status) === 'proses'  ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status', $qc->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" id="edit-keterangan-qc" rows="3">{{ old('keterangan', $qc->keterangan) }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-purple" id="btn-update-qc">
                <i class="ph ph-floppy-disk"></i> Update QC
            </button>
            <a href="{{ route('qcs.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
