@extends('layouts.app')
@section('title', 'Material')
@section('page-title', 'Material')
@section('page-sub', 'Kelola data material')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div></div>
    <button type="button" class="btn btn-primary" onclick="openModal('modal-create')">
        <i class="ph ph-plus"></i> Tambah Material
    </button>
</div>

{{-- Card --}}
<div class="card">
    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('materials.index') }}" id="form-filter-material">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-material" onchange="this.form.submit()">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <div class="search-box">
                <input type="text" name="search" id="search-material"
                       placeholder="Cari material..."
                       value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request()->hasAny(['customer','search']))
                <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="table" id="table-material">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Nama Material</th>
                    <th>Kode Material</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $i => $material)
                <tr>
                    <td>{{ $materials->firstItem() + $i }}</td>
                    <td>{{ $material->nama_customer ?? '-' }}</td>
                    <td><strong>{{ $material->nama_material }}</strong></td>
                    <td><code style="background:var(--body-bg);padding:2px 6px;border-radius:4px;font-size:12px">{{ $material->kode_part ?? '-' }}</code></td>
                    <td>{{ $material->satuan ?? 'Pcs' }}</td>
                    <td>
                        <span style="font-weight:600;color:{{ $material->jumlah < 100 ? 'var(--ng)' : 'var(--text-dark)' }}">
                            {{ number_format($material->jumlah) }}
                        </span>
                    </td>
                    <td>
                        @if($material->gambar)
                            <img src="{{ asset('storage/'.$material->gambar) }}" class="mat-img" alt="{{ $material->nama_material }}">
                        @else
                            <div class="mat-img-placeholder"><i class="ph ph-image"></i></div>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-edit" title="Edit" onclick="openModal('modal-edit-{{ $material->id }}')">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus material ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus" id="btn-del-material-{{ $material->id }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-package" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data material
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($materials->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($materials->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $materials->previousPageUrl() }}" class="page-btn">«</a>
        @endif

        @foreach($materials->getUrlRange(1, $materials->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $materials->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        @if($materials->hasMorePages())
            <a href="{{ $materials->nextPageUrl() }}" class="page-btn">»</a>
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
            <div class="modal-title">Form Tambah Material</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="nama_customer" class="form-control" required placeholder="Contoh: Fujiseat" value="{{ old('nama_customer') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="nama_material" class="form-control" required placeholder="Contoh: Kain MB Tech" value="{{ old('nama_material') }}">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kode Material <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="kode_part" class="form-control" required placeholder="Contoh: MAT-001" value="{{ old('kode_part') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                        <select name="satuan" class="form-select-full" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach(['Pcs','Roll','Meter','Kg','Liter','Set','Box'] as $s)
                                <option value="{{ $s }}" {{ old('satuan') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah / Stok <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah" class="form-control" required min="0" value="{{ old('jumlah') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk <span style="color:var(--ng)">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" required value="{{ old('tanggal_masuk', now()->toDateString()) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Material <span style="color:var(--text-muted);font-weight:400">(opsional, max 2MB)</span></label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Simpan Material</button>
            </div>
        </form>
    </div>
</div>

{{-- MODALS EDIT --}}
@foreach($materials as $material)
<div id="modal-edit-{{ $material->id }}" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Edit Material</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-{{ $material->id }}')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="modal_id" value="{{ $material->id }}">
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Customer <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="nama_customer" class="form-control" required value="{{ old('nama_customer', $material->nama_customer) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Material <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="nama_material" class="form-control" required value="{{ old('nama_material', $material->nama_material) }}">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kode Material <span style="color:var(--ng)">*</span></label>
                        <input type="text" name="kode_part" class="form-control" required value="{{ old('kode_part', $material->kode_part) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span style="color:var(--ng)">*</span></label>
                        <select name="satuan" class="form-select-full" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach(['Pcs','Roll','Meter','Kg','Liter','Set','Box'] as $s)
                                <option value="{{ $s }}" {{ old('satuan', $material->satuan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah / Stok <span style="color:var(--ng)">*</span></label>
                        <input type="number" name="jumlah" class="form-control" required min="0" value="{{ old('jumlah', $material->jumlah) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk <span style="color:var(--ng)">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" required value="{{ old('tanggal_masuk', $material->tanggal_masuk) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Ganti Gambar <span style="color:var(--text-muted);font-weight:400">(biarkan kosong jika tidak diganti)</span></label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $material->id }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Update Material</button>
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
@if($errors->any())
    @if(old('modal_id'))
        openModal('modal-edit-{{ old('modal_id') }}');
    @else
        openModal('modal-create');
    @endif
@endif
</script>
@endpush