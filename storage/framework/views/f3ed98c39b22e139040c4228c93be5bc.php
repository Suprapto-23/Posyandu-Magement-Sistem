<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Kader Workspace'); ?> — PosyanduCare</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><defs><linearGradient id=%22g%22 x1=%220%25%22 y1=%220%25%22 x2=%22100%25%22 y2=%22100%25%22><stop offset=%220%25%22 stop-color=%22%23818cf8%22/><stop offset=%22100%25%22 stop-color=%22%234338ca%22/></linearGradient></defs><path fill=%22url(%23g)%22 d=%22M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z%22/><path fill=%22white%22 d=%22M11 7h2v3h3v2h-3v3h-2v-3H8v-2h3V7z%22/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    
    <style type="text/tailwindcss">
        @theme { --font-sans: 'Plus Jakarta Sans', sans-serif; --font-poppins: 'Poppins', sans-serif; }
        body { font-family: var(--font-sans); background-color: #f8fafc; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .glass-header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(16px); }
        @keyframes menuPop { 0% { opacity: 0; transform: scale(0.95) translateY(-10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-pop { animation: menuPop 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; transform-origin: top right; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900 flex">

    <div id="globalLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-200 opacity-0 pointer-events-none">
        <div class="relative w-20 h-20 flex items-center justify-center mb-5">
            <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center shadow-inner"><i class="fas fa-heart-pulse text-indigo-600 text-xl animate-pulse"></i></div>
        </div>
        <p class="text-indigo-800 font-poppins font-black tracking-[0.2em] text-[11px] uppercase animate-pulse">Memuat Ruang Kerja...</p>
    </div>

    <div id="mobileOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-screen w-[280px] bg-white border-r border-slate-200/80 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col overflow-hidden">
        <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M30 30c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zm0 0c0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15 0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15zm0 0c8.284 0 15-6.716 15-15 0-8.284-6.716-15-15-15-8.284 0-15 6.716-15 15 0 8.284 6.716 15 15 15zm0 0c-8.284 0-15 6.716-15 15 0 8.284 6.716 15 15 15 8.284 0 15-6.716 15-15 0-8.284-6.716-15-15-15z%22 fill=%22%234f46e5%22 fill-opacity=%221%22 fill-rule=%22evenodd%22/%3E%3C/svg%3E'); background-repeat: repeat;"></div>

        <div class="relative z-10 flex flex-col pt-6 pb-2 shrink-0">
            <div class="px-6 flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-heart-pulse text-lg"></i></div>
                <div class="flex-1 min-w-0"><h1 class="text-[22px] font-black text-slate-800 tracking-tight truncate font-poppins">Kader<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400">Care</span></h1></div>
                <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-indigo-600 p-2 rounded-lg"><i class="fas fa-times"></i></button>
            </div>
            <div class="px-5">
                <div class="p-3 bg-white/80 backdrop-blur border border-slate-100 rounded-2xl flex items-center gap-3 shadow-[0_2px_10px_rgba(0,0,0,0.02)] cursor-pointer group" onclick="document.getElementById('userDropdownBtn').click()">
                    <div class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-lg shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors"><?php echo e(strtoupper(substr(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'K', 0, 1))); ?></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate"><?php echo e(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'Kader'); ?></p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Kader Aktif</p>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-3 py-4 relative z-10 custom-scrollbar">
            <?php echo $__env->make('partials.sidebar.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>
    </aside>

    <div class="flex-1 lg:ml-[280px] min-h-screen flex flex-col w-full relative">
        <header class="h-20 glass-header border-b border-slate-200/80 sticky top-0 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <button id="menuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors"><i class="fas fa-bars-staggered"></i></button>
                <nav class="hidden sm:flex items-center gap-2 text-[13px] font-bold text-slate-400">
                    <a href="<?php echo e(route('kader.dashboard')); ?>" class="hover:text-indigo-600 transition-colors"><i class="fas fa-home text-sm"></i></a>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 mx-1"></i>
                    <span class="text-slate-700"><?php echo $__env->yieldContent('page-name', 'Dashboard'); ?></span>
                </nav>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-4 relative">
                <div class="hidden md:flex items-center bg-slate-100 hover:bg-slate-50 transition-colors rounded-full px-5 py-2.5 w-64 border border-transparent focus-within:bg-white focus-within:border-indigo-200 focus-within:ring-4 focus-within:ring-indigo-50/50 mr-2">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari warga posyandu..." class="bg-transparent border-none outline-none text-[13px] w-full ml-3 placeholder:text-slate-400 font-semibold text-slate-700">
                </div>
                
                <?php
                    $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
                    $latestNotifs = \App\Models\Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();
                ?>

                <div class="static sm:relative">
                    <button id="notifDropdownBtn" class="relative w-10 h-10 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-all">
                        <i class="fas fa-bell text-[19px]"></i>
                        <span id="notifBadge" class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white animate-pulse <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>"></span>
                    </button>
                    
                    <div id="notifDropdown" class="hidden absolute top-16 right-0 w-[calc(100vw-2rem)] sm:top-auto sm:right-0 sm:mt-3 sm:w-80 bg-white rounded-2xl shadow-[0_15px_50px_-10px_rgba(0,0,0,0.15)] border border-slate-200 z-50 animate-pop overflow-hidden flex flex-col">
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                            <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                            <span id="notifCount" class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo e($unreadNotifCount > 0 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-400'); ?>"><?php echo e($unreadNotifCount); ?> Baru</span>
                        </div>
                        
                        <div id="notifList" class="max-h-72 overflow-y-auto custom-scrollbar flex-1">
                            <?php $__empty_1 = true; $__currentLoopData = $latestNotifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="notif-item <?php echo e($n->is_read ? '' : 'unread'); ?> flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-100 <?php echo e($n->is_read ? 'bg-white border-l-4 border-l-transparent' : 'bg-indigo-50/40 border-l-4 border-l-indigo-500'); ?>">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 border <?php echo e($n->is_read ? 'bg-slate-100 text-slate-500 border-transparent' : 'bg-indigo-100 text-indigo-600 border-indigo-200'); ?>">
                                        <i class="fas fa-<?php echo e(str_contains(strtolower($n->judul), 'jadwal') ? 'calendar-alt' : (str_contains(strtolower($n->judul), 'import') ? 'file-excel' : 'bell')); ?> text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold <?php echo e($n->is_read ? 'text-slate-600' : 'text-slate-800'); ?> leading-tight"><?php echo e($n->judul); ?></p>
                                        <p class="text-[12px] text-slate-500 mt-0.5 line-clamp-1"><?php echo e($n->pesan); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                                    <i class="fas fa-bell-slash text-3xl mb-2 opacity-30"></i>
                                    <p class="text-[12px] font-medium">Belum ada notifikasi</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-3 bg-white border-t border-slate-100 flex flex-col gap-1.5 shrink-0 shadow-[0_-4px_10px_rgba(0,0,0,0.02)]">
                            <div id="markAllContainer" class="<?php echo e($unreadNotifCount > 0 ? 'block' : 'hidden'); ?>">
                                <form action="<?php echo e(route('kader.notifikasi.markAllRead')); ?>" method="POST" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" id="markAllReadBtn" class="w-full py-2.5 text-[12px] font-bold text-slate-500 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                        <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                                    </button>
                                </form>
                            </div>
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="w-full py-2.5 text-[12px] font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 text-center rounded-xl transition-colors">
                                Lihat Semua Notifikasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="static sm:relative ml-1">
                    <button id="userDropdownBtn" class="w-10 h-10 rounded-full border border-slate-200 overflow-hidden flex items-center justify-center bg-white text-indigo-600 font-bold hover:ring-2 hover:ring-indigo-100 transition-all shadow-sm"><?php echo e(strtoupper(substr(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'K', 0, 1))); ?></button>
                    <div id="userDropdown" class="hidden absolute top-16 right-0 w-[calc(100vw-2rem)] sm:top-auto sm:right-0 sm:mt-3 sm:w-60 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-200 z-50 animate-pop overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-[14px] font-bold text-slate-900 truncate"><?php echo e(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'Kader'); ?></p>
                            <p class="text-[12px] text-slate-500 mt-0.5 truncate"><?php echo e(Auth::user()->email ?? 'kader@posyandu.com'); ?></p>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo e(route('kader.profile.index')); ?>" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded-xl transition-all"><i class="fas fa-user-circle w-4 text-center"></i> Profil Saya</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="showGlobalLoader('MENGAKHIRI SESI...')" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium text-rose-600 hover:bg-rose-50 rounded-xl mt-1 transition-all"><i class="fas fa-sign-out-alt w-4 text-center text-rose-500"></i> Keluar Sistem</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1400px] mx-auto w-full relative z-0">
            <?php $__currentLoopData = ['success' => ['bg-emerald-50', 'text-emerald-800', 'text-emerald-500', 'fa-check-circle', 'border-emerald-200'], 'error' => ['bg-rose-50', 'text-rose-800', 'text-rose-500', 'fa-circle-exclamation', 'border-rose-200']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg => $classes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($msg)): ?>
                    <div class="mb-6 px-5 py-4 <?php echo e($classes[0]); ?> border <?php echo e($classes[4]); ?> rounded-xl flex items-center justify-between shadow-sm animate-[slideDown_0.4s_ease-out]">
                        <div class="flex items-center gap-3"><i class="fas <?php echo e($classes[3]); ?> <?php echo e($classes[2]); ?> text-xl"></i><span class="<?php echo e($classes[1]); ?> text-[14px] font-semibold"><?php echo e(session($msg)); ?></span></div>
                        <button onclick="this.parentElement.style.display='none'" class="<?php echo e($classes[2]); ?> hover:opacity-70 p-2 transition-opacity"><i class="fas fa-times"></i></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        const showGlobalLoader = (t = 'MEMUAT DATA...') => { const l = document.getElementById('globalLoader'); if(l) { l.querySelector('p').innerText = t; l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100'); } };
        const hideGlobalLoader = () => { const l = document.getElementById('globalLoader'); if(l) { l.classList.remove('opacity-100'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 200); } };
        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideGlobalLoader();
            document.querySelectorAll('.smooth-route').forEach(el => el.addEventListener('click', e => { if(!el.target || el.target !== '_blank') showGlobalLoader('MEMUAT HALAMAN...'); }));

            const sidebar = document.getElementById('sidebar'), overlay = document.getElementById('mobileOverlay'), toggleBtn = document.getElementById('menuToggle'), closeBtn = document.getElementById('closeSidebar');
            const toggleSidebar = () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); setTimeout(() => overlay.classList.add('opacity-100'), 10); document.body.classList.add('overflow-hidden');
                } else {
                    sidebar.classList.add('-translate-x-full'); overlay.classList.remove('opacity-100'); setTimeout(() => overlay.classList.add('hidden'), 300); document.body.classList.remove('overflow-hidden');
                }
            };
            if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);

            const uBtn = document.getElementById('userDropdownBtn'), uMenu = document.getElementById('userDropdown'), nBtn = document.getElementById('notifDropdownBtn'), nMenu = document.getElementById('notifDropdown');
            if (uBtn && uMenu) uBtn.addEventListener('click', e => { e.stopPropagation(); uMenu.classList.toggle('hidden'); if(nMenu) nMenu.classList.add('hidden'); });
            if (nBtn && nMenu) nBtn.addEventListener('click', e => { e.stopPropagation(); nMenu.classList.toggle('hidden'); if(uMenu) uMenu.classList.add('hidden'); });
            document.addEventListener('click', e => {
                if (uMenu && !uMenu.contains(e.target) && !uBtn.contains(e.target)) uMenu.classList.add('hidden');
                if (nMenu && !nMenu.contains(e.target) && !nBtn.contains(e.target)) nMenu.classList.add('hidden');
            });

            // 🌟 JAVASCRIPT AJAX REAL-TIME POLLING PINTAR (Setiap 10 Detik) 🌟
            let currentUnreadNotif = <?php echo e(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count()); ?>;

            function checkNewNotifications() {
                fetch("<?php echo e(route('kader.notifikasi.fetch')); ?>", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const countText = document.getElementById('notifCount');
                    const list = document.getElementById('notifList');
                    const markAll = document.getElementById('markAllContainer');

                    // 1. Update Dropdown Lonceng
                    if (badge) {
                        if (data.unreadCount > 0) badge.classList.remove('hidden');
                        else badge.classList.add('hidden');
                    }
                    if (countText) {
                        countText.textContent = data.unreadCount + ' Baru';
                        countText.className = data.unreadCount > 0 
                            ? 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-600'
                            : 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-400';
                    }
                    if (list) list.innerHTML = data.html;
                    if (markAll) {
                        if (data.unreadCount > 0) markAll.classList.remove('hidden');
                        else markAll.classList.add('hidden');
                    }

                    // 2. Update Halaman Utama Secara Diam-diam Jika Ada Perubahan
                    if (data.unreadCount !== currentUnreadNotif) {
                        currentUnreadNotif = data.unreadCount;
                        
                        const mainWrapper = document.getElementById('main-notif-wrapper');
                        
                        // Jika elemen ini ada, berarti user sedang berada di halaman "Semua Notifikasi"
                        if (mainWrapper) {
                            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const doc = new DOMParser().parseFromString(html, 'text/html');
                                
                                // Ganti isi daftar panjangnya secara instan
                                const newList = doc.getElementById('main-notif-wrapper');
                                if (newList) mainWrapper.innerHTML = newList.innerHTML;
                                
                                // Update angka merah di header halaman
                                const headerCount = document.getElementById('header-unread-count');
                                const newHeaderCount = doc.getElementById('header-unread-count');
                                if (headerCount && newHeaderCount) {
                                    headerCount.innerHTML = newHeaderCount.innerHTML;
                                }
                            });
                        }
                    }
                })
                .catch(error => console.error("Error fetching notifications:", error));
            }

            setInterval(checkNewNotifications, 10000); // Polling setiap 10 detik

            // Logika UI Instan "Tandai Semua Dibaca" di Dropdown Lonceng
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
                    const nBadge = document.getElementById('notifBadge');
                    const nCount = document.getElementById('notifCount');
                    if(nBadge) nBadge.style.display = 'none';
                    if(nCount) {
                        nCount.textContent = '0 Baru';
                        nCount.className = 'text-[10px] font-bold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full';
                    }
                    document.querySelectorAll('.notif-item.unread').forEach(item => {
                        item.classList.remove('bg-indigo-50/40', 'border-l-indigo-500', 'unread');
                        item.classList.add('bg-white', 'border-l-transparent');
                        const iconWrapper = item.querySelector('div.rounded-full');
                        if (iconWrapper) {
                            iconWrapper.className = 'w-9 h-9 rounded-full flex items-center justify-center shrink-0 border bg-slate-100 text-slate-500 border-transparent';
                        }
                    });
                });
            }
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>