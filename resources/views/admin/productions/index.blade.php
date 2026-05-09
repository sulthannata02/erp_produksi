@extends('layouts.app')
@section('title', 'Produksi')
@section('page-title', 'Produksi')
@section('page-sub', 'Kelola data produksi')

@section('content')

<div class="page-header">
    <div></div>
    <button type="button" class="btn btn-success" onclick="openModal('modal-create')">
        <i class="ph ph-plus"></i> Tambah Produksi
    </button>
</div>

<div class="card">
    <form method="GET" action="{{ route('productions.index') }}" id="form-filter-produksi">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-produksi">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-produksi"
                   value="{{ request('tanggal') }}">

            <div class="search-box">
                <input type="text" name="search" id="search-produksi"
                       placeholder="Cari produksi..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-produksi">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request()->hasAny(['customer','tanggal','search']))
                <a href="{{ route('productions.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-produksi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Produksi</th>
                    <th>Material</th>
                    <th>Qty Produksi</th>
                    <th>Satuan</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $i => $prod)
                <tr>
                    <td>{{ $productions->firstItem() + $i }}</td>
                    <td>{{ $prod->tanggal_produksi ? \Carbon\Carbon::parse($prod->tanggal_produksi)->format('d/m/Y') : '-' }}</td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $prod->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional($prod->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }}</td>
                    <td>{{ optional($prod->material)->satuan ?? '-' }}</td>
                    <td>{{ $prod->operator ?? '-' }}</td>
                    <td>
                        @if($prod->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-edit" title="Edit" onclick="openModal('modal-edit-{{ $prod->id }}')">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                            <form action="{{ route('productions.destroy', $prod->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus data produksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" id="btn-del-prod-{{ $prod->id }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-factory" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data produksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($productions->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($productions->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $productions->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($productions->getUrlRange(1, $productions->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $productions->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($productions->hasMorePages())
            <a href="{{ $productions->nextPageUrl() }}" class="page-btn">»</a>
        @else
            <span class="page-btn" style="opacity:.4">»</span>
        @endif
    </div>
    @endif
</div>

{{-- MODAL CREATE --}}
<div id="modal-create" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Tambah Produksi</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('productions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Material <span style="color:var(--ng)">*</span></label>
                    <select name="material_id" class="form-select-full" id="input-material-id-create" required onchange="loadMaterialInfo(this, 'create')">
                        <option value="">-- Pilih Material --</option>
                        @foreach($materialsList as $mat)
                            <option value="{{ $mat->id }}"
                                    data-customer="{{ $mat->nama_customer }}"
                                    data-satuan="{{ $mat->satuan }}"
                                    data-stok="{{ $mat->jumlah }}"
                                    {{ old('material_id') == $mat->id ? 'selected' : '' }}>
                                {{ $mat->nama_material }} ({{ $mat->kode_part }}) — Stok: {{ number_format($mat->jumlah) }} {{ $mat->satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="info-material-create" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
                    Customer: <strong id="info-customer-create">-</strong> &nbsp;|&nbsp;
                    Satuan: <strong id="info-satuan-create">-</strong> &nbsp;|&nbsp;
                    Stok tersedia: <strong id="info-stok-create" style="color:var(--primary)">-</strong>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah Produksi <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah_produksi" class="form-control" value="{{ old('jumlah_produksi') }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Produksi <span style="color:var(--ng)">*</span></label>
                        <input type="date" name="tanggal_produksi" class="form-control" value="{{ old('tanggal_produksi', now()->toDateString()) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Operator <span style="color:var(--ng)">*</span></label>
                    <select name="operator" class="form-select-full" required>
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operatorsList as $op)
                            <option value="{{ $op->name }}" {{ old('operator') == $op->name ? 'selected' : '' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-success"><i class="ph ph-floppy-disk"></i> Simpan Produksi</button>
            </div>
        </form>
    </div>
</div>

{{-- MODALS EDIT --}}
@foreach($productions as $prod)
<div id="modal-edit-{{ $prod->id }}" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Edit Produksi</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-{{ $prod->id }}')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('productions.update', $prod->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="modal_id" value="{{ $prod->id }}">
            <div class="modal-body">
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ $prod->kode_produksi ?? '-' }}</strong></div>
                        <div><span style="color:var(--text-muted)">Material</span><br><strong>{{ optional($prod->material)->nama_material ?? '-' }}</strong></div>
                        <div><span style="color:var(--text-muted)">Customer</span><br><strong>{{ optional($prod->material)->nama_customer ?? '-' }}</strong></div>
                        <div><span style="color:var(--text-muted)">Qty Produksi</span><br><strong>{{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }}</strong></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Operator <span style="color:var(--ng)">*</span></label>
                    <select name="operator" class="form-select-full" required>
                        <option value="">-- Pilih Operator --</option>
                        @foreach($operatorsList as $op)
                            <option value="{{ $op->name }}" {{ old('operator', $prod->operator) == $op->name ? 'selected' : '' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
                    <select name="status" class="form-select-full" required>
                        <option value="proses" {{ old('status', $prod->status) === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status', $prod->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $prod->id }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Update Produksi</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
function loadMaterialInfo(sel, prefix) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-material-' + prefix).style.display = 'block';
        document.getElementById('info-customer-' + prefix).textContent = opt.dataset.customer || '-';
        document.getElementById('info-satuan-' + prefix).textContent   = opt.dataset.satuan   || '-';
        document.getElementById('info-stok-' + prefix).textContent     = parseInt(opt.dataset.stok).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
    } else {
        document.getElementById('info-material-' + prefix).style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-material-id-create');
    if (sel && sel.value) loadMaterialInfo(sel, 'create');
});
@if($errors->any())
    @if(old('modal_id'))
        openModal('modal-edit-{{ old('modal_id') }}');
    @else
        openModal('modal-create');
    @endif
@endif
</script>
@endpush