@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-name', 'Overview Sistem')

@section('content')
<style>
/* ── EXECUTIVE OBSIDIAN DASHBOARD STYLES ── */
.animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* Hero Dark Slate Premium */
.hero-admin { 
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
    border-radius: 32px; 
    padding: 48px; 
    margin-bottom: 32px; 
    position: relative; 
    overflow: hidden; 
    box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.4); 
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #334155;
}
.hero-admin::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px); background-size: 24px 24px; pointer-events: none; }
.hero-glow { position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%); pointer-events: none; }

.hero-txt { position: relative; z-index: 1; }
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.1); color: #cbd5e1; font-size: 11px; font-weight: 800; padding: 6px 16px; border-radius: 50px; margin-bottom: 20px; letter-spacing: 1px; text-transform: uppercase; }
.hero-title { font-size: 36px; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 12px; letter-spacing: -0.5px; font-family: 'Poppins', sans-serif; }
.hero-desc { font-size: 15px; color: #94a3b8; max-width: 500px; font-weight: 500; line-height: 1.6; }

/* Bento Grid Stats */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
.stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 28px; padding: 24px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02); transition: all 0.3s ease; position: relative; display: flex; flex-direction: column; justify-content: space-between; }
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); border-color: #cbd5e1; }
.stat-icon { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; transition: transform 0.3s; }
.stat-card:hover .stat-icon { transform: scale(1.1) rotate(5deg); }
.stat-val { font-size: 32px; font-weight: 900; color: #0f172a; line-height: 1; letter-spacing: -1px; margin-bottom: 6px; }
.stat-lbl { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Poppins', sans-serif; }
.stat-sub { font-size: 11px; font-weight: 600; color: #94a3b8; margin-top: 8px; }

/* Section Cards */
.section-card { background: #fff; border-radius: 32px; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.02); padding: 32px; height: 100%; display: flex; flex-direction: column; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;}
.section-title { font-size: 16px; font-weight: 900; color: #0f172a; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 12px; }
.section-icon-wrap { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; }

/* List Activity */
.act-row { display: flex; align-items: center; gap: 16px; padding: 12px; border-radius: 16px; transition: background 0.2s; border: 1px solid transparent; }
.act-row:hover { background: #f8fafc; border-color: #f1f5f9; }
.act-av { width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 900; color: #fff; }

/* Responsive */
@media(max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 768px) { .hero-admin { padding: 32px; flex-direction: column; text-align: center; } .hero-title { font-size: 28px; } .stats-grid { grid-template-columns: 1fr 1fr; gap: 16px; } .stat-card { padding: 20px; border-radius: 20px; } .stat-val { font-size: 26px; } .section-card { padding: 24px; border-radius: 24px; } }
@media(max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

<div class="animate-slide-up">
    
    <div class="hero-admin">
        <div class="hero-glow"></div>
        <div class="hero-txt">
            <div class="hero-badge"><i class="fas fa-shield-alt text-amber-500 mr-2"></i> Sistem Manajemen Root</div>
            <h1 class="hero-title">Selamat Datang, <span class="text-amber-500">{{ auth()->user()->name }}</span></h1>
            <p class="hero-desc">Server Posyandu beroperasi secara optimal. Pantau lalu lintas registrasi dan atur entitas pengguna dengan otoritas penuh.</p>
        </div>
        <div class="hidden md:flex items-center justify-center w-40 h-40 bg-slate-800 border border-slate-700 rounded-[2rem] shadow-2xl relative z-10 transform rotate-6 hover:rotate-0 transition-all duration-500">
            <i class="fas fa-fingerprint text-6xl text-slate-400 drop-shadow-md"></i>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card border-b-[5px] border-b-slate-800">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-val">{{ $stats['total_user'] ?? 0 }}</div>
                    <div class="stat-lbl">Total Warga</div>
                </div>
                <div class="stat-icon bg-slate-100 text-slate-800"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-sub"><span class="text-emerald-600 font-bold">{{ $stats['user_aktif'] ?? 0 }} Aktif</span> · {{ $stats['user_nonaktif'] ?? 0 }} Suspended</div>
        </div>

        <div class="stat-card border-b-[5px] border-b-amber-500 bg-amber-50/30">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-val text-amber-700">{{ $userBaruBulanIni ?? 0 }}</div>
                    <div class="stat-lbl text-amber-900">Registrasi Baru</div>
                </div>
                <div class="stat-icon bg-amber-500 text-white shadow-lg"><i class="fas fa-user-plus"></i></div>
            </div>
            <div class="stat-sub text-amber-600">Tercatat Bulan {{ now()->translatedFormat('F Y') }}</div>
        </div>

        <div class="stat-card border-b-[5px] border-b-slate-600">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-val">{{ $stats['total_kader'] ?? 0 }}</div>
                    <div class="stat-lbl">Akun Kader</div>
                </div>
                <div class="stat-icon bg-slate-100 text-slate-600"><i class="fas fa-user-nurse"></i></div>
            </div>
            <div class="stat-sub">Akses Pendataan Lapangan</div>
        </div>

        <div class="stat-card border-b-[5px] border-b-slate-500">
            <div class="flex justify-between items-start">
                <div>
                    <div class="stat-val">{{ $stats['total_bidan'] ?? 0 }}</div>
                    <div class="stat-lbl">Akun Bidan</div>
                </div>
                <div class="stat-icon bg-slate-100 text-slate-500"><i class="fas fa-user-md"></i></div>
            </div>
            <div class="stat-sub">Akses Verifikasi Medis</div>
        </div>

        <div class="stat-card border-b-[4px] border-b-slate-300">
            <div class="flex justify-between items-center">
                <div>
                    <div class="stat-val">{{ $stats['total_balita'] ?? 0 }}</div>
                    <div class="stat-lbl">Data Balita</div>
                </div>
                <div class="stat-icon bg-slate-50 text-slate-400 border border-slate-100 mb-0"><i class="fas fa-baby"></i></div>
            </div>
        </div>

        <div class="stat-card border-b-[4px] border-b-slate-300">
            <div class="flex justify-between items-center">
                <div>
                    <div class="stat-val">{{ $stats['total_remaja'] ?? 0 }}</div>
                    <div class="stat-lbl">Data Remaja</div>
                </div>
                <div class="stat-icon bg-slate-50 text-slate-400 border border-slate-100 mb-0"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>

        <div class="stat-card border-b-[4px] border-b-slate-300">
            <div class="flex justify-between items-center">
                <div>
                    <div class="stat-val">{{ $stats['total_lansia'] ?? 0 }}</div>
                    <div class="stat-lbl">Data Lansia</div>
                </div>
                <div class="stat-icon bg-slate-50 text-slate-400 border border-slate-100 mb-0"><i class="fas fa-wheelchair"></i></div>
            </div>
        </div>

        <div class="stat-card border-b-[4px] border-b-slate-300">
            <div class="flex justify-between items-center">
                <div>
                    <div class="stat-val">{{ $jadwalHariIni->count() ?? 0 }}</div>
                    <div class="stat-lbl">Jadwal Aktif</div>
                </div>
                <div class="stat-icon bg-slate-50 text-slate-400 border border-slate-100 mb-0"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="lg:col-span-2 section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <div class="section-icon-wrap bg-slate-100 text-slate-800 border border-slate-200"><i class="fas fa-chart-area"></i></div>
                    Pertumbuhan Registrasi Warga
                </h3>
            </div>
            <div class="relative flex-1 min-h-[250px]">
                <canvas id="regChart"></canvas>
            </div>
        </div>

        <div class="section-card p-0 overflow-hidden">
            <div class="section-header mx-8 mt-8 mb-4">
                <h3 class="section-title">
                    <div class="section-icon-wrap bg-slate-100 text-slate-800 border border-slate-200"><i class="fas fa-history"></i></div>
                    Log Sistem Terbaru
                </h3>
            </div>
            <div class="flex-1 overflow-y-auto px-6 pb-6 custom-scrollbar">
                @forelse($loginTerbaru ?? [] as $l)
                <div class="act-row">
                    @php
                        $bg = 'bg-slate-400'; 
                        if($l->role == 'admin') $bg = 'bg-slate-900';
                        if($l->role == 'kader') $bg = 'bg-slate-700';
                        if($l->role == 'bidan') $bg = 'bg-slate-500';
                    @endphp
                    <div class="act-av {{ $bg }} shadow-sm">
                        {{ strtoupper(substr($l->display_name ?? 'U', 0, 1)) }}
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-[13px] font-bold text-slate-800 truncate">{{ $l->display_name ?? '-' }}</div>
                        <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">{{ $l->role }}</div>
                    </div>
                    
                    <div class="text-right flex-shrink-0">
                        @if($l->status === 'success')
                            <span class="px-2 py-0.5 rounded text-slate-500 text-[10px] font-bold border border-slate-200"><i class="fas fa-check text-emerald-500"></i> OK</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-rose-500 text-[10px] font-bold border border-rose-200"><i class="fas fa-times"></i> Fail</span>
                        @endif
                        <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ \Carbon\Carbon::parse($l->login_at)->diffForHumans(null, true, true) }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-slate-400">
                    <i class="fas fa-server d-block mb-3 text-3xl opacity-30"></i>
                    <p class="text-xs font-semibold">Tidak ada log aktivitas.</p>
                </div>
                @endforelse
            </div>
        </div>
        
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_10px_40px_rgb(0,0,0,0.02)] p-8">
        <h3 class="section-title mb-6">
            <div class="section-icon-wrap bg-slate-100 text-slate-800 border border-slate-200"><i class="fas fa-terminal"></i></div>
            Aksi Root Sistem
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-800 hover:shadow-md transition-all group">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl group-hover:bg-slate-800 group-hover:text-white transition-all"><i class="fas fa-users"></i></div>
                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Kelola Warga</span>
            </a>
            
            <a href="{{ route('admin.kaders.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-800 hover:shadow-md transition-all group">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl group-hover:bg-slate-800 group-hover:text-white transition-all"><i class="fas fa-user-nurse"></i></div>
                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Kelola Kader</span>
            </a>
            
            <a href="{{ route('admin.bidans.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-800 hover:shadow-md transition-all group">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl group-hover:bg-slate-800 group-hover:text-white transition-all"><i class="fas fa-user-md"></i></div>
                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Kelola Bidan</span>
            </a>
            
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 hover:border-slate-800 hover:shadow-md transition-all group">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl group-hover:bg-slate-800 group-hover:text-white transition-all"><i class="fas fa-cog"></i></div>
                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Pengaturan Web</span>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    const ctx = document.getElementById('regChart');
    if(ctx) {
        const c = ctx.getContext('2d');
        const gradient = c.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(15, 23, 42, 0.4)'); // Slate-900 transparan
        gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels'] ?? []) !!},
                datasets: [{
                    label: 'Warga Baru',
                    data: {!! json_encode($chartData['userData'] ?? []) !!},
                    borderColor: '#0f172a', // Slate-900 sangat maskulin
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4, // Kurva Halus
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 12, borderRadius: 12, displayColors: false, titleFont: {size: 13, family: 'Poppins'}, bodyFont: {size: 15, weight: 'bold'} }
                },
                scales: {
                    y: { beginAtZero: true, border: {display: false}, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { stepSize: 1, font: {weight: 'bold'} } },
                    x: { border: {display: false}, grid: { display: false }, ticks: { font: {weight: 'bold'} } }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }
});
</script>
@endpush