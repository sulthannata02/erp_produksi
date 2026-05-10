@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-sub', 'Perbarui data akun pengguna')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <h3 class="card-title" style="margin-bottom:0">Form Edit Pengguna</h3>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="ph ph-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:var(--ng)">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name', $user->name) }}">
            @error('name') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email <span style="color:var(--ng)">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email', $user->email) }}">
            @error('email') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
        </div>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px">
            <div class="form-group">
                <label class="form-label">Role <span style="color:var(--ng)">*</span></label>
                <select name="role" class="form-select-full @error('role') is-invalid @enderror" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator (Lapangan)</option>
                </select>
                @error('role') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Ganti Password <small style="color:var(--text-muted);font-weight:400">(Opsional)</small></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" placeholder="Kosongkan jika tidak diganti">
                @error('password') <div style="color:var(--ng); font-size:12px; margin-top:4px">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; justify-content:flex-end; gap:12px; margin-top:20px">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" style="padding:10px 30px"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
