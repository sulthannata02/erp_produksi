@extends('layouts.app')
@section('title', 'QC (Quality Control)')
@section('page-title', 'QC (Quality Control)')
@section('page-sub', 'Kelola data quality control')

@section('content')

<div class="page-header">
    <div></div>
    <button type="button" class="btn btn-purple" onclick="openModal('modal-create')">
        <i class="ph ph-plus"></i> Tambah QC
    </button>
</div>

<div class="card">
    <form method="GET" action="{{ route('qcs.index') }}" id="form-filter-qc">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-qc">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-qc" value="{{ request('tanggal') }}">

            <select name="status" class="form-select" id="filter-status-qc">
                <option value="">Semua Status</option>
                <option value="proses"  {{ request('status') == 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-qc">
                <i class="ph ph-funnel"></i> Filter
            </button>

            <div class="search-box">
                <input type="text" name="search" id="search-qc"
                       placeholder="Cari QC..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            @if(request()->hasAny(['customer','tanggal','status','search']))
                <a href="{{ route('qcs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-qc">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Produksi</th>
                    <th>Material</th>
                    <th>Qty QC</th>
                    <th>Hasil</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qcs as $i => $qc)
                <tr>
                    <td>{{ $qcs->firstItem() + $i }}</td>
                    <td>{{ $qc->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional(optional($qc->production)->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ optional($qc->production)->kode_produksi ?? '-' }}</code></td>
                    <td>{{ optional(optional($qc->production)->material)->nama_material ?? '-' }}</td>
                    <td>{{ number_format($qc->qty_qc ?? 0) }}</td>
                    <td>
                        @if($qc->hasil === 'good')
                            <span class="badge badge-fg">FG (OK)</span>
                        @else
                            <span class="badge badge-ng">NG</span>
                        @endif
                    </td>
                    <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $qc->keterangan }}">
                        {{ $qc->keterangan ?? '-' }}
                    </td>
                    <td>
                        @if($qc->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('qcs.show', $qc->id) }}" class="btn-view" title="Lihat Detail" id="btn-view-qc-{{ $qc->id }}">
                                <i class="ph ph-eye"></i>
                            </a>
                            <button type="button" class="btn-edit" title="Edit" onclick="openModal('modal-edit-{{ $qc->id }}')">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-shield-check" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data QC
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($qcs->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($qcs->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $qcs->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($qcs->getUrlRange(1, $qcs->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $qcs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($qcs->hasMorePages())
            <a href="{{ $qcs->nextPageUrl() }}" class="page-btn">»</a>
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
            <div class="modal-title">Form Tambah QC</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('qcs.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Data Produksi <span style="color:var(--ng)">*</span></label>
                    <select name="production_id" class="form-select-full" id="input-production-id-create" required onchange="loadProdInfo(this, 'create')">
                        <option value="">-- Pilih Data Produksi --</option>
                        @foreach($productionsList as $prod)
                            <option value="{{ $prod->id }}"
                                    data-customer="{{ optional($prod->material)->nama_customer }}"
                                    data-material="{{ optional($prod->material)->nama_material }}"
                                    data-qty="{{ $prod->jumlah_produksi }}"
                                    data-satuan="{{ optional($prod->material)->satuan }}"
                                    {{ (old('production_id') == $prod->id || request('production') == $prod->id) ? 'selected' : '' }}>
                                {{ $prod->kode_produksi ?? 'ID-'.$prod->id }} — {{ optional($prod->material)->nama_material }} ({{ number_format($prod->jumlah_produksi) }} {{ optional($prod->material)->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="info-produksi-create" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
                    Customer: <strong id="info-prod-customer-create">-</strong> &nbsp;|&nbsp;
                    Material: <strong id="info-prod-material-create">-</strong> &nbsp;|&nbsp;
                    Qty: <strong id="info-prod-qty-create" style="color:var(--primary)">-</strong>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Qty QC <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="qty_qc" class="form-control" id="input-qty-qc-create" value="{{ old('qty_qc') }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hasil <span style="color:var(--ng)">*</span></label>
                        <select name="hasil" class="form-select-full" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="good"     {{ old('hasil') === 'good'     ? 'selected' : '' }}>FG (OK) — Sesuai Standar</option>
                            <option value="not_good" {{ old('hasil') === 'not_good' ? 'selected' : '' }}>NG — Tidak Sesuai</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-purple"><i class="ph ph-floppy-disk"></i> Simpan QC</button>
            </div>
        </form>
    </div>
</div>

{{-- MODALS EDIT --}}
@foreach($qcs as $qc)
<div id="modal-edit-{{ $qc->id }}" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Edit QC</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-{{ $qc->id }}')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('qcs.update', $qc->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="modal_id" value="{{ $qc->id }}">
            <div class="modal-body">
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ optional($qc->production)->kode_produksi ?? '-' }}</strong></div>
                        <div><span style="color:var(--text-muted)">Qty QC</span><br><strong>{{ number_format($qc->qty_qc) }} {{ optional(optional($qc->production)->material)->satuan ?? '' }}</strong></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hasil <span style="color:var(--ng)">*</span></label>
                    <select name="hasil" class="form-select-full" required>
                        <option value="good"     {{ old('hasil', $qc->hasil) === 'good'     ? 'selected' : '' }}>FG (OK) — Sesuai Standar</option>
                        <option value="not_good" {{ old('hasil', $qc->hasil) === 'not_good' ? 'selected' : '' }}>NG — Tidak Sesuai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $qc->keterangan) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
                    <select name="status" class="form-select-full" required>
                        <option value="proses"  {{ old('status', $qc->status) === 'proses'  ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status', $qc->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $qc->id }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Update QC</button>
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
function loadProdInfo(sel, prefix) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-produksi-' + prefix).style.display = 'block';
        document.getElementById('info-prod-customer-' + prefix).textContent = opt.dataset.customer || '-';
        document.getElementById('info-prod-material-' + prefix).textContent = opt.dataset.material || '-';
        document.getElementById('info-prod-qty-' + prefix).textContent      = parseInt(opt.dataset.qty).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
        document.getElementById('input-qty-qc-' + prefix).max = opt.dataset.qty;
    } else {
        document.getElementById('info-produksi-' + prefix).style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-production-id-create');
    if (sel && sel.value) loadProdInfo(sel, 'create');
    
    // Auto open Create if redirected from dashboard
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('production')) {
        openModal('modal-create');
    }
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