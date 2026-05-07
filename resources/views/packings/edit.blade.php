@extends('layouts.app')
@section('title', 'Edit Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Edit data packing')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-title">Form Edit Packing</div>

    <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div><span style="color:var(--text-muted)">Kode Packing</span><br><strong>{{ $packing->kode_packing ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Material</span><br><strong>{{ optional(optional(optional($packing->qc)->production)->material)->nama_material ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Customer</span><br><strong>{{ optional(optional(optional($packing->qc)->production)->material)->nama_customer ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ optional(optional($packing->qc)->production)->kode_produksi ?? '-' }}</strong></div>
        </div>
    </div>

    <form action="{{ route('packings.update', $packing->id) }}" method="POST" id="form-edit-packing">
        @csrf @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Total Finish Good (FG) <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah_fg" class="form-control" id="edit-jumlah-fg"
                       value="{{ old('jumlah_fg', $packing->jumlah_fg) }}" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Total Not Good (NG) <span style="color:var(--ng)">*</span></label>
                <input type="number" name="jumlah_ng" class="form-control" id="edit-jumlah-ng"
                       value="{{ old('jumlah_ng', $packing->jumlah_ng) }}" min="0" required>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Operator <span style="color:var(--ng)">*</span></label>
                <input type="text" name="operator" class="form-control" id="edit-operator-packing"
                       value="{{ old('operator', $packing->operator) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
                <select name="status" class="form-select-full" id="edit-status-packing" required>
                    <option value="proses"  {{ old('status', $packing->status) === 'proses'  ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status', $packing->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" id="edit-keterangan-packing" rows="3">{{ old('keterangan', $packing->keterangan) }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-warning" id="btn-update-packing">
                <i class="ph ph-floppy-disk"></i> Update Packing
            </button>
            <a href="{{ route('packings.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
