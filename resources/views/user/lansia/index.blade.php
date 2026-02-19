@extends('layouts.user')

@section('title', 'Data Lansia')
@section('page-title', 'Kesehatan Lansia')

@section('content')
<div class="container-fluid animate-fade-in">
    @if($lansia)
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-user-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $lansia->nama_lengkap }}</h4>
                            <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i> {{ $lansia->alamat }}</p>
                        </div>
                    </div>
                    <div class="text-end d-none d-md-block">
                        <span class="display-6 fw-bold text-dark">{{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }}</span>
                        <span class="d-block text-muted small">Tahun</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Riwayat Pemeriksaan Rutin</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Tanggal</th>
                                    <th>Tekanan Darah</th>
                                    <th>Gula Darah</th>
                                    <th>Kolesterol</th>
                                    <th>Asam Urat</th>
                                    <th>Keluhan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatPemeriksaan as $item)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ \Carbon\Carbon::parse($item->tanggal_periksa)->format('d M Y') }}</td>
                                    <td class="text-danger fw-bold">{{ $item->tekanan_darah }}</td>
                                    <td>{{ $item->gula_darah }}</td>
                                    <td>{{ $item->kolesterol }}</td>
                                    <td>{{ $item->asam_urat }}</td>
                                    <td class="text-muted small">{{ Str::limit($item->keluhan, 40) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada data pemeriksaan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning">Data Lansia tidak ditemukan.</div>
    @endif
</div>
@endsection