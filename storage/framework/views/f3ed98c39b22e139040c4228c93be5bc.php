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

        /* UI Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
        .menu-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .submenu-grid { display: grid; transition: grid-template-rows 0.35s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Toast Animation */
        @keyframes toastEnter { 0% { opacity: 0; transform: translateX(100%) scale(0.9); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes toastLeave { 0% { opacity: 1; transform: translateX(0) scale(1); max-height: 100px; margin-bottom: 12px; } 100% { opacity: 0; transform: translateX(100%) scale(0.9); max-height: 0; margin-bottom: 0; padding: 0; border: 0; } }
        .toast-show { animation: toastEnter 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .toast-hide { animation: toastLeave 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }

        /* SUPER SMOOTH LAYOUT SHIFT (CSS CONTROLLER) */
        .layout-transition { transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* State Desktop (Layar Lebar) */
        @media (min-width: 1024px) {
            #sidebar { transform: translateX(0); }
            #mainWrapper { margin-left: 280px; }
            
            /* Saat Focus Mode Aktif (Sidebar Disembunyikan) */
            body.sidebar-closed #sidebar { transform: translateX(-100%); }
            body.sidebar-closed #mainWrapper { margin-left: 0; }
        }
        
        /* State Mobile (Layar Kecil) */
        @media (max-width: 1023px) {
            #sidebar { transform: translateX(-100%); }
            /* Saat Menu Mobile Dibuka */
            body.mobile-sidebar-open #sidebar { transform: translateX(0); }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="flex h-screen overflow-hidden selection:bg-indigo-100 selection:text-indigo-900">

    
    <div id="offlineBanner" class="fixed top-0 left-0 right-0 z-[99999] bg-rose-500 text-white text-[11px] font-black uppercase tracking-widest py-2 text-center transform -translate-y-full transition-transform duration-300 flex items-center justify-center gap-2 shadow-lg">
        <i class="fas fa-wifi-slash animate-pulse"></i> Koneksi Terputus. Menunggu Jaringan...
    </div>

    
    <div id="toastContainer" class="fixed top-6 right-6 z-[10000] flex flex-col gap-3 w-full max-w-[340px] pointer-events-none"></div>

    
    <div id="globalLoader" class="fixed inset-0 z-[9998] bg-slate-50/80 backdrop-blur-sm flex flex-col items-center justify-center opacity-100 pointer-events-auto transition-opacity duration-300">
        <div class="relative w-16 h-16 flex items-center justify-center mb-4">
            <div class="absolute inset-0 border-4 border-slate-200 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                <i class="fas fa-heart-pulse text-indigo-600 animate-pulse text-lg"></i>
            </div>
        </div>
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Memuat Layar</p>
    </div>

    
    <div id="mobileOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 lg:hidden"></div>

    
    <?php echo $__env->make('partials.sidebar.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 h-screen bg-slate-50 relative layout-transition">
        
        
        <header class="h-[76px] glass-panel sticky top-0 z-40 flex items-center justify-between px-4 lg:px-8 border-b border-slate-200/60 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            
            <div class="flex items-center gap-3 lg:gap-5">
                
                <button id="toggleSidebarDesktop" class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-[12px] transition-colors bg-white border border-slate-200 shadow-sm" title="Toggle Layar Penuh">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <div class="hidden md:flex flex-col">
                    <h2 class="text-[18px] font-black text-slate-800 tracking-tight font-poppins leading-none"><?php echo $__env->yieldContent('page-name', 'Beranda'); ?></h2>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] font-semibold text-slate-400 tracking-wide">
                        <span>Workspace</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-indigo-500">Kader Pelaksana</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-3">
                
                
                <div class="hidden xl:flex items-center bg-slate-100 hover:bg-slate-200/50 rounded-full px-4 py-2 w-64 transition-all focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-100 focus-within:border-indigo-300 border border-transparent">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" id="globalSearchInput" placeholder="Pencarian cepat NIK/Nama..." class="bg-transparent border-none outline-none text-[13px] w-full ml-3 placeholder:text-slate-400 font-medium text-slate-700">
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
                                <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="loader-trigger flex gap-4 px-5 py-3.5 hover:bg-slate-50 transition-colors border-b border-slate-50 <?php echo e($n->is_read ? '' : 'bg-indigo-50/20'); ?>">
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
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="loader-trigger w-full py-2.5 text-[12px] font-bold text-indigo-600 hover:bg-white border border-transparent hover:border-slate-200 shadow-sm text-center rounded-xl transition-all block">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>
                
                
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="hidden sm:block m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="relative w-10 h-10 flex items-center justify-center bg-white text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-[12px] transition-all border border-slate-200 shadow-sm hover:shadow group" title="Keluar Sistem">
                        <i class="fas fa-sign-out-alt text-[16px] group-hover:-translate-x-0.5 transition-transform"></i>
                    </button>
                </form>

            </div>
        </header>

        
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 relative z-0 custom-scrollbar">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 h-16 glass-panel border-t border-slate-200 z-50 flex items-center justify-around px-2 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.03)]">
            <?php $route = request()->route()->getName(); ?>
            <a href="<?php echo e(route('kader.dashboard')); ?>" class="loader-trigger flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e($route == 'kader.dashboard' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-chart-pie text-lg mb-1 <?php echo e($route == 'kader.dashboard' ? 'drop-shadow-sm' : ''); ?>"></i> Beranda
            </a>
            <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="loader-trigger flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route, 'kader.data.') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-users text-lg mb-1 <?php echo e(Str::startsWith($route, 'kader.data.') ? 'drop-shadow-sm' : ''); ?>"></i> Warga
            </a>
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="loader-trigger flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-balance-scale text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.pemeriksaan') ? 'drop-shadow-sm' : ''); ?>"></i> Medis
            </a>
            <a href="<?php echo e(route('kader.absensi.index')); ?>" class="loader-trigger flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors <?php echo e(Str::startsWith($route,'kader.absensi') ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fas fa-clipboard-user text-lg mb-1 <?php echo e(Str::startsWith($route,'kader.absensi') ? 'drop-shadow-sm' : ''); ?>"></i> Presensi
            </a>
        </nav>
    </div>

    
    
    
    <script>
        // --- TOAST ENGINE ---
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
                </div>`;
            container.insertAdjacentHTML('beforeend', toastHtml);
            setTimeout(() => removeToast(container.lastElementChild.querySelector('button')), 5000);
        };
        const removeToast = (btn) => {
            const toast = btn.closest('.toast-item');
            if(toast) { toast.classList.remove('toast-show'); toast.classList.add('toast-hide'); setTimeout(() => toast.remove(), 400); }
        };

        <?php if(session('success')): ?> document.addEventListener('DOMContentLoaded', () => showToast('success', 'Aksi Berhasil', "<?php echo e(session('success')); ?>")); <?php endif; ?>
        <?php if(session('error')): ?> document.addEventListener('DOMContentLoaded', () => showToast('error', 'Kesalahan Sistem', "<?php echo e(session('error')); ?>")); <?php endif; ?>

        // --- GLOBAL LOADER ENGINE ---
        const globalLoader = document.getElementById('globalLoader');
        const hideLoader = () => { if(globalLoader) { globalLoader.classList.remove('opacity-100'); globalLoader.classList.add('opacity-0', 'pointer-events-none'); } };
        
        document.addEventListener('DOMContentLoaded', hideLoader);
        window.addEventListener('pageshow', hideLoader); // Anti-Stuck BFCache
        
        document.querySelectorAll('.loader-trigger').forEach(el => el.addEventListener('click', e => { 
            if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) { 
                if(globalLoader) { globalLoader.classList.remove('opacity-0', 'pointer-events-none'); globalLoader.classList.add('opacity-100', 'pointer-events-auto'); } 
            }
        }));

        // --- CSS CLASS CONTROLLER FOR SIDEBAR (ANTI LAYOUT BREAK) ---
        const toggleBtnDesktop = document.getElementById('toggleSidebarDesktop');
        const closeBtnMobile = document.getElementById('closeSidebarMobile');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        // Cek memory browser
        let isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        // Terapkan saat load pertama kali
        if (window.innerWidth >= 1024 && isSidebarCollapsed) {
            document.body.classList.add('sidebar-closed');
        }

        // Toggle Desktop
        if (toggleBtnDesktop) {
            toggleBtnDesktop.addEventListener('click', () => {
                if (window.innerWidth >= 1024) {
                    isSidebarCollapsed = !isSidebarCollapsed;
                    localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
                    document.body.classList.toggle('sidebar-closed', isSidebarCollapsed);
                } else {
                    document.body.classList.add('mobile-sidebar-open');
                    mobileOverlay.classList.remove('hidden'); 
                    setTimeout(() => mobileOverlay.classList.add('opacity-100'), 10); 
                    document.body.classList.add('overflow-hidden');
                }
            });
        }

        // Tutup Mobile
        const closeMobileSidebar = () => {
            document.body.classList.remove('mobile-sidebar-open');
            mobileOverlay.classList.remove('opacity-100'); 
            setTimeout(() => mobileOverlay.classList.add('hidden'), 300); 
            document.body.classList.remove('overflow-hidden');
        };

        if (closeBtnMobile) closeBtnMobile.addEventListener('click', closeMobileSidebar);
        if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileSidebar);

        // --- POPOVER NOTIFIKASI ---
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
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>