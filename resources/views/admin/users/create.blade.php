@extends('layouts.app')
@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna')
@section('page-sub', 'Daftarkan akun admin atau operator baru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Pengguna Baru</h3>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:var(--ng)">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}" placeholder="Contoh: John Doe">
            @error('name') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email <span style="color:var(--ng)">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}" placeholder="john@example.com">
            @error('email') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Role <span style="color:var(--ng)">*</span></label>
                <select name="role" class="form-select-full @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator (Lapangan)</option>
                </select>
                @error('role') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password <span style="color:var(--ng)">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8" placeholder="Minimal 8 karakter">
                @error('password') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px; margin-top:20px">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 30px; background:#14B8A6; border:none"><i class="ph ph-floppy-disk"></i> Simpan Pengguna</button>
        </div>
    </form>
</div>
@endsection
