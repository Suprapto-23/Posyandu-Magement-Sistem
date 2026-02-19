@extends('layouts.kader')

@section('title', 'Detail Pemeriksaan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Detail Pemeriksaan</h1>
        <div>
            <a href="{{ route('kader.pemeriksaan.edit', $pemeriksaan->id) }}" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Edit</a>
            <a href="{{ route('kader.pemeriksaan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
        </div>
    </div>

    <div class="row">
        {{-- KIRI: DATA PASIEN --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4 border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 fw-bold">Data Pasien</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $pemeriksaan->kunjungan->pasien->nama_lengkap }}</h4>
                    
                    {{-- Badge Kategori --}}
                    @php
                        $kat = $pemeriksaan->kategori_pasien;
                        $badgeInfo = $kat == 'balita' ? 'info' : ($kat == 'remaja' ? 'primary' : 'warning');
                    @endphp
                    <span class="badge bg-{{ $badgeInfo }} mb-3">{{ strtoupper($kat) }}</span>

                    <ul class="list-group list-group-flush text-start">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">NIK</span>
                            <span class="fw-bold">{{ $pemeriksaan->kunjungan->pasien->nik }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Usia</span>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($pemeriksaan->kunjungan->pasien->tanggal_lahir)->age }} Tahun</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Tgl Periksa</span>
                            <span class="fw-bold">{{ $pemeriksaan->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- KANAN: HASIL PEMERIKSAAN --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4 border-0 rounded-4">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold">Hasil Pemeriksaan Fisik</h6>
                </div>
                <div class="card-body">
                    
                    {{-- 1. TANDA VITAL (UMUM) --}}
                    <h6 class="text-success fw-bold mb-3 border-bottom pb-2">Tanda Vital</h6>
                    <div class="row text-center mb-4 g-3">
                        <div class="col-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="d-block text-muted mb-1">Berat Badan</small>
                                <h4 class="fw-bold text-dark mb-0">{{ $pemeriksaan->berat_badan }} <small class="fs-6 text-muted">kg</small></h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="d-block text-muted mb-1">Tinggi Badan</small>
                                <h4 class="fw-bold text-dark mb-0">{{ $pemeriksaan->tinggi_badan }} <small class="fs-6 text-muted">cm</small></h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 border rounded bg-light h-100">
                                <small class="d-block text-muted mb-1">Suhu</small>
                                <h4 class="fw-bold text-dark mb-0">{{ $pemeriksaan->suhu_tubuh ?? '-' }} <small class="fs-6 text-muted">°C</small></h4>
                            </div>
                        </div>
                    </div>

                    {{-- 2. TAMPILAN KHUSUS BERDASARKAN KATEGORI --}}
                    
                    {{-- JIKA REMAJA ATAU LANSIA (Tampilkan IMT & Lab) --}}
                    @if($pemeriksaan->kategori_pasien == 'remaja' || $pemeriksaan->kategori_pasien == 'lansia')
                        <h6 class="text-success fw-bold mb-3 border-bottom pb-2">Analisis Gizi & Lab</h6>
                        <div class="row g-3 mb-4">
                            {{-- IMT Card --}}
                            <div class="col-md-4">
                                <div class="p-3 border rounded h-100 bg-white shadow-sm border-start border-4 border-{{ $pemeriksaan->status_gizi_color }}">
                                    <small class="d-block text-muted fw-bold">IMT (Body Mass Index)</small>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <h3 class="mb-0 fw-bold">{{ number_format($pemeriksaan->imt, 1) }}</h3>
                                        {{-- PANGGIL ACCESSOR YANG SUDAH DIPERBAIKI --}}
                                        <span class="badge bg-{{ $pemeriksaan->status_gizi_color }}">
                                            {{ $pemeriksaan->status_gizi_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Lab Results --}}
                            <div class="col-md-8">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="p-2 border rounded d-flex justify-content-between">
                                            <span class="text-muted small">Tensi</span>
                                            <span class="fw-bold">{{ $pemeriksaan->tekanan_darah ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded d-flex justify-content-between">
                                            <span class="text-muted small">Hemoglobin</span>
                                            <span class="fw-bold text-danger">{{ $pemeriksaan->hemoglobin ?? '-' }} g/dL</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Khusus Lansia Ada Gula/Asam Urat --}}
                                    @if($pemeriksaan->kategori_pasien == 'lansia')
                                    <div class="col-6">
                                        <div class="p-2 border rounded d-flex justify-content-between">
                                            <span class="text-muted small">Gula Darah</span>
                                            <span class="fw-bold">{{ $pemeriksaan->gula_darah ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded d-flex justify-content-between">
                                            <span class="text-muted small">Asam Urat</span>
                                            <span class="fw-bold">{{ $pemeriksaan->asam_urat ?? '-' }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- JIKA BALITA (Tampilkan Lingkar Kepala) --}}
                    @if($pemeriksaan->kategori_pasien == 'balita')
                        <h6 class="text-info fw-bold mb-3 border-bottom pb-2">Pengukuran Balita</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-info bg-opacity-10 rounded border border-info">
                                    <small class="text-muted d-block">Lingkar Kepala</small>
                                    <strong class="h5 text-dark">{{ $pemeriksaan->lingkar_kepala ?? '-' }} cm</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-info bg-opacity-10 rounded border border-info">
                                    <small class="text-muted d-block">Lingkar Lengan</small>
                                    <strong class="h5 text-dark">{{ $pemeriksaan->lingkar_lengan ?? '-' }} cm</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-{{ $pemeriksaan->status_gizi_color }} d-flex justify-content-between align-items-center">
                                    <span>Status Gizi Balita:</span>
                                    <span class="fw-bold">{{ $pemeriksaan->status_gizi_label }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 3. DIAGNOSA --}}
                    <div class="alert alert-secondary border-0 mb-0">
                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <h6 class="fw-bold text-dark"><i class="fas fa-stethoscope me-1"></i> Diagnosa</h6>
                                <p class="mb-0 text-muted">{{ $pemeriksaan->diagnosa ?? 'Tidak ada diagnosa khusus' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark"><i class="fas fa-pills me-1"></i> Tindakan / Resep</h6>
                                <p class="mb-0 text-muted">{{ $pemeriksaan->tindakan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection