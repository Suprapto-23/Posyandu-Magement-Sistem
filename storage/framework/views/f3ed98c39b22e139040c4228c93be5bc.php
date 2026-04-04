<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#ffffff">
    <title><?php echo $__env->yieldContent('title', 'Kader Workspace'); ?> — PosyanduCare</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%234f46e5'/%3E%3Cstop offset='100%25' stop-color='%236d28d9'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23g)'/%3E%3Cpath d='M16 23.5l-1.2-1.1C10.2 18.3 7.5 15.8 7.5 12.5 7.5 10 9.5 8 12 8c1.4 0 2.8.7 4 1.9C17.2 8.7 18.6 8 20 8c2.5 0 4.5 2 4.5 4.5 0 3.3-2.7 5.8-7.3 9.9L16 23.5z' fill='white' opacity='0.95'/%3E%3C/svg%3E">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    
    <style type="text/tailwindcss">
        @theme { 
            --font-sans: 'Inter', sans-serif; 
            --font-poppins: 'Poppins', sans-serif; 
        }
        body { 
            font-family: var(--font-sans); 
            background-color: #f8fafc; 
            -webkit-font-smoothing: antialiased;
            color: #0f172a;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        /* Ultra Clean Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }

        /* Smooth UI Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
        .menu-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .submenu-grid { display: grid; transition: grid-template-rows 0.35s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Toast Animation Spring */
        @keyframes toastEnter { 0% { opacity: 0; transform: translateX(100%) scale(0.9); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes toastLeave { 0% { opacity: 1; transform: translateX(0) scale(1); max-height: 100px; margin-bottom: 12px; } 100% { opacity: 0; transform: translateX(100%) scale(0.9); max-height: 0; margin-bottom: 0; padding: 0; border: 0; } }
        .toast-show { animation: toastEnter 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .toast-hide { animation: toastLeave 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="flex h-screen overflow-hidden selection:bg-indigo-100 selection:text-indigo-900">

    
    <div id="offlineBanner" class="fixed top-0 left-0 right-0 z-[99999] bg-rose-500 text-white text-[11px] font-black uppercase tracking-widest py-2 text-center transform -translate-y-full transition-transform duration-300 flex items-center justify-center gap-2 shadow-lg">
        <i class="fas fa-wifi-slash animate-pulse"></i> Koneksi Terputus. Menunggu Jaringan...
    </div>

    
    <div id="toastContainer" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-full max-w-[340px] pointer-events-none"></div>

    
    <div id="globalLoader" class="fixed inset-0 z-[9998] bg-slate-50/80 backdrop-blur-md flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="relative w-16 h-16 flex items-center justify-center mb-4">
            <div class="absolute inset-0 border-4 border-slate-200 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                <i class="fas fa-heart-pulse text-indigo-600 animate-pulse text-lg"></i>
            </div>
        </div>
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Memuat Layar</p>
    </div>

    
    <div id="mobileOverlay" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 lg:hidden"></div>

    <?php 
        $route = request()->route()->getName();
        $isDataWarga = Str::startsWith($route, 'kader.data.');
        $isAbsensi = in_array($route, ['kader.absensi.index', 'kader.absensi.riwayat']);
        
        // PILIHAN GAYA MENU YANG SUPER CLEAN & SEXY
        $menuAktif = 'bg-indigo-50/80 text-indigo-700 font-bold shadow-[0_2px_10px_rgba(79,70,229,0.06)]';
        $menuPasif = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium';
        $iconAktif = 'text-indigo-600';
        $iconPasif = 'text-slate-400 group-hover:text-indigo-500 transition-colors';
    ?>

    
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/80 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col shadow-2xl lg:shadow-none">
        
        
        <div class="h-[76px] flex items-center px-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(79,70,229,0.3)] shrink-0">
                    <i class="fas fa-heart-pulse text-[18px]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-[21px] font-black text-slate-800 tracking-tight truncate font-poppins">Kader<span class="text-indigo-600">Care</span></h1>
                </div>
                <button id="closeSidebar" class="lg:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        
        <nav class="flex-1 overflow-y-auto px-4 py-6 custom-scrollbar space-y-7">
            
            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Overview</p>
                <a href="<?php echo e(route('kader.dashboard')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e($route == 'kader.dashboard' ? $menuAktif : $menuPasif); ?>">
                    <i class="fas fa-chart-pie w-5 text-center text-[16px] <?php echo e($route == 'kader.dashboard' ? $iconAktif : $iconPasif); ?>"></i> 
                    <span>Dashboard Utama</span>
                </a>
            </div>

            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Registrasi</p>
                <div class="space-y-1">
                    <button onclick="toggleSubmenu('menuPasien', 'iconPasien')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e($isDataWarga ? $menuAktif : $menuPasif); ?>">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users w-5 text-center text-[16px] <?php echo e($isDataWarga ? $iconAktif : $iconPasif); ?>"></i> 
                            <span>Database Warga</span>
                        </div>
                        <i id="iconPasien" class="fas fa-chevron-down text-[10px] transition-transform duration-300 <?php echo e($isDataWarga ? 'rotate-180 text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'); ?>"></i>
                    </button>
                    
                    <div id="menuPasien" class="submenu-grid <?php echo e($isDataWarga ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'); ?>">
                        <div class="overflow-hidden">
                            <div class="pl-[48px] pr-2 py-1 space-y-1 relative before:absolute before:left-[26px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                                <?php $__currentLoopData = [
                                    ['route' => 'kader.data.balita.index', 'label' => 'Data Balita', 'active' => Str::startsWith($route, 'kader.data.balita')],
                                    ['route' => 'kader.data.ibu-hamil.index', 'label' => 'Data Ibu Hamil', 'active' => Str::startsWith($route, 'kader.data.ibu-hamil')],
                                    ['route' => 'kader.data.remaja.index', 'label' => 'Data Remaja', 'active' => Str::startsWith($route, 'kader.data.remaja')],
                                    ['route' => 'kader.data.lansia.index', 'label' => 'Data Lansia', 'active' => Str::startsWith($route, 'kader.data.lansia')],
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route($item['route'])); ?>" class="smooth-route block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($item['active'] ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300'); ?>">
                                        <?php echo e($item['label']); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Operasional</p>
                <div class="space-y-1">
                    
                    <button onclick="toggleSubmenu('menuAbsensi', 'iconAbsensi')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e($isAbsensi ? $menuAktif : $menuPasif); ?>">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clipboard-user w-5 text-center text-[16px] <?php echo e($isAbsensi ? $iconAktif : $iconPasif); ?>"></i> 
                            <span>Buku Kehadiran</span>
                        </div>
                        <i id="iconAbsensi" class="fas fa-chevron-down text-[10px] transition-transform duration-300 <?php echo e($isAbsensi ? 'rotate-180 text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'); ?>"></i>
                    </button>
                    
                    <div id="menuAbsensi" class="submenu-grid <?php echo e($isAbsensi ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'); ?>">
                        <div class="overflow-hidden">
                            <div class="pl-[48px] pr-2 py-1 space-y-1 relative before:absolute before:left-[26px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                                <a href="<?php echo e(route('kader.absensi.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($route == 'kader.absensi.index' ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300'); ?>">Input Presensi</a>
                                <a href="<?php echo e(route('kader.absensi.riwayat')); ?>" class="smooth-route block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full <?php echo e($route == 'kader.absensi.riwayat' ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300'); ?>">Riwayat Arsip</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-stethoscope w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Pemeriksaan Medis</span>
                    </a>
                    <a href="<?php echo e(route('kader.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.imunisasi') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-syringe w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.imunisasi') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Imunisasi Vaksin</span>
                    </a>
                    <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-notes-medical w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Log Antrian / Tamu</span>
                    </a>
                </div>
            </div>

            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Manajemen Alat</p>
                <div class="space-y-1">
                    <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-calendar-alt w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Jadwal Posyandu</span>
                    </a>
                    <a href="<?php echo e(route('kader.import.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.import') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-file-import w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.import') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Import Data Masal</span>
                    </a>
                    <a href="<?php echo e(route('kader.laporan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition <?php echo e(Str::startsWith($route, 'kader.laporan') ? $menuAktif : $menuPasif); ?>">
                        <i class="fas fa-print w-5 text-center text-[16px] <?php echo e(Str::startsWith($route, 'kader.laporan') ? $iconAktif : $iconPasif); ?>"></i> 
                        <span>Cetak Dokumen PDF</span>
                    </a>
                </div>
            </div>
            
        </nav>

        
        <div class="p-4 border-t border-slate-100 bg-white shrink-0">
            <a href="<?php echo e(route('kader.profile.index')); ?>" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center font-black text-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all">
                    <?php echo e(strtoupper(substr(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'K', 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-800 truncate leading-tight"><?php echo e(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'Kader'); ?></p>
                    <p class="text-[10px] font-medium text-slate-400 truncate mt-0.5">Pengaturan Akun</p>
                </div>
                <i class="fas fa-cog text-slate-300 text-sm group-hover:text-indigo-500 transition-colors"></i>
            </a>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col min-w-0 h-screen bg-slate-50 relative">
        
        
        <header class="h-[76px] glass-panel sticky top-0 z-40 flex items-center justify-between px-4 lg:px-8 border-b border-slate-200/60 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            
            <div class="flex items-center gap-3 lg:gap-5">
                <button id="menuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-[12px] transition-colors bg-white border border-slate-200 shadow-sm"><i class="fas fa-bars-staggered"></i></button>
                <div class="hidden md:flex flex-col">
                    <h2 class="text-[18px] font-black text-slate-800 tracking-tight font-poppins leading-none"><?php echo $__env->yieldContent('page-name', 'Beranda'); ?></h2>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] font-semibold text-slate-400 tracking-wide">
                        <span>Workspace</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-indigo-500">Sistem Berjalan</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-3">
                
                
                <div class="hidden xl:flex items-center bg-slate-100 hover:bg-slate-200/50 rounded-full px-4 py-2 w-64 transition-all focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-300 border border-transparent">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" id="globalSearchInput" placeholder="Pencarian cepat..." class="bg-transparent border-none outline-none text-[13px] w-full ml-3 placeholder:text-slate-400 font-medium text-slate-700">
                    <kbd class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-[9px] font-bold text-slate-400 shadow-sm">/ </kbd>
                </div>

                <div class="w-px h-6 bg-slate-200 mx-1 hidden lg:block"></div>
                
                <?php
                    $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
                    $latestNotifs = \App\Models\Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();
                ?>

                
                <div class="relative">
                    <button id="notifDropdownBtn" class="relative w-10 h-10 flex items-center justify-center bg-white text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-[12px] transition-all border border-slate-200 shadow-sm hover:shadow group">
                        <i class="fas fa-bell text-[16px]"></i>
                        <?php if($unreadNotifCount > 0): ?>
                            <span id="notifBadge" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-rose-500 border-2 border-white"></span>
                            </span>
                        <?php endif; ?>
                    </button>
                    
                    
                    <div id="notifDropdown" class="hidden absolute top-[120%] right-0 w-[calc(100vw-2rem)] mx-4 sm:mx-0 sm:w-[380px] bg-white/95 backdrop-blur-xl border border-slate-200 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.1)] rounded-[24px] z-50 overflow-hidden flex flex-col transition-all origin-top-right scale-95 opacity-0">
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 rounded-t-[24px]">
                            <h3 class="text-[14px] font-black text-slate-800 font-poppins">Pusat Notifikasi</h3>
                            <span id="notifCount" class="text-[10px] font-bold px-2 py-0.5 rounded text-rose-600 bg-rose-50 border border-rose-100 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>"><?php echo e($unreadNotifCount); ?> Baru</span>
                        </div>
                        <div id="notifList" class="max-h-[320px] overflow-y-auto custom-scrollbar flex-1 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $latestNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="flex gap-4 px-5 py-3.5 hover:bg-slate-50 transition-colors border-b border-slate-50 <?php echo e($n->is_read ? '' : 'bg-indigo-50/20'); ?>">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 <?php echo e($n->is_read ? 'bg-slate-100 text-slate-400' : 'bg-indigo-100 text-indigo-600'); ?>">
                                        <i class="fas fa-<?php echo e(str_contains(strtolower($n->judul), 'jadwal') ? 'calendar-alt' : 'bell'); ?> text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-[13px] font-bold <?php echo e($n->is_read ? 'text-slate-600' : 'text-slate-900'); ?> truncate font-poppins"><?php echo e($n->judul); ?></p>
                                        <p class="text-[12px] <?php echo e($n->is_read ? 'text-slate-400' : 'text-slate-600'); ?> line-clamp-1 mt-0.5"><?php echo e($n->pesan); ?></p>
                                        <p class="text-[10px] font-medium text-slate-400 mt-1.5"><?php echo e($n->created_at->diffForHumans()); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="py-10 text-center text-slate-400"><i class="fas fa-check-circle text-3xl mb-3 opacity-30"></i><p class="text-xs font-medium">Semua bersih.</p></div>
                            <?php endif; ?>
                        </div>
                        <div class="p-3 border-t border-slate-100 rounded-b-[24px] bg-slate-50/80">
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="w-full py-2.5 text-[12px] font-bold text-indigo-600 hover:bg-white border border-transparent hover:border-slate-200 shadow-sm text-center rounded-xl transition-all block">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>
                
                
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="hidden sm:block">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white text-rose-500 hover:text-white hover:bg-rose-500 rounded-[12px] transition-all border border-slate-200 shadow-sm" title="Keluar">
                        <i class="fas fa-power-off text-[14px]"></i>
                    </button>
                </form>

            </div>
        </header>

        
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 relative z-0 custom-scrollbar">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 h-16 glass-panel border-t border-slate-200 z-50 flex items-center justify-around px-2 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.03)]">
            <a href="<?php echo e(route('kader.dashboard')); ?>" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e($route == 'kader.dashboard' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-chart-pie text-lg mb-1 <?php echo e($route == 'kader.dashboard' ? 'drop-shadow-sm' : ''); ?>"></i> Beranda
            </a>
            <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e($isDataWarga ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-users text-lg mb-1 <?php echo e($isDataWarga ? 'drop-shadow-sm' : ''); ?>"></i> Warga
            </a>
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-stethoscope text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'drop-shadow-sm' : ''); ?>"></i> Medis
            </a>
            <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.jadwal') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-calendar-alt text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.jadwal') ? 'drop-shadow-sm' : ''); ?>"></i> Jadwal
            </a>
        </nav>
    </div>

    
    <script>
        // --- A. PREMIUM TOAST ENGINE ---
        const showToast = (type, title, message) => {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
            const color = type === 'success' ? 'emerald' : 'rose';
            
            const toastHtml = `
                <div class="toast-item pointer-events-auto bg-white p-4 rounded-[20px] shadow-[0_15px_40px_rgba(0,0,0,0.08)] border border-slate-100 flex items-start gap-3 toast-show relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-${color}-500"></div>
                    <div class="w-8 h-8 rounded-full bg-${color}-50 text-${color}-500 flex items-center justify-center shrink-0 ml-1">${icon}</div>
                    <div class="flex-1 pt-1.5"><h4 class="text-[13px] font-black text-slate-800 leading-none mb-1">${title}</h4><p class="text-[11px] font-medium text-slate-500">${message}</p></div>
                    <button onclick="removeToast(this)" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', toastHtml);
            const newToast = container.lastElementChild;
            setTimeout(() => removeToast(newToast.querySelector('button')), 5000);
        };
        const removeToast = (btn) => {
            const toast = btn.closest('.toast-item');
            if(toast) { toast.classList.remove('toast-show'); toast.classList.add('toast-hide'); setTimeout(() => toast.remove(), 400); }
        };

        // Inject Laravel Session to Toast
        <?php if(session('success')): ?> document.addEventListener('DOMContentLoaded', () => showToast('success', 'Aksi Berhasil', "<?php echo e(session('success')); ?>")); <?php endif; ?>
        <?php if(session('error')): ?> document.addEventListener('DOMContentLoaded', () => showToast('error', 'Kesalahan Sistem', "<?php echo e(session('error')); ?>")); <?php endif; ?>

        // --- B. UI & LOADER CONTROLS ---
        const hideLoader = () => { const l = document.getElementById('globalLoader'); if(l) { l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); } };
        window.addEventListener('pageshow', hideLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideLoader();
            
            // Tembak Loader untuk link biasa
            document.querySelectorAll('.smooth-route').forEach(el => el.addEventListener('click', e => { 
                if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) { const l = document.getElementById('globalLoader'); if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); } }
            }));

            // Sidebar Mobile Toggle
            const sidebar = document.getElementById('sidebar'), overlay = document.getElementById('mobileOverlay'), toggleBtn = document.getElementById('menuToggle'), closeBtn = document.getElementById('closeSidebar');
            const toggleSidebar = () => {
                if (sidebar.classList.contains('-translate-x-full')) { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); setTimeout(() => overlay.classList.add('opacity-100'), 10); document.body.classList.add('overflow-hidden'); } 
                else { sidebar.classList.add('-translate-x-full'); overlay.classList.remove('opacity-100'); setTimeout(() => overlay.classList.add('hidden'), 300); document.body.classList.remove('overflow-hidden'); }
            };
            if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);

            // Popover Notifikasi dengan Efek Scale
            const nBtn = document.getElementById('notifDropdownBtn'), nMenu = document.getElementById('notifDropdown');
            if (nBtn && nMenu) {
                nBtn.addEventListener('click', e => { 
                    e.stopPropagation(); 
                    if(nMenu.classList.contains('hidden')) {
                        nMenu.classList.remove('hidden'); setTimeout(()=> { nMenu.classList.remove('scale-95','opacity-0'); nMenu.classList.add('scale-100','opacity-100'); }, 10);
                    } else {
                        nMenu.classList.remove('scale-100','opacity-100'); nMenu.classList.add('scale-95','opacity-0'); setTimeout(()=> nMenu.classList.add('hidden'), 200);
                    }
                });
                document.addEventListener('click', e => {
                    if (!nMenu.contains(e.target) && !nBtn.contains(e.target) && !nMenu.classList.contains('hidden')) {
                        nMenu.classList.remove('scale-100','opacity-100'); nMenu.classList.add('scale-95','opacity-0'); setTimeout(()=> nMenu.classList.add('hidden'), 200);
                    }
                });
            }

            // Keyboard Shortcut Search
            const searchInput = document.getElementById('globalSearchInput');
            if(searchInput) { document.addEventListener('keydown', e => { if (e.key === '/') { e.preventDefault(); searchInput.focus(); } }); }

            // Network State Banner
            const offlineBanner = document.getElementById('offlineBanner');
            window.addEventListener('offline', () => { offlineBanner.classList.remove('-translate-y-full'); showToast('error', 'Koneksi Terputus', 'Periksa jaringan internet Anda.'); });
            window.addEventListener('online', () => { offlineBanner.classList.add('-translate-y-full'); showToast('success', 'Kembali Online', 'Sistem terhubung kembali.'); });
            if(!navigator.onLine) offlineBanner.classList.remove('-translate-y-full');

            // Notifikasi Polling & Native Push
            if (Notification.permission !== "granted" && Notification.permission !== "denied") Notification.requestPermission();
            let currentUnreadNotif = <?php echo e(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count() ?? 0); ?>;
            const notifSound = new Audio('data:audio/mp3;base64,//NExAAAAANIAAAAAExBTUUzLjEwMKqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq'); // Bip ringan
            
            function checkNewNotifications() {
                if(!navigator.onLine) return;
                fetch("<?php echo e(route('kader.notifikasi.fetch')); ?>", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json()).then(data => {
                    const badge = document.getElementById('notifBadge'), countText = document.getElementById('notifCount'), list = document.getElementById('notifList');
                    if (badge) { if (data.unreadCount > 0) badge.classList.remove('hidden'); else badge.classList.add('hidden'); }
                    if (countText) { countText.textContent = data.unreadCount + ' Baru'; countText.className = data.unreadCount > 0 ? 'text-[10px] font-bold px-2 py-0.5 rounded text-rose-600 bg-rose-50 border border-rose-100' : 'hidden'; }
                    if (list) list.innerHTML = data.html;

                    if (data.unreadCount > currentUnreadNotif) {
                        notifSound.play().catch(e=>{}); // Silent reject
                        showToast('success', 'Pesan Baru', data.latest_title ?? 'Anda memiliki notifikasi baru.');
                        if (Notification.permission === "granted" && data.latest_title) {
                            const pushNotif = new Notification("KaderCare: " + data.latest_title, { body: data.latest_body, icon: "https://cdn-icons-png.flaticon.com/512/3063/3063206.png" });
                            pushNotif.onclick = function() { window.focus(); window.location.href = "<?php echo e(route('kader.notifikasi.index')); ?>"; };
                        }
                    }
                    currentUnreadNotif = data.unreadCount;
                }).catch(e => {});
            }
            setInterval(checkNewNotifications, 12000); // Tiap 12 detik agar tidak terlalu berat
        });

        // ACCORDION MENU LOGIC
        function toggleSubmenu(menuId, iconId) {
            const menu = document.getElementById(menuId), icon = document.getElementById(iconId);
            if (menu.classList.contains('grid-rows-[0fr]')) {
                menu.classList.remove('grid-rows-[0fr]'); menu.classList.add('grid-rows-[1fr]');
                icon.classList.add('rotate-180', 'text-indigo-600'); icon.classList.remove('text-slate-400');
            } else {
                menu.classList.remove('grid-rows-[1fr]'); menu.classList.add('grid-rows-[0fr]');
                icon.classList.remove('rotate-180', 'text-indigo-600'); icon.classList.add('text-slate-400');
            }
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>