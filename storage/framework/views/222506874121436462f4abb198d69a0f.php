

<?php $__env->startSection('title', 'Dashboard Operasional'); ?>
<?php $__env->startSection('page-name', 'Beranda Utama'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ====================================================================
       NEXUS ANIMATION & GLASS ENGINE
       ==================================================================== */
    .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
    
    .nexus-glass-card { 
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); 
        border: 1px solid rgba(226, 232, 240, 0.8); 
        box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.05); 
        border-radius: 28px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nexus-glass-card:hover {
        transform: translateY(-4px); border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 20px 50px -10px rgba(79, 70, 229, 0.12);
    }

    /* SCROLLBAR MICRO */
    .micro-scroll::-webkit-scrollbar { width: 4px; }
    .micro-scroll::-webkit-scrollbar-track { background: transparent; }
    .micro-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .micro-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* ====================================================================
       CSS MASCOT ANIMATION (DIAMANKAN UNTUK MOBILE)
       ==================================================================== */
    .mascot-orbit { position: absolute; inset: -8px; border-radius: 50%; border: 2px dashed rgba(255,255,255,0.2); animation: orbitSpin 12s linear infinite; }
    .mascot-orbit::before { content: '✦'; position: absolute; top: -4px; left: 50%; transform: translateX(-50%); font-size: 12px; color: rgba(255,255,255,0.8); }
    @keyframes orbitSpin { to { transform: rotate(360deg); } }
    
    .mascot-face {
        background: linear-gradient(145deg, #ffffff 0%, #eef2ff 100%); border-radius: 50%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2), inset 0 -4px 20px rgba(79,70,229,0.15);
        animation: mascotFloat 3.5s ease-in-out infinite; display: flex; align-items: center; justify-content: center;
    }
    @keyframes mascotFloat { 0%, 100% { transform: translateY(0px) rotate(-2deg); } 50% { transform: translateY(-10px) rotate(2deg); } }

    .spark { position: absolute; border-radius: 50%; animation: sparkle 2.5s ease-in-out infinite; }
    @keyframes sparkle { 0%, 100% { opacity: 0; transform: scale(0); } 50% { opacity: 1; transform: scale(1); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $jam = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
    $sapaan = $jam < 11 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $emoji  = $jam < 11 ? '🌤️' : ($jam < 15 ? '☀️' : ($jam < 18 ? '🌅' : '🌙'));
    $namaDepan = explode(' ', Auth::user()->name)[0] ?? 'Kader';
    $totalWarga = ($stats['total_balita'] ?? 0) + ($stats['total_remaja'] ?? 0) + ($stats['total_lansia'] ?? 0) + ($stats['total_ibu_hamil'] ?? 0);
?>

<div class="max-w-[1400px] mx-auto relative pb-16">

    
    <div class="fixed top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-indigo-50/80 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

    
    <div class="relative rounded-[32px] md:rounded-[40px] p-6 md:p-10 mb-8 overflow-hidden shadow-[0_20px_60px_-15px_rgba(79,70,229,0.4)] fade-in-up border border-indigo-400/30 bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 z-10">
        
        
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHBhdGggZD0iTTAgMGgyMHYyMEgwem0xMCAxMGgxMHYxMEgxMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMSIvPjwvc3ZnPg==')]"></div>
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
            
            
            <div class="flex-1 text-center lg:text-left w-full">
                
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-6">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                    </span>
                    <span id="realtime-clock" class="text-[11px] font-black text-white uppercase tracking-widest font-poppins">Memuat Waktu...</span>
                </div>

                <h1 class="text-3xl md:text-5xl lg:text-[54px] font-black text-white tracking-tight font-poppins mb-4 leading-tight">
                    <?php echo e($sapaan); ?>, <?php echo e($namaDepan); ?>! <?php echo e($emoji); ?>

                </h1>
                <p class="text-indigo-100 font-medium text-[13px] md:text-[15px] leading-relaxed max-w-xl mx-auto lg:mx-0 mb-8">
                    Selamat datang di pusat komando Posyandu. Bersama-sama kita wujudkan generasi yang sehat, cerdas, dan kuat.
                </p>

                
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                    <div class="px-4 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full flex items-center gap-2">
                        <i class="fas fa-users text-sky-300"></i>
                        <span class="text-white text-[12px] font-bold"><?php echo e(number_format($totalWarga)); ?> Total Warga</span>
                    </div>
                    <div class="px-4 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full flex items-center gap-2">
                        <i class="fas fa-syringe text-emerald-300"></i>
                        <span class="text-white text-[12px] font-bold"><?php echo e($stats['imunisasi_hari_ini'] ?? 0); ?> Imunisasi Hari Ini</span>
                    </div>
                    <?php if(($stats['jadwal_hari_ini'] ?? 0) > 0): ?>
                    <div class="px-4 py-2.5 bg-amber-500/20 backdrop-blur-md border border-amber-500/40 rounded-full flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-300"></i>
                        <span class="text-amber-100 text-[12px] font-bold"><?php echo e($stats['jadwal_hari_ini']); ?> Agenda Aktif</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="shrink-0 flex flex-col items-center gap-6 w-full lg:w-auto">
                <div class="relative w-36 h-36 hidden sm:block">
                    <div class="mascot-orbit"></div>
                    <div class="mascot-face w-28 h-28 mx-auto mt-4 text-5xl">🩺</div>
                    <div class="spark bg-amber-300 w-2 h-2 top-4 right-6"></div>
                    <div class="spark bg-sky-300 w-1.5 h-1.5 bottom-8 left-4 delay-100"></div>
                    <div class="spark bg-pink-300 w-2 h-2 top-10 left-6 delay-200"></div>
                </div>

                <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-white text-indigo-600 font-black text-[13px] uppercase tracking-widest rounded-2xl hover:bg-indigo-50 hover:scale-105 transition-all duration-300 shadow-[0_10px_25px_rgba(0,0,0,0.2)] group">
                    <i class="fas fa-stethoscope text-lg group-hover:rotate-12 transition-transform"></i> Mulai Pelayanan
                </a>
            </div>
        </div>
    </div>

    
    <?php
        $statCards = [
            ['label' => 'Total Balita', 'val' => $stats['total_balita'] ?? 0, 'new' => $pendaftaran_bulan_ini['balita'] ?? 0, 'icon' => 'fa-baby', 'col' => 'rose'],
            ['label' => 'Ibu Hamil', 'val' => $stats['total_ibu_hamil'] ?? 0, 'new' => $pendaftaran_bulan_ini['ibu_hamil'] ?? 0, 'icon' => 'fa-female', 'col' => 'pink'],
            ['label' => 'Remaja', 'val' => $stats['total_remaja'] ?? 0, 'new' => $pendaftaran_bulan_ini['remaja'] ?? 0, 'icon' => 'fa-user-graduate', 'col' => 'sky'],
            ['label' => 'Lansia', 'val' => $stats['total_lansia'] ?? 0, 'new' => $pendaftaran_bulan_ini['lansia'] ?? 0, 'icon' => 'fa-wheelchair', 'col' => 'emerald'],
        ];
        $maxVal = max(1, $stats['total_balita'] ?? 1, $stats['total_ibu_hamil'] ?? 1, $stats['total_remaja'] ?? 1, $stats['total_lansia'] ?? 1);
    ?>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-8 relative z-10 fade-in-up delay-1">
        
        <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="nexus-glass-card p-5 md:p-6 flex flex-col justify-between group overflow-hidden relative min-h-[160px]">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-<?php echo e($c['col']); ?>-50 rounded-full opacity-50 pointer-events-none group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-12 h-12 rounded-[14px] bg-<?php echo e($c['col']); ?>-50 text-<?php echo e($c['col']); ?>-500 flex items-center justify-center text-xl border border-<?php echo e($c['col']); ?>-100 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-sm shrink-0">
                    <i class="fas <?php echo e($c['icon']); ?>"></i>
                </div>
                <span class="px-2.5 py-1 bg-<?php echo e($c['new'] > 0 ? $c['col'].'-100' : 'slate-100'); ?> text-<?php echo e($c['new'] > 0 ? $c['col'].'-600' : 'slate-400'); ?> text-[9px] font-black rounded-full border border-<?php echo e($c['new'] > 0 ? $c['col'].'-200' : 'slate-200'); ?>">
                    <?php echo e($c['new'] > 0 ? '+'.$c['new'] : '0'); ?> BLN
                </span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-slate-800 font-poppins leading-none"><?php echo e(number_format($c['val'])); ?></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5"><?php echo e($c['label']); ?></p>
                
                <div class="w-full h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-<?php echo e($c['col']); ?>-400 rounded-full transition-all duration-1000" style="width: <?php echo e(min(100, ($c['val'] / $maxVal) * 100)); ?>%"></div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="nexus-glass-card col-span-2 lg:col-span-1 p-5 md:p-6 flex flex-col justify-between group overflow-hidden relative min-h-[160px] bg-gradient-to-br from-slate-800 to-slate-900 border-slate-700">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-xl"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-12 h-12 rounded-[14px] bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xl border border-indigo-400/30 shadow-sm shrink-0">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="px-2.5 py-1 bg-amber-500/20 text-amber-300 text-[9px] font-black rounded-full border border-amber-500/30 flex items-center gap-1">
                    <i class="fas fa-bolt text-[8px]"></i> AKTIF
                </span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-white font-poppins leading-none"><?php echo e($stats['jadwal_hari_ini'] ?? 0); ?></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Agenda Hari Ini</p>
                <div class="w-full h-1.5 bg-slate-700 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full" style="width: <?php echo e(($stats['jadwal_hari_ini'] ?? 0) > 0 ? '100%' : '0%'); ?>"></div>
                </div>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 relative z-10 fade-in-up delay-2">
        
        
        <div class="xl:col-span-8 nexus-glass-card overflow-hidden flex flex-col min-h-[400px]">
            <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-white/50 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-none">Trafik Absensi Warga</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">Pergerakan kehadiran selama 7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                        <span class="text-[11px] font-bold text-slate-500">Absensi</span>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-100">7 Hari</span>
                </div>
            </div>
            
            <div class="p-6 md:p-8 flex-1 relative bg-white/30">
                <?php if(empty($chartData) || array_sum($chartData) == 0): ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-slate-50/50 m-6 rounded-3xl border-2 border-dashed border-slate-200">
                        <div class="text-5xl mb-4 animate-bounce"><i class="fas fa-chart-bar text-slate-300"></i></div>
                        <h4 class="text-[14px] font-black text-slate-700 font-poppins">Belum Ada Data Absensi</h4>
                        <p class="text-[12px] text-slate-400 mt-1 max-w-xs">Grafik analitik akan tergambar otomatis setelah warga melakukan registrasi kehadiran.</p>
                    </div>
                <?php else: ?>
                    <div class="relative w-full h-[250px] md:h-[300px]">
                        <canvas id="trafficChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="px-6 md:px-8 py-4 bg-slate-50/80 border-t border-slate-100 flex flex-wrap gap-6 md:gap-10">
                <?php
                    $totalChart = array_sum($chartData ?? [0]);
                    $avgChart = count($chartData ?? []) > 0 ? round($totalChart / count($chartData)) : 0;
                    $maxChart = !empty($chartData) ? max($chartData) : 0;
                ?>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Hadir</p>
                    <p class="text-xl font-black text-slate-800 font-poppins"><?php echo e($totalChart); ?></p>
                </div>
                <div class="w-px h-10 bg-slate-200 hidden md:block"></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rata-rata Harian</p>
                    <p class="text-xl font-black text-indigo-600 font-poppins"><?php echo e($avgChart); ?></p>
                </div>
                <div class="w-px h-10 bg-slate-200 hidden md:block"></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Puncak Tertinggi</p>
                    <p class="text-xl font-black text-emerald-500 font-poppins"><?php echo e($maxChart); ?></p>
                </div>
            </div>
        </div>

        
        <div class="xl:col-span-4 nexus-glass-card overflow-hidden flex flex-col min-h-[400px]">
            <div class="px-6 py-5 border-b border-slate-100 bg-white/50 flex justify-between items-center">
                <div>
                    <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-none">Warga Baru</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">Balita terdaftar terbaru</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm"><i class="fas fa-baby"></i></div>
            </div>
            
            <div class="p-4 flex-1 overflow-y-auto micro-scroll bg-slate-50/30 max-h-[300px]">
                <?php if(isset($balita_baru) && count($balita_baru) > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $balita_baru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $balita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 bg-white border border-slate-100 rounded-[16px] flex items-center gap-4 hover:shadow-md hover:border-indigo-100 transition-all group">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-baby text-sm"></i></div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex justify-between items-start mb-0.5">
                                        <p class="text-[13px] font-black text-slate-800 truncate font-poppins"><?php echo e($balita->nama_lengkap ?? 'Balita'); ?></p>
                                        <span class="text-[9px] font-black px-2 py-0.5 bg-slate-100 text-slate-500 rounded-md shrink-0"><?php echo e($balita->created_at->translatedFormat('d M')); ?></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate"><span class="font-bold text-rose-500">Balita</span> &bull; Terdaftar di sistem</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="h-full flex flex-col items-center justify-center text-center p-6">
                        <div class="text-4xl mb-3"><i class="fas fa-inbox text-slate-300"></i></div>
                        <h4 class="text-[13px] font-black text-slate-700 font-poppins">Belum Ada Data</h4>
                        <p class="text-[11px] text-slate-400 mt-1">Data warga baru akan muncul di sini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="p-4 bg-white border-t border-slate-100">
                <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="w-full py-3.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 rounded-xl text-[11px] font-black uppercase tracking-widest text-center flex items-center justify-center gap-2 transition-all">
                    Database Balita <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6 relative z-10 fade-in-up delay-3">
        
        
        <div class="xl:col-span-7 nexus-glass-card overflow-hidden flex flex-col min-h-[300px]">
            <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-white/50 flex justify-between items-center">
                <div>
                    <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-none">Jadwal Mendatang</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">Agenda operasional posyandu</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100 shadow-sm"><i class="fas fa-calendar-alt"></i></div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col justify-center bg-white/30">
                <?php $__empty_1 = true; $__currentLoopData = $jadwal_mendatang ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 mb-3 last:mb-0 bg-white border border-slate-100 rounded-[20px] hover:border-indigo-200 hover:shadow-lg transition-all group">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex flex-col items-center justify-center shrink-0 border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <span class="text-lg font-black font-poppins leading-none"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                            <span class="text-[9px] font-black uppercase tracking-widest mt-0.5"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[14px] font-black text-slate-800 truncate font-poppins"><?php echo e($jadwal->nama_kegiatan ?? 'Kegiatan Posyandu'); ?></p>
                            <p class="text-[12px] font-medium text-slate-500 mt-1 truncate"><i class="fas fa-map-marker-alt text-slate-400 mr-1.5"></i><?php echo e($jadwal->lokasi ?? 'Posyandu Bantarkulon'); ?></p>
                        </div>
                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-black rounded-full w-max">AKTIF</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center text-center p-6">
                        <div class="text-4xl mb-3"><i class="far fa-calendar-times text-slate-300"></i></div>
                        <h4 class="text-[14px] font-black text-slate-700 font-poppins">Tidak Ada Jadwal</h4>
                        <p class="text-[12px] text-slate-400 mt-1">Belum ada agenda mendatang yang dijadwalkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="xl:col-span-5 nexus-glass-card overflow-hidden flex flex-col min-h-[300px]">
            <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-white/50 flex justify-between items-center">
                <div>
                    <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-none">Pendaftaran Bulan Ini</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-1">Distribusi warga <?php echo e(now()->translatedFormat('F Y')); ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center border border-sky-100 shadow-sm"><i class="fas fa-chart-pie"></i></div>
            </div>
            
            <div class="p-6 md:p-8 flex-1 flex flex-col sm:flex-row items-center justify-center gap-8 md:gap-10 bg-white/30">
                
                <div class="relative w-[160px] h-[160px] shrink-0">
                    <canvas id="donutChart"></canvas>
                    <?php $totalBulan = array_sum(array_values($pendaftaran_bulan_ini ?? ['balita'=>0,'remaja'=>0,'lansia'=>0,'ibu_hamil'=>0])); ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-3xl font-black text-slate-800 font-poppins leading-none"><?php echo e($totalBulan); ?></p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">WARGA</p>
                    </div>
                </div>

                
                <div class="flex flex-col gap-3 w-full sm:w-auto">
                    <?php $__currentLoopData = [
                        ['label' => 'Balita', 'val' => $pendaftaran_bulan_ini['balita'] ?? 0, 'col' => '#fb7185'],
                        ['label' => 'Ibu Hamil', 'val' => $pendaftaran_bulan_ini['ibu_hamil'] ?? 0, 'col' => '#f472b6'],
                        ['label' => 'Remaja', 'val' => $pendaftaran_bulan_ini['remaja'] ?? 0, 'col' => '#38bdf8'],
                        ['label' => 'Lansia', 'val' => $pendaftaran_bulan_ini['lansia'] ?? 0, 'col' => '#34d399'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $di): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between gap-6 px-4 py-2 bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: <?php echo e($di['col']); ?>"></div>
                            <span class="text-[12px] font-bold text-slate-600"><?php echo e($di['label']); ?></span>
                        </div>
                        <span class="text-[14px] font-black text-slate-800 font-poppins"><?php echo e($di['val']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. JAM REALTIME DENGAN FORMAT PREMIUM
    function initClock() {
        const el = document.getElementById('realtime-clock');
        if (!el) return;
        const hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

        function tick() {
            const d  = new Date();
            const hh = String(d.getHours()).padStart(2,'0');
            const mm = String(d.getMinutes()).padStart(2,'0');
            el.innerHTML = `${hari[d.getDay()]}, ${d.getDate()} ${bulan[d.getMonth()]} &bull; ${hh}:${mm} WIB`;
        }
        tick(); setInterval(tick, 1000);
    }

    // 2. CHART.JS: TRAFIK ABSENSI (LINE CHART)
    function renderTrafficChart() {
        const canvas = document.getElementById('trafficChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const labels = <?php echo json_encode($chartLabels ?? []); ?>;
        const data   = <?php echo json_encode($chartData ?? []); ?>;

        if (window._trafficChart) window._trafficChart.destroy();

        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)'); // Indigo
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        
        window._trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kehadiran Warga',
                    data: data,
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, // Curvy line
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b', padding: 12, cornerRadius: 12,
                        titleFont: { size: 13, family: "'Poppins', sans-serif" },
                        bodyFont: { size: 12 }, displayColors: false,
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { stepSize: 1, padding: 10, color: '#94a3b8' } },
                    x: { grid: { display: false, drawBorder: false }, ticks: { padding: 10, color: '#94a3b8', font: {weight: 'bold'} } }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }

    // 3. CHART.JS: DISTRIBUSI WARGA (DONUT CHART)
    function renderDonutChart() {
        const canvas = document.getElementById('donutChart');
        if (!canvas || typeof Chart === 'undefined') return;

        if (window._donutChart) window._donutChart.destroy();

        const donutData = [
            <?php echo $pendaftaran_bulan_ini['balita'] ?? 0; ?>,
            <?php echo $pendaftaran_bulan_ini['ibu_hamil'] ?? 0; ?>,
            <?php echo $pendaftaran_bulan_ini['remaja'] ?? 0; ?>,
            <?php echo $pendaftaran_bulan_ini['lansia'] ?? 0; ?>

        ];

        const total = donutData.reduce((a,b) => a+b, 0);
        const finalData = total === 0 ? [1,1,1,1] : donutData; // Placeholder jika kosong
        const bgColors = total === 0 ? ['#f1f5f9','#f1f5f9','#f1f5f9','#f1f5f9'] : ['#fb7185','#f472b6','#38bdf8','#34d399'];

        window._donutChart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Balita','Ibu Hamil','Remaja','Lansia'],
                datasets: [{ data: finalData, backgroundColor: bgColors, borderColor: '#ffffff', borderWidth: 4, hoverOffset: 4 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: total > 0, backgroundColor: '#1e293b', padding: 12, cornerRadius: 12, displayColors: false,
                        callbacks: { label: (item) => `${item.label}: ${total > 0 ? item.parsed : 0} orang` }
                    }
                }
            }
        });
    }

    // INIT ALL SCRIPTS (Aman untuk SPA Navigation)
    document.addEventListener('DOMContentLoaded', () => {
        initClock();
        setTimeout(() => { renderTrafficChart(); renderDonutChart(); }, 200);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/dashboard.blade.php ENDPATH**/ ?>