@extends('layouts.app')
@section('title', 'Packing')
@section('page-title', 'Packing')
@section('page-sub', 'Kelola data packing')

@section('content')

<div class="page-header">
    <div></div>
    <button type="button" class="btn btn-warning" onclick="openModal('modal-create')">
        <i class="ph ph-plus"></i> Tambah Packing
    </button>
</div>

<div class="card">
    <form method="GET" action="{{ route('packings.index') }}" id="form-filter-packing">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-packing">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <input type="date" name="tanggal" class="form-select" id="filter-tanggal-packing" value="{{ request('tanggal') }}">

            <select name="status" class="form-select" id="filter-status-packing">
                <option value="">Semua Status</option>
                <option value="proses"  {{ request('status') == 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-packing">
                <i class="ph ph-funnel"></i> Filter
            </button>

            <div class="search-box">
                <input type="text" name="search" id="search-packing"
                       placeholder="Cari packing..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            @if(request()->hasAny(['customer','tanggal','status','search']))
                <a href="{{ route('packings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-packing">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Customer</th>
                    <th>Kode Packing</th>
                    <th>Material</th>
                    <th>Total Finish Good</th>
                    <th>Total Not Good</th>
                    <th>Keterangan</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packings as $i => $packing)
                <tr>
                    <td>{{ $packings->firstItem() + $i }}</td>
                    <td>{{ $packing->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional(optional(optional($packing->qc)->production)->material)->nama_customer ?? '-' }}</td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $packing->kode_packing ?? '-' }}</code></td>
                    <td>{{ optional(optional(optional($packing->qc)->production)->material)->nama_material ?? '-' }}</td>
                    <td><span style="font-weight:600;color:var(--fg-ok)">{{ number_format($packing->jumlah_fg) }}</span></td>
                    <td><span style="font-weight:600;color:var(--ng)">{{ number_format($packing->jumlah_ng) }}</span></td>
                    <td style="max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $packing->keterangan }}">
                        {{ $packing->keterangan ?? '-' }}
                    </td>
                    <td>{{ $packing->operator ?? '-' }}</td>
                    <td>
                        @if($packing->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-proses">Proses</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-edit" title="Edit" onclick="openModal('modal-edit-{{ $packing->id }}')">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                            <form action="{{ route('packings.destroy', $packing->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus data packing ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" id="btn-del-packing-{{ $packing->id }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-archive-box" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data packing
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($packings->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($packings->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $packings->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($packings->getUrlRange(1, $packings->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $packings->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($packings->hasMorePages())
            <a href="{{ $packings->nextPageUrl() }}" class="page-btn">»</a>
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
            <div class="modal-title">Form Tambah Packing</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('packings.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Data QC (hasil Good) <span style="color:var(--ng)">*</span></label>
                    <select name="qc_id" class="form-select-full" id="input-qc-id-create" required onchange="loadQcInfo(this, 'create')">
                        <option value="">-- Pilih Data QC --</option>
                        @foreach($qcsList as $qc)
                            <option value="{{ $qc->id }}"
                                    data-customer="{{ optional(optional($qc->production)->material)->nama_customer }}"
                                    data-material="{{ optional(optional($qc->production)->material)->nama_material }}"
                                    data-kode="{{ optional($qc->production)->kode_produksi }}"
                                    data-qty="{{ $qc->qty_qc }}"
                                    data-satuan="{{ optional(optional($qc->production)->material)->satuan }}"
                                    {{ (old('qc_id') == $qc->id || request('qc') == $qc->id) ? 'selected' : '' }}>
                                {{ optional($qc->production)->kode_produksi ?? 'ID-'.$qc->id }} — {{ optional(optional($qc->production)->material)->nama_material }} ({{ number_format($qc->qty_qc ?? 0) }} {{ optional(optional($qc->production)->material)->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="info-qc-create" style="display:none;background:var(--primary-light);border:1px solid var(--primary);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:13px">
                    Customer: <strong id="info-qc-customer-create">-</strong> &nbsp;|&nbsp;
                    Material: <strong id="info-qc-material-create">-</strong> &nbsp;|&nbsp;
                    Qty QC: <strong id="info-qc-qty-create" style="color:var(--primary)">-</strong>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Total Finish Good (FG) <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah_fg" class="form-control" value="{{ old('jumlah_fg') }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Not Good (NG) <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah_ng" class="form-control" value="{{ old('jumlah_ng') }}" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="ph ph-floppy-disk"></i> Simpan Packing</button>
            </div>
        </form>
    </div>
</div>

{{-- MODALS EDIT --}}
@foreach($packings as $packing)
<div id="modal-edit-{{ $packing->id }}" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Edit Packing</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-{{ $packing->id }}')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('packings.update', $packing->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="modal_id" value="{{ $packing->id }}">
            <div class="modal-body">
                <div style="background:var(--body-bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <div><span style="color:var(--text-muted)">Kode Packing</span><br><strong>{{ $packing->kode_packing ?? '-' }}</strong></div>
                        <div><span style="color:var(--text-muted)">Kode Produksi</span><br><strong>{{ optional(optional($packing->qc)->production)->kode_produksi ?? '-' }}</strong></div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Total Finish Good (FG) <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah_fg" class="form-control" value="{{ old('jumlah_fg', $packing->jumlah_fg) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Not Good (NG) <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah_ng" class="form-control" value="{{ old('jumlah_ng', $packing->jumlah_ng) }}" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $packing->keterangan) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--ng)">*</span></label>
                    <select name="status" class="form-select-full" required>
                        <option value="proses"  {{ old('status', $packing->status) === 'proses'  ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status', $packing->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $packing->id }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Update Packing</button>
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
function loadQcInfo(sel, prefix) {
    const opt = sel.options[sel.selectedIndex];
    if (opt.value) {
        document.getElementById('info-qc-' + prefix).style.display = 'block';
        document.getElementById('info-qc-customer-' + prefix).textContent = opt.dataset.customer || '-';
        document.getElementById('info-qc-material-' + prefix).textContent = opt.dataset.material || '-';
        document.getElementById('info-qc-qty-' + prefix).textContent      = parseInt(opt.dataset.qty || 0).toLocaleString('id') + ' ' + (opt.dataset.satuan || '');
    } else {
        document.getElementById('info-qc-' + prefix).style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('input-qc-id-create');
    if (sel && sel.value) loadQcInfo(sel, 'create');
    
    // Auto open Create if redirected from dashboard
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('qc')) {
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