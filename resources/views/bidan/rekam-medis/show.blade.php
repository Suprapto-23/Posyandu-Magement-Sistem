@extends('layouts.bidan')

@section('title', 'Electronic Medical Record')
@section('page-name', 'Detail Rekam Medis')

@push('styles')
<style>
    /* Animasi Masuk Halus */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Scrollbar Modern */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Nexus Card Style */
    .nexus-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.03);
        position: relative;
        overflow: hidden;
    }

    /* List Item Timeline Premium */
    .nexus-timeline-row { 
        background: #ffffff; 
        border-radius: 16px; 
        border: 1px solid #f1f5f9; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nexus-timeline-row:hover { 
        transform: translateY(-2px); 
        border-color: #e0f2fe; 
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.1); 
    }
</style>
@endpush

@section('content')
@php
    $nama = $pasien->nama_lengkap ?? 'Pasien Anonim';
    $nik = $pasien->nik ?? '-';
    $tglLahir = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $umur = $tglLahir ? $tglLahir->age : 0;
    
    $themeColor = match($pasien_type) {
        'balita' => 'cyan',
        'ibu_hamil' => 'pink',
        'remaja' => 'indigo',
        default => 'emerald'
    };
@endphp

<div class="max-w-[1300px] mx-auto space-y-6 animate-slide-up pb-12">

    {{-- =================================================================
         1. NAVIGASI ATAS
         ================================================================= --}}
    <div class="flex items-center justify-between px-1">
        <a href="{{ route('bidan.rekam-medis.index', ['type' => $pasien_type]) }}" class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-400 hover:text-cyan-600 transition-colors uppercase tracking-widest group">
            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-cyan-200 group-hover:bg-cyan-50 transition-all">
                <i class="fas fa-arrow-left"></i>
            </div>
            Kembali ke Buku Induk
        </a>
    </div>

    {{-- =================================================================
         2. HEADER KARTU IDENTITAS (NEXUS CARD)
         ================================================================= --}}
    <div class="nexus-card">
        <div class="absolute right-0 top-0 w-64 h-64 bg-{{ $themeColor }}-50 rounded-full blur-3xl opacity-60 -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
        
        <div class="p-6 md:p-8 flex flex-col lg:flex-row items-center gap-6 md:gap-8 relative z-10">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-[24px] bg-gradient-to-br from-{{ $themeColor }}-400 to-{{ $themeColor }}-600 text-white flex items-center justify-center text-4xl md:text-5xl shadow-[0_10px_25px_rgba(0,0,0,0.1)] border-[4px] border-white shrink-0">
                <i class="fas fa-{{ $pasien_type == 'balita' ? 'baby' : ($pasien_type == 'ibu_hamil' ? 'female' : 'user') }}"></i>
            </div>
            
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-3 justify-center lg:justify-start">
                    <h1 class="text-2xl md:text-3xl font-black text-slate-800 font-poppins tracking-tight">{{ $nama }}</h1>
                    <span class="px-3 py-1 bg-{{ $themeColor }}-50 text-{{ $themeColor }}-600 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-{{ $themeColor }}-100 shrink-0">
                        {{ str_replace('_', ' ', $pasien_type) }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-x-6 gap-y-3 text-slate-600 text-[13px] font-semibold">
                    <span class="flex items-center gap-1.5"><i class="fas fa-fingerprint text-slate-400 text-[14px]"></i> NIK: {{ $nik }}</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-birthday-cake text-slate-400 text-[14px]"></i> {{ $tglLahir ? $tglLahir->translatedFormat('d M Y') : '-' }} ({{ $umur }} Tahun)</span>
                    <span class="flex items-center gap-1.5 w-full sm:w-auto"><i class="fas fa-map-marker-alt text-slate-400 text-[14px]"></i> {{ $pasien->alamat ?? 'Alamat tidak tercatat' }}</span>
                </div>
            </div>

            <div class="flex gap-3 shrink-0 mt-4 lg:mt-0 w-full lg:w-auto justify-center">
                <div class="bg-slate-900 text-white px-5 py-4 rounded-[20px] text-center min-w-[120px] shadow-lg flex flex-col justify-center">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Periksa</p>
                    <p class="text-2xl font-black">{{ $riwayatMedis->count() }} <span class="text-[11px] font-medium text-slate-400">Kali</span></p>
                </div>
                <div class="bg-cyan-50 border border-cyan-100 text-cyan-800 px-5 py-4 rounded-[20px] text-center min-w-[120px] shadow-sm flex flex-col justify-center">
                    <p class="text-[9px] font-bold text-cyan-500/80 uppercase tracking-widest mb-1">Imunisasi</p>
                    <p class="text-2xl font-black text-cyan-600">{{ $riwayatImunisasi->count() }} <span class="text-[11px] font-medium text-cyan-500/80">Log</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================
         3. ANALITIK: GRAFIK & LOG VAKSIN
         ================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-8 nexus-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[15px] font-bold text-slate-800 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-500"><i class="fas fa-chart-line"></i></div>
                    Grafik Pertumbuhan
                </h3>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">7 Sesi Terakhir</span>
            </div>
            
            @if($riwayatMedis->isEmpty())
                <div class="h-[300px] flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 rounded-[20px] border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-slate-100">
                        <i class="fas fa-chart-area text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-[13px] font-bold text-slate-600">Grafik belum tersedia.</p>
                    <p class="text-[11px] font-medium">Lakukan pemeriksaan medis (Meja 5) terlebih dahulu.</p>
                </div>
            @else
                <div class="h-[300px] relative w-full">
                    <canvas id="emrChart"></canvas>
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 nexus-card p-6 flex flex-col">
            <h3 class="text-[15px] font-bold text-slate-800 mb-5 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-500"><i class="fas fa-syringe"></i></div>
                Riwayat Imunisasi
            </h3>
            
            <div class="space-y-3 flex-1 overflow-y-auto pr-2 custom-scrollbar max-h-[300px]">
                @forelse($riwayatImunisasi as $imu)
                <div class="p-3.5 bg-slate-50 rounded-[14px] border border-slate-100 flex justify-between items-center group hover:bg-white hover:border-teal-200 hover:shadow-sm transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-teal-400 group-hover:scale-150 transition-transform"></div>
                        <div>
                            <p class="text-[13px] font-bold text-slate-800 tracking-tight">{{ $imu->vaksin }}</p>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($imu->tanggal_imunisasi)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black px-2.5 py-1 bg-white border border-slate-200 text-teal-600 rounded-lg uppercase shadow-sm shrink-0">{{ $imu->dosis }}</span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-10">
                    <i class="fas fa-shield-virus text-4xl mb-3 text-slate-200"></i>
                    <p class="text-[12px] font-bold text-slate-400">Belum ada riwayat vaksinasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- =================================================================
         4. TIMELINE PEMERIKSAAN (1 FLEXBOX SEAMLESS GRID)
         ================================================================= --}}
    <div class="nexus-card">
        <div class="p-5 md:p-6 border-b border-slate-100 bg-white flex items-center justify-between">
            <h3 class="text-[16px] font-bold text-slate-800 font-poppins">Timeline Pemeriksaan Klinis</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1.5 rounded-lg">Diurutkan Terbaru</span>
        </div>
        
        <div class="p-5 md:p-6 bg-slate-50/30">
            <div class="flex flex-col gap-3">
                @forelse($riwayatMedis as $med)
                
                <div class="nexus-timeline-row p-4 md:p-5 flex flex-col lg:flex-row lg:items-center w-full group relative overflow-hidden gap-4 lg:gap-0">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-200 group-hover:bg-cyan-500 transition-colors duration-300"></div>

                    <div class="w-full lg:w-[20%] shrink-0 pl-3">
                        <p class="text-[13px] font-bold text-slate-800">{{ \Carbon\Carbon::parse($med->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                        <p class="text-[11px] font-medium text-slate-500 font-mono mt-0.5">EMR: #{{ $med->id }}</p>
                    </div>

                    <div class="h-px w-full bg-slate-100 block lg:hidden"></div>

                    <div class="w-full lg:w-[30%] flex flex-wrap items-center gap-4 lg:gap-6 lg:border-l lg:border-slate-100 lg:pl-6">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Berat</span>
                            <span class="text-[13px] font-semibold text-slate-700 flex items-center gap-1"><i class="fas fa-weight w-3 text-slate-300"></i> {{ $med->berat_badan ?? 0 }} <span class="text-[10px]">kg</span></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tinggi</span>
                            <span class="text-[13px] font-semibold text-slate-700 flex items-center gap-1"><i class="fas fa-ruler-vertical w-3 text-slate-300"></i> {{ $med->tinggi_badan ?? 0 }} <span class="text-[10px]">cm</span></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">IMT</span>
                            <span class="text-[13px] font-bold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded">{{ $med->imt ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="h-px w-full bg-slate-100 block lg:hidden"></div>

                    <div class="w-full lg:w-[30%] flex flex-col gap-0.5 lg:border-l lg:border-slate-100 lg:pl-6 pr-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diagnosa Klinis</span>
                        <p class="text-[13px] font-bold text-slate-800 truncate" title="{{ $med->diagnosa ?: 'Tidak ada diagnosa spesifik' }}">
                            {{ $med->diagnosa ?: 'Tidak ada diagnosa spesifik' }}
                        </p>
                        <p class="text-[11px] font-medium text-slate-500 truncate" title="{{ $med->tindakan ?: 'Hanya pemeriksaan rutin.' }}">
                            {{ $med->tindakan ?: 'Hanya pemeriksaan rutin.' }}
                        </p>
                    </div>

                    <div class="w-full lg:w-[20%] flex flex-row lg:flex-col items-center lg:items-end justify-between lg:justify-center shrink-0 lg:border-l lg:border-slate-100 lg:pl-6">
                        <div class="flex items-center gap-2 lg:hidden">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-[10px]"><i class="fas fa-check"></i></div>
                            <span class="text-[11px] font-bold text-slate-600">Verifikator</span>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <span class="text-[12px] font-bold text-slate-800">{{ Str::words($med->verifikator->name ?? 'Bidan Desa', 2, '') }}</span>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-1 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 flex items-center gap-1.5 shadow-sm">
                                <i class="fas fa-check-double"></i> Verified
                            </span>
                        </div>
                    </div>

                </div>
                @empty
                <div class="py-16 text-center bg-white rounded-[16px] border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                    </div>
                    <h3 class="text-[15px] font-bold text-slate-800 mb-1">Belum Ada Rekam Medis</h3>
                    <p class="text-[13px] font-medium text-slate-500">Pasien ini belum memiliki riwayat pemeriksaan klinis di sistem.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = {!! json_encode($chartData ?? []) !!};
    
    if(chartData && chartData.length > 0) {
        const ctx = document.getElementById('emrChart').getContext('2d');
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal_periksa);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        const bbData = chartData.map(item => item.berat_badan || 0);
        const tbData = chartData.map(item => item.tinggi_badan || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Berat Badan (kg)',
                        data: bbData,
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.08)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#06b6d4',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Tinggi Badan (cm)',
                        data: tbData,
                        borderColor: '#94a3b8',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: '#94a3b8'
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
                    legend: {
                        position: 'bottom',
                        labels: { 
                            usePointStyle: true,
                            boxWidth: 8, 
                            padding: 20, 
                            font: { size: 11, weight: '600', family: "'Inter', sans-serif" },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 12, family: "'Inter', sans-serif" },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: false,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        border: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8', padding: 10 }
                    },
                    x: { 
                        grid: { display: false, drawBorder: false },
                        border: { display: false },
                        ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8', padding: 10 }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection