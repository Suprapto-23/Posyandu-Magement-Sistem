<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#0f766e">
    <title><?php echo $__env->yieldContent('title', 'Portal Warga'); ?> — PosyanduCare</title>

    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%230f766e'/%3E%3Cpath d='M50 30 C35 15 15 30 15 50 C15 70 50 85 50 85 C50 85 85 70 85 50 C85 30 65 15 50 30 Z' fill='%23ccfbf1'/%3E%3Cpath d='M50 42 L50 58 M40 50 L60 50' stroke='%230f766e' stroke-width='6' stroke-linecap='round'/%3E%3C/svg%3E">
    <link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%230f766e'/%3E%3C/svg%3E">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

    <style type="text/tailwindcss">
        @theme { --font-sans: 'Plus Jakarta Sans', sans-serif; --font-poppins: 'Poppins', sans-serif; }
        
        body { font-family: var(--font-sans); background-color: #f8fafc; -webkit-tap-highlight-color: transparent; }
        
        /* Custom Scrollbar Minimalis */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Efek Tembus Pandang (Kaca) Modern */
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(241, 245, 249, 0.8); }
        .glass-bottom-nav { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-top: 1px solid rgba(241, 245, 249, 0.8); }
        
        /* Navigasi Bawah Mobile App-Like */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 16px); }
        .nav-item { @apply flex flex-col items-center justify-center w-full py-3 transition-all duration-300 relative; }
        .nav-item.active { @apply text-teal-600 font-bold; }
        .nav-item.active i { @apply transform -translate-y-1 scale-110 drop-shadow-sm; }
        .nav-item:not(.active) { @apply text-slate-400 font-medium hover:text-teal-500; }
        
        /* Floating Center Button (KMS / Rekam Medis) */
        .center-fab { position: absolute; top: -24px; left: 50%; transform: translateX(-50%); width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #14b8a6, #0f766e); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 10px 25px -5px rgba(15, 118, 110, 0.5); border: 4px solid #f8fafc; z-index: 10; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .center-fab:hover { transform: translateX(-50%) scale(1.08) rotate(-5deg); }
        
        /* Animasi Pop-up Dropdown */
        @keyframes popUp { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-pop { animation: popUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Animasi Breathing untuk Loader Baru */
        @keyframes breathe {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 25px -5px rgba(20, 184, 166, 0.5); }
            50% { transform: scale(1.05); box-shadow: 0 15px 35px -5px rgba(20, 184, 166, 0.7); }
        }
        .animate-breathe { animation: breathe 2s infinite ease-in-out; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="text-slate-800 antialiased flex pb-[76px] lg:pb-0">

    
    <div id="globalLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-xl z-[9999] flex flex-col items-center justify-center transition-opacity duration-500 opacity-0 pointer-events-none">
        <div class="relative flex items-center justify-center">
            
            <div class="absolute w-24 h-24 bg-teal-400/30 rounded-full animate-ping" style="animation-duration: 2s;"></div>
            <div class="absolute w-32 h-32 bg-emerald-400/10 rounded-full animate-ping" style="animation-duration: 2.5s; animation-delay: 0.2s;"></div>
            
            
            <div class="relative z-10 w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-[18px] flex items-center justify-center animate-breathe">
                <i class="fas fa-leaf text-white text-3xl drop-shadow-md"></i>
            </div>
        </div>
        <p class="mt-8 text-teal-800 font-bold font-poppins tracking-[0.3em] text-[11px] uppercase">Memuat Data...</p>
    </div>

    
    <aside class="hidden lg:flex fixed top-0 left-0 h-screen w-[280px] z-50 flex-col">
        <?php echo $__env->make('partials.sidebar.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </aside>

    
    <div class="flex-1 lg:ml-[280px] min-h-screen flex flex-col w-full relative z-10 transition-all duration-300">
        
        
        <header class="h-[72px] glass-header sticky top-0 z-40 flex items-center justify-between px-5 lg:hidden shadow-sm">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-[12px] bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-leaf text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-[17px] font-black text-slate-800 tracking-tight leading-none font-poppins">Posyandu<span class="text-teal-600">Care</span></h1>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Portal Warga</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 relative">
                
                <?php $unreadCount = \App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count(); ?>
                
                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route relative w-10 h-10 flex items-center justify-center text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-full transition-colors">
                    <i class="fas fa-bell text-[20px]"></i>
                    <span id="notifBadgeTop" class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse <?php echo e($unreadCount > 0 ? '' : 'hidden'); ?>"></span>
                </a>

                
                <div class="relative">
                    <button id="userDropdownBtn" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 font-black text-sm shadow-inner border border-slate-200">
                        <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1))); ?>

                    </button>
                    
                    
                    <div id="userDropdown" class="hidden absolute top-14 right-0 w-56 bg-white rounded-2xl shadow-[0_15px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-100 animate-pop overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-50 bg-slate-50/50">
                            <p class="text-[13px] font-bold text-slate-800 truncate"><?php echo e(Auth::user()->name ?? 'Warga'); ?></p>
                        </div>
                        <div class="p-2">
                            <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold text-slate-500 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition-all"><i class="fas fa-user-circle w-4 text-center text-lg"></i> Profil Saya</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="showGlobalLoader()" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-all"><i class="fas fa-power-off w-4 text-center text-lg"></i> Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1400px] mx-auto relative z-0">
            
            
            <?php $__currentLoopData = ['success' => ['bg-emerald-50', 'text-emerald-600', 'fa-check-circle', 'border-emerald-200'], 'error' => ['bg-rose-50', 'text-rose-600', 'fa-exclamation-triangle', 'border-rose-200']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg => $cls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($msg)): ?>
                    <div class="mb-6 px-5 py-4 <?php echo e($cls[0]); ?> border <?php echo e($cls[3]); ?> rounded-[16px] flex items-center justify-between shadow-sm animate-pop">
                        <div class="flex items-center gap-3">
                            <i class="fas <?php echo e($cls[2]); ?> <?php echo e($cls[1]); ?> text-xl shrink-0"></i>
                            <span class="<?php echo e($cls[1]); ?> text-[13px] font-bold leading-tight"><?php echo e(session($msg)); ?></span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="<?php echo e($cls[1]); ?> opacity-50 hover:opacity-100 p-2 transition-opacity"><i class="fas fa-times"></i></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo $__env->yieldContent('content'); ?>

        </main>
    </div>

    
    <nav class="lg:hidden fixed bottom-0 left-0 w-full glass-bottom-nav z-50 pb-safe shadow-[0_-10px_40px_rgba(0,0,0,0.04)]">
        <div class="flex justify-between items-end px-2 relative h-[65px]">
            
            <a href="<?php echo e(route('user.dashboard')); ?>" class="smooth-route nav-item <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-home text-[20px] mb-1 transition-transform"></i>
                <span class="text-[10px] tracking-wide">Beranda</span>
            </a>
            
            <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route nav-item <?php echo e(request()->routeIs('user.jadwal.*') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-alt text-[20px] mb-1 transition-transform"></i>
                <span class="text-[10px] tracking-wide">Jadwal</span>
            </a>
            
            
            <div class="w-full flex justify-center relative">
                <a href="<?php echo e(route('user.riwayat.index')); ?>" class="smooth-route center-fab">
                    <i class="fas fa-notes-medical"></i>
                </a>
                <span class="absolute bottom-3 text-[10px] font-bold text-teal-600 tracking-wide mt-2">KMS Medis</span>
            </div>
            
            <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route nav-item <?php echo e(request()->routeIs('user.notifikasi.*') ? 'active' : ''); ?>">
                <div class="relative">
                    <i class="fas fa-envelope text-[20px] mb-1 transition-transform"></i>
                    <span id="badgeNotifBottom" class="hidden absolute -top-1 -right-2 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                </div>
                <span class="text-[10px] tracking-wide">Pesan</span>
            </a>
            
            <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route nav-item <?php echo e(request()->routeIs('user.profile.*') ? 'active' : ''); ?>">
                <i class="fas fa-user-circle text-[20px] mb-1 transition-transform"></i>
                <span class="text-[10px] tracking-wide">Profil</span>
            </a>

        </div>
    </nav>

    
    <script>
        // Engine Loader Halus (Pengganti Spinner)
        const showGlobalLoader = () => { 
            const l = document.getElementById('globalLoader'); 
            if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100'); } 
        };
        const hideGlobalLoader = () => { 
            const l = document.getElementById('globalLoader'); 
            if(l) { l.classList.remove('opacity-100'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 500); } 
        };

        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideGlobalLoader();
            
            // Pasang loader pada link navigasi untuk transisi smooth
            document.querySelectorAll('.smooth-route').forEach(el => {
                el.addEventListener('click', e => { 
                    if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) showGlobalLoader(); 
                });
            });

            // Kontrol Dropdown Profil Mobile/Tablet
            const uBtn = document.getElementById('userDropdownBtn');
            const uMenu = document.getElementById('userDropdown');
            if (uBtn && uMenu) {
                uBtn.addEventListener('click', e => { e.stopPropagation(); uMenu.classList.toggle('hidden'); });
                document.addEventListener('click', e => { if (!uMenu.contains(e.target) && !uBtn.contains(e.target)) uMenu.classList.add('hidden'); });
            }

            // AJAX REAL-TIME POLLING NOTIFIKASI
            let currentUnread = <?php echo e(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count()); ?>;

            setInterval(() => {
                fetch("<?php echo e(route('user.notifikasi.fetch')); ?>", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    const badgeTop = document.getElementById('notifBadgeTop');
                    const badgeBot = document.getElementById('badgeNotifBottom');

                    if (badgeTop) badgeTop.classList.toggle('hidden', data.unreadCount === 0);
                    if (badgeBot) badgeBot.classList.toggle('hidden', data.unreadCount === 0);

                    // Auto-refresh jika ada pesan baru DAN user sedang di halaman yang punya main-notif-wrapper (Dashboard)
                    if (data.unreadCount !== currentUnread && document.getElementById('main-notif-wrapper')) {
                        currentUnread = data.unreadCount;
                        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                const doc = new DOMParser().parseFromString(html, 'text/html');
                                const newWrapper = doc.getElementById('main-notif-wrapper');
                                if (newWrapper) {
                                    document.getElementById('main-notif-wrapper').innerHTML = newWrapper.innerHTML;
                                }
                            });
                    }
                }).catch(e => console.error(e));
            }, 10000); // Cek secara senyap setiap 10 detik
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/user.blade.php ENDPATH**/ ?>