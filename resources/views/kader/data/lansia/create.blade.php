@extends('layouts.kader')

@section('title', 'Tambah Data Lansia')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Tambah Lansia</h1>
        <p class="text-muted mb-0">Registrasi warga lanjut usia (Pra-Lansia & Lansia)</p>
    </div>
    <a href="{{ route('kader.data.lansia.index') }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('kader.data.lansia.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-primary"><i class="fas fa-user-clock me-2"></i>Identitas Lansia</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Nama sesuai KTP">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NIK (Wajib 16 Digit) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control bg-light border-0" name="nik" value="{{ old('nik') }}" required placeholder="Kunci Integrasi Akun">
                            <div class="form-text small text-primary"><i class="fas fa-info-circle"></i> Pastikan NIK benar agar terhubung ke akun.</div>
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
                            <input type="text" class="form-control bg-light border-0" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light border-0" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Alamat Domisili <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-0" name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 rounded-4 bg-danger bg-opacity-10">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-danger"><i class="fas fa-heartbeat me-2"></i>Riwayat Kesehatan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Penyakit Bawaan / Keluhan Rutin</label>
                        <textarea class="form-control border-0 shadow-sm" name="penyakit_bawaan" rows="4" placeholder="Contoh: Hipertensi, Diabetes, Asam Urat...">{{ old('penyakit_bawaan') }}</textarea>
                        <div class="form-text small">Kosongkan jika tidak ada.</div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-danger rounded-pill py-3 fw-bold shadow">
                            <i class="fas fa-save me-2"></i>Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Set max date agar tidak input tanggal masa depan
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection