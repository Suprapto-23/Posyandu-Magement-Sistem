@extends('layouts.app')

@section('title', 'Tambah Bidan')

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
    .section-title i {
        color: #27ae60;
        margin-right: 0.5rem;
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
                <h2><i class="fas fa-user-plus me-2"></i>Tambah Akses Bidan</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bidans.index') }}">Bidan</a></li>
                        <li class="breadcrumb-item active">Buat Akun</li>
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
            <h4 class="mb-0"><i class="fas fa-key me-2"></i>Form Pembuatan Akun</h4>
            <p class="mb-0 opacity-75">Buat akses masuk untuk bidan baru</p>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('admin.bidans.store') }}" method="POST">
                @csrf
                
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-id-card"></i> Identitas & Login</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label required-label">Nama Lengkap</label>
                            <input type="text" 
                                   name="full_name" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   value="{{ old('full_name') }}" 
                                   placeholder="Contoh: Bidan Siti Aminah, Amd.Keb">
                            @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label required-label">NIK (16 Digit)</label>
                            <input type="text" 
                                   name="nik" 
                                   id="nik" 
                                   class="form-control @error('nik') is-invalid @enderror" 
                                   value="{{ old('nik') }}" 
                                   maxlength="16" 
                                   placeholder="Nomor Induk Kependudukan">
                            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label required-label">Email Login</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="alamat@email.com">
                            <small class="text-muted">Email ini akan digunakan untuk login ke sistem.</small>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label required-label">Status Akun</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif (Dapat Login)</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif (Blokir)</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 ps-3">
                            <li>Password akan <strong>digenerate otomatis</strong> oleh sistem.</li>
                            <li>Password akan muncul di layar setelah Anda menekan tombol Simpan.</li>
                            <li>Detail profil lainnya (Alamat, RS, SIP) dapat dilengkapi mandiri oleh Bidan nanti.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.bidans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Buat Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hanya izinkan angka untuk input NIK
    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush