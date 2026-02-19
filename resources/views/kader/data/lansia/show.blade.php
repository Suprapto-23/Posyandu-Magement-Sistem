@extends('layouts.kader')

@section('title', 'Detail Lansia')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-primary p-4 text-white position-relative" style="min-height: 120px;">
                    <div class="d-flex align-items-center position-relative z-index-1">
                        <div class="bg-white text-primary rounded-circle shadow p-3 me-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $lansia->nama_lengkap }}</h4>
                            <p class="mb-0 opacity-75">Kode: {{ $lansia->kode_lansia }}</p>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('kader.data.lansia.edit', $lansia->id) }}" class="btn btn-light text-primary fw-bold rounded-pill">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('kader.data.lansia.index') }}" class="btn btn-outline-light fw-bold rounded-pill ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-uppercase text-muted small mb-3">Biodata Diri</h6>
                
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <small class="text-muted d-block">NIK</small>
                        <span class="fw-bold text-dark">{{ $lansia->nik }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span class="badge bg-{{ $lansia->jenis_kelamin == 'L' ? 'primary' : 'danger' }}-subtle text-{{ $lansia->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                            {{ $lansia->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">TTL & Usia</small>
                        <span class="text-dark">{{ $lansia->tempat_lahir }}, {{ $lansia->tanggal_lahir->format('d M Y') }}</span>
                        {{-- MENGGUNAKAN $usia DARI CONTROLLER (INTEGER) --}}
                        <div class="text-primary small fw-bold">{{ $usia }} Tahun</div>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Alamat</small>
                        <span class="text-dark">{{ $lansia->alamat }}</span>
                    </li>
                </ul>

                <hr class="border-light my-4">

                <h6 class="fw-bold text-uppercase text-muted small mb-3">Kesehatan</h6>
                @if($lansia->penyakit_bawaan)
                    @foreach(explode(',', $lansia->penyakit_bawaan) as $sakit)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1">{{ trim($sakit) }}</span>
                    @endforeach
                @else
                    <span class="text-success small"><i class="fas fa-check-circle me-1"></i> Tidak ada riwayat penyakit</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Riwayat Pemeriksaan</h5>
                <a href="{{ route('kader.pemeriksaan.create') }}?pasien_id={{ $lansia->id }}&pasien_type=lansia" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Periksa Baru
                </a>
            </div>
            <div class="card-body p-4">
                @forelse($lansia->kunjungans as $kunjungan)
                    <div class="d-flex align-items-start mb-4 border-bottom pb-3 last-no-border">
                        <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-1">{{ ucfirst($kunjungan->jenis_kunjungan) }}</h6>
                                <small class="text-muted">{{ $kunjungan->tanggal_kunjungan->format('d M Y') }}</small>
                            </div>
                            <p class="mb-1 text-muted small">{{ $kunjungan->keluhan ?? 'Tidak ada keluhan' }}</p>
                            @if($kunjungan->pemeriksaan)
                                <div class="d-flex gap-2 flex-wrap small mt-2">
                                    <span class="badge bg-light text-dark border">Tensi: {{ $kunjungan->pemeriksaan->tekanan_darah ?? '-' }}</span>
                                    <span class="badge bg-light text-dark border">Gula: {{ $kunjungan->pemeriksaan->gula_darah ?? '-' }}</span>
                                    <span class="badge bg-light text-dark border">Asam Urat: {{ $kunjungan->pemeriksaan->asam_urat ?? '-' }}</span>
                                    <span class="badge bg-light text-dark border">Kolesterol: {{ $kunjungan->pemeriksaan->kolesterol ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada riwayat pemeriksaan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection