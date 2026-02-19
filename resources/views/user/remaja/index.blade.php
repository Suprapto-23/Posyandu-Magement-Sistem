@extends('layouts.user')

@section('title', 'Data Remaja')
@section('page-title', 'Profil Kesehatan Remaja')

@section('content')
<div class="container-fluid animate-fade-in">
    {{-- Card Data Diri --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-3">
                        {{-- Icon Default --}}
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-user-graduate text-primary fa-3x"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark">{{ $remaja->nama_lengkap }}</h4>
                    <p class="text-muted mb-2">{{ $remaja->sekolah ?? 'Belum ada data sekolah' }} {{ $remaja->kelas ? '- Kelas ' . $remaja->kelas : '' }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                        <span class="badge bg-light text-dark border"><i class="fas fa-id-card me-1 text-secondary"></i> {{ $remaja->nik }}</span>
                        <span class="badge bg-light text-dark border"><i class="fas fa-venus-mars me-1 text-secondary"></i> {{ $remaja->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span class="badge bg-light text-dark border"><i class="fas fa-birthday-cake me-1 text-secondary"></i> {{ \Carbon\Carbon::parse($remaja->tanggal_lahir)->age }} Thn</span>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Kontak Orang Tua</small>
                        <div class="d-flex align-items-center mt-2">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-user-friends text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark">{{ $remaja->nama_ortu ?? '-' }}</h6>
                                <small class="text-muted">{{ $remaja->telepon_ortu ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Kesehatan Terakhir --}}
        <div class="col-md-8">
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-danger h-100">
                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">HEMOGLOBIN (HB)</small>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pemeriksaanTerakhir->hemoglobin ?? '-' }} <small class="fs-6 text-muted fw-normal">g/dL</small></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-primary h-100">
                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">TEKANAN DARAH</small>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pemeriksaanTerakhir->tekanan_darah ?? '-' }} <small class="fs-6 text-muted fw-normal">mmHg</small></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-success h-100">
                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">BERAT BADAN</small>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pemeriksaanTerakhir->berat_badan ?? '-' }} <small class="fs-6 text-muted fw-normal">kg</small></h4>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 border-info h-100">
                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">TINGGI BADAN</small>
                        <h4 class="mb-0 fw-bold text-dark">{{ $pemeriksaanTerakhir->tinggi_badan ?? '-' }} <small class="fs-6 text-muted fw-normal">cm</small></h4>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-notes-medical text-primary me-2"></i>Riwayat Check-up Terakhir</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Keluhan</th>
                                    <th>Diagnosa</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPemeriksaan as $row)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">
                                        {{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d M Y') }}
                                    </td>
                                    <td>{{ Str::limit($row->keluhan, 30) ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                            {{ $row->diagnosa ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($row->tindakan, 30) ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fa-2x mb-2 opacity-25"></i>
                                        <p class="mb-0">Belum ada riwayat pemeriksaan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection