@extends('layouts.kader')

@section('title', 'Edit Data Balita')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit Data Balita</h1>
        <p class="text-muted mb-0">Perbarui informasi data balita dan orang tua</p>
    </div>
    <a href="{{ route('kader.data.balita.show', $balita->id) }}" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('kader.data.balita.update', $balita->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-warning"><i class="fas fa-edit me-2"></i>Identitas Balita</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" name="nama_lengkap" value="{{ old('nama_lengkap', $balita->nama_lengkap) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NIK Balita</label>
                            <input type="number" class="form-control bg-light border-0" name="nik" value="{{ old('nik', $balita->nik) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Jenis Kelamin</label>
                            <select class="form-select bg-light border-0" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tempat Lahir</label>
                            <input type="text" class="form-control bg-light border-0" name="tempat_lahir" value="{{ old('tempat_lahir', $balita->tempat_lahir) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal Lahir</label>
                            <input type="date" class="form-control bg-light border-0" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Berat Lahir (kg)</label>
                            <input type="number" step="0.01" class="form-control bg-light border-0" name="berat_lahir" value="{{ old('berat_lahir', $balita->berat_lahir) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Panjang Lahir (cm)</label>
                            <input type="number" step="0.01" class="form-control bg-light border-0" name="panjang_lahir" value="{{ old('panjang_lahir', $balita->panjang_lahir) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 rounded-4 bg-warning bg-opacity-10">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark"><i class="fas fa-users me-2"></i>Data Orang Tua</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NIK Ibu (Kunci Integrasi)</label>
                        <input type="number" class="form-control border-0 shadow-sm" name="nik_ibu" value="{{ old('nik_ibu', $balita->nik_ibu) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Ibu</label>
                        <input type="text" class="form-control border-0 shadow-sm" name="nama_ibu" value="{{ old('nama_ibu', $balita->nama_ibu) }}" required>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">NIK Ayah</label>
                        <input type="number" class="form-control bg-white border-0" name="nik_ayah" value="{{ old('nik_ayah', $balita->nik_ayah) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Ayah</label>
                        <input type="text" class="form-control bg-white border-0" name="nama_ayah" value="{{ old('nama_ayah', $balita->nama_ayah) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Alamat</label>
                        <textarea class="form-control bg-white border-0" name="alamat" rows="3" required>{{ old('alamat', $balita->alamat) }}</textarea>
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