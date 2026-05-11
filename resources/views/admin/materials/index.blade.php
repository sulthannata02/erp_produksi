@extends('layouts.app')
@section('title', 'Material Master')
@section('page-title', 'Material Master Data')
@section('page-sub', 'Kelola data master material dan stok target')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('materials.create') }}" class="btn btn-success">
        <i class="ph ph-plus"></i> Tambah Material Baru
    </a>
</div>

<div class="card">
    <form method="GET" action="{{ route('materials.index') }}" id="form-filter-material">
        <div class="filter-bar">
            <select name="customer" class="form-select" id="filter-customer-material">
                <option value="">Pilih Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ request('customer') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <div class="search-box">
                <input type="text" name="search" id="search-material"
                       placeholder="Cari material..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" id="btn-filter-material">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request('customer') || request('search'))
                <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table" id="table-material">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Nama Material</th>
                    <th>Kode Part</th>
                    <th>Satuan</th>
                    <th>Hgr/Box</th>
                    <th>Target Stok</th>
                    <th>Aktual Stok</th>
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
                        <small style="display:block">{{ $material->qty_per_hanger }} Q/H</small>
                        <small style="display:block">{{ $material->qty_per_box }} Q/B</small>
                    </td>
                    <td>
                        <span class="badge" style="background:#F5F3FF; color:#7C3AED; border: 1px solid #DDD6FE; font-weight:700">
                            {{ number_format($material->jumlah) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $material->aktual_stok <= 0 ? 'badge-urgent' : 'badge-selesai' }}" style="font-weight:700">
                            {{ number_format($material->aktual_stok) }}
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
                            <a href="{{ route('materials.edit', $material->id) }}" class="btn-edit" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:inline" id="form-delete-{{ $material->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete('form-delete-{{ $material->id }}')">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-package" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data material
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materials->hasPages())
    <div class="pagination" style="margin-top:16px">
        {{ $materials->links() }}
    </div>
    @endif
</div>

@endsection