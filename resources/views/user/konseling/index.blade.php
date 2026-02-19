@extends('layouts.user')

@section('title', 'Ruang Konseling')
@section('page-title', 'Konsultasi Kesehatan')

@section('content')
<div class="container-fluid animate-fade-in">
    {{-- Header --}}
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
            <i class="fas fa-comments text-primary fa-2x"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Ruang Konseling {{ $kategori ? "($kategori)" : "" }}</h5>
            <small class="text-muted">Riwayat konsultasi & saran kesehatan dari Bidan/Kader</small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if($profil)
                @forelse($riwayatKonseling as $konsel)
                <div class="card border-0 shadow-sm mb-3 hover-shadow transition-all">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-2">
                                    {{ $konsel->topik ?? 'Konsultasi Umum' }}
                                </span>
                                <h6 class="fw-bold text-dark mb-1">
                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($konsel->tanggal_konseling)->format('d F Y') }}
                                </h6>
                            </div>
                        </div>

                        <div class="row g-3">
                            {{-- Keluhan --}}
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded h-100">
                                    <small class="fw-bold d-block text-secondary mb-1">
                                        <i class="fas fa-user-injured me-1"></i> Keluhan Anda:
                                    </small>
                                    <p class="mb-0 text-dark">{{ $konsel->keluhan }}</p>
                                </div>
                            </div>
                            
                            {{-- Saran (Highlighted) --}}
                            <div class="col-md-6">
                                <div class="bg-success bg-opacity-10 p-3 rounded border border-success h-100">
                                    <small class="fw-bold d-block text-success mb-1">
                                        <i class="fas fa-user-md me-1"></i> Saran Petugas:
                                    </small>
                                    <p class="mb-0 text-dark fw-medium">{{ $konsel->saran }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="far fa-comments fa-4x text-muted opacity-25"></i>
                    </div>
                    <h6 class="fw-bold text-muted">Belum ada riwayat konseling.</h6>
                    <p class="small text-muted">Konsultasi akan muncul di sini setelah Anda melakukan sesi dengan Bidan/Kader.</p>
                </div>
                @endforelse
            @else
                {{-- Jika User tidak terdeteksi sebagai Remaja/Lansia --}}
                <div class="alert alert-warning border-0 shadow-sm">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Data profil kesehatan tidak ditemukan. Silakan hubungi Kader untuk menghubungkan NIK Anda.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transform: translateY(-2px); }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection