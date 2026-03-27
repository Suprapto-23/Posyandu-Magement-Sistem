<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Portal Warga'); ?> — PosyanduCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

    <style type="text/tailwindcss">
        @theme { --font-sans: 'Plus Jakarta Sans', sans-serif; --font-poppins: 'Poppins', sans-serif; }
        body { font-family: var(--font-sans); background-color: #f8fafc; -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .glass-effect { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 16px); }
        .nav-bottom-item { @apply flex flex-col items-center justify-center w-full py-2 transition-all duration-300; }
        .nav-bottom-item.active { @apply text-teal-600 font-bold -translate-y-1 scale-105; }
        .nav-bottom-item:not(.active) { @apply text-slate-400 font-medium hover:text-teal-500; }
        @keyframes menuPop { 0% { opacity: 0; transform: scale(0.95) translateY(-10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-pop { animation: menuPop 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; transform-origin: top right; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="text-slate-800 antialiased lg:pb-0 pb-[80px] flex">

    <div id="globalLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
        <div class="relative w-20 h-20 flex items-center justify-center mb-4">
            <div class="absolute inset-0 border-4 border-teal-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-teal-500 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center shadow-inner">
                <i class="fas fa-heartbeat text-teal-600 animate-pulse text-2xl"></i>
            </div>
        </div>
        <p class="text-teal-800 font-black font-poppins tracking-[0.2em] text-[11px] uppercase animate-pulse">Memuat Layanan...</p>
    </div>

    <aside class="hidden lg:flex fixed top-0 left-0 h-screen w-[280px] bg-white border-r border-slate-200/80 z-50 flex-col overflow-hidden">
        <?php echo $__env->make('partials.sidebar.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </aside>

    <div class="flex-1 lg:ml-[280px] min-h-screen flex flex-col w-full relative transition-all duration-300">
        
        <header class="h-20 glass-effect sticky top-0 z-30 flex items-center justify-between px-5 sm:px-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-[12px] bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm border border-teal-100 lg:hidden">
                    <i class="fas fa-hand-holding-medical text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight leading-none font-poppins">Posyandu<span class="text-teal-600">Care</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden sm:block mt-1">Sistem Layanan Warga</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4 relative">
                
                <?php
                    $unreadNotifCount = \App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
                ?>

                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route relative w-10 h-10 flex items-center justify-center text-slate-500 hover:text-teal-600 hover:bg-teal-50 rounded-full transition-all border border-transparent focus:ring-2 focus:ring-teal-100">
                    <i class="fas fa-bell text-[19px]"></i>
                    <span id="notifBadge" class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white animate-pulse <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>"></span>
                </a>

                <div class="static sm:relative ml-1">
                    <button id="userDropdownBtn" class="w-10 h-10 rounded-full ring-2 ring-slate-100 overflow-hidden flex items-center justify-center bg-white border border-slate-200 text-teal-600 font-extrabold shadow-sm hover:ring-teal-200 hover:bg-teal-50 transition-all">
                        <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1))); ?>

                    </button>
                    
                    <div id="userDropdown" class="hidden absolute top-16 right-0 w-[calc(100vw-2rem)] sm:top-auto sm:right-0 sm:mt-3 sm:w-60 bg-white rounded-2xl shadow-[0_15px_50px_-10px_rgba(0,0,0,0.15)] border border-slate-200 z-50 animate-pop overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-[14px] font-bold text-slate-900 truncate"><?php echo e(Auth::user()->name ?? 'Warga'); ?></p>
                            <p class="text-[12px] text-slate-500 mt-0.5 truncate"><?php echo e(Auth::user()->email ?? 'Akses Terhubung'); ?></p>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo e(route('user.profile.edit')); ?>" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium text-slate-600 hover:bg-slate-50 hover:text-teal-600 rounded-xl transition-all"><i class="fas fa-user-circle w-4 text-center"></i> Profil Saya</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="showGlobalLoader()" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-medium text-rose-600 hover:bg-rose-50 rounded-xl mt-1 transition-all"><i class="fas fa-sign-out-alt w-4 text-center text-rose-500"></i> Keluar Sistem</button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-6xl mx-auto relative z-0">
            <?php $__currentLoopData = ['success' => ['bg-emerald-50', 'text-emerald-600', 'fa-check-circle', 'border-emerald-200'], 'error' => ['bg-rose-50', 'text-rose-600', 'fa-exclamation-circle', 'border-rose-200']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg => $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($msg)): ?>
                    <div class="mb-5 px-5 py-4 <?php echo e($cls[0]); ?> border <?php echo e($cls[3]); ?> rounded-xl flex items-center justify-between shadow-sm animate-[slideDown_0.4s_ease-out]">
                        <div class="flex items-center gap-3">
                            <i class="fas <?php echo e($cls[2]); ?> <?php echo e($cls[1]); ?> text-xl"></i>
                            <span class="<?php echo e($cls[1]); ?> text-sm font-bold"><?php echo e(session($msg)); ?></span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="<?php echo e($cls[1]); ?> hover:opacity-70 p-1 transition-opacity"><i class="fas fa-times"></i></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <nav class="lg:hidden fixed bottom-0 left-0 w-full glass-effect border-t border-slate-200/80 z-50 pb-safe rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center px-4 pt-1">
            <a href="<?php echo e(route('user.dashboard')); ?>" class="smooth-route nav-bottom-item <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-home text-xl mb-1"></i>
                <span class="text-[10px] tracking-wide">Beranda</span>
            </a>
            <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route nav-bottom-item <?php echo e(request()->routeIs('user.jadwal.*') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-alt text-xl mb-1"></i>
                <span class="text-[10px] tracking-wide">Jadwal</span>
            </a>
            <div class="relative -top-6 px-3">
                <a href="<?php echo e(route('user.riwayat.index')); ?>" class="smooth-route w-14 h-14 rounded-full bg-gradient-to-tr from-teal-500 to-teal-600 text-white flex items-center justify-center text-2xl shadow-[0_8px_20px_rgba(13,148,136,0.4)] border-[4px] border-[#f8fafc] hover:scale-105 transition-transform">
                    <i class="fas fa-notes-medical"></i>
                </a>
            </div>
            <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route nav-bottom-item <?php echo e(request()->routeIs('user.notifikasi.*') ? 'active' : ''); ?> relative">
                <i class="fas fa-envelope text-xl mb-1"></i>
                <span class="text-[10px] tracking-wide">Pesan</span>
                <span id="badgeNotifBottom" class="hidden absolute top-2 right-1/4 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
            </a>
            <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route nav-bottom-item <?php echo e(request()->routeIs('user.profile.*') ? 'active' : ''); ?>">
                <i class="fas fa-user-circle text-xl mb-1"></i>
                <span class="text-[10px] tracking-wide">Profil</span>
            </a>
        </div>
    </nav>

    <script>
        const showGlobalLoader = () => { 
            const l = document.getElementById('globalLoader'); 
            if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100'); } 
        };
        const hideGlobalLoader = () => { 
            const l = document.getElementById('globalLoader'); 
            if(l) { l.classList.remove('opacity-100'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); } 
        };
        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideGlobalLoader();
            document.querySelectorAll('.smooth-route').forEach(el => el.addEventListener('click', e => { 
                if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) showGlobalLoader(); 
            }));

            const uBtn = document.getElementById('userDropdownBtn'), uMenu = document.getElementById('userDropdown');
            if (uBtn && uMenu) uBtn.addEventListener('click', e => { e.stopPropagation(); uMenu.classList.toggle('hidden'); });
            document.addEventListener('click', e => { if (uMenu && !uMenu.contains(e.target) && !uBtn.contains(e.target)) uMenu.classList.add('hidden'); });

            // 🌟 AJAX REAL-TIME POLLING UNTUK UPDATE HALAMAN UTAMA & BADGE 🌟
            let currentUnreadNotif = <?php echo e(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count()); ?>;

            function checkNewNotifications() {
                fetch("<?php echo e(route('user.notifikasi.fetch')); ?>", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    const badgeTop = document.getElementById('notifBadge');
                    const badgeBot = document.getElementById('badgeNotifBottom');

                    // Update Titik Merah Lonceng
                    if (badgeTop) {
                        if (data.unreadCount > 0) badgeTop.classList.remove('hidden');
                        else badgeTop.classList.add('hidden');
                    }
                    if (badgeBot) {
                        if (data.unreadCount > 0) badgeBot.classList.remove('hidden');
                        else badgeBot.classList.add('hidden');
                    }

                    // 🌟 Update Halaman Utama Notifikasi Secara Otomatis! 🌟
                    if (data.unreadCount !== currentUnreadNotif) {
                        currentUnreadNotif = data.unreadCount;
                        const mainWrapper = document.getElementById('main-notif-wrapper');
                        
                        // Cek apakah user sedang membuka halaman "Pesan dari Bidan"
                        if (mainWrapper) {
                            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const doc = new DOMParser().parseFromString(html, 'text/html');
                                const newList = doc.getElementById('main-notif-wrapper');
                                if (newList) mainWrapper.innerHTML = newList.innerHTML;
                                
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

            setInterval(checkNewNotifications, 10000); // Cek setiap 10 Detik
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/user.blade.php ENDPATH**/ ?>