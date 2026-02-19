@extends('layouts.user')

@section('title', 'Profil Keluarga')
@section('page-title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Informasi Akun</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">NIK (Username)</label>
                            <input type="text" class="form-control bg-light" value="{{ $user->nik ?? $user->username }}" readonly disabled>
                            <small class="text-muted" style="font-size: 10px;">NIK tidak dapat diubah oleh user.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Email (Opsional)</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Ganti Password</h6>
                </div>
                <div class="card-body p-4">
                    {{-- Bisa arahkan ke route change password yang ada di Auth --}}
                    <form action="{{ route('password.change.post') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning text-white px-4">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection