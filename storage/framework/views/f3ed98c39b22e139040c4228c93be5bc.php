<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#ffffff">
    <title><?php echo $__env->yieldContent('title', 'Kader Workspace'); ?> — PosyanduCare</title>
    
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNGY0NmU1IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTIwLjQyIDEwLjE4YTUuMiA1LjIgMCAwIDAtNy4zNy03LjM3bC0xLjA1IDEuMDUtMS4wNS0xLjA1YTUuMiA1LjIgMCAwIDAtNy4zNyA3LjM3bDEuMDUgMS4wNUwxMiAyMS4wNWw3LjM3LTcuMzcgMS4wNS0xLjA1eiIvPjwvc3ZnPg==">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    
    <style type="text/tailwindcss">
        @theme { 
            --font-sans: 'Plus Jakarta Sans', sans-serif; 
            --font-poppins: 'Poppins', sans-serif; 
        }
        body { 
            font-family: var(--font-sans); 
            background-color: #f8fafc; 
            -webkit-font-smoothing: antialiased;
            color: #0f172a;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        /* Scrollbar Premium */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Loading Bar SPA (NProgress Style) */
        @keyframes loadingBar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }
        .animate-loading-bar { animation: loadingBar 1s infinite ease-in-out; }

        /* Glass Panel Khusus Kader */
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="flex h-screen overflow-hidden selection:bg-indigo-200 selection:text-indigo-900" x-data="{ sidebarOpen: false, isNavigating: false, notifOpen: false, userOpen: false }">

    
    <div x-show="isNavigating" class="fixed top-0 left-0 w-full h-1 z-[9999] bg-indigo-100 overflow-hidden" style="display: none;">
        <div class="h-full bg-indigo-600 animate-loading-bar w-1/3 rounded-full"></div>
    </div>

    
    <div id="toastContainer" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-full max-w-[340px] pointer-events-none"></div>

    
    <div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 xl:hidden" style="display: none;"></div>

    <?php 
        $route = request()->route()->getName();
        $isDataWarga = Str::startsWith($route, 'kader.data.');
        $isAbsensi = in_array($route, ['kader.absensi.index', 'kader.absensi.riwayat']);
        
        $menuAktif = 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 font-bold scale-[1.02]';
        $menuPasif = 'text-slate-500 hover:bg-slate-100 hover:text-indigo-600 font-semibold';
        $iconAktif = 'text-white';
        $iconPasif = 'text-slate-400 group-hover:text-indigo-500 transition-colors';
    ?>

    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/80 transform xl:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl xl:shadow-none">
        
        
        <div class="h-[76px] flex items-center px-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3 w-full cursor-pointer" onclick="window.location.href='<?php echo e(route('kader.dashboard')); ?>'">
                <div class="w-10 h-10 rounded-[14px] bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(79,70,229,0.3)] shrink-0 hover:rotate-6 transition-transform">
                    <i class="fas fa-heart-pulse text-[18px]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-[21px] font-black text-slate-800 tracking-tight truncate font-poppins">Kader<span class="text-indigo-600">Care</span></h1>
                </div>
                <button @click.stop="sidebarOpen = false" class="xl:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        
        <nav class="flex-1 overflow-y-auto px-4 py-6 custom-scrollbar space-y-7">
            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins">Workspace Utama</p>
                <a href="<?php echo e(route('kader.dashboard')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e($route == 'kader.dashboard' ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-chart-pie text-[16px] <?php echo e($route == 'kader.dashboard' ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="tracking-wide">Dashboard Operasional</span>
                </a>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins">Entitas Pasien</p>
                <div class="space-y-1" x-data="{ openWarga: <?php echo e($isDataWarga ? 'true' : 'false'); ?> }">
                    <button @click="openWarga = !openWarga" class="w-full group flex items-center justify-between px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e($isDataWarga ? 'bg-slate-50 text-indigo-700 font-bold' : $menuPasif); ?>">
                        <div class="flex items-center gap-3.5">
                            <div class="w-5 flex justify-center"><i class="fas fa-users text-[16px] <?php echo e($isDataWarga ? 'text-indigo-600' : $iconPasif); ?>"></i></div>
                            <span class="tracking-wide">Database Warga</span>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openWarga ? 'rotate-180 text-indigo-600' : 'text-slate-400'"></i>
                    </button>
                    
                    <div x-show="openWarga" x-collapse class="overflow-hidden">
                        <div class="pl-[46px] pr-2 py-1.5 space-y-1 relative before:absolute before:left-[24px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100 before:rounded-full">
                            <?php $__currentLoopData = [
                                ['route' => 'kader.data.balita.index', 'label' => 'Data Balita', 'active' => Str::startsWith($route, 'kader.data.balita')],
                                ['route' => 'kader.data.ibu-hamil.index', 'label' => 'Data Ibu Hamil', 'active' => Str::startsWith($route, 'kader.data.ibu-hamil')],
                                ['route' => 'kader.data.remaja.index', 'label' => 'Data Remaja', 'active' => Str::startsWith($route, 'kader.data.remaja')],
                                ['route' => 'kader.data.lansia.index', 'label' => 'Data Lansia', 'active' => Str::startsWith($route, 'kader.data.lansia')],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route($item['route'])); ?>" class="spa-route block px-4 py-2.5 text-[12px] rounded-xl transition-all relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($item['active'] ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200'); ?>">
                                    <?php echo e($item['label']); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins">Tugas Lapangan</p>
                <div class="space-y-1.5">
                    
                    <div x-data="{ openAbsensi: <?php echo e($isAbsensi ? 'true' : 'false'); ?> }">
                        <button @click="openAbsensi = !openAbsensi" class="w-full group flex items-center justify-between px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e($isAbsensi ? 'bg-slate-50 text-indigo-700 font-bold' : $menuPasif); ?>">
                            <div class="flex items-center gap-3.5">
                                <div class="w-5 flex justify-center"><i class="fas fa-clipboard-user text-[16px] <?php echo e($isAbsensi ? 'text-indigo-600' : $iconPasif); ?>"></i></div>
                                <span class="tracking-wide">Buku Kehadiran</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openAbsensi ? 'rotate-180 text-indigo-600' : 'text-slate-400'"></i>
                        </button>
                        <div x-show="openAbsensi" x-collapse class="overflow-hidden">
                            <div class="pl-[46px] pr-2 py-1.5 space-y-1 relative before:absolute before:left-[24px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100 before:rounded-full">
                                <a href="<?php echo e(route('kader.absensi.index')); ?>" class="spa-route block px-4 py-2.5 text-[12px] rounded-xl transition-all relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($route == 'kader.absensi.index' ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200'); ?>">Input Presensi</a>
                                <a href="<?php echo e(route('kader.absensi.riwayat')); ?>" class="spa-route block px-4 py-2.5 text-[12px] rounded-xl transition-all relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($route == 'kader.absensi.riwayat' ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200'); ?>">Riwayat Arsip</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-stethoscope text-[16px] <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Pemeriksaan Medis</span>
                    </a>
                    <a href="<?php echo e(route('kader.imunisasi.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.imunisasi') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-syringe text-[16px] <?php echo e(Str::startsWith($route, 'kader.imunisasi') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Imunisasi Vaksin</span>
                    </a>
                    <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-notes-medical text-[16px] <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Log Antrian Tamu</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins">Manajemen Alat</p>
                <div class="space-y-1.5">
                    <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-calendar-alt text-[16px] <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Jadwal Posyandu</span>
                    </a>
                    <a href="<?php echo e(route('kader.import.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.import') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-file-import text-[16px] <?php echo e(Str::startsWith($route, 'kader.import') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Import Data Masal</span>
                    </a>
                    <a href="<?php echo e(route('kader.laporan.index')); ?>" class="spa-route group flex items-center gap-3.5 px-4 py-3.5 rounded-[14px] text-[13px] transition-all <?php echo e(Str::startsWith($route, 'kader.laporan') ? $menuAktif : $menuPasif); ?>">
                        <div class="w-5 flex justify-center"><i class="fas fa-file-pdf text-[16px] <?php echo e(Str::startsWith($route, 'kader.laporan') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="tracking-wide">Cetak Dokumen PDF</span>
                    </a>
                </div>
            </div>
            
        </nav>

        
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 shrink-0">
            <a href="<?php echo e(route('kader.profile.index')); ?>" class="spa-route flex items-center gap-3 p-2 rounded-xl hover:bg-white hover:shadow-sm border border-transparent hover:border-slate-200 transition-all group">
                <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-black text-sm group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner group-hover:shadow-indigo-500/30">
                    <?php echo e(strtoupper(substr(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'K', 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-800 truncate"><?php echo e(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'Kader'); ?></p>
                    <p class="text-[10px] font-bold text-slate-400 truncate mt-0.5">Pengaturan Akun</p>
                </div>
                <i class="fas fa-cog text-slate-300 text-sm group-hover:text-indigo-500 transition-colors"></i>
            </a>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col min-w-0 h-screen bg-slate-50 relative xl:ml-[280px]">
        
        
        <header class="h-[76px] glass-panel sticky top-0 z-40 flex items-center justify-between px-4 lg:px-8 border-b border-slate-200/60 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            
            <div class="flex items-center gap-3 lg:gap-5">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-[12px] transition-colors bg-white border border-slate-200 shadow-sm xl:hidden">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <div class="hidden md:flex flex-col">
                    <h2 class="text-[18px] font-black text-slate-800 tracking-tight font-poppins leading-none"><?php echo $__env->yieldContent('page-name', 'Beranda'); ?></h2>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] font-bold text-slate-400 tracking-wide uppercase">
                        <span>Workspace</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-indigo-500">Sistem Berjalan</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-3">
                
                
                <div class="hidden xl:flex items-center bg-slate-100 hover:bg-slate-200/50 rounded-full px-4 py-2.5 w-64 transition-all focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-300 border border-transparent">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Pencarian cepat (Tekan '/')" class="bg-transparent border-none outline-none text-[12px] w-full ml-3 placeholder:text-slate-400 font-bold text-slate-700">
                </div>

                <div class="w-px h-6 bg-slate-200 mx-1 hidden lg:block"></div>
                
                <?php
                    $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
                    $latestNotifs = \App\Models\Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();
                ?>

                
                <div class="relative">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative w-10 h-10 flex items-center justify-center bg-white text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-[12px] transition-all border border-slate-200 shadow-sm hover:shadow group">
                        <i class="fas fa-bell text-[16px] group-hover:animate-shake"></i>
                        <?php if($unreadNotifCount > 0): ?>
                            <span id="notifBadge" class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500 border-2 border-white"></span>
                            </span>
                        <?php endif; ?>
                    </button>
                    
                    
                    <div x-show="notifOpen" x-transition.opacity.scale.95 style="display: none;" class="absolute top-[120%] right-0 w-[calc(100vw-2rem)] mx-4 sm:mx-0 sm:w-[380px] bg-white/95 backdrop-blur-xl border border-slate-200 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.1)] rounded-[24px] z-50 overflow-hidden flex flex-col origin-top-right">
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 rounded-t-[24px]">
                            <h3 class="text-[13px] font-black text-slate-800 font-poppins">Pusat Notifikasi</h3>
                            <span id="notifCount" class="text-[10px] font-bold px-2 py-0.5 rounded text-rose-600 bg-rose-50 border border-rose-100 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>"><?php echo e($unreadNotifCount); ?> Baru</span>
                        </div>
                        <div id="notifList" class="max-h-[320px] overflow-y-auto custom-scrollbar flex-1 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $latestNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="spa-route flex gap-4 px-5 py-3.5 hover:bg-slate-50 transition-colors border-b border-slate-50 <?php echo e($n->is_read ? '' : 'bg-indigo-50/30'); ?>">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 <?php echo e($n->is_read ? 'bg-slate-100 text-slate-400' : 'bg-indigo-100 text-indigo-600'); ?>">
                                        <i class="fas fa-<?php echo e(str_contains(strtolower($n->judul), 'jadwal') ? 'calendar-alt' : 'bell'); ?> text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-[13px] font-bold <?php echo e($n->is_read ? 'text-slate-600' : 'text-slate-900'); ?> truncate"><?php echo e($n->judul); ?></p>
                                        <p class="text-[11px] font-medium <?php echo e($n->is_read ? 'text-slate-400' : 'text-slate-600'); ?> line-clamp-1 mt-0.5"><?php echo e($n->pesan); ?></p>
                                        <p class="text-[9px] font-black text-slate-400 mt-1.5 uppercase tracking-widest"><?php echo e($n->created_at->diffForHumans()); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="py-10 text-center text-slate-400"><i class="fas fa-check-circle text-3xl mb-3 opacity-30"></i><p class="text-xs font-bold uppercase tracking-widest">Semua bersih.</p></div>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 border-t border-slate-100 rounded-b-[24px] bg-slate-50/80">
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="spa-route w-full py-2.5 text-[11px] font-black tracking-widest uppercase text-indigo-600 hover:bg-white border border-transparent hover:border-slate-200 shadow-sm text-center rounded-xl transition-all block">Lihat Riwayat</a>
                        </div>
                    </div>
                </div>
                
                
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="hidden sm:block">
                    <?php echo csrf_field(); ?>
                    <button type="submit" @click="isNavigating = true" class="w-10 h-10 flex items-center justify-center bg-white text-rose-500 hover:text-white hover:bg-rose-500 rounded-[12px] transition-all border border-slate-200 shadow-sm" title="Keluar">
                        <i class="fas fa-power-off text-[14px]"></i>
                    </button>
                </form>

            </div>
        </header>

        
        <main :class="{'opacity-50 blur-[2px] pointer-events-none': isNavigating, 'opacity-100 blur-0': !isNavigating}" class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 relative z-0 custom-scrollbar transition-all duration-300 ease-out">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <nav class="xl:hidden fixed bottom-0 left-0 right-0 h-16 glass-panel border-t border-slate-200 z-50 flex items-center justify-around px-2 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.03)]">
            <a href="<?php echo e(route('kader.dashboard')); ?>" class="spa-route flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e($route == 'kader.dashboard' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-chart-pie text-lg mb-1 <?php echo e($route == 'kader.dashboard' ? 'drop-shadow-sm' : ''); ?>"></i> Beranda
            </a>
            <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="spa-route flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e($isDataWarga ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-users text-lg mb-1 <?php echo e($isDataWarga ? 'drop-shadow-sm' : ''); ?>"></i> Warga
            </a>
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="spa-route flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-stethoscope text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'drop-shadow-sm' : ''); ?>"></i> Medis
            </a>
            <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="spa-route flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.jadwal') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-calendar-alt text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.jadwal') ? 'drop-shadow-sm' : ''); ?>"></i> Jadwal
            </a>
        </nav>
    </div>

    
    <script>
        // SPA Routing Navigation
        document.addEventListener('DOMContentLoaded', () => {
            const rootAlpine = document.querySelector('[x-data]').__x.$data;
            document.querySelectorAll('.spa-route').forEach(link => {
                link.addEventListener('click', function(e) {
                    if(!e.ctrlKey && !e.metaKey) { rootAlpine.isNavigating = true; }
                });
            });
            window.addEventListener('pageshow', (e) => { if (e.persisted) rootAlpine.isNavigating = false; });
        });

        // SWEETALERT TOAST ENGINE
        const showToast = (type, title, message) => {
            Swal.fire({
                toast: true, position: 'top-end', icon: type,
                title: title, text: message,
                showConfirmButton: false, timer: 4000, timerProgressBar: true,
                customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100 font-sans' }
            });
        };
        <?php if(session('success')): ?> document.addEventListener('DOMContentLoaded', () => showToast('success', 'Berhasil!', "<?php echo e(session('success')); ?>")); <?php endif; ?>
        <?php if(session('error')): ?> document.addEventListener('DOMContentLoaded', () => showToast('error', 'Error!', "<?php echo e(session('error')); ?>")); <?php endif; ?>

        // AJAX Polling Notifikasi
        document.addEventListener('DOMContentLoaded', () => {
            let currentUnreadNotif = <?php echo e($unreadNotifCount ?? 0); ?>;
            function checkNewNotifications() {
                if(!navigator.onLine) return;
                fetch("<?php echo e(route('kader.notifikasi.fetch')); ?>", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json()).then(data => {
                    const badge = document.getElementById('notifBadge'), countText = document.getElementById('notifCount'), list = document.getElementById('notifList');
                    if (badge) { if (data.unreadCount > 0) badge.classList.remove('hidden'); else badge.classList.add('hidden'); }
                    if (countText) { countText.textContent = data.unreadCount + ' Baru'; countText.className = data.unreadCount > 0 ? 'text-[10px] font-bold px-2 py-0.5 rounded text-rose-600 bg-rose-50 border border-rose-100' : 'hidden'; }
                    if (list) list.innerHTML = data.html;

                    if (data.unreadCount > currentUnreadNotif) {
                        showToast('info', 'Pesan Baru', data.latest_title ?? 'Anda memiliki notifikasi baru.');
                    }
                    currentUnreadNotif = data.unreadCount;
                }).catch(e => {});
            }
            setInterval(checkNewNotifications, 15000); 
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>