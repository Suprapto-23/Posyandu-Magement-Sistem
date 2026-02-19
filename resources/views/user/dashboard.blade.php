@extends('layouts.user')

@section('title', 'Dashboard - SIM Posyandu')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid animate-fade-in">

    {{-- ALERT ERROR JIKA NIK KOSONG --}}
    @if(isset($pesanError) && $pesanError)
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
        <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-circle me-3">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Masalah Data Akun</h6>
            <p class="mb-0 small text-muted">{{ $pesanError }}</p>
        </div>
    </div>
    @endif

    {{-- WELCOME CARD --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 bottom-0 bg-primary" style="width: 6px;"></div>
                
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8 position-relative z-1">
                            <h2 class="fw-bold text-dark mb-2">Halo, {{ $user->name }}! 👋</h2>
                            <p class="text-secondary mb-0" style="font-size: 1.05rem;">
                                @if(in_array('orang_tua', $peranUser))
                                    Selamat datang di <strong>Panel Orang Tua</strong>. Pantau tumbuh kembang si kecil di sini.
                                @elseif(in_array('remaja', $peranUser))
                                    Selamat datang di <strong>Panel Remaja</strong>. Jaga kesehatanmu untuk masa depan.
                                @elseif(in_array('lansia', $peranUser))
                                    Selamat datang di <strong>Panel Lansia</strong>. Sehat dan bahagia di usia emas.
                                @else
                                    Selamat datang di Sistem Informasi Posyandu Terpadu.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0 position-relative z-1">
                            <div class="d-inline-flex align-items-center bg-light px-4 py-2 rounded-pill border">
                                <i class="far fa-calendar-alt text-primary me-2"></i> 
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                            </div>
                        </div>
                        
                        {{-- Icon Background Dekoratif --}}
                        <div class="position-absolute top-50 end-0 translate-middle-y me-5 d-none d-md-block opacity-10" style="pointer-events: none;">
                            @if(in_array('orang_tua', $peranUser))
                                <i class="fas fa-baby-carriage text-primary" style="font-size: 8rem;"></i>
                            @elseif(in_array('remaja', $peranUser))
                                <i class="fas fa-user-graduate text-primary" style="font-size: 8rem;"></i>
                            @elseif(in_array('lansia', $peranUser))
                                <i class="fas fa-hands-helping text-warning" style="font-size: 8rem;"></i>
                            @else
                                <i class="fas fa-heartbeat text-danger" style="font-size: 8rem;"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- BAGIAN 1: PANEL KHUSUS ORANG TUA (DATA ANAK) --}}
    {{-- ========================================================== --}}
    @if(in_array('orang_tua', $peranUser))
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-baby text-primary me-2"></i>Data Buah Hati</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        @forelse($dataAnak as $anak)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 bg-light position-relative h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-3 text-primary fw-bold" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ substr($anak->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $anak->nama_lengkap }}</h6>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->age }} Thn 
                                            {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->diffInMonths(\Carbon\Carbon::now()) % 12 }} Bln
                                        </small>
                                    </div>
                                </div>
                                
                                @if($anak->pemeriksaan_terakhir)
                                <div class="row text-center g-2 mt-2">
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded-3 border h-100">
                                            <small class="d-block text-secondary" style="font-size: 0.7rem; font-weight: 600;">BERAT</small>
                                            <strong class="text-primary">{{ $anak->pemeriksaan_terakhir->berat_badan }}kg</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded-3 border h-100">
                                            <small class="d-block text-secondary" style="font-size: 0.7rem; font-weight: 600;">TINGGI</small>
                                            <strong class="text-info">{{ $anak->pemeriksaan_terakhir->tinggi_badan }}cm</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded-3 border h-100">
                                            <small class="d-block text-secondary" style="font-size: 0.7rem; font-weight: 600;">GIZI</small>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill" style="font-size: 0.7rem">
                                                {{ strtoupper($anak->pemeriksaan_terakhir->status_gizi ?? '-') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <div class="alert alert-warning py-2 mb-0 small border-0 bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-info-circle me-1"></i> Belum ada data periksa
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">Tidak ada data anak yang ditemukan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Grafik Hanya Muncul Jika Ada Data Anak --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-line text-info me-2"></i>Grafik Perkembangan</h5>
                </div>
                <div class="card-body">
                    @if(!empty($grafikData) && count($grafikData['labels']) > 0)
                        <div style="position: relative; height: 250px;">
                            <canvas id="grafikBalita"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted">Grafik pertumbuhan 1 tahun terakhir</small>
                            <div class="d-flex justify-content-center gap-3 mt-1 text-xs">
                                <span class="text-success"><i class="fas fa-circle me-1"></i>Berat (kg)</span>
                                <span class="text-info"><i class="fas fa-minus me-1"></i>Tinggi (cm)</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <div class="bg-light rounded-circle p-4 mb-3 d-inline-block">
                                <i class="fas fa-chart-area fa-2x text-secondary opacity-50"></i>
                            </div>
                            <p class="mb-0">Data grafik belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- BAGIAN 2: PANEL KHUSUS REMAJA --}}
    {{-- ========================================================== --}}
    @if(in_array('remaja', $peranUser))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-user-graduate text-primary me-2"></i>Data Kesehatan Remaja</h5>
                </div>
                <div class="card-body">
                    @if($dataRemaja)
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0 border-end">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                                <i class="fas fa-user text-primary fa-3x"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $dataRemaja->nama_lengkap }}</h5>
                            <span class="badge bg-light text-dark border">{{ $dataRemaja->sekolah ?? '-' }}</span>
                        </div>
                        <div class="col-md-9 ps-md-4">
                            @if($dataRemaja->pemeriksaan_terakhir)
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-light rounded-3 border text-center">
                                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">BERAT</small>
                                        <span class="h5 fw-bold text-dark mb-0">{{ $dataRemaja->pemeriksaan_terakhir->berat_badan }} kg</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-light rounded-3 border text-center">
                                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">TINGGI</small>
                                        <span class="h5 fw-bold text-dark mb-0">{{ $dataRemaja->pemeriksaan_terakhir->tinggi_badan }} cm</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-light rounded-3 border text-center">
                                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">TENSI</small>
                                        <span class="h5 fw-bold text-danger mb-0">{{ $dataRemaja->pemeriksaan_terakhir->tekanan_darah ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-light rounded-3 border text-center">
                                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem;">HB</small>
                                        <span class="h5 fw-bold text-primary mb-0">{{ $dataRemaja->pemeriksaan_terakhir->hemoglobin ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada riwayat pemeriksaan.
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                        <div class="alert alert-light text-center">Data remaja tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- BAGIAN 3: PANEL KHUSUS LANSIA --}}
    {{-- ========================================================== --}}
    @if(in_array('lansia', $peranUser))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-blind text-warning me-2"></i>Data Kesehatan Lansia</h5>
                </div>
                <div class="card-body">
                    @if($dataLansia)
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="text-center me-md-5 mb-3 mb-md-0">
                            <div class="position-relative d-inline-block">
                                <i class="fas fa-user-circle text-secondary opacity-25" style="font-size: 5rem;"></i>
                                <span class="position-absolute bottom-0 end-0 bg-warning text-white rounded-circle p-1 fs-6 border border-white">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold mt-2 text-dark">{{ $dataLansia->nama_lengkap }}</h5>
                        </div>
                        
                        <div class="flex-grow-1 w-100">
                            @if($dataLansia->pemeriksaan_terakhir)
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-3 p-3 h-100 border-start border-4 border-danger bg-white">
                                            <div class="text-muted small fw-bold mb-1">TENSI</div>
                                            <h4 class="mb-0 fw-bold text-dark">{{ $dataLansia->pemeriksaan_terakhir->tekanan_darah ?? '-' }}</h4>
                                            <small class="text-danger">mmHg</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-3 p-3 h-100 border-start border-4 border-warning bg-white">
                                            <div class="text-muted small fw-bold mb-1">GULA DARAH</div>
                                            <h4 class="mb-0 fw-bold text-dark">{{ $dataLansia->pemeriksaan_terakhir->gula_darah ?? '-' }}</h4>
                                            <small class="text-warning">mg/dL</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-3 p-3 h-100 border-start border-4 border-success bg-white">
                                            <div class="text-muted small fw-bold mb-1">ASAM URAT</div>
                                            <h4 class="mb-0 fw-bold text-dark">{{ $dataLansia->pemeriksaan_terakhir->asam_urat ?? '-' }}</h4>
                                            <small class="text-success">mg/dL</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-3 p-3 h-100 border-start border-4 border-primary bg-white">
                                            <div class="text-muted small fw-bold mb-1">KOLESTEROL</div>
                                            <h4 class="mb-0 fw-bold text-dark">{{ $dataLansia->pemeriksaan_terakhir->kolesterol ?? '-' }}</h4>
                                            <small class="text-primary">mg/dL</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-light border border-secondary border-opacity-25 text-center text-muted mb-0">
                                    Belum ada data pemeriksaan kesehatan terakhir.
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                        <div class="alert alert-light text-center">Data lansia tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================== --}}
    {{-- BAGIAN UMUM: JADWAL DAN NOTIFIKASI --}}
    {{-- ========================================================== --}}
    <div class="row g-4">
        {{-- Jadwal Posyandu --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-alt text-success me-2"></i>Jadwal Posyandu</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($jadwalTerdekat as $jadwal)
                        <li class="list-group-item px-4 py-3 border-light">
                            <div class="d-flex align-items-start">
                                <div class="me-3 text-center">
                                    <div class="bg-success text-white rounded-3 py-1 px-2 shadow-sm" style="min-width: 55px;">
                                        <span class="d-block fw-bold h4 mb-0">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                                        <small class="d-block text-uppercase fw-bold" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('M') }}</small>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">{{ $jadwal->judul }}</h6>
                                    <small class="text-muted d-block mb-1">
                                        <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB
                                    </small>
                                    <span class="badge bg-light text-dark border">{{ $jadwal->lokasi }}</span>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item px-4 py-5 text-center">
                            <div class="mb-2"><i class="far fa-calendar-times fa-2x text-muted opacity-25"></i></div>
                            <small class="text-muted">Tidak ada jadwal kegiatan dalam waktu dekat.</small>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Notifikasi Widget --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bell text-warning me-2"></i>Notifikasi</h5>
                    <span id="notif-badge-count" class="badge bg-danger rounded-pill {{ $totalNotifikasiBelumDibaca > 0 ? '' : 'd-none' }}">
                        {{ $totalNotifikasiBelumDibaca }} Baru
                    </span>
                </div>
                <div class="card-body p-0">
                    {{-- Container ID untuk AJAX update --}}
                    <div id="notification-container" class="list-group list-group-flush">
                        @forelse($notifikasiTerbaru as $notif)
                        <div class="list-group-item px-4 py-3 border-light {{ $notif->is_read ? '' : 'bg-warning bg-opacity-10' }}">
                            <div class="d-flex w-100 justify-content-between mb-1">
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                    @if(!$notif->is_read) 
                                        <span class="d-inline-block bg-danger rounded-circle me-2" style="width: 8px; height: 8px;"></span> 
                                    @endif
                                    {{ $notif->judul }}
                                </h6>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 small text-secondary" style="line-height: 1.4;">{{ Str::limit($notif->pesan, 80) }}</p>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="mb-2"><i class="far fa-bell-slash fa-2x text-muted opacity-25"></i></div>
                            <small class="text-muted">Belum ada notifikasi baru.</small>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT DUAL AXIS CHART (BERAT & TINGGI) --}}
@if(in_array('orang_tua', $peranUser) && !empty($grafikData))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('grafikBalita');
        if(ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($grafikData['labels']),
                    datasets: [
                        {
                            label: 'Berat Badan (kg)',
                            data: @json($grafikData['berat']),
                            borderColor: '#06D6A0', // Warna Hijau
                            backgroundColor: 'rgba(6, 214, 160, 0.1)',
                            borderWidth: 2,
                            yAxisID: 'y', // Axis Kiri
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#06D6A0'
                        },
                        {
                            label: 'Tinggi Badan (cm)',
                            data: @json($grafikData['tinggi']),
                            borderColor: '#118AB2', // Warna Biru
                            backgroundColor: 'rgba(17, 138, 178, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5], // Garis putus-putus
                            yAxisID: 'y1', // Axis Kanan
                            tension: 0.4,
                            fill: false,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#118AB2'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: { 
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y + (context.datasetIndex === 0 ? ' kg' : ' cm');
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Berat (kg)' },
                            grid: { borderDash: [2, 2], drawBorder: false }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'Tinggi (cm)' },
                            grid: { drawOnChartArea: false } // Grid kanan disembunyikan agar bersih
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush
@endif

{{-- SCRIPT AUTO-UPDATE NOTIFIKASI (AJAX Polling) --}}
@push('scripts')
<script>
    function updateNotifications() {
        // Panggil endpoint yang sudah dibuat di controller
        fetch("{{ route('user.notifikasi.latest') }}")
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // 1. Update Badge Count
                    const badge = document.getElementById('notif-badge-count');
                    if(badge) {
                        if(data.unread_count > 0) {
                            badge.classList.remove('d-none');
                            badge.innerText = data.unread_count + ' Baru';
                        } else {
                            badge.classList.add('d-none');
                        }
                    }

                    // 2. Update List Notifikasi
                    const container = document.getElementById('notification-container');
                    if(container && data.notifikasi.length > 0) {
                        let html = '';
                        data.notifikasi.forEach(n => {
                            const bg = n.is_read == 0 ? 'bg-warning bg-opacity-10' : '';
                            const dot = n.is_read == 0 ? '<span class="d-inline-block bg-danger rounded-circle me-2" style="width: 8px; height: 8px;"></span>' : '';
                            
                            html += `
                                <div class="list-group-item px-4 py-3 border-light ${bg}">
                                    <div class="d-flex w-100 justify-content-between mb-1">
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">${dot} ${n.judul}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">${n.waktu}</small>
                                    </div>
                                    <p class="mb-0 small text-secondary" style="line-height: 1.4;">${n.pesan}</p>
                                </div>`;
                        });
                        container.innerHTML = html;
                    }
                }
            })
            .catch(err => console.error('Gagal update notifikasi:', err));
    }
    
    // Update setiap 10 detik agar terasa realtime
    setInterval(updateNotifications, 10000);
</script>
@endpush

@endsection