
<?php $__env->startSection('title','Dashboard Kader'); ?>
<?php $__env->startSection('page-name','Dashboard Overview'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* ── DASHBOARD ULTRA PREMIUM STYLES ── */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { 
        from { opacity: 0; transform: translateY(30px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.12);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .blob-bg {
        position: absolute; filter: blur(80px); z-index: 0; opacity: 0.3;
        animation: floatBlob 12s infinite alternate ease-in-out;
        pointer-events: none;
    }
    @keyframes floatBlob {
        0% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(30px, -30px) scale(1.1); }
        100% { transform: translate(-10px, 20px) scale(0.9); }
    }

    @keyframes pulseRing { 
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4) } 
        50% { box-shadow: 0 0 0 6px rgba(255, 255, 255, 0) } 
    }
    .pulse-dot { animation: pulseRing 2s infinite; }
</style>

<div class="max-w-[1400px] mx-auto relative pb-10">
    
    <div class="blob-bg bg-indigo-400 w-96 h-96 rounded-full top-0 left-0 hidden md:block"></div>
    <div class="blob-bg bg-fuchsia-300 w-80 h-80 rounded-full bottom-40 right-10 hidden md:block" style="animation-delay: -4s;"></div>

    <div class="relative z-10">
        
        <div class="bg-gradient-to-br from-indigo-500 via-violet-600 to-indigo-800 rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 md:p-12 mb-6 sm:mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(79,70,229,0.5)] flex flex-col md:flex-row items-center justify-between gap-6 sm:gap-8 animate-slide-up text-center md:text-left">
            
            <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 w-full md:w-auto">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] sm:text-[11px] font-black px-4 py-2 rounded-full mb-5 sm:mb-6 uppercase tracking-widest shadow-sm">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-400 pulse-dot"></span> Sistem Aktif
                </div>
                
                <h1 class="text-2xl sm:text-3xl md:text-5xl font-black text-white tracking-tight font-poppins mb-2 sm:mb-3 leading-tight">
                    Halo, <span class="text-indigo-200"><?php echo e(Str::words(Auth::user()->profile->full_name ?? 'Kader Hebat', 2, '')); ?>!</span> 👋
                </h1>
                
                <p class="text-indigo-100 font-medium text-[12px] sm:text-[14px] md:text-[15px] max-w-xl leading-relaxed mb-6 sm:mb-8 mx-auto md:mx-0">
                    Pantau metrik kesehatan warga, jadwal pelayanan, dan demografi Posyandu Anda secara *real-time* di Command Center ini.
                </p>

                <div class="flex flex-wrap justify-center md:justify-start items-center gap-2 sm:gap-3">
                    <div class="flex items-center gap-2 sm:gap-3 px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-black/20 backdrop-blur-sm border border-white/10 text-white text-[11px] sm:text-[13px] font-bold shadow-inner">
                        <i class="fas fa-calendar-day text-indigo-300 text-sm sm:text-lg"></i> <span class="hidden sm:inline"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span><span class="sm:hidden"><?php echo e(now()->translatedFormat('d M Y')); ?></span>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-black/20 backdrop-blur-sm border border-white/10 text-white text-[11px] sm:text-[13px] font-bold shadow-inner">
                        <i class="fas fa-users text-emerald-400 text-sm sm:text-lg"></i> <?php echo e(($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0)); ?> Warga
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex w-48 h-48 rounded-[32px] bg-white/10 backdrop-blur-md border border-white/20 text-white items-center justify-center text-[80px] shrink-0 shadow-2xl relative z-10 transform rotate-6 hover:rotate-0 hover:scale-105 transition-all duration-500">
                <i class="fas fa-laptop-medical drop-shadow-lg"></i>
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8 animate-slide-up" style="animation-delay: 0.1s;">
            
            <div class="glass-card rounded-[20px] sm:rounded-[24px] p-4 sm:p-6 relative overflow-hidden group flex flex-col h-full">
                <div class="absolute -right-4 -top-4 sm:-right-6 sm:-top-6 w-16 h-16 sm:w-24 sm:h-24 bg-rose-500/10 rounded-full blur-xl group-hover:bg-rose-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3 sm:mb-4 relative z-10 gap-2 sm:gap-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider sm:tracking-widest mb-0.5 sm:mb-1 truncate">Balita</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 font-poppins"><?php echo e($stats['total_balita'] ?? 0); ?></h3>
                    </div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm sm:text-xl shadow-inner border border-rose-200 shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-baby"></i>
                    </div>
                </div>
                <div class="mt-auto pt-3 sm:pt-0 border-t border-slate-100/50 sm:border-0 flex items-center gap-1.5 text-[9px] sm:text-[11px] font-bold text-slate-500 relative z-10">
                    <span class="flex items-center justify-center w-4 h-4 sm:w-auto sm:h-auto sm:px-1.5 sm:py-0.5 rounded-full sm:rounded bg-emerald-50 text-emerald-500 border border-emerald-100 shrink-0"><i class="fas fa-check sm:hidden text-[8px]"></i><i class="fas fa-arrow-up hidden sm:inline mr-1"></i><span class="hidden sm:inline">Terupdate</span></span> 
                    <span class="truncate">di sistem</span>
                </div>
            </div>

            <div class="glass-card rounded-[20px] sm:rounded-[24px] p-4 sm:p-6 relative overflow-hidden group flex flex-col h-full">
                <div class="absolute -right-4 -top-4 sm:-right-6 sm:-top-6 w-16 h-16 sm:w-24 sm:h-24 bg-sky-500/10 rounded-full blur-xl group-hover:bg-sky-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3 sm:mb-4 relative z-10 gap-2 sm:gap-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider sm:tracking-widest mb-0.5 sm:mb-1 truncate">Remaja</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 font-poppins"><?php echo e($stats['total_remaja'] ?? 0); ?></h3>
                    </div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-sm sm:text-xl shadow-inner border border-sky-200 shrink-0 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="mt-auto pt-3 sm:pt-0 border-t border-slate-100/50 sm:border-0 flex items-center gap-1.5 text-[9px] sm:text-[11px] font-bold text-slate-500 relative z-10">
                    <span class="flex items-center justify-center w-4 h-4 sm:w-auto sm:h-auto sm:px-1.5 sm:py-0.5 rounded-full sm:rounded bg-emerald-50 text-emerald-500 border border-emerald-100 shrink-0"><i class="fas fa-check sm:hidden text-[8px]"></i><i class="fas fa-arrow-up hidden sm:inline mr-1"></i><span class="hidden sm:inline">Terupdate</span></span> 
                    <span class="truncate">di sistem</span>
                </div>
            </div>

            <div class="glass-card rounded-[20px] sm:rounded-[24px] p-4 sm:p-6 relative overflow-hidden group flex flex-col h-full">
                <div class="absolute -right-4 -top-4 sm:-right-6 sm:-top-6 w-16 h-16 sm:w-24 sm:h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3 sm:mb-4 relative z-10 gap-2 sm:gap-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider sm:tracking-widest mb-0.5 sm:mb-1 truncate">Lansia</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 font-poppins"><?php echo e($stats['total_lansia'] ?? 0); ?></h3>
                    </div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm sm:text-xl shadow-inner border border-emerald-200 shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-wheelchair"></i>
                    </div>
                </div>
                <div class="mt-auto pt-3 sm:pt-0 border-t border-slate-100/50 sm:border-0 flex items-center gap-1.5 text-[9px] sm:text-[11px] font-bold text-slate-500 relative z-10">
                    <span class="flex items-center justify-center w-4 h-4 sm:w-auto sm:h-auto sm:px-1.5 sm:py-0.5 rounded-full sm:rounded bg-emerald-50 text-emerald-500 border border-emerald-100 shrink-0"><i class="fas fa-check sm:hidden text-[8px]"></i><i class="fas fa-arrow-up hidden sm:inline mr-1"></i><span class="hidden sm:inline">Terupdate</span></span> 
                    <span class="truncate">di sistem</span>
                </div>
            </div>

            <div class="glass-card rounded-[20px] sm:rounded-[24px] p-4 sm:p-6 relative overflow-hidden group flex flex-col h-full">
                <div class="absolute -right-4 -top-4 sm:-right-6 sm:-top-6 w-16 h-16 sm:w-24 sm:h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div class="flex justify-between items-start mb-3 sm:mb-4 relative z-10 gap-2 sm:gap-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider sm:tracking-widest mb-0.5 sm:mb-1 truncate">Jadwal</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 font-poppins"><?php echo e($stats['jadwal_hari_ini'] ?? 0); ?></h3>
                    </div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm sm:text-xl shadow-inner border border-indigo-200 shrink-0 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="mt-auto pt-3 sm:pt-0 border-t border-slate-100/50 sm:border-0 flex items-center gap-1.5 text-[9px] sm:text-[11px] font-bold text-slate-500 relative z-10">
                    <span class="truncate">Bulan ini</span>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 animate-slide-up" style="animation-delay: 0.2s;">
            
            <div class="lg:col-span-2 glass-card rounded-[24px] sm:rounded-[32px] p-5 sm:p-6 md:p-8 flex flex-col relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-[10px] sm:rounded-[14px] bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm shrink-0"><i class="fas fa-chart-area text-sm sm:text-lg"></i></div>
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-slate-800 font-poppins leading-none">Statistik Kunjungan</h3>
                            <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Tren Layanan 7 Hari Terakhir</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="px-3 sm:px-5 py-2 sm:py-2.5 bg-white hover:bg-slate-50 text-slate-600 text-[10px] sm:text-[12px] font-black uppercase tracking-wider rounded-lg sm:rounded-xl transition-all border border-slate-200 shadow-sm hover:shadow flex items-center gap-1.5 sm:gap-2 smooth-route">
                            <i class="fas fa-list-ul"></i> <span class="hidden sm:inline">Detail</span>
                        </a>
                        <a href="<?php echo e(route('kader.laporan.index')); ?>" class="px-3 sm:px-5 py-2 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] sm:text-[12px] font-black uppercase tracking-wider rounded-lg sm:rounded-xl transition-all shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 flex items-center gap-1.5 sm:gap-2 smooth-route">
                            <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">Laporan</span>
                        </a>
                    </div>
                </div>
                
                <div class="relative w-full flex-1 z-10" style="min-h: 250px; sm:min-h: 300px;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <div class="glass-card rounded-[24px] sm:rounded-[32px] p-5 sm:p-6 md:p-8 flex flex-col relative overflow-hidden">
                <div class="flex items-center gap-3 mb-6 sm:mb-8 relative z-10">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-[10px] sm:rounded-[14px] bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100 shadow-sm shrink-0"><i class="fas fa-chart-pie text-sm sm:text-lg"></i></div>
                    <div>
                        <h3 class="text-base sm:text-lg font-black text-slate-800 font-poppins leading-none">Demografi Warga</h3>
                        <p class="text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Distribusi Pasien Aktif</p>
                    </div>
                </div>
                
                <?php $totalW = ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0); ?>
                
                <?php if($totalW == 0): ?>
                    <div class="h-40 sm:h-48 flex flex-col items-center justify-center text-slate-400 mb-4 sm:mb-6 relative z-10">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-50 border border-slate-100 rounded-xl sm:rounded-2xl flex items-center justify-center text-xl sm:text-2xl shadow-inner mb-3"><i class="fas fa-users-slash"></i></div>
                        <p class="font-black text-[11px] sm:text-[13px] uppercase tracking-widest text-slate-400">Database Kosong</p>
                    </div>
                <?php else: ?>
                    <div class="relative w-full aspect-square max-h-[180px] sm:max-h-[220px] mx-auto mb-6 sm:mb-8 z-10">
                        <canvas id="donutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-800 font-poppins leading-none"><?php echo e($totalW); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col gap-2.5 sm:gap-3 mt-auto relative z-10">
                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-rose-200 transition-colors group">
                        <div class="flex items-center gap-2.5 sm:gap-3"><span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span><span class="text-[11px] sm:text-[13px] font-black text-slate-600 uppercase tracking-wider">Balita</span></div>
                        <span class="text-[14px] sm:text-[16px] font-black text-slate-900 font-poppins group-hover:text-rose-600 transition-colors"><?php echo e($stats['total_balita'] ?? 0); ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-sky-200 transition-colors group">
                        <div class="flex items-center gap-2.5 sm:gap-3"><span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.5)]"></span><span class="text-[11px] sm:text-[13px] font-black text-slate-600 uppercase tracking-wider">Remaja</span></div>
                        <span class="text-[14px] sm:text-[16px] font-black text-slate-900 font-poppins group-hover:text-sky-600 transition-colors"><?php echo e($stats['total_remaja'] ?? 0); ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-emerald-200 transition-colors group">
                        <div class="flex items-center gap-2.5 sm:gap-3"><span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span><span class="text-[11px] sm:text-[13px] font-black text-slate-600 uppercase tracking-wider">Lansia</span></div>
                        <span class="text-[14px] sm:text-[16px] font-black text-slate-900 font-poppins group-hover:text-emerald-600 transition-colors"><?php echo e($stats['total_lansia'] ?? 0); ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Data dari Controller
    const formattedDates = <?php echo json_encode($chartLabels ?? []); ?>;
    const rawData = <?php echo json_encode($chartData ?? []); ?>;

    // 1. CHART KUNJUNGAN (LINE)
    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx = lc.getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)'); 
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)'); 

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Kehadiran Warga',
                    data: rawData,
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4, 
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#94a3b8',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { size: 10, family: "'Inter', sans-serif", weight: 'bold' },
                        bodyFont: { size: 13, weight: '900', family: "'Poppins', sans-serif" },
                        displayColors: false,
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        callbacks: {
                            title: function(context) { return context[0].label.toUpperCase(); },
                            label: function(context) { return context.parsed.y + ' Warga Hadir'; }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        border: { display: false }, 
                        grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false, borderDash: [5, 5] }, 
                        ticks: { 
                            stepSize: 1, 
                            color: '#94a3b8', 
                            font: { weight: 'bold', family: "'Inter', sans-serif", size: 10 },
                            padding: 8
                        } 
                    },
                    x: { 
                        border: { display: false }, 
                        grid: { display: false }, 
                        ticks: { 
                            color: '#64748b', 
                            font: { weight: 'bold', family: "'Inter', sans-serif", size: 10 },
                            padding: 8
                        } 
                    }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }

    // 2. CHART DEMOGRAFI (DONUT)
    const dc = document.getElementById('donutChart');
    const balitaCount = <?php echo e($stats['total_balita'] ?? 0); ?>;
    const remajaCount = <?php echo e($stats['total_remaja'] ?? 0); ?>;
    const lansiaCount = <?php echo e($stats['total_lansia'] ?? 0); ?>;
    const totalWarga = balitaCount + remajaCount + lansiaCount;

    if(dc && totalWarga > 0) {
        new Chart(dc, {
            type: 'doughnut',
            data: {
                labels: ['Balita', 'Remaja', 'Lansia'],
                datasets: [{
                    data: [balitaCount, remajaCount, lansiaCount],
                    backgroundColor: ['#f43f5e', '#0ea5e9', '#10b981'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: 'rgba(15, 23, 42, 0.9)', 
                        padding: 12, 
                        cornerRadius: 12,
                        titleFont: { size: 10, family: "'Inter', sans-serif", weight: 'bold' },
                        bodyFont: { size: 13, weight: '900', family: "'Poppins', sans-serif" },
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let percentage = Math.round((value / totalWarga) * 100) + '%';
                                return ` ${value} Warga (${percentage})`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/dashboard.blade.php ENDPATH**/ ?>