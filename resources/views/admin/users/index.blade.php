@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Pengguna')
@section('page-sub', 'Kelola akun admin dan operator')

@section('content')

<div class="page-header">
    <div></div>
    <a href="{{ route('users.create') }}" class="btn btn-primary" style="background:#14B8A6; border:none">
        <i class="ph ph-plus"></i> Tambah Pengguna
    </a>
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
                            <span class="badge" style="background:#E0F2FE; color:#0369A1; border: 1px solid #BAE6FD">Admin</span>
                        @else
                            <span class="badge badge-low">Operator</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn-edit" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline" id="form-delete-{{ $user->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" title="Hapus" onclick="confirmDelete('form-delete-{{ $user->id }}')">
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
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
