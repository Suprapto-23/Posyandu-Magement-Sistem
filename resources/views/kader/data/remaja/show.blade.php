@extends('layouts.kader')

@section('title', 'Detail Remaja')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-primary p-4 text-white position-relative" style="min-height: 120px;">
                    <div class="d-flex align-items-center position-relative z-index-1">
                        <div class="bg-white text-primary rounded-circle shadow p-3 me-3">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $remaja->nama_lengkap }}</h4>
                            <p class="mb-0 opacity-75">Kode: {{ $remaja->kode_remaja }}</p>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('kader.data.remaja.edit', $remaja->id) }}" class="btn btn-light text-primary fw-bold rounded-pill">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('kader.data.remaja.index') }}" class="btn btn-outline-light fw-bold rounded-pill ms-2">
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
                        <span class="fw-bold text-dark">{{ $remaja->nik }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Jenis Kelamin</small>
                        <span class="badge bg-{{ $remaja->jenis_kelamin == 'L' ? 'primary' : 'danger' }}-subtle text-{{ $remaja->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                            {{ $remaja->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">TTL & Usia</small>
                        <span class="text-dark">{{ $remaja->tempat_lahir }}, {{ $remaja->tanggal_lahir->format('d M Y') }}</span>
                        <div class="text-primary small fw-bold">{{ $usia }} Tahun</div>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Alamat</small>
                        <span class="text-dark">{{ $remaja->alamat }}</span>
                    </li>
                </ul>

                <hr class="border-light my-4">

                <h6 class="fw-bold text-uppercase text-muted small mb-3">Sekolah & Ortu</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <small class="text-muted d-block">Sekolah / Kelas</small>
                        <span class="fw-bold text-dark">{{ $remaja->sekolah ?? '-' }} <span class="text-muted fw-normal">({{ $remaja->kelas ?? '-' }})</span></span>
                    </li>
                    <li>
                        <small class="text-muted d-block">Nama Orang Tua (HP)</small>
                        <span class="text-dark">{{ $remaja->nama_ortu ?? '-' }} <span class="text-muted">({{ $remaja->telepon_ortu ?? '-' }})</span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Riwayat Pemeriksaan</h5>
                <a href="{{ route('kader.pemeriksaan.create') }}?pasien_id={{ $remaja->id }}&pasien_type=remaja" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Periksa Baru
                </a>
            </div>
            <div class="card-body p-4">
                @forelse($remaja->kunjungans as $kunjungan)
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
                                <div class="d-flex gap-3 small mt-2">
                                    <span class="badge bg-light text-dark border">BB: {{ $kunjungan->pemeriksaan->berat_badan }} kg</span>
                                    <span class="badge bg-light text-dark border">TB: {{ $kunjungan->pemeriksaan->tinggi_badan }} cm</span>
                                    <span class="badge bg-light text-dark border">Tensi: {{ $kunjungan->pemeriksaan->tekanan_darah ?? '-' }}</span>
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