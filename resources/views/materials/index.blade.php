@extends('layouts.app')
@section('title', 'Material')
@section('page-title', 'Material')
@section('page-sub', 'Kelola data material')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div></div>
    <a href="{{ route('materials.create') }}" class="btn btn-primary" id="btn-tambah-material">
        <i class="ph ph-plus"></i> Tambah Material
    </a>
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
                            <a href="{{ route('materials.edit', $material->id) }}" class="btn-edit" title="Edit" id="btn-edit-material-{{ $material->id }}">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
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

@endsection