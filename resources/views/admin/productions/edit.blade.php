@extends('layouts.app')
@section('title', 'Edit Produksi')
@section('page-title', 'Produksi')
@section('page-sub', 'Edit data produksi')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-title">Form Edit Produksi</div>

    {{-- Info read-only --}}
    <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ $production->kode_produksi ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Material</span><br><strong>{{ optional($production->material)->nama_material ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Customer</span><br><strong>{{ optional($production->material)->nama_customer ?? '-' }}</strong></div>
            <div><span style="color:var(--text-muted)">Qty Produksi</span><br><strong>{{ number_format($production->jumlah_produksi) }} {{ optional($production->material)->satuan }}</strong></div>
        </div>
    </div>

    <form action="{{ route('productions.update', $production->id) }}" method="POST" id="form-edit-produksi">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">Operator <span style="color:var(--ng)">*</span></label>
            <select name="operator" class="form-select-full" id="edit-operator" required>
                <option value="">-- Pilih Operator --</option>
                @foreach($operators as $op)
                    <option value="{{ $op->name }}" {{ old('operator', $production->operator) == $op->name ? 'selected' : '' }}>
                        {{ $op->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
            <select name="status" class="form-select-full" id="edit-status-produksi" required>
                <option value="proses"  {{ old('status', $production->status) === 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ old('status', $production->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn btn-primary" id="btn-update-produksi">
                <i class="ph ph-floppy-disk"></i> Update
            </button>
            <a href="{{ route('productions.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
