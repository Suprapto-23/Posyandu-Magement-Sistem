@extends('layouts.user')

@section('title', 'Detail Balita')
@section('page-title', 'Detail Perkembangan')

@section('content')
<div class="container-fluid animate-fade-in">
    <a href="{{ route('user.balita.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($balita->nama_lengkap, 0, 1) }}
                    </div>
                    <h5 class="fw-bold">{{ $balita->nama_lengkap }}</h5>
                    <span class="badge bg-light text-dark border">{{ $balita->kode_balita }}</span>
                    
                    <hr class="my-4">
                    
                    <div class="row text-start g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Tgl Lahir</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->format('d M Y') }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Usia</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->age }} Tahun</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Nama Ibu</small>
                            <span class="fw-medium">{{ $balita->nama_ibu }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Riwayat Pemeriksaan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Berat</th>
                                    <th>Tinggi</th>
                                    <th>Status Gizi</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPemeriksaan as $periksa)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold d-block">{{ \Carbon\Carbon::parse($periksa->tanggal_periksa)->format('d M Y') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($periksa->tanggal_periksa)->format('H:i') }} WIB</small>
                                    </td>
                                    <td>{{ $periksa->berat_badan }} kg</td>
                                    <td>{{ $periksa->tinggi_badan }} cm</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">
                                            {{ strtoupper($periksa->status_gizi ?? '-') }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($periksa->tindakan ?? '-', 30) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pemeriksaan.</td>
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