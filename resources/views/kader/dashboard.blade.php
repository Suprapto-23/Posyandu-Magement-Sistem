{{-- PATH: resources/views/kader/dashboard.blade.php --}}
@extends('layouts.kader')
@section('title','Dashboard Kader')
@section('page-name','Dashboard')

@section('content')
<style>
/* ── DASHBOARD PREMIUM STYLES ── */
@keyframes pulseRing { 
    0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4) } 
    50% { box-shadow: 0 0 0 6px rgba(255, 255, 255, 0) } 
}
.animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes slideUpFade { 
    from { opacity: 0; transform: translateY(20px); } 
    to { opacity: 1; transform: translateY(0); } 
}

/* HERO - Tema Indigo Premium */
.hero { 
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); 
    border-radius: 32px; 
    padding: 40px 48px; 
    margin-bottom: 32px; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    gap: 24px; 
    position: relative; 
    overflow: hidden; 
    box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.5); 
}
.hero::before { 
    content: ''; 
    position: absolute; 
    inset: 0; 
    background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px); 
    background-size: 24px 24px; 
    opacity: 0.5; 
    pointer-events: none; 
}
.hero-glow-1 { position: absolute; top: -100px; right: 100px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.4) 0%, transparent 70%); pointer-events: none; }
.hero-glow-2 { position: absolute; bottom: -80px; right: -50px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(165, 180, 252, 0.2) 0%, transparent 70%); pointer-events: none; }

.hero-txt { position: relative; z-index: 1; }
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; font-size: 11px; font-weight: 800; padding: 6px 14px; border-radius: 50px; margin-bottom: 16px; letter-spacing: 1px; text-transform: uppercase; }
.hero-badge .pulse { width: 6px; height: 6px; border-radius: 50%; background: #fff; animation: pulseRing 2s infinite; }
.hero-title { font-size: 36px; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 12px; letter-spacing: -0.5px; font-family: 'Poppins', sans-serif; }
.hero-title span { color: #a5b4fc; font-weight: 800; }
.hero-desc { font-size: 14.5px; color: rgba(255, 255, 255, 0.85); margin-bottom: 24px; max-width: 480px; font-weight: 500; line-height: 1.6; }

/* STATS CARDS */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
.stat-card { background: #fff; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 24px; padding: 24px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02); transition: all 0.3s ease; display: flex; flex-direction: column; justify-content: space-between; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.06); border-color: #cbd5e1; }
.stat-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; }
.stat-value { font-size: 36px; font-weight: 900; color: #0f172a; line-height: 1; letter-spacing: -1px; font-family: 'Poppins', sans-serif; }
.stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; }

/* RESPONSIVE */
@media(max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 900px) { 
    .hero { padding: 32px 24px; border-radius: 24px; } 
    .hero-vis { display: none; } 
    .hero-title { font-size: 28px; } 
}
@media(max-width: 580px) { 
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 16px; } 
    .hero { padding: 24px 20px; } 
    .hero-title { font-size: 24px; } 
    .stat-value { font-size: 28px; } 
    .stat-card { padding: 20px; }
}
</style>

<div class="animate-slide-up">
    
    <div class="hero">
        <div class="hero-glow-1"></div><div class="hero-glow-2"></div>
        <div class="hero-txt">
            <div class="hero-badge"><span class="pulse"></span> Dasbor Aktif</div>
            <h1 class="hero-title">Selamat Datang,<br><span>Kader {{ Str::words(Auth::user()->profile->full_name ?? 'Hebat', 2, '') }}!</span> 👋</h1>
            <p class="hero-desc">Pantau rekapitulasi data warga, jadwal kegiatan, dan grafik kesehatan posyandu Anda dengan mudah dan cepat.</p>
            
            <div class="flex flex-wrap items-center gap-3 mt-4">
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white text-[13px] font-bold">
                    <i class="fas fa-calendar-day text-indigo-200"></i> {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white text-[13px] font-bold">
                    <i class="fas fa-users text-emerald-300"></i> {{ ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0) }} Warga Terdaftar
                </div>
            </div>
        </div>
        <div class="hero-vis hidden lg:flex items-center justify-center w-48 h-48 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2.5rem] shadow-2xl relative z-10 rotate-3 hover:rotate-0 transition-all duration-500">
            <i class="fas fa-clinic-medical text-[70px] text-white drop-shadow-lg"></i>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card group">
            <p class="stat-label">Total Balita</p>
            <div class="flex items-end justify-between">
                <h3 class="stat-value">{{ $stats['total_balita'] ?? 0 }}</h3>
                <div class="stat-icon bg-rose-50 text-rose-500 border border-rose-100 group-hover:scale-110 transition-transform"><i class="fas fa-baby"></i></div>
            </div>
        </div>
        <div class="stat-card group">
            <p class="stat-label">Total Remaja</p>
            <div class="flex items-end justify-between">
                <h3 class="stat-value">{{ $stats['total_remaja'] ?? 0 }}</h3>
                <div class="stat-icon bg-sky-50 text-sky-500 border border-sky-100 group-hover:scale-110 transition-transform"><i class="fas fa-child"></i></div>
            </div>
        </div>
        <div class="stat-card group">
            <p class="stat-label">Total Lansia</p>
            <div class="flex items-end justify-between">
                <h3 class="stat-value">{{ $stats['total_lansia'] ?? 0 }}</h3>
                <div class="stat-icon bg-emerald-50 text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform"><i class="fas fa-user-clock"></i></div>
            </div>
        </div>
        <div class="stat-card group">
            <p class="stat-label">Jadwal Bulan Ini</p>
            <div class="flex items-end justify-between">
                <h3 class="stat-value">{{ $stats['jadwal_hari_ini'] ?? 0 }}</h3>
                <div class="stat-icon bg-indigo-50 text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
        
        <div class="lg:col-span-2 bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] p-6 md:p-8 flex flex-col">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <h3 class="text-lg font-black text-slate-800 font-poppins flex items-center gap-2">
                    <i class="fas fa-chart-area text-indigo-500"></i> Tren Kunjungan 7 Hari
                </h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('kader.kunjungan.index') }}" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[12px] font-bold rounded-xl transition-colors border border-slate-200 flex items-center gap-1.5">
                        <i class="fas fa-arrow-up-right-from-square"></i> Detail
                    </a>
                    <a href="{{ route('kader.laporan.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[12px] font-bold rounded-xl transition-all shadow-sm hover:shadow-md flex items-center gap-1.5">
                        <i class="fas fa-file-chart-pie"></i> Laporan
                    </a>
                </div>
            </div>
            
            <div class="relative w-full flex-1" style="min-h: 300px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] p-6 md:p-8 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 font-poppins flex items-center gap-2 mb-8">
                <i class="fas fa-chart-pie text-amber-500"></i> Demografi Warga
            </h3>
            
            @php $totalW = ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0); @endphp
            
            @if($totalW == 0)
                <div class="h-48 flex flex-col items-center justify-center text-slate-400 mb-6">
                    <i class="fas fa-users-slash text-4xl mb-3 opacity-50"></i>
                    <p class="font-medium text-[13px]">Belum ada warga terdaftar</p>
                </div>
            @else
                <div class="relative w-full aspect-square max-h-[220px] mx-auto mb-8">
                    <canvas id="donutChart"></canvas>
                </div>
            @endif

            <div class="flex flex-col gap-3 mt-auto">
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-rose-50/50 border border-rose-100 hover:bg-rose-50 transition-colors">
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-rose-500 shadow-sm"></span><span class="text-[13px] font-bold text-slate-700">Balita</span></div>
                    <span class="text-[15px] font-black text-slate-900 font-poppins">{{ $stats['total_balita'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-sky-50/50 border border-sky-100 hover:bg-sky-50 transition-colors">
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-sky-500 shadow-sm"></span><span class="text-[13px] font-bold text-slate-700">Remaja</span></div>
                    <span class="text-[15px] font-black text-slate-900 font-poppins">{{ $stats['total_remaja'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-amber-50/50 border border-amber-100 hover:bg-amber-50 transition-colors">
                    <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm"></span><span class="text-[13px] font-bold text-slate-700">Lansia</span></div>
                    <span class="text-[15px] font-black text-slate-900 font-poppins">{{ $stats['total_lansia'] ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. DATA DARI CONTROLLER UNTUK TREN 7 HARI
    // Variabel ini otomatis disuplai oleh DashboardController (Kader)
    const formattedDates = {!! json_encode($chartLabels ?? []) !!};
    const rawData = {!! json_encode($chartData ?? []) !!};

    // 2. INISIALISASI LINE CHART KUNJUNGAN
    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx = lc.getContext('2d');
        
        // Gradient Background di bawah garis agar premium
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)'); // Indigo-600 transparansi 25%
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)'); // Pudar ke bawah

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Kunjungan',
                    data: rawData,
                    borderColor: '#4f46e5', // Warna garis Indigo
                    backgroundColor: gradient,
                    borderWidth: 3.5,
                    fill: true,
                    tension: 0.4, // Kurva melengkung mulus (smooth)
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        padding: 14,
                        borderRadius: 12,
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 15, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        displayColors: false, // Menghilangkan kotak warna default
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Warga Dilayani';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        border: { display: false }, 
                        grid: { color: '#f1f5f9', drawBorder: false }, 
                        ticks: { 
                            stepSize: 1, // Angka harus bulat (1, 2, 3...)
                            color: '#94a3b8', 
                            font: { weight: '600', family: "'Plus Jakarta Sans', sans-serif" },
                            precision: 0
                        } 
                    },
                    x: { 
                        border: { display: false }, 
                        grid: { display: false }, 
                        ticks: { 
                            color: '#64748b', 
                            font: { weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" } 
                        } 
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });
    }

    // 3. INISIALISASI DONUT CHART DEMOGRAFI
    const dc = document.getElementById('donutChart');
    const balitaCount = {{ $stats['total_balita'] ?? 0 }};
    const remajaCount = {{ $stats['total_remaja'] ?? 0 }};
    const lansiaCount = {{ $stats['total_lansia'] ?? 0 }};
    const totalWarga = balitaCount + remajaCount + lansiaCount;

    if(dc && totalWarga > 0) {
        new Chart(dc, {
            type: 'doughnut',
            data: {
                labels: ['Balita', 'Remaja', 'Lansia'],
                datasets: [{
                    data: [balitaCount, remajaCount, lansiaCount],
                    backgroundColor: ['#f43f5e', '#0ea5e9', '#f59e0b'], // Rose, Sky, Amber
                    borderWidth: 0,
                    hoverOffset: 6,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '75%', // Memperlebar lubang donat (Estetika UI modern)
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: '#1e293b', 
                        padding: 14, 
                        borderRadius: 12,
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let percentage = Math.round((value / totalWarga) * 100) + '%';
                                return ` ${label}: ${value} Warga (${percentage})`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush