@extends('layouts.kader')

@section('title', 'Dashboard Kader')
@section('page-name', 'Beranda Utama')

@push('styles')
<style>
    /* ANIMASI & KEYFRAMES */
    .fade-in-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .fade-in-scale { opacity: 0; animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes fadeInUp { 
        0% { opacity: 0; transform: translateY(30px); } 
        100% { opacity: 1; transform: translateY(0); } 
    }
    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes floatBlob {
        0% { transform: translate(0px, 0px) scale(1); }
        50% { transform: translate(15px, -15px) scale(1.05); }
        100% { transform: translate(0px, 0px) scale(1); }
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }

    /* PREMIUM CARD (Bento UI) */
    .bento-card { 
        background: #ffffff; 
        border: 1px solid #f1f5f9; 
        border-radius: 1.5rem; /* 24px */
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
        position: relative;
        overflow: hidden;
    }
    .bento-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.1);
        transform: translateY(-4px);
    }

    /* Stat Card Specifics */
    .stat-card .icon-box {
        width: 54px; height: 54px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; transition: all 0.4s ease;
    }
    .stat-card:hover .icon-box { transform: scale(1.15) rotate(6deg); }
    
    /* Hero Mesh */
    .hero-mesh {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        position: relative; overflow: hidden;
    }
    .hero-mesh::before {
        content: ''; position: absolute; inset: 0; opacity: 0.15;
        background-image: radial-gradient(#ffffff 2px, transparent 2px);
        background-size: 30px 30px; pointer-events: none;
    }

    /* Timeline Components */
    .timeline-wrap { position: relative; padding-left: 24px; }
    .timeline-wrap::before {
        content: ''; position: absolute; left: 8px; top: 10px; bottom: 10px; width: 2px;
        background: #f1f5f9; border-radius: 2px;
    }
    .tl-item { position: relative; padding-bottom: 20px; }
    .tl-dot {
        position: absolute; left: -24px; top: 4px; width: 18px; height: 18px;
        border-radius: 50%; background: white; border: 4px solid var(--tl-color, #818cf8);
        box-shadow: 0 0 0 4px #ffffff, 0 2px 5px rgba(0,0,0,0.1); z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- =========================================================================
         1. HERO SECTION (DEEP INDIGO SAAS)
         ========================================================================= --}}
    @php
        $jam = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
        if ($jam < 11) { $greeting = 'Selamat Pagi'; }
        elseif ($jam < 15) { $greeting = 'Selamat Siang'; }
        elseif ($jam < 18) { $greeting = 'Selamat Sore'; }
        else { $greeting = 'Selamat Malam'; }
    @endphp

    <div class="hero-mesh rounded-[2.5rem] p-8 md:p-12 flex flex-col lg:flex-row items-center justify-between gap-8 fade-in-up shadow-xl shadow-indigo-500/20">
        
        <div class="relative z-10 text-center lg:text-left flex-1">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-[11px] font-black uppercase tracking-widest mb-5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistem Aktif
            </div>

            <h1 class="text-3xl lg:text-[44px] font-black font-poppins text-white tracking-tight leading-[1.2] mb-4 text-shadow-sm">
                {{ $greeting }}, <br class="hidden lg:block">
                <span class="text-indigo-200">
                    {{ Str::words(Auth::user()->profile->full_name ?? Auth::user()->name, 2, '') }}!
                </span>
            </h1>

            <p class="text-indigo-100/90 text-[14px] leading-relaxed max-w-xl mb-8 font-medium">
                Pusat komando lapangan digital Anda. Lakukan pendataan warga, pantau riwayat kesehatan balita hingga lansia secara akurat dalam satu genggaman.
            </p>

            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-3">
                <div class="flex items-center gap-2.5 px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white text-[13px] font-bold font-poppins">
                    <i class="far fa-calendar-alt text-indigo-300"></i>
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}
                </div>
                <a href="{{ route('kader.pemeriksaan.index') }}" class="spa-route flex items-center gap-2 px-6 py-3 rounded-2xl bg-white text-indigo-600 hover:bg-slate-50 text-[13px] font-black font-poppins transition-all shadow-lg hover:-translate-y-0.5">
                    <i class="fas fa-stethoscope"></i> Mulai Pelayanan
                </a>
            </div>
        </div>

        <div class="relative z-10 hidden xl:flex shrink-0 w-64 h-64 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-tr from-sky-400/30 to-purple-400/30 rounded-full blur-[40px] animate-[floatBlob_8s_infinite]"></div>
            <div class="absolute inset-4 bg-white/10 backdrop-blur-xl border border-white/30 rounded-full shadow-2xl flex items-center justify-center">
                <i class="fas fa-briefcase-medical text-[70px] text-white drop-shadow-md"></i>
            </div>
            
            <div class="absolute top-6 -left-6 bg-white px-4 py-2 rounded-2xl shadow-xl flex items-center gap-3 animate-[floatBlob_6s_infinite_reverse]">
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center"><i class="fas fa-check"></i></div>
                <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status Data</p><p class="text-[12px] font-black text-slate-800 font-poppins">Tersinkron</p></div>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         2. BENTO GRID STAT CARDS (Responsif & Interaktif)
         ========================================================================= --}}
    @php
        $cards = [
            ['label'=>'Bayi & Balita', 'val'=>$stats['total_balita']??0, 'icon'=>'fa-baby', 'bg'=>'bg-rose-50', 'color'=>'text-rose-500', 'route'=>'kader.data.balita.index'],
            ['label'=>'Ibu Hamil', 'val'=>$stats['total_ibu_hamil']??0, 'icon'=>'fa-female', 'bg'=>'bg-pink-50', 'color'=>'text-pink-500', 'route'=>'kader.data.ibu-hamil.index'],
            ['label'=>'Remaja', 'val'=>$stats['total_remaja']??0, 'icon'=>'fa-user-graduate', 'bg'=>'bg-sky-50', 'color'=>'text-sky-500', 'route'=>'kader.data.remaja.index'],
            ['label'=>'Lansia', 'val'=>$stats['total_lansia']??0, 'icon'=>'fa-wheelchair', 'bg'=>'bg-emerald-50', 'color'=>'text-emerald-500', 'route'=>'kader.data.lansia.index'],
            ['label'=>'Jadwal Hari Ini', 'val'=>$stats['jadwal_hari_ini']??0, 'icon'=>'fa-calendar-check', 'bg'=>'bg-indigo-50', 'color'=>'text-indigo-600', 'route'=>'kader.jadwal.index'],
        ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-5 fade-in-scale delay-100">
        @foreach($cards as $c)
        <a href="{{ route($c['route']) }}" class="spa-route bento-card stat-card p-6 flex flex-col group">
            <div class="flex justify-between items-start w-full mb-2">
                <div class="icon-box {{ $c['bg'] }} {{ $c['color'] }}">
                    <i class="fas {{ $c['icon'] }}"></i>
                </div>
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity {{ $c['color'] }}">
                    <i class="fas fa-arrow-right text-[12px]"></i>
                </div>
            </div>
            <p class="text-[32px] font-black font-poppins text-slate-800 leading-none mb-1 group-hover:scale-105 transition-transform transform origin-left">{{ $c['val'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">{{ $c['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- =========================================================================
         3. CHARTS & ACTIVITY ROW
         ========================================================================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 fade-in-up delay-200">

        {{-- ADVANCED LINE CHART --}}
        <div class="xl:col-span-2 bento-card p-8 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg"><i class="fas fa-chart-area"></i></div>
                        <h3 class="text-[18px] font-black font-poppins text-slate-800">Trafik Pelayanan Warga</h3>
                    </div>
                    <p class="text-[12px] text-slate-400 font-semibold ml-[52px]">Statistik kehadiran dan pelayanan 7 hari terakhir.</p>
                </div>
            </div>
            
            <div class="relative flex-1 w-full min-h-[300px]">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        {{-- AKTIVITAS TERKINI (TIMELINE) --}}
        <div class="bento-card p-0 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[15px] font-black font-poppins text-slate-800"><i class="fas fa-history text-slate-400 mr-2"></i>Aktivitas Baru</h3>
                <span class="flex items-center gap-1.5 text-[9px] font-black text-emerald-600 bg-emerald-100 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                </span>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar max-h-[320px]">
                <div class="timeline-wrap">
                    @forelse($kunjungan_baru ?? [] as $kunj)
                        @php
                            $typeStr = class_basename($kunj->pasien_type);
                            $icons = ['Balita'=>'fa-baby','IbuHamil'=>'fa-female','Remaja'=>'fa-user-graduate','Lansia'=>'fa-wheelchair'];
                            $iconClass = $icons[$typeStr] ?? 'fa-user';
                            $colorHex = ['Balita'=>'#38bdf8','IbuHamil'=>'#f472b6','Remaja'=>'#818cf8','Lansia'=>'#34d399'][$typeStr] ?? '#94a3b8';
                        @endphp
                        <div class="tl-item" style="--tl-color: {{ $colorHex }}">
                            <div class="tl-dot flex items-center justify-center text-[9px] text-slate-400">
                                <i class="fas {{ $iconClass }}" style="color: {{ $colorHex }}"></i>
                            </div>
                            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all ml-1">
                                <div class="flex justify-between gap-2 mb-1">
                                    <p class="text-[13px] font-bold text-slate-800 truncate font-poppins">{{ $kunj->pasien->nama_lengkap ?? 'Warga' }}</p>
                                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">{{ $kunj->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium">Telah mendaftar di buku kehadiran.</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center text-slate-400">
                            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100"><i class="fas fa-folder-open text-2xl opacity-50"></i></div>
                            <p class="text-[12px] font-black font-poppins uppercase tracking-widest text-slate-500 mb-1">Log Kosong</p>
                            <p class="text-[11px] font-medium">Belum ada kunjungan yang tercatat hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <a href="{{ route('kader.kunjungan.index') }}" class="spa-route block w-full text-center text-[11px] font-black font-poppins text-indigo-600 hover:bg-white hover:shadow-sm border border-transparent hover:border-indigo-100 py-3 rounded-xl transition-all uppercase tracking-widest">Tampilkan Semua Log</a>
            </div>
        </div>
    </div>

    {{-- =========================================================================
         4. AGENDA & DISTRIBUSI DEMOGRAFI
         ========================================================================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 fade-in-up delay-300 pb-10">

        {{-- AGENDA PELAYANAN --}}
        <div class="xl:col-span-2 bento-card p-0 flex flex-col">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[16px] font-black font-poppins text-slate-800"><i class="far fa-calendar-check text-indigo-500 mr-2"></i>Agenda Mendatang</h3>
                <a href="{{ route('kader.jadwal.index') }}" class="spa-route text-[11px] font-black font-poppins text-slate-500 hover:text-indigo-600 bg-white border border-slate-200 px-4 py-2 rounded-xl transition-all uppercase tracking-widest shadow-sm">
                    Kalender Lengkap
                </a>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($jadwal_mendatang ?? [] as $jdw)
                <a href="{{ route('kader.jadwal.show', $jdw->id) }}" class="spa-route group flex bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-indigo-300 hover:shadow-[0_10px_25px_-5px_rgba(79,70,229,0.1)] transition-all">
                    {{-- Kotak Tanggal --}}
                    <div class="w-[72px] bg-slate-50 border-r border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-indigo-50 transition-colors">
                        <span class="text-[10px] font-black font-poppins text-slate-400 uppercase tracking-widest group-hover:text-indigo-500">{{ \Carbon\Carbon::parse($jdw->tanggal)->translatedFormat('M') }}</span>
                        <span class="text-[24px] font-black font-poppins text-slate-800 leading-none mt-1 group-hover:text-indigo-600">{{ \Carbon\Carbon::parse($jdw->tanggal)->format('d') }}</span>
                    </div>
                    {{-- Info Agenda --}}
                    <div class="p-4 flex-1 min-w-0 flex flex-col justify-center">
                        <h4 class="text-[14px] font-bold font-poppins text-slate-800 truncate mb-2 group-hover:text-indigo-700 transition-colors">{{ $jdw->judul }}</h4>
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500"><i class="far fa-clock text-indigo-400"></i> {{ \Carbon\Carbon::parse($jdw->waktu_mulai)->format('H:i') }}</span>
                            <span class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 truncate"><i class="fas fa-map-marker-alt text-rose-400"></i> {{ Str::limit($jdw->lokasi, 15) }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="md:col-span-2 py-10 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                    <i class="far fa-calendar-times text-3xl text-slate-300 mb-3"></i>
                    <h4 class="text-[13px] font-black font-poppins text-slate-600 mb-1">Agenda Kosong</h4>
                    <p class="text-[12px] font-medium">Belum ada jadwal kegiatan posyandu terbaru.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- DEMOGRAFI DONUT CHART --}}
        <div class="bento-card p-6 flex flex-col">
            <div class="mb-6 text-center border-b border-slate-100 pb-4">
                <h3 class="text-[16px] font-black font-poppins text-slate-800"><i class="fas fa-chart-pie text-sky-500 mr-2"></i>Distribusi Peserta</h3>
            </div>

            @php $totalW = ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0)+($stats['total_ibu_hamil']??0); @endphp

            <div class="relative mx-auto mb-8" style="width:200px;height:200px;">
                @if($totalW > 0)
                    <canvas id="donutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[10px] font-black font-poppins text-slate-400 uppercase tracking-widest mb-1">Total Data</span>
                        <span class="text-[32px] font-black font-poppins text-slate-800 leading-none">{{ $totalW }}</span>
                    </div>
                @else
                    <div class="absolute inset-0 rounded-full border-[10px] border-slate-100 flex flex-col items-center justify-center text-slate-400 bg-slate-50 shadow-inner">
                        <i class="fas fa-database text-3xl opacity-20 mb-2"></i>
                        <span class="text-[10px] font-black font-poppins uppercase tracking-widest">Kosong</span>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-3 mt-auto">
                @foreach([
                    ['label'=>'Balita', 'val'=>$stats['total_balita']??0, 'color'=>'bg-rose-500'],
                    ['label'=>'Bumil',  'val'=>$stats['total_ibu_hamil']??0, 'color'=>'bg-pink-500'],
                    ['label'=>'Remaja', 'val'=>$stats['total_remaja']??0, 'color'=>'bg-sky-500'],
                    ['label'=>'Lansia', 'val'=>$stats['total_lansia']??0, 'color'=>'bg-emerald-500'],
                ] as $d)
                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-md transition-all group">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $d['color'] }} shadow-sm"></span>
                        <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider font-poppins">{{ $d['label'] }}</span>
                    </div>
                    <span class="text-[13px] font-black font-poppins text-slate-800">{{ $d['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Global Chart.js Configuration
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const formattedDates = {!! json_encode($chartLabels ?? []) !!};
    const rawData        = {!! json_encode($chartData ?? []) !!};

    // =========================================================
    // 1. LINE CHART (Trafik Kehadiran)
    // =========================================================
    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx  = lc.getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo
        grad.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Kedatangan',
                    data: rawData,
                    borderColor: '#4f46e5',
                    backgroundColor: grad,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.45, // Sangat Halus
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 8,
                    hitRadius: 30,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff', titleColor: '#64748b', bodyColor: '#0f172a',
                        borderColor: '#e2e8f0', borderWidth: 1, padding: 12, borderRadius: 12,
                        titleFont: { size: 11, family: "'Inter', sans-serif", weight: 'bold' },
                        bodyFont: { size: 15, weight: '900', family: "'Poppins', sans-serif" },
                        displayColors: false,
                        callbacks: { label: function(c) { return c.parsed.y + ' Warga Dilayani'; } }
                    }
                },
                scales: {
                    y: { beginAtZero: true, border: { display: false }, grid: { color: '#f8fafc', drawTicks: false }, ticks: { stepSize: 1, padding: 10, font: { weight: '700' } } },
                    x: { grid: { display: false, drawBorder: false }, ticks: { padding: 10, font: { weight: '700' } } }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }

    // =========================================================
    // 2. DONUT CHART (Distribusi Demografi)
    // =========================================================
    const dc  = document.getElementById('donutChart');
    const bC  = {{ $stats['total_balita']    ?? 0 }};
    const ihC = {{ $stats['total_ibu_hamil'] ?? 0 }};
    const rC  = {{ $stats['total_remaja']    ?? 0 }};
    const lC  = {{ $stats['total_lansia']    ?? 0 }};
    const tot = bC + ihC + rC + lC;

    if(dc && tot > 0) {
        new Chart(dc, {
            type: 'doughnut',
            data: {
                labels: ['Balita', 'Ibu Hamil', 'Remaja', 'Lansia'],
                datasets: [{
                    data: [bC, ihC, rC, lC],
                    backgroundColor: ['#f43f5e', '#ec4899', '#0ea5e9', '#10b981'], 
                    borderWidth: 4, borderColor: '#ffffff', hoverOffset: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '78%', layout: { padding: 5 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff', titleColor: '#64748b', bodyColor: '#0f172a',
                        borderColor: '#e2e8f0', borderWidth: 1, padding: 12, borderRadius: 12,
                        titleFont: { size: 11, family: "'Inter', sans-serif", weight: 'bold' },
                        bodyFont: { size: 14, weight: '900', family: "'Poppins', sans-serif" },
                        callbacks: {
                            label: function(c) {
                                let label = c.label || ''; if (label) label += ': ';
                                let value = c.parsed; let percentage = Math.round((value / tot) * 100);
                                return label + value + ' Warga (' + percentage + '%)';
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