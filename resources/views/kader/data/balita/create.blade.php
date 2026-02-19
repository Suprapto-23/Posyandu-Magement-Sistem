@extends('layouts.kader')

@section('title', 'Tambah Data Balita')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Balita Baru</h1>
        <p class="text-muted mb-0">Pastikan NIK Ibu diisi dengan benar agar terhubung ke akun warga.</p>
    </div>
    <a href="{{ route('kader.data.balita.index') }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('kader.data.balita.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-primary"><i class="fas fa-baby me-2"></i>Identitas Balita</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap Anak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Muhammad Al-Fatih">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NIK Balita (Opsional)</label>
                            <input type="number" class="form-control bg-light border-0" name="nik" value="{{ old('nik') }}" placeholder="16 digit angka (jika ada)">
                            <div class="form-text small">Kosongkan jika balita belum memiliki NIK/KIA.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-0" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-0" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota kelahiran">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-0" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Berat Lahir (kg)</label>
                            <input type="number" step="0.01" class="form-control bg-light border-0" name="berat_lahir" value="{{ old('berat_lahir') }}" placeholder="0.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Panjang Lahir (cm)</label>
                            <input type="number" step="0.01" class="form-control bg-light border-0" name="panjang_lahir" value="{{ old('panjang_lahir') }}" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 rounded-4 bg-primary bg-opacity-10">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Data Orang Tua (Penting)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-light border-0 small shadow-sm text-primary mb-3">
                        <i class="fas fa-info-circle me-1"></i> <strong>Integrasi Akun:</strong> Pastikan NIK Ibu diisi dengan benar. Data balita akan otomatis muncul di akun warga dengan NIK tersebut.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NIK Ibu (Wajib) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control border-0 shadow-sm" name="nik_ibu" value="{{ old('nik_ibu') }}" required placeholder="16 digit NIK Ibu">
                        @error('nik_ibu')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Ibu Kandung <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-0 shadow-sm" name="nama_ibu" value="{{ old('nama_ibu') }}" required placeholder="Nama lengkap ibu">
                    </div>
                    
                    <hr class="border-secondary opacity-25">

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">NIK Ayah (Opsional)</label>
                        <input type="number" class="form-control bg-white border-0" name="nik_ayah" value="{{ old('nik_ayah') }}" placeholder="16 digit NIK Ayah">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Ayah</label>
                        <input type="text" class="form-control bg-white border-0" name="nama_ayah" value="{{ old('nama_ayah') }}" placeholder="Nama lengkap ayah">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Alamat Domisili <span class="text-danger">*</span></label>
                        <textarea class="form-control bg-white border-0" name="alamat" rows="3" required placeholder="Alamat lengkap saat ini...">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow">
                            <i class="fas fa-save me-2"></i>Simpan Data Balita
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Validasi Tanggal Lahir (Maksimal Hari Ini)
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection