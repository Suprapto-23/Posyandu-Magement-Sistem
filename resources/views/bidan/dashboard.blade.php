@extends('layouts.bidan')

@section('title', 'Dashboard Klinis')
@section('page-name', 'Beranda Utama')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .stat-card { transition: all 0.3s ease; border: 2px solid transparent; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -5px rgba(6, 182, 212, 0.15); border-color: #cffafe; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto pb-10">

    {{-- 1. HERO GREETING BANNER --}}
    <div class="animate-slide-up bg-gradient-to-br from-cyan-600 via-cyan-700 to-blue-800 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_rgba(6,182,212,0.3)] border border-cyan-400 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -right-10 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-5 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none hidden lg:block transform rotate-12">
            <i class="fas fa-stethoscope text-[140px] text-white"></i>
        </div>

        <div class="relative z-10 flex items-center gap-6">
            <div class="w-20 h-20 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-inner">
                {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
            </div>
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-cyan-900/50 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2 backdrop-blur-md border border-cyan-500/30 text-white">
                    <i class="far fa-calendar-alt text-cyan-400"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-none mb-1 font-poppins">Selamat Bertugas, {{ Auth::user()->name ?? 'Bidan' }}!</h1>
                <p class="text-[13px] font-medium text-cyan-100 max-w-xl">Semoga hari ini berjalan lancar. Berikut adalah ringkasan aktivitas klinis dan jadwal Posyandu Anda hari ini.</p>
            </div>
        </div>

        <div class="relative z-10 hidden sm:block">
            <a href="{{ route('bidan.pemeriksaan.index') }}" class="smooth-route inline-flex items-center justify-center gap-2 px-6 py-4 bg-white text-cyan-700 font-black text-[12px] uppercase tracking-widest rounded-xl hover:bg-cyan-50 transition-all shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1">
                <i class="fas fa-bolt text-lg"></i> Validasi Antrian
            </a>
        </div>
    </div>

    {{-- 2. QUICK STATS CARDS --}}
    @php
        // Data Disesuaikan dengan Model Asli Anda
        $antrianPending = \App\Models\Pemeriksaan::where('status_verifikasi', 'pending')->count() ?? 0;
        $totalPasien = (\App\Models\Balita::count() ?? 0) + (\App\Models\IbuHamil::count() ?? 0) + (\App\Models\Remaja::count() ?? 0) + (\App\Models\Lansia::count() ?? 0);
        $totalImunisasi = \App\Models\Imunisasi::count() ?? 0;
        
        // MENGGUNAKAN SCOPE DARI MODEL JADWALPOSYANDU
        $jadwalAktif = \App\Models\JadwalPosyandu::aktif()->count() ?? 0;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 animate-slide-up-delay-1">
        
        {{-- Card 1: Antrian --}}
        <div class="stat-card bg-white rounded-[24px] p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl shadow-inner border border-rose-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-procedures"></i>
                </div>
                @if($antrianPending > 0)
                    <span class="flex h-3 w-3 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-white"></span></span>
                @endif
            </div>
            <div>
                <h3 class="text-[32px] font-black text-slate-800 font-poppins leading-none mb-1">{{ $antrianPending }}</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Antrian Menunggu</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-[70px] text-rose-500/5 group-hover:text-rose-500/10 transition-colors pointer-events-none"><i class="fas fa-procedures"></i></div>
        </div>

        {{-- Card 2: Total Warga --}}
        <div class="stat-card bg-white rounded-[24px] p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-xl shadow-inner border border-cyan-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div>
                <h3 class="text-[32px] font-black text-slate-800 font-poppins leading-none mb-1">{{ $totalPasien }}</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Warga Terdata</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-[70px] text-cyan-500/5 group-hover:text-cyan-500/10 transition-colors pointer-events-none"><i class="fas fa-users"></i></div>
        </div>

        {{-- Card 3: Imunisasi --}}
        <div class="stat-card bg-white rounded-[24px] p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-inner border border-emerald-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-syringe"></i>
                </div>
            </div>
            <div>
                <h3 class="text-[32px] font-black text-slate-800 font-poppins leading-none mb-1">{{ $totalImunisasi }}</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Injeksi Diberikan</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-[70px] text-emerald-500/5 group-hover:text-emerald-500/10 transition-colors pointer-events-none"><i class="fas fa-syringe"></i></div>
        </div>

        {{-- Card 4: Jadwal Aktif --}}
        <div class="stat-card bg-white rounded-[24px] p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl shadow-inner border border-indigo-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div>
                <h3 class="text-[32px] font-black text-slate-800 font-poppins leading-none mb-1">{{ $jadwalAktif }}</h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Agenda Aktif</p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-[70px] text-indigo-500/5 group-hover:text-indigo-500/10 transition-colors pointer-events-none"><i class="fas fa-calendar-check"></i></div>
        </div>

    </div>

    {{-- 3. CHARTS & UPCOMING SCHEDULES --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-slide-up-delay-2">
        
        {{-- Kiri: Grafik Demografi Warga --}}
        <div class="xl:col-span-2 bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-6 md:p-8 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-[16px] font-black text-slate-800 font-poppins flex items-center gap-2"><i class="fas fa-chart-pie text-cyan-500"></i> Demografi Warga Binaan</h3>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Distribusi pasien berdasarkan klaster usia sasaran Posyandu.</p>
                </div>
                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 border border-slate-100"><i class="fas fa-ellipsis-h"></i></div>
            </div>
            
            <div class="flex-1 w-full relative min-h-[300px]">
                <canvas id="demografiChart"></canvas>
            </div>
        </div>

        {{-- Kanan: Jadwal Terdekat --}}
        <div class="xl:col-span-1 bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-6 md:p-8 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[16px] font-black text-slate-800 font-poppins flex items-center gap-2"><i class="fas fa-calendar-day text-indigo-500"></i> Agenda Terdekat</h3>
            </div>
            
            @php
                // MENGGUNAKAN SCOPE DARI MODEL JADWALPOSYANDU
                $upcomingJadwals = \App\Models\JadwalPosyandu::aktif()->mendatang()->orderBy('tanggal', 'asc')->take(3)->get();
            @endphp

            <div class="flex-1 flex flex-col gap-4">
                @forelse($upcomingJadwals as $jadwal)
                    <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-100 hover:border-indigo-200 transition-colors group relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $jadwal->target_peserta == 'ibu_hamil' ? 'pink' : 'indigo' }}-500 rounded-l-2xl"></div>
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</p>
                            <span class="text-[8px] font-black px-2 py-0.5 rounded border shadow-sm {{ $jadwal->target_peserta == 'ibu_hamil' ? 'bg-pink-50 text-pink-600 border-pink-200' : 'bg-indigo-50 text-indigo-600 border-indigo-200' }}">
                                {{ $jadwal->label_target }}
                            </span>
                        </div>
                        <h4 class="text-[13px] font-bold text-slate-800 font-poppins mb-2 line-clamp-1">{{ $jadwal->judul }}</h4>
                        <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                            <span class="flex items-center gap-1 bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm"><i class="far fa-clock text-cyan-500"></i> {{ date('H:i', strtotime($jadwal->waktu_mulai)) }}</span>
                            <span class="flex items-center gap-1 bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm"><i class="fas fa-map-marker-alt text-rose-400"></i> {{ Str::limit($jadwal->lokasi, 15) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-2xl mb-3 border border-slate-100 shadow-inner"><i class="fas fa-calendar-times"></i></div>
                        <p class="text-[13px] font-bold text-slate-600">Tidak Ada Agenda</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-1">Belum ada jadwal aktif yang direncanakan ke depannya.</p>
                    </div>
                @endforelse
            </div>
            
            <a href="{{ route('bidan.jadwal.index') }}" class="smooth-route mt-6 w-full text-center py-3 bg-indigo-50 text-indigo-600 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300">
                Kelola Semua Jadwal
            </a>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi Chart.js saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('demografiChart');
        if(ctx) {
            // Mengambil data dari database
            const countBalita = {{ \App\Models\Balita::count() ?? 0 }};
            const countBumil = {{ \App\Models\IbuHamil::count() ?? 0 }};
            const countRemaja = {{ \App\Models\Remaja::count() ?? 0 }};
            const countLansia = {{ \App\Models\Lansia::count() ?? 0 }};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Anak & Balita', 'Ibu Hamil', 'Usia Remaja', 'Lansia (Manula)'],
                    datasets: [{
                        label: 'Total Terdata (Jiwa)',
                        data: [countBalita, countBumil, countRemaja, countLansia],
                        backgroundColor: [
                            'rgba(244, 63, 94, 0.8)',   // Rose untuk Balita
                            'rgba(236, 72, 153, 0.8)',  // Pink untuk Bumil
                            'rgba(99, 102, 241, 0.8)',  // Indigo untuk Remaja
                            'rgba(16, 185, 129, 0.8)'   // Emerald untuk Lansia
                        ],
                        borderColor: [
                            'rgb(244, 63, 94)',
                            'rgb(236, 72, 153)',
                            'rgb(99, 102, 241)',
                            'rgb(16, 185, 129)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { family: 'Poppins', size: 13 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.5)', borderDash: [5, 5] },
                            ticks: { font: { family: 'Inter', weight: 'bold' }, color: '#64748b' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', weight: 'bold' }, color: '#475569' }
                        }
                    },
                    animation: {
                        y: { duration: 2000, easing: 'easeOutElastic' }
                    }
                }
            });
        }
    });

    const showLoader = () => { document.getElementById('globalLoader').classList.replace('opacity-0', 'opacity-100'); };
    document.querySelectorAll('.smooth-route').forEach(l => l.addEventListener('click', (e) => { if(l.target !== '_blank' && !e.ctrlKey) showLoader(); }));
</script>
@endpush