@extends('layouts.kader')

@section('title', 'Edit Pemeriksaan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Edit Pemeriksaan</h1>
        <a href="{{ route('kader.pemeriksaan.show', $pemeriksaan->id) }}" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-warning text-white py-3 rounded-top-4">
            <h6 class="m-0 fw-bold"><i class="fas fa-edit me-2"></i>Form Edit Data</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('kader.pemeriksaan.update', $pemeriksaan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Info Pasien Readonly --}}
                <div class="alert alert-warning bg-opacity-10 border-warning mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-dark mb-1">{{ $pemeriksaan->kunjungan->pasien->nama_lengkap }}</h5>
                            <span class="badge bg-warning text-dark">{{ strtoupper($pasien_type) }}</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted d-block">Tanggal Kunjungan</small>
                            <strong>{{ $pemeriksaan->kunjungan->tanggal_kunjungan->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Fisik Dasar --}}
                <h6 class="text-primary fw-bold mb-3">Pemeriksaan Fisik</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.01" class="form-control" name="berat_badan" value="{{ $pemeriksaan->berat_badan }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.01" class="form-control" name="tinggi_badan" value="{{ $pemeriksaan->tinggi_badan }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Suhu Tubuh (°C)</label>
                        <input type="number" step="0.1" class="form-control" name="suhu_tubuh" value="{{ $pemeriksaan->suhu_tubuh }}">
                    </div>
                </div>

                {{-- Parameter Balita --}}
                @if($pasien_type == 'balita')
                <div class="card bg-info bg-opacity-10 border-0 mb-4">
                    <div class="card-body">
                        <h6 class="text-info fw-bold mb-3">Parameter Balita</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Lingkar Kepala (cm)</label>
                                <input type="number" step="0.1" class="form-control border-info" name="lingkar_kepala" value="{{ $pemeriksaan->lingkar_kepala }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lingkar Lengan (cm)</label>
                                <input type="number" step="0.1" class="form-control border-info" name="lingkar_lengan" value="{{ $pemeriksaan->lingkar_lengan }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Parameter Umum (Remaja & Lansia) --}}
                @if($pasien_type == 'remaja' || $pasien_type == 'lansia')
                <div class="card bg-success bg-opacity-10 border-0 mb-4">
                    <div class="card-body">
                        <h6 class="text-success fw-bold mb-3">Laboratorium Sederhana</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tekanan Darah (mmHg)</label>
                                <input type="text" class="form-control border-success" name="tekanan_darah" value="{{ $pemeriksaan->tekanan_darah }}">
                            </div>
                            
                            {{-- UPDATE: Input Hemoglobin --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Hemoglobin (Hb)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" class="form-control border-success fw-bold text-success" name="hemoglobin" value="{{ $pemeriksaan->hemoglobin }}">
                                    <span class="input-group-text bg-success text-white border-success">g/dL</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Lingkar Lengan (cm)</label>
                                <input type="number" step="0.1" class="form-control border-success" name="lingkar_lengan" value="{{ $pemeriksaan->lingkar_lengan }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gula Darah (mg/dL)</label>
                                <input type="number" class="form-control border-success" name="gula_darah" value="{{ $pemeriksaan->gula_darah }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kolesterol (mg/dL)</label>
                                <input type="number" class="form-control border-success" name="kolesterol" value="{{ $pemeriksaan->kolesterol }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Asam Urat (mg/dL)</label>
                                <input type="number" step="0.1" class="form-control border-success" name="asam_urat" value="{{ $pemeriksaan->asam_urat }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <h6 class="text-primary fw-bold mb-3">Diagnosa & Tindakan</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Diagnosa</label>
                            <textarea class="form-control" name="diagnosa" rows="3">{{ $pemeriksaan->diagnosa }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tindakan / Resep</label>
                            <textarea class="form-control" name="tindakan" rows="3">{{ $pemeriksaan->tindakan }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-warning px-5 rounded-pill fw-bold shadow"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection