@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Pengguna')
@section('page-sub', 'Kelola akun admin dan operator')

@section('content')

<div class="page-header">
    <div></div>
    <button type="button" class="btn btn-primary" onclick="openModal('modal-create')" style="background:#14B8A6">
        <i class="ph ph-plus"></i> Tambah Pengguna
    </button>
</div>

<div class="card">
    <form method="GET" action="{{ route('users.index') }}">
        <div class="filter-bar">
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
            </select>

            <div class="search-box">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                <i class="ph ph-magnifying-glass search-icon"></i>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="ph ph-funnel"></i> Filter
            </button>

            @if(request()->hasAny(['role','search']))
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td>{{ $users->firstItem() + $i }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-normal">Admin</span>
                        @else
                            <span class="badge badge-low">Operator</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-edit" title="Edit" onclick="openModal('modal-edit-{{ $user->id }}')">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" title="Hapus">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);padding:32px">
                        <i class="ph ph-users" style="font-size:32px;display:block;margin-bottom:8px"></i>
                        Belum ada data pengguna
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination" style="margin-top:16px">
        @if($users->onFirstPage())
            <span class="page-btn" style="opacity:.4">«</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" class="page-btn">«</a>
        @endif
        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page == $users->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="page-btn">»</a>
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
            <div class="modal-title">Form Tambah Pengguna</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-create')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--ng)">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:var(--ng)">*</span></label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="john@example.com">
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Role <span style="color:var(--ng)">*</span></label>
                        <select name="role" class="form-select-full" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password <span style="color:var(--ng)">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Minimal 8 karakter">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-create')">Batal</button>
                <button type="submit" class="btn btn-primary" style="background:#14B8A6;border:none"><i class="ph ph-floppy-disk"></i> Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

{{-- MODALS EDIT --}}
@foreach($users as $user)
<div id="modal-edit-{{ $user->id }}" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Form Edit Pengguna</div>
            <button type="button" class="modal-close" onclick="closeModal('modal-edit-{{ $user->id }}')"><i class="ph ph-x"></i></button>
        </div>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="modal_id" value="{{ $user->id }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--ng)">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:var(--ng)">*</span></label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Role <span style="color:var(--ng)">*</span></label>
                        <select name="role" class="form-select-full" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ganti Password <span style="color:var(--text-muted);font-weight:400">(Opsional)</span></label>
                        <input type="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak diganti">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-{{ $user->id }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="ph ph-floppy-disk"></i> Update Pengguna</button>
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
