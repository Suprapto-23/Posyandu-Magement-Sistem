@extends('layouts.kader')
@section('title','Dashboard')
@section('page-name','Dashboard')

@push('styles')
<style>
    /* Staggered fade-in-up */
    .fade-in-up { 
        opacity: 0; 
        animation: fadeInUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
    }
    @keyframes fadeInUp { 
        from { opacity: 0; transform: translateY(16px); } 
        to   { opacity: 1; transform: translateY(0); } 
    }
    .delay-1 { animation-delay: 80ms; }
    .delay-2 { animation-delay: 180ms; }
    .delay-3 { animation-delay: 280ms; }

    /* Clean Card */
    .clean-card { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        border-radius: 20px; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 4px 8px -4px rgba(0,0,0,0.03);
        transition: box-shadow 0.25s ease, border-color 0.25s ease, transform 0.25s ease; 
    }
    .clean-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 24px -6px rgba(0,0,0,0.07);
        transform: translateY(-2px);
    }

    /* Stat card icon circle */
    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 14px;
        flex-shrink: 0;
        transition: transform 0.25s ease;
    }
    .clean-card:hover .stat-icon { transform: scale(1.1); }

    /* Timeline */
    .tl-item { position: relative; padding-left: 22px; padding-bottom: 18px; }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot { position: absolute; left: 0; top: 5px; width: 9px; height: 9px; border-radius: 50%; background: #818cf8; box-shadow: 0 0 0 3px rgba(129,140,248,0.15); }
    .tl-line { position: absolute; left: 3.5px; top: 18px; bottom: -4px; width: 2px; background: linear-gradient(to bottom, #e2e8f0, transparent); }
    .tl-item:last-child .tl-line { display: none; }

    /* Jadwal hover item */
    .jadwal-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        background: #fafafa;
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
    }
    .jadwal-item:hover {
        background: #ffffff;
        border-color: #c7d2fe;
        transform: translateX(2px);
        box-shadow: 0 2px 12px -4px rgba(99,102,241,0.12);
    }
    .jadwal-date {
        width: 48px; height: 54px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s, border-color 0.2s;
    }
    .jadwal-item:hover .jadwal-date {
        background: #eef2ff;
        border-color: #c7d2fe;
    }

    /* Legend row */
    .legend-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 9px 12px;
        border-radius: 10px;
        background: #fafafa;
        border: 1px solid #f1f5f9;
    }

    /* Hero accent line */
    .hero-accent {
        position: absolute;
        top: 0; left: 50%; transform: translateX(-50%);
        width: 60%; height: 2px;
        background: linear-gradient(90deg, transparent, #6366f1 40%, #818cf8 60%, transparent);
        border-radius: 0 0 4px 4px;
        opacity: 0.5;
    }

    /* Live badge */
    .live-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 99px;
        background: #eef2ff; border: 1px solid #e0e7ff;
        color: #6366f1; font-size: 10px; font-weight: 700;
        letter-spacing: 0.08em; text-transform: uppercase;
    }
    @keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.6;transform:scale(1.3)} }
    .pulse { animation: pulseDot 2s ease-in-out infinite; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto pb-8 space-y-6">

    {{-- ============================================================ --}}
    {{-- 1. HERO — Minimalist, Centered --}}
    {{-- ============================================================ --}}
    <div class="clean-card p-8 md:p-12 flex flex-col items-center text-center fade-in-up relative overflow-hidden">
        <div class="hero-accent"></div>

        <div class="live-badge mb-5">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 pulse"></span>
            Workspace Aktif
        </div>

        <h1 class="text-3xl md:text-[42px] font-extrabold text-slate-900 tracking-tight leading-tight mb-3" style="font-family:'Syne',sans-serif;">
            Halo, <span class="text-indigo-600">{{ Str::words(Auth::user()->profile->full_name ?? 'Kader', 2, '') }}!</span> 👋
        </h1>

        <p class="text-slate-500 text-[14px] md:text-[15px] leading-relaxed max-w-xl mb-7 font-medium">
            Pantau metrik kesehatan warga, kelola jadwal pelayanan, dan lihat demografi Posyandu Anda secara real-time.
        </p>

        <div class="flex flex-wrap justify-center items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-[13px] font-semibold">
                <i class="far fa-calendar text-slate-400 text-sm"></i>
                {{ now()->translatedFormat('d F Y') }}
            </div>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-[13px] font-semibold">
                <i class="fas fa-users text-slate-400 text-sm"></i>
                {{ ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0)+($stats['total_ibu_hamil']??0) }} Warga Terdaftar
            </div>
            <a href="{{ route('kader.kunjungan.index') }}" class="smooth-route flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-[13px] font-semibold transition-colors shadow-sm shadow-indigo-200">
                <i class="fas fa-book-open text-sm"></i>
                Buku Tamu
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 2. STAT CARDS --}}
    {{-- ============================================================ --}}
    @php
        $cards = [
            ['label'=>'Balita', 'val'=>$stats['total_balita']??0, 'icon'=>'fa-baby', 'iconBg'=>'#fff1f2', 'iconColor'=>'#f43f5e'],
            ['label'=>'Ibu Hamil', 'val'=>$stats['total_ibu_hamil']??0, 'icon'=>'fa-person-pregnant', 'iconBg'=>'#fdf2f8', 'iconColor'=>'#ec4899'],
            ['label'=>'Remaja', 'val'=>$stats['total_remaja']??0, 'icon'=>'fa-user-graduate', 'iconBg'=>'#f0f9ff', 'iconColor'=>'#0ea5e9'],
            ['label'=>'Lansia', 'val'=>$stats['total_lansia']??0, 'icon'=>'fa-person-cane', 'iconBg'=>'#f0fdf4', 'iconColor'=>'#10b981'],
            ['label'=>'Jadwal Aktif', 'val'=>$stats['jadwal_hari_ini']??0, 'icon'=>'fa-calendar-check', 'iconBg'=>'#eef2ff', 'iconColor'=>'#6366f1'],
        ];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 fade-in-up delay-1">
        @foreach($cards as $c)
        <div class="clean-card p-6 flex flex-col items-center text-center">
            <div class="stat-icon" style="background:{{ $c['iconBg'] }};color:{{ $c['iconColor'] }};">
                <i class="fas {{ $c['icon'] }}"></i>
            </div>
            <p class="text-[36px] font-extrabold text-slate-900 leading-none mb-1.5" style="font-family:'Syne',sans-serif;">{{ $c['val'] }}</p>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">{{ $c['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- 3. CHARTS ROW --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 fade-in-up delay-2">

        {{-- Line Chart --}}
        <div class="lg:col-span-2 clean-card p-7 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-[17px] font-bold text-slate-800 leading-tight" style="font-family:'Syne',sans-serif;">Trafik Kehadiran</h3>
                    <p class="text-[12px] text-slate-400 font-medium mt-0.5">Tren kunjungan 7 hari terakhir</p>
                </div>
                <a href="{{ route('kader.kunjungan.index') }}" class="smooth-route hidden sm:flex items-center gap-1.5 px-4 py-2 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 text-[12px] font-semibold rounded-xl transition-colors border border-slate-200">
                    Buku Tamu <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="relative flex-1" style="min-height:260px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        {{-- Aktivitas Terkini --}}
        <div class="clean-card p-7 flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-[17px] font-bold text-slate-800 leading-tight" style="font-family:'Syne',sans-serif;">Aktivitas Terkini</h3>
                    <p class="text-[12px] text-slate-400 font-medium mt-0.5">Log pendaftaran warga</p>
                </div>
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse"></span> Live
                </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-1" style="max-height:280px;scrollbar-width:thin;scrollbar-color:#e2e8f0 transparent;">
                @forelse($kunjungan_baru as $kunj)
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <div class="tl-line"></div>
                    <div class="hover:bg-slate-50 rounded-xl p-3 -ml-1 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-0.5">
                            <p class="text-[13px] font-semibold text-slate-800 leading-tight">{{ $kunj->pasien->nama_lengkap ?? 'Warga' }}</p>
                            <span class="text-[10px] text-slate-400 shrink-0 font-medium">{{ $kunj->created_at->diffForHumans(null, true, true) }}</span>
                        </div>
                        <p class="text-[12px] text-slate-500 mb-1.5">Mendaftar kedatangan.</p>
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md border border-slate-200 uppercase tracking-wide">
                            <i class="fas fa-tag text-[8px]"></i> {{ class_basename($kunj->pasien_type) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-36 text-slate-400 text-center">
                    <i class="far fa-clock text-2xl mb-2 opacity-40"></i>
                    <p class="text-[12px] font-semibold uppercase tracking-wider">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- 4. AGENDA & DEMOGRAFI --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 fade-in-up delay-3">

        {{-- Agenda Mendatang --}}
        <div class="lg:col-span-2 clean-card p-7">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-[17px] font-bold text-slate-800 leading-tight" style="font-family:'Syne',sans-serif;">Agenda Mendatang</h3>
                    <p class="text-[12px] text-slate-400 font-medium mt-0.5">Jadwal operasional Posyandu</p>
                </div>
                <a href="{{ route('kader.jadwal.index') }}" class="smooth-route text-[12px] font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors">
                    Lihat Semua
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($jadwal_mendatang as $jdw)
                <a href="{{ route('kader.jadwal.show', $jdw->id) }}" class="jadwal-item smooth-route group">
                    <div class="jadwal-date">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide group-hover:text-indigo-400 transition-colors">{{ \Carbon\Carbon::parse($jdw->tanggal)->translatedFormat('M') }}</span>
                        <span class="text-[19px] font-extrabold text-slate-800 leading-none mt-0.5" style="font-family:'Syne',sans-serif;">{{ \Carbon\Carbon::parse($jdw->tanggal)->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-[13px] font-semibold text-slate-800 truncate mb-1 group-hover:text-indigo-600 transition-colors">{{ $jdw->judul }}</h4>
                        <p class="text-[12px] text-slate-400 flex items-center gap-1.5">
                            <i class="far fa-clock text-[11px]"></i>
                            {{ \Carbon\Carbon::parse($jdw->waktu_mulai)->format('H:i') }} WIB
                        </p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover:text-indigo-400 transition-colors shrink-0"></i>
                </a>
                @empty
                <div class="sm:col-span-2 py-10 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/80">
                    <i class="far fa-calendar-times text-2xl mb-2 block opacity-40"></i>
                    <p class="text-[12px] font-medium">Tidak ada jadwal dalam waktu dekat</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Distribusi Warga --}}
        <div class="clean-card p-7 flex flex-col">
            <div class="mb-5 text-center">
                <h3 class="text-[17px] font-bold text-slate-800 leading-tight" style="font-family:'Syne',sans-serif;">Distribusi Warga</h3>
                <p class="text-[12px] text-slate-400 font-medium mt-0.5">Demografi database saat ini</p>
            </div>

            @php $totalW = ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0)+($stats['total_ibu_hamil']??0); @endphp

            <div class="relative mx-auto mb-6" style="width:170px;height:170px;">
                @if($totalW > 0)
                    <canvas id="donutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Total</span>
                        <span class="text-[30px] font-extrabold text-slate-900 leading-tight mt-0.5" style="font-family:'Syne',sans-serif;">{{ $totalW }}</span>
                    </div>
                @else
                    <div class="flex items-center justify-center h-full text-slate-300 text-4xl">
                        <i class="fas fa-chart-pie opacity-30"></i>
                    </div>
                @endif
            </div>

            <div class="space-y-2 mt-auto">
                @foreach([
                    ['label'=>'Balita',    'val'=>$stats['total_balita']??0,    'hex'=>'#f43f5e'],
                    ['label'=>'Ibu Hamil', 'val'=>$stats['total_ibu_hamil']??0, 'hex'=>'#ec4899'],
                    ['label'=>'Remaja',    'val'=>$stats['total_remaja']??0,    'hex'=>'#0ea5e9'],
                    ['label'=>'Lansia',    'val'=>$stats['total_lansia']??0,    'hex'=>'#10b981'],
                ] as $d)
                <div class="legend-row">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $d['hex'] }};"></span>
                        <span class="text-[12px] font-semibold text-slate-600">{{ $d['label'] }}</span>
                    </div>
                    <span class="text-[14px] font-bold text-slate-800" style="font-family:'Syne',sans-serif;">{{ $d['val'] }}</span>
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

    // Global chart defaults — sesuai DM Sans
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const formattedDates = {!! json_encode($chartLabels ?? []) !!};
    const rawData        = {!! json_encode($chartData ?? []) !!};

    // Line Chart
    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx  = lc.getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, 'rgba(99,102,241,0.18)');
        grad.addColorStop(1, 'rgba(99,102,241,0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Kedatangan',
                    data: rawData,
                    borderColor: '#6366f1',
                    backgroundColor: grad,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#0f172a',
                        bodyColor: '#0f172a',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' Warga'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#f1f5f9', drawTicks: false },
                        ticks: { stepSize: 1, padding: 10, font: { size: 11, weight: '500' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { padding: 10, font: { size: 11, weight: '500' } }
                    }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }

    // Donut Chart
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
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#0f172a',
                        bodyColor: '#0f172a',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 10,
                    }
                }
            }
        });
    }
});
</script>
@endpush