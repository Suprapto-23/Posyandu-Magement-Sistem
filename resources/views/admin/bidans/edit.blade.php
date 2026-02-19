@extends('layouts.app')

@section('title', 'Edit Bidan')

@push('styles')
<style>
    .card-form {
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        background: white;
    }
    
    .card-header-form {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
        border: none;
    }
    
    .form-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #27ae60;
    }
    
    .required-label::after {
        content: " *";
        color: #e74c3c;
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2><i class="fas fa-user-edit me-2"></i>Edit Akses Bidan</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bidans.index') }}">Bidan</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.bidans.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card-form mt-4">
        <div class="card-header-form">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Akun</h4>
            <p class="mb-0 opacity-75">Perbarui informasi dasar untuk {{ $bidan->name }}</p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.bidans.update', $bidan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-user-cog"></i> Informasi Utama</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label required-label">Nama Lengkap</label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name', $bidan->profile->full_name ?? $bidan->name) }}"
                                   placeholder="Nama lengkap bidan">
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label required-label">Status Akun</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status">
                                <option value="active" {{ old('status', $bidan->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $bidan->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email (Username)</label>
                            <input type="text" class="form-control bg-light" value="{{ $bidan->email }}" readonly>
                            <small class="text-muted">Email tidak dapat diubah dari menu edit.</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control bg-light" value="{{ $bidan->profile->nik ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.bidans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection