@extends('layouts.bidan')

@section('title', 'Dashboard Bidan')
@section('page-title', 'Dashboard Monitoring')
@section('page-subtitle', 'Pantau kesehatan warga dan validasi data pemeriksaan')

@section('content')
<div class="container-fluid animate-fade-in">

    {{-- KARTU STATISTIK --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase text-muted fw-bold mb-2 small">Risiko Stunting</div>
                            <h2 class="mb-0 fw-bold text-danger">{{ $balitaStunting ?? 0 }}</h2>
                            <small class="text-muted">Kasus Balita</small>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                            <i class="fas fa-child fa-2x"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-danger" style="width: {{ $totalBalita > 0 ? ($balitaStunting/$totalBalita)*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase text-muted fw-bold mb-2 small">Lansia Hipertensi</div>
                            <h2 class="mb-0 fw-bold text-warning">{{ $lansiaHipertensi ?? 0 }}</h2>
                            <small class="text-muted">Kasus Lansia</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                            <i class="fas fa-heartbeat fa-2x"></i>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 5px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalLansia > 0 ? ($lansiaHipertensi/$totalLansia)*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase fw-bold mb-2 small opacity-75">Perlu Validasi</div>
                            <h2 class="mb-0 fw-bold">{{ $jumlahBelumValidasi ?? 0 }}</h2>
                            <small class="opacity-75">Inputan Kader</small>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                    </div>
                    <a href="#antrian-section" class="btn btn-sm btn-light text-primary mt-3 w-100 fw-bold rounded-pill">
                        Periksa Sekarang <i class="fas fa-arrow-down ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-uppercase text-muted fw-bold mb-2 small">Agenda Berikutnya</div>
                            @if($jadwalBerikutnya)
                                <h4 class="mb-0 fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($jadwalBerikutnya->tanggal)->format('d M Y') }}
                                </h4>
                                <small class="text-muted">{{ Str::limit($jadwalBerikutnya->judul, 15) }}</small>
                            @else
                                <h5 class="mb-0 fw-bold text-muted">Belum Ada</h5>
                                <small class="text-muted">Buat jadwal baru</small>
                            @endif
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="{{ route('bidan.jadwal.index') }}" class="text-decoration-none small fw-bold">
                            Kelola Jadwal <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Status Gizi Balita
                    </h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartGizi" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Tren Kunjungan (6 Bulan)
                    </h6>
                    <span class="badge bg-light text-dark border">Update Realtime</span>
                </div>
                <div class="card-body">
                    <canvas id="chartKunjungan" style="max-height: 250px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ANTRIAN VALIDASI --}}
    <div class="row mb-4" id="antrian-section">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1 text-primary">
                            <i class="fas fa-user-md me-2"></i>Antrian Validasi Data
                        </h5>
                        <p class="text-muted mb-0 small">Data fisik yang diinput Kader, menunggu diagnosa Bidan.</p>
                    </div>
                    @if(count($antrianPemeriksaan) > 0)
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger pulse-animation">
                                {{ count($antrianPemeriksaan) }} Perlu Dicek
                            </span>
                            <a href="{{ route('bidan.pemeriksaan.index') }}?status=pending"
                               class="btn btn-sm btn-outline-primary rounded-pill">
                                Lihat Semua
                            </a>
                        </div>
                    @else
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>Semua Beres
                        </span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase small text-muted">
                                <tr>
                                    <th class="ps-4">Waktu</th>
                                    <th>Nama Pasien</th>
                                    <th>Kategori</th>
                                    <th>Hasil Ukur (Kader)</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($antrianPemeriksaan as $item)
                                <tr>
                                    <td class="ps-4 text-muted small">
                                        {{ $item->created_at->format('H:i') }}<br>
                                        {{ $item->created_at->diffForHumans() }}
                                    </td>
                                    <td class="fw-bold text-dark">{{ $item->nama_pasien }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ ucfirst($item->kategori_pasien) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            BB: <strong>{{ $item->berat_badan }} kg</strong>
                                            | TB: <strong>{{ $item->tinggi_badan }} cm</strong>
                                            @if($item->tekanan_darah)
                                                <br>TD: <span class="text-danger">{{ $item->tekanan_darah }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        {{-- ✅ FIX: pakai route show bukan edit --}}
                                        <a href="{{ route('bidan.pemeriksaan.show', $item->id) }}"
                                           class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fas fa-stethoscope me-1"></i> Diagnosa
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x mb-3 d-block text-success opacity-50"></i>
                                        <p class="mb-0">Tidak ada antrian data. Kerja bagus!</p>
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

    {{-- MONITORING RISIKO TINGGI --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Monitoring Pasien Risiko Tinggi
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($pasienBerisiko) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Diagnosa / Masalah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pasienBerisiko as $resiko)
                                <tr>
                                    <td>{{ $resiko->tanggal_periksa->format('d/m/Y') }}</td>
                                    <td class="fw-bold">{{ $resiko->nama_pasien }}</td>
                                    <td>{{ ucfirst($resiko->kategori_pasien) }}</td>
                                    <td class="text-danger fw-bold">
                                        {{ $resiko->diagnosa ?? $resiko->status_gizi }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Tidak ada pasien dengan risiko tinggi yang tercatat baru-baru ini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const ctxGizi = document.getElementById('chartGizi');
    if (ctxGizi) {
        new Chart(ctxGizi, {
            type: 'doughnut',
            data: {
                labels: ['Normal', 'Kurang', 'Stunting', 'Obesitas'],
                datasets: [{
                    data: [
                        {{ $chartGizi['normal'] }},
                        {{ $chartGizi['kurang'] }},
                        {{ $chartGizi['stunting'] }},
                        {{ $chartGizi['lebih'] }}
                    ],
                    backgroundColor: ['#198754','#ffc107','#dc3545','#0dcaf0'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } }
            }
        });
    }

    const ctxKunjungan = document.getElementById('chartKunjungan');
    if (ctxKunjungan) {
        new Chart(ctxKunjungan, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelBulan) !!},
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: {!! json_encode($dataKunjungan) !!},
                    borderColor: '#5e72e4',
                    backgroundColor: 'rgba(94,114,228,0.1)',
                    tension: 0.4, fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#5e72e4',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5,5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.hover-lift { transition: transform 0.2s ease; }
.hover-lift:hover { transform: translateY(-4px); }
.pulse-animation { animation: pulse 2s infinite; }
@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(220,53,69,.6); }
    70%  { box-shadow: 0 0 0 10px rgba(220,53,69,0); }
    100% { box-shadow: 0 0 0 0 rgba(220,53,69,0); }
}
</style>
@endpush
@endsection