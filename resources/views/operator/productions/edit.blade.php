@extends('layouts.app')
@section('title', 'Edit Data Produksi')
@section('page-title', 'Edit Produksi')
@section('page-sub', 'Koreksi rencana atau input hasil aktual produksi')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Perubahan Data Produksi</h3>
        <a href="{{ route('productions.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('productions.update', $production->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="background:#F8FAFC; border-radius:12px; padding:20px; margin-bottom:24px; border:1px solid var(--border)">
            <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px">
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Kode Produksi</small>
                    <strong style="color:var(--primary); font-size:16px">{{ $production->kode_produksi }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Material</small>
                    <strong style="color:var(--text-dark)">{{ optional($production->material)->nama_material }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Customer</small>
                    <strong style="color:var(--text-dark)">{{ optional($production->material)->nama_customer }}</strong>
                </div>
                <div>
                    <small style="color:var(--text-muted); display:block; font-size:10px; text-transform:uppercase">Status Saat Ini</small>
                    @if($production->status === 'rencana')
                        <span class="badge" style="background:#E0F2FE; color:#0369A1">Rencana</span>
                    @elseif($production->status === 'proses')
                        <span class="badge badge-proses">Dalam Proses</span>
                    @else
                        <span class="badge badge-selesai">Selesai</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px">
            {{-- Left Side: Perencanaan --}}
            <div style="border-right: 1px solid var(--border); padding-right:24px">
                <h4 style="font-size:14px; color:var(--primary); margin-bottom:15px; display:flex; align-items:center; gap:8px">
                    <i class="ph ph-calendar-blank"></i> 1. Perencanaan (Blueprint)
                </h4>
                
                <div class="form-group">
                    <label class="form-label">Tanggal Produksi</label>
                    <input type="date" name="tanggal_produksi" class="form-control" value="{{ old('tanggal_produksi', $production->tanggal_produksi) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Target Hanger (Plan)</label>
                    <div style="position:relative">
                        <input type="number" name="target_hanger" id="input-target-hanger" class="form-control" value="{{ old('target_hanger', $production->target_hanger) }}" min="1" required oninput="calculatePreview()" style="padding-right:60px">
                        <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Hanger</span>
                    </div>
                </div>

                @if(auth()->user()->role !== 'operator')
                <div class="form-group">
                    <label class="form-label">Ditugaskan Kepada (Operator)</label>
                    <select name="operator" class="form-select-full" required>
                        @foreach($operatorsList as $op)
                            <option value="{{ $op->name }}" {{ old('operator', $production->operator) == $op->name ? 'selected' : '' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            {{-- Right Side: Hasil Aktual --}}
            <div>
                <h4 style="font-size:14px; color:var(--secondary); margin-bottom:15px; display:flex; align-items:center; gap:8px">
                    <i class="ph ph-factory"></i> 2. Hasil Aktual (Lapangan)
                </h4>

                <div class="form-group">
                    <label class="form-label">Status Produksi</label>
                    <select name="status" class="form-select-full" required>
                        <option value="rencana" {{ old('status', $production->status) == 'rencana' ? 'selected' : '' }}>Masih Rencana (Belum Mulai)</option>
                        <option value="proses" {{ old('status', $production->status) == 'proses' ? 'selected' : '' }}>Sedang Diproses (Mulai Kerja)</option>
                        <option value="selesai" {{ old('status', $production->status) == 'selesai' ? 'selected' : '' }}>Selesai Produksi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Actual Hanger (Input Lapangan)</label>
                    <div style="position:relative">
                        <input type="number" name="jumlah_hanger" id="input-actual-hanger" class="form-control" value="{{ old('jumlah_hanger', $production->jumlah_hanger) }}" min="0" oninput="calculatePreview()" style="padding-right:60px; {{ $production->status === 'rencana' ? 'background:#f8fafc' : '' }}">
                        <span style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--text-muted)">Hanger</span>
                    </div>
                    <small style="color:var(--text-muted); font-size:11px; margin-top:5px; display:block">Abaikan jika status masih 'Rencana'</small>
                </div>

                <div style="margin-top:20px; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:12px; padding:15px">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px">
                        <span style="font-size:12px; color:var(--text-mid)">Total Qty (Pcs):</span>
                        <strong id="display-total-pcs" style="font-size:16px; color:var(--secondary)">{{ number_format($production->jumlah_produksi) }} Pcs</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between">
                        <span style="font-size:12px; color:var(--text-mid)">Total Kemasan:</span>
                        <strong id="display-total-box" style="font-size:16px; color:var(--text-dark)">0 Box</strong>
                    </div>
                </div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px">
            <a href="{{ route('productions.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:12px 30px"><i class="ph ph-check-square"></i> Simpan Perubahan Data</button>
        </div>
    </form>
</div>

{{-- Hidden Material Data for JS --}}
<div id="material-data" 
     data-qty-hanger="{{ optional($production->material)->qty_per_hanger ?? 0 }}"
     data-qty-box="{{ optional($production->material)->qty_per_box ?? 1 }}"
     style="display:none"></div>

@push('scripts')
<script>
function calculatePreview() {
    const data = document.getElementById('material-data').dataset;
    const qtyPerHanger = parseInt(data.qtyHanger) || 0;
    const qtyPerBox = parseInt(data.qtyBox) || 1;
    
    // Kita utamakan hitung dari Actual Hanger kalau statusnya bukan rencana
    const actualHanger = parseInt(document.getElementById('input-actual-hanger').value) || 0;
    const targetHanger = parseInt(document.getElementById('input-target-hanger').value) || 0;
    
    // Jika actual ada isinya, pake actual. Kalo 0 (rencana), nampilin estimasi dari target buat preview
    const usedHanger = actualHanger > 0 ? actualHanger : 0;
    
    const totalPcs = usedHanger * qtyPerHanger;
    const totalBox = Math.ceil(totalPcs / qtyPerBox);
    
    document.getElementById('display-total-pcs').textContent = totalPcs.toLocaleString('id') + ' Pcs';
    document.getElementById('display-total-box').textContent = totalBox.toLocaleString('id') + ' Box';
}

document.addEventListener('DOMContentLoaded', calculatePreview);
</script>
@endpush
@endsection
