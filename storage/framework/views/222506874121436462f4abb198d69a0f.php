

<?php $__env->startSection('title', 'Dashboard Kader'); ?>
<?php $__env->startSection('page-name', 'Beranda Utama'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* =====================================================================
       1. ADVANCED ANIMATIONS & KEYFRAMES
       ===================================================================== */
    .fade-in-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .fade-in-scale { opacity: 0; animation: fadeInScale 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    
    @keyframes fadeInUp { 
        0% { opacity: 0; transform: translateY(20px); } 
        100% { opacity: 1; transform: translateY(0); } 
    }
    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes floatBlob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(15px, -20px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    @keyframes pulseGlow {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }

    /* =====================================================================
       2. PREMIUM CARD UTILITIES
       ===================================================================== */
    .premium-card { 
        background: rgba(255, 255, 255, 0.95); 
        backdrop-filter: blur(20px);
        border: 1px solid rgba(226, 232, 240, 0.6); 
        border-radius: 24px; 
        box-shadow: 0 4px 20px -5px rgba(0,0,0,0.03), 0 2px 10px -2px rgba(0,0,0,0.02);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        position: relative;
        overflow: hidden;
    }
    .premium-card:hover {
        border-color: rgba(199, 210, 254, 0.5);
        box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.08), 0 8px 15px -5px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    /* Stat Card Specifics */
    .stat-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: var(--card-color, #6366f1);
        opacity: 0; transition: opacity 0.3s ease;
    }
    .stat-card:hover::before { opacity: 1; }
    
    .stat-card .icon-wrapper {
        width: 48px; height: 48px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin-bottom: 16px; flex-shrink: 0;
        transition: all 0.4s ease;
        box-shadow: inset 0 2px 4px 0 rgba(255,255,255,0.5);
    }
    .stat-card:hover .icon-wrapper { transform: scale(1.1) rotate(5deg); }
    
    .stat-bg-blob {
        position: absolute; width: 100px; height: 100px; border-radius: 50%;
        background: var(--card-color, #6366f1); opacity: 0.04;
        top: -30px; right: -30px; z-index: 0; pointer-events: none;
        transition: all 0.5s ease;
    }
    .stat-card:hover .stat-bg-blob { transform: scale(2); opacity: 0.06; }

    /* =====================================================================
       3. HERO SECTION BACKGROUNDS
       ===================================================================== */
    .hero-mesh {
        background-color: #ffffff;
        background-image: 
            radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.06) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.04) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.06) 0px, transparent 50%);
    }
    .dot-pattern {
        position: absolute; inset: 0; opacity: 0.3; z-index: 0; pointer-events: none;
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px;
    }
    
    .live-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 14px; border-radius: 99px;
        background: rgba(238, 242, 255, 0.8); border: 1px solid rgba(199, 210, 254, 0.5);
        color: #4f46e5; font-size: 11px; font-weight: 800;
        letter-spacing: 0.1em; text-transform: uppercase;
        backdrop-filter: blur(8px);
    }
    .live-dot { width: 6px; height: 6px; border-radius: 50%; background: #4f46e5; animation: pulseGlow 2s infinite; }

    /* =====================================================================
       4. TIMELINE COMPONENTS
       ===================================================================== */
    .timeline-container { position: relative; padding-left: 28px; }
    .timeline-container::before {
        content: ''; position: absolute; left: 8px; top: 12px; bottom: 12px; width: 2px;
        background: linear-gradient(to bottom, #e2e8f0 0%, #f1f5f9 80%, transparent 100%);
    }
    .tl-item { position: relative; padding-bottom: 20px; }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot {
        position: absolute; left: -28px; top: 4px; width: 18px; height: 18px;
        border-radius: 50%; background: white; border: 4px solid #818cf8;
        box-shadow: 0 0 0 4px rgba(255,255,255,1), 0 2px 4px rgba(0,0,0,0.05);
        z-index: 2;
    }

    .jadwal-list-item {
        display: flex; align-items: center; gap: 16px; padding: 14px;
        border-radius: 16px; border: 1px solid rgba(226, 232, 240, 0.6);
        background: linear-gradient(to right, rgba(248, 250, 252, 0.5), transparent);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .jadwal-list-item:hover {
        background: #ffffff; border-color: #c7d2fe;
        transform: translateX(4px);
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.08);
    }
    .jadwal-cal {
        width: 52px; height: 56px; border-radius: 12px; background: white; border: 1px solid #e2e8f0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 2px 4px -1px rgba(0,0,0,0.02); transition: all 0.3s;
    }
    .jadwal-list-item:hover .jadwal-cal { border-color: #818cf8; background: #eef2ff; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

   
    <?php
        // FIX MANAJEMEN RISIKO: Mengunci Timezone ke WIB agar tidak meleset saat sidang
        $jam = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
        if ($jam < 11) { $greeting = 'Selamat Pagi'; }
        elseif ($jam < 15) { $greeting = 'Selamat Siang'; }
        elseif ($jam < 18) { $greeting = 'Selamat Sore'; }
        else { $greeting = 'Selamat Malam'; }
    ?>

    <div class="premium-card hero-mesh p-8 flex flex-col lg:flex-row items-center justify-between gap-8 fade-in-up border-0 shadow-[0_10px_40px_-10px_rgba(99,102,241,0.12)]">
        <div class="dot-pattern"></div>
        
        <div class="relative z-10 text-center lg:text-left flex-1">
            <div class="live-badge mb-5">
                <span class="live-dot"></span> SISTEM POSYANDU BERJALAN
            </div>

            <h1 class="text-3xl lg:text-[42px] font-black font-poppins text-slate-900 tracking-tight leading-[1.2] mb-3">
                <?php echo e($greeting); ?>, <br class="hidden lg:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">
                    <?php echo e(Str::words(Auth::user()->profile->full_name ?? Auth::user()->name, 2, '')); ?>!
                </span> 👋
            </h1>

            <p class="text-slate-500 text-[14px] leading-relaxed max-w-xl mb-6 font-medium">
                Pusat komando digital Anda. Pantau statistik kesehatan warga, kelola pendaftaran layanan, dan evaluasi grafik pertumbuhan secara *real-time*.
            </p>

            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-3">
                <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-white border border-slate-200/80 shadow-sm text-slate-700 text-[13px] font-bold font-poppins">
                    <div class="w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center text-slate-400"><i class="far fa-calendar-alt"></i></div>
                    <?php echo e(\Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y')); ?>

                </div>
                <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="smooth-route flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-[13px] font-bold font-poppins transition-all shadow-[0_8px_20px_rgba(79,70,229,0.25)] hover:shadow-[0_12px_25px_rgba(79,70,229,0.35)] hover:-translate-y-0.5">
                    <i class="fas fa-stethoscope mr-1"></i> Mulai Pelayanan
                </a>
            </div>
        </div>

        <div class="relative z-10 hidden xl:flex shrink-0 w-64 h-64 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-400/20 to-purple-400/20 rounded-full animate-[floatBlob_8s_infinite]"></div>
            <div class="absolute inset-5 bg-white/50 backdrop-blur-md border border-white rounded-full shadow-2xl flex items-center justify-center">
                <i class="fas fa-heart-pulse text-[70px] text-transparent bg-clip-text bg-gradient-to-br from-indigo-500 to-rose-400 drop-shadow-sm"></i>
            </div>
            
            <div class="absolute top-8 -left-4 bg-white px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 flex items-center gap-2 animate-[floatBlob_6s_infinite_reverse]">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div><span class="text-[9px] font-black text-slate-700 font-poppins uppercase">Data Aman</span>
            </div>
            <div class="absolute bottom-12 -right-4 bg-white px-3 py-1.5 rounded-xl shadow-lg border border-slate-100 flex items-center gap-2 animate-[floatBlob_7s_infinite]">
                <div class="w-5 h-5 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-[10px]"><i class="fas fa-sync-alt"></i></div><span class="text-[9px] font-black text-slate-700 font-poppins uppercase">Sync Cepat</span>
            </div>
        </div>
    </div>

    
    <?php
        // FIX: Label diubah agar mencakup Bayi & Balita. Target route disesuaikan.
        $cards = [
            ['label'=>'Bayi & Balita', 'val'=>$stats['total_balita']??0, 'icon'=>'fa-baby', 'bg'=>'#fff1f2', 'color'=>'#e11d48', 'shadow'=>'rgba(225,29,72,0.15)', 'route'=>'kader.data.balita.index'],
            ['label'=>'Ibu Hamil', 'val'=>$stats['total_ibu_hamil']??0, 'icon'=>'fa-person-pregnant', 'bg'=>'#fdf2f8', 'color'=>'#db2777', 'shadow'=>'rgba(219,39,119,0.15)', 'route'=>'kader.data.ibu-hamil.index'],
            ['label'=>'Remaja', 'val'=>$stats['total_remaja']??0, 'icon'=>'fa-user-graduate', 'bg'=>'#f0f9ff', 'color'=>'#0284c7', 'shadow'=>'rgba(2,132,199,0.15)', 'route'=>'kader.data.remaja.index'],
            ['label'=>'Lansia', 'val'=>$stats['total_lansia']??0, 'icon'=>'fa-person-cane', 'bg'=>'#f0fdf4', 'color'=>'#059669', 'shadow'=>'rgba(5,150,105,0.15)', 'route'=>'kader.data.lansia.index'],
            ['label'=>'Jadwal Hari Ini', 'val'=>$stats['jadwal_hari_ini']??0, 'icon'=>'fa-calendar-day', 'bg'=>'#eef2ff', 'color'=>'#4f46e5', 'shadow'=>'rgba(79,70,229,0.15)', 'route'=>'kader.jadwal.index'],
        ];
    ?>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 fade-in-scale delay-100">
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <a href="<?php echo e(route($c['route'])); ?>" class="premium-card stat-card p-5 flex flex-col relative group cursor-pointer" style="--card-color: <?php echo e($c['color']); ?>;">
            <div class="stat-bg-blob"></div>
            <div class="relative z-10 flex flex-col items-start w-full">
                <div class="flex justify-between items-start w-full">
                    <div class="icon-wrapper" style="background:<?php echo e($c['bg']); ?>; color:<?php echo e($c['color']); ?>; box-shadow: 0 4px 10px <?php echo e($c['shadow']); ?>;">
                        <i class="fas <?php echo e($c['icon']); ?>"></i>
                    </div>
                    
                    <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" style="color:<?php echo e($c['color']); ?>;">
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </div>
                </div>
                <p class="text-[28px] font-black font-poppins text-slate-800 leading-none mb-1 group-hover:scale-105 transition-transform transform origin-left"><?php echo e($c['val']); ?></p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors"><?php echo e($c['label']); ?></p>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 fade-in-up delay-200">

        
        <div class="xl:col-span-2 premium-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fas fa-chart-line"></i></div>
                        <h3 class="text-[16px] font-black font-poppins text-slate-800">Trafik Pelayanan Warga</h3>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium ml-10">Fluktuasi kehadiran pelayanan dalam 7 hari terakhir.</p>
                </div>
                
                <div class="hidden sm:block px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[11px] font-bold font-poppins rounded-lg border border-indigo-100">
                    7 Hari Terakhir
                </div>
            </div>
            
            <div class="relative flex-1 w-full" style="min-height:280px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        
        <div class="premium-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-3">
                <h3 class="text-[15px] font-black font-poppins text-slate-800"><i class="fas fa-history text-slate-300 mr-2"></i>Aktivitas Baru</h3>
                <div class="flex items-center gap-1.5 text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-active"></span> Live
                </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar" style="max-height:280px;">
                <div class="timeline-container">
                    <?php $__empty_1 = true; $__currentLoopData = $kunjungan_baru ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $typeStr = class_basename($kunj->pasien_type);
                            $icons = ['Balita'=>'fa-baby text-sky-500','IbuHamil'=>'fa-female text-pink-500','Remaja'=>'fa-user-graduate text-indigo-500','Lansia'=>'fa-user-clock text-emerald-500'];
                            $iconClass = $icons[$typeStr] ?? 'fa-user text-slate-500';
                            $borderColor = ['Balita'=>'border-sky-400','IbuHamil'=>'border-pink-400','Remaja'=>'border-indigo-400','Lansia'=>'border-emerald-400'][$typeStr] ?? 'border-slate-400';
                        ?>
                        <div class="tl-item">
                            <div class="tl-dot <?php echo e($borderColor); ?> flex items-center justify-center">
                                <i class="fas <?php echo e($iconClass); ?> text-[8px]"></i>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 ml-2 hover:bg-white hover:border-indigo-200 transition-all">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <p class="text-[12px] font-bold text-slate-800 leading-tight truncate"><?php echo e($kunj->pasien->nama_lengkap ?? 'Warga'); ?></p>
                                    <span class="text-[9px] text-slate-400 font-bold bg-white px-1.5 py-0.5 rounded border border-slate-100 whitespace-nowrap"><?php echo e($kunj->created_at->diffForHumans(null, true, true)); ?></span>
                                </div>
                                <p class="text-[11px] text-slate-500 mb-2">Telah mendaftar di buku kehadiran.</p>
                                <span class="inline-block text-[8px] font-black px-2 py-0.5 bg-white text-slate-500 rounded border border-slate-200 uppercase tracking-widest shadow-sm">
                                    Kategori: <?php echo e($typeStr); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex flex-col items-center justify-center h-full text-slate-400 text-center py-6">
                            <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-2"><i class="far fa-folder-open text-xl opacity-50"></i></div>
                            <p class="text-[11px] font-bold font-poppins uppercase tracking-widest text-slate-500">Log Kosong</p>
                            <p class="text-[11px] mt-1">Belum ada kunjungan hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="block w-full text-center text-[11px] font-black font-poppins text-indigo-600 hover:text-indigo-800 uppercase tracking-widest bg-indigo-50/50 hover:bg-indigo-50 py-2 rounded-xl transition-colors">Tampilkan Semua</a>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 fade-in-up delay-300">

        
        <div class="xl:col-span-2 premium-card p-6 bg-gradient-to-br from-white to-slate-50/50">
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-[16px] font-black font-poppins text-slate-800"><i class="far fa-calendar-check text-indigo-400 mr-2"></i>Agenda Posyandu Mendatang</h3>
                </div>
                <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="text-[10px] font-black font-poppins text-slate-600 hover:text-indigo-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider">
                    Lihat Kalender <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__empty_1 = true; $__currentLoopData = $jadwal_mendatang ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jdw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('kader.jadwal.show', $jdw->id)); ?>" class="jadwal-list-item group">
                    <div class="jadwal-cal">
                        <span class="text-[9px] font-black font-poppins text-slate-400 uppercase tracking-widest group-hover:text-indigo-500 transition-colors"><?php echo e(\Carbon\Carbon::parse($jdw->tanggal)->translatedFormat('M')); ?></span>
                        <span class="text-[20px] font-black font-poppins text-slate-800 leading-none mt-0.5 group-hover:text-indigo-600"><?php echo e(\Carbon\Carbon::parse($jdw->tanggal)->format('d')); ?></span>
                    </div>
                    <div class="flex-1 min-w-0 border-l border-slate-200/60 pl-3">
                        <h4 class="text-[13px] font-bold font-poppins text-slate-800 truncate mb-1 group-hover:text-indigo-700 transition-colors"><?php echo e($jdw->judul); ?></h4>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-white px-1.5 py-0.5 rounded border border-slate-100">
                                <i class="far fa-clock text-indigo-400"></i> <?php echo e(\Carbon\Carbon::parse($jdw->waktu_mulai)->format('H:i')); ?>

                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 truncate">
                                <i class="fas fa-map-marker-alt text-rose-400"></i> <?php echo e(Str::limit($jdw->lokasi, 15)); ?>

                            </span>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-all shrink-0">
                        <i class="fas fa-chevron-right text-[9px]"></i>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="md:col-span-2 py-8 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                    <i class="far fa-calendar-times text-2xl text-slate-300 mb-2"></i>
                    <h4 class="text-[12px] font-black font-poppins text-slate-600">Agenda Kosong</h4>
                    <p class="text-[11px]">Belum ada jadwal kegiatan posyandu terbaru.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="premium-card p-6 flex flex-col">
            <div class="mb-4 text-center border-b border-slate-100 pb-3">
                <h3 class="text-[16px] font-black font-poppins text-slate-800"><i class="fas fa-chart-pie text-pink-400 mr-2"></i>Distribusi Peserta</h3>
            </div>

            <?php 
                $totalW = ($stats['total_balita']??0)+($stats['total_remaja']??0)+($stats['total_lansia']??0)+($stats['total_ibu_hamil']??0); 
            ?>

            <div class="relative mx-auto mb-6 mt-2" style="width:180px;height:180px;">
                <?php if($totalW > 0): ?>
                    <canvas id="donutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[9px] font-bold font-poppins text-slate-400 uppercase tracking-widest mb-0.5">Total Data</span>
                        <span class="text-[28px] font-black font-poppins text-slate-800 leading-none"><?php echo e($totalW); ?></span>
                    </div>
                <?php else: ?>
                    <div class="absolute inset-0 rounded-full border-[8px] border-slate-100 flex flex-col items-center justify-center text-slate-400 bg-slate-50 shadow-inner">
                        <i class="fas fa-database text-2xl opacity-20 mb-1"></i>
                        <span class="text-[9px] font-black font-poppins uppercase tracking-widest">Kosong</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-auto">
                <?php $__currentLoopData = [
                    ['label'=>'Balita',    'val'=>$stats['total_balita']??0,    'color'=>'bg-rose-500', 'border'=>'border-rose-200'],
                    ['label'=>'Bumil',     'val'=>$stats['total_ibu_hamil']??0, 'color'=>'bg-pink-500', 'border'=>'border-pink-200'],
                    ['label'=>'Remaja',    'val'=>$stats['total_remaja']??0,    'color'=>'bg-sky-500',  'border'=>'border-sky-200'],
                    ['label'=>'Lansia',    'val'=>$stats['total_lansia']??0,    'color'=>'bg-emerald-500','border'=>'border-emerald-200'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-white hover:shadow-sm transition-all group">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full <?php echo e($d['color']); ?> shadow-sm"></span>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider font-poppins"><?php echo e($d['label']); ?></span>
                    </div>
                    <span class="text-[11px] font-black font-poppins text-slate-800 bg-white px-1.5 py-0.5 rounded border <?php echo e($d['border']); ?> shadow-sm"><?php echo e($d['val']); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Global Chart.js Configuration
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    const formattedDates = <?php echo json_encode($chartLabels ?? []); ?>;
    const rawData        = <?php echo json_encode($chartData ?? []); ?>;

    // =========================================================
    // 1. ADVANCED LINE CHART (Trafik Kehadiran)
    // =========================================================
    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx  = lc.getContext('2d');
        
        const grad = ctx.createLinearGradient(0, 0, 0, 300);
        grad.addColorStop(0, 'rgba(99,102,241,0.25)'); // Indigo
        grad.addColorStop(1, 'rgba(99,102,241,0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Kedatangan',
                    data: rawData,
                    borderColor: '#4f46e5',
                    backgroundColor: grad,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, 
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        titleFont: { size: 12, family: "'Poppins', sans-serif", weight: 'bold' },
                        bodyColor: '#e2e8f0',
                        bodyFont: { size: 11, weight: '500' },
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return context.parsed.y + ' Warga Dilayani'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: '#f1f5f9', drawTicks: false },
                        ticks: { stepSize: 1, padding: 8, font: { size: 10, weight: '600' } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { padding: 8, font: { size: 10, weight: '600' } }
                    }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }

    // =========================================================
    // 2. PREMIUM DONUT CHART (Distribusi Demografi)
    // =========================================================
    const dc  = document.getElementById('donutChart');
    const bC  = <?php echo e($stats['total_balita']    ?? 0); ?>;
    const ihC = <?php echo e($stats['total_ibu_hamil'] ?? 0); ?>;
    const rC  = <?php echo e($stats['total_remaja']    ?? 0); ?>;
    const lC  = <?php echo e($stats['total_lansia']    ?? 0); ?>;
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
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '78%',
                layout: { padding: 5 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#0f172a',
                        bodyColor: '#0f172a',
                        titleFont: { size: 12, family: "'Poppins', sans-serif", weight: 'bold' },
                        bodyFont: { size: 11, weight: 'bold' },
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) label += ': ';
                                let value = context.parsed;
                                let percentage = Math.round((value / tot) * 100);
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/dashboard.blade.php ENDPATH**/ ?>