@extends('layouts.user')

@section('title', 'Data Balita')
@section('page-title', 'Data Balita')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-dark mb-0">Daftar Buah Hati</h5>
    </div>

    <div class="row g-4">
        @forelse($dataBalita as $balita)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 position-relative d-inline-block">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                            {{ substr($balita->nama_lengkap, 0, 1) }}
                        </div>
                        <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 border">
                            <i class="fas {{ $balita->jenis_kelamin == 'L' ? 'fa-mars text-primary' : 'fa-venus text-danger' }}"></i>
                        </span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $balita->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">
                        {{ \Carbon\Carbon::parse($balita->tanggal_lahir)->translatedFormat('d F Y') }} 
                        ({{ \Carbon\Carbon::parse($balita->tanggal_lahir)->age }} Thn)
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('user.balita.show', $balita->id) }}" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-file-medical me-2"></i>Lihat Detail & Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light border text-center py-5">
                <i class="fas fa-baby fa-3x text-muted mb-3 opacity-50"></i>
                <p class="mb-0 text-muted">Belum ada data balita yang terhubung dengan akun Anda.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection