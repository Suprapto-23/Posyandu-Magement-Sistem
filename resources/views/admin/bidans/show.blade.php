@extends('layouts.app')

@section('title', 'Detail Bidan')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }
    .profile-avatar {
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; font-weight: bold; color: white;
        margin: 0 auto 1rem; border: 5px solid rgba(255, 255, 255, 0.3);
    }
    .info-card {
        background: white; border-radius: 15px; padding: 1.5rem;
        margin-bottom: 1.5rem; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }
    .info-title {
        color: #2c3e50; font-weight: 600; margin-bottom: 1rem;
        padding-bottom: 0.5rem; border-bottom: 2px solid #27ae60;
    }
    .info-row {
        display: flex; margin-bottom: 0.75rem; padding-bottom: 0.75rem;
        border-bottom: 1px dashed #eee;
    }
    .info-label { flex: 0 0 150px; font-weight: 500; color: #6c757d; }
    .info-value { flex: 1; color: #2c3e50; }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="profile-header">
        <div class="text-center">
            <div class="profile-avatar">
                {{ strtoupper(substr($bidan->profile->full_name ?? 'B', 0, 1)) }}
            </div>
            <h2 class="mb-2">{{ $bidan->profile->full_name ?? $bidan->name }}</h2>
            <p class="mb-0 opacity-75">
                <i class="fas fa-envelope me-1"></i>{{ $bidan->email }}
            </p>
        </div>
    </div>
    
    <div class="d-flex justify-content-end mb-4 gap-2">
        <a href="{{ route('admin.bidans.edit', $bidan->id) }}" class="btn btn-success">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <form action="{{ route('admin.bidans.reset-password', $bidan->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-info text-white" onclick="return confirm('Reset password bidan ini?')">
                <i class="fas fa-key me-2"></i>Reset Password
            </button>
        </form>
        <form action="{{ route('admin.bidans.destroy', $bidan->id) }}" method="POST" style="display: inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus bidan ini?')">
                <i class="fas fa-trash me-2"></i>Hapus
            </button>
        </form>
        <a href="{{ route('admin.bidans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="info-title"><i class="fas fa-user me-2"></i>Data Identitas</h5>
                
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $bidan->profile->full_name ?? '-' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $bidan->profile->nik ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $bidan->email }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="info-title"><i class="fas fa-user-circle me-2"></i>Status Akun</h5>
                
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge {{ $bidan->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $bidan->status == 'active' ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Bergabung Sejak</div>
                    <div class="info-value">{{ $bidan->created_at->translatedFormat('d F Y') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Terakhir Update</div>
                    <div class="info-value">{{ $bidan->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection