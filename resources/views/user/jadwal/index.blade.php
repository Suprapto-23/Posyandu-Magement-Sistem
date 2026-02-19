@extends('layouts.user')

@section('title', 'Jadwal Posyandu')
@section('page-title', 'Kalender Kegiatan')

@section('content')
<div class="container-fluid animate-fade-in">
    {{-- Header Section --}}
    <div class="d-flex align-items-center mb-4">
        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
            <i class="far fa-calendar-check text-success fa-2x"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Jadwal Kegiatan</h5>
            <small class="text-muted">Agenda kegiatan posyandu mendatang</small>
        </div>
    </div>

    {{-- Grid Jadwal --}}
    <div class="row g-4">
        @forelse($jadwalKegiatan as $jadwal)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-card overflow-hidden">
                
                {{-- Logika Warna Strip & Status Hari Ini --}}
                @php
                    $stripColor = match($jadwal->target_peserta) {
                        'balita' => 'bg-info',     // Biru untuk Balita
                        'lansia' => 'bg-warning',  // Kuning untuk Lansia
                        'remaja' => 'bg-primary',  // Biru Tua untuk Remaja
                        default => 'bg-success'    // Hijau untuk Umum
                    };
                    $isToday = \Carbon\Carbon::parse($jadwal->tanggal)->isToday();
                @endphp
                
                {{-- Garis Indikator Warna di Atas Kartu --}}
                <div class="{{ $stripColor }}" style="height: 5px;"></div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        {{-- Kotak Tanggal --}}
                        <div class="bg-light border rounded px-3 py-2 text-center" style="min-width: 60px;">
                            <span class="d-block h4 mb-0 fw-bold {{ $isToday ? 'text-danger' : 'text-dark' }}">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}
                            </span>
                            <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem;">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('M') }}
                            </small>
                        </div>

                        {{-- Badges Status & Target --}}
                        <div class="text-end">
                            @if($isToday)
                                <span class="badge bg-danger animate-pulse mb-1">HARI INI</span><br>
                            @endif
                            <span class="badge bg-white text-secondary border">{{ ucfirst($jadwal->target_peserta) }}</span>
                        </div>
                    </div>
                    
                    {{-- Judul & Deskripsi --}}
                    <h5 class="fw-bold text-dark mb-2 line-clamp-2" title="{{ $jadwal->judul }}">{{ $jadwal->judul }}</h5>
                    <p class="text-muted small mb-3">{{ Str::limit($jadwal->deskripsi, 80) }}</p>
                    
                    <hr class="border-light">

                    {{-- Detail Info Icon --}}
                    <div class="d-flex flex-column gap-2 text-secondary small">
                        <div class="d-flex align-items-center">
                            <i class="far fa-clock me-3 text-center" style="width: 20px;"></i> 
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-3 text-center" style="width: 20px;"></i> 
                            {{ $jadwal->lokasi }}
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag me-3 text-center" style="width: 20px;"></i> 
                            Kategori: {{ ucfirst($jadwal->kategori) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Tampilan Kosong --}}
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="far fa-calendar-times fa-4x text-muted opacity-25"></i>
            </div>
            <h6 class="fw-bold text-muted">Belum ada jadwal kegiatan.</h6>
            <p class="small text-muted">Silakan cek kembali nanti untuk informasi terbaru.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Link --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $jadwalKegiatan->links() }}
    </div>
</div>

{{-- CSS Tambahan Langsung di Halaman Ini --}}
<style>
    .hover-card { 
        transition: transform 0.2s ease, box-shadow 0.2s ease; 
    }
    .hover-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; 
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    .animate-pulse { 
        animation: pulse 1.5s infinite; 
    }
</style>
@endsection