@extends('layouts.kader')

@section('title', 'Edit Data Lansia')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit Lansia</h1>
        <p class="text-muted mb-0">Perbarui data lansia</p>
    </div>
    <a href="{{ route('kader.data.lansia.show', $lansia->id) }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('kader.data.lansia.update', $lansia->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-warning"><i class="fas fa-user-edit me-2"></i>Edit Identitas</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" name="nama_lengkap" value="{{ old('nama_lengkap', $lansia->nama_lengkap) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NIK (Kunci Integrasi)</label>
                            <input type="number" class="form-control bg-light border-0" name="nik" value="{{ old('nik', $lansia->nik) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Jenis Kelamin</label>
                            <select class="form-select bg-light border-0" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tempat Lahir</label>
                            <input type="text" class="form-control bg-light border-0" name="tempat_lahir" value="{{ old('tempat_lahir', $lansia->tempat_lahir) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal Lahir</label>
                            <input type="date" class="form-control bg-light border-0" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $lansia->tanggal_lahir->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Alamat Domisili</label>
                            <textarea class="form-control bg-light border-0" name="alamat" rows="2" required>{{ old('alamat', $lansia->alamat) }}</textarea>
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
                        <label class="form-label fw-bold small text-muted">Penyakit Bawaan</label>
                        <textarea class="form-control border-0 shadow-sm" name="penyakit_bawaan" rows="4">{{ old('penyakit_bawaan', $lansia->penyakit_bawaan) }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-warning text-white rounded-pill py-3 fw-bold shadow">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection