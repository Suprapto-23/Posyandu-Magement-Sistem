<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#4f46e5">
    <title><?php echo $__env->yieldContent('title', 'Kader Workspace'); ?> — KaderCare</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNGY0NmU1IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTEyIDJMMiAxMmwzIDMgNy03IDcgNyAzLTMtMTAtMTB6IiBmaWxsPSIjNGY0NmU1IiBmaWxsLW9wYWNpdHk9IjAuMiIvPjxwYXRoIGQ9Ik0xMiAyMnYtOG0tNCA0aDgiIHN0cm9rZT0iIzRmNDZlNSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz48L3N2Zz4=">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* MEMAKSA FONT UNTUK TERAPLIKASI (Mencegah Fallback ke Arial) */
        :root {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-poppins: 'Poppins', sans-serif;
        }
        body, html, input, button, select, textarea { 
            font-family: var(--font-sans) !important; 
            background-color: #f4f7fe; /* Warna bg modern */
            color: #1e293b;
        }
        h1, h2, h3, h4, h5, h6, .font-poppins { 
            font-family: var(--font-poppins) !important; 
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        @keyframes loadingBar { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
        .animate-loading-bar { animation: loadingBar 1.5s infinite ease-in-out; }
        .nexus-glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255,255,255,0.5); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="flex h-screen overflow-hidden selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false, isNavigating: false, notifOpen: false, profileOpen: false }">

    
    <div x-show="isNavigating" class="fixed top-0 left-0 w-full h-[3px] z-[9999] bg-indigo-100/50 overflow-hidden" style="display: none;">
        <div class="h-full bg-indigo-600 animate-loading-bar w-1/3 rounded-r-full shadow-[0_0_10px_#4f46e5]"></div>
    </div>

    <div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 xl:hidden" style="display: none;"></div>

    
    <?php echo $__env->make('partials.sidebar.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen relative xl:ml-[280px] transition-all duration-300">
        
        
        <header class="h-[80px] nexus-glass sticky top-0 z-30 flex items-center justify-between px-6 lg:px-10">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-white rounded-xl transition-all shadow-sm border border-slate-200/50 xl:hidden">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <div class="hidden md:flex flex-col">
                    <h2 class="text-[22px] font-bold text-slate-800 tracking-tight font-poppins leading-none"><?php echo $__env->yieldContent('page-name', 'Beranda Utama'); ?></h2>
                    <div class="flex items-center gap-2 mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">
                        <span>Workspace</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-indigo-500">Sistem Berjalan</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                
                <?php $unreadNotifCount = class_exists('\App\Models\Notifikasi') ? \App\Models\Notifikasi::where('user_id', Auth::id() ?? 1)->where('is_read', false)->count() : 0; ?>

                
                <div class="relative">
                    <button @click="notifOpen = !notifOpen; profileOpen = false" @click.away="notifOpen = false" class="relative w-11 h-11 flex items-center justify-center bg-white text-slate-500 hover:text-indigo-600 rounded-full transition-all border border-slate-200/60 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                        <i class="fas fa-bell text-[18px] group-hover:animate-shake"></i>
                        <span id="notifBadge" class="absolute top-0 right-0 flex h-3.5 w-3.5 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-rose-500 border-2 border-white"></span>
                        </span>
                    </button>
                    
                    
                    <div x-show="notifOpen" x-transition.opacity.scale.95 style="display: none;" class="absolute top-[130%] right-0 w-[350px] bg-white/95 backdrop-blur-xl border border-slate-100 shadow-[0_20px_50px_-15px_rgba(0,0,0,0.1)] rounded-[24px] z-50 overflow-hidden flex flex-col origin-top-right">
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-800 font-poppins">Pusat Notifikasi</h3>
                            <span id="notifCount" class="text-[10px] font-bold px-2 py-0.5 rounded-md text-rose-600 bg-rose-50 border border-rose-100 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>"><?php echo e($unreadNotifCount); ?> Baru</span>
                        </div>
                        <div id="notifList" class="max-h-[300px] overflow-y-auto custom-scrollbar flex-1 bg-white">
                            <div class="py-10 text-center text-slate-400">
                                <i class="fas fa-spinner fa-spin text-2xl mb-2 opacity-50"></i>
                                <p class="text-[11px] font-medium">Memuat data...</p>
                            </div>
                        </div>
                        <div class="p-3 border-t border-slate-100 bg-slate-50/50">
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="spa-route w-full py-2.5 text-[11px] font-bold tracking-widest uppercase text-indigo-600 hover:bg-white rounded-xl transition-all block text-center shadow-sm border border-transparent hover:border-slate-200">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>

                
                <div class="relative">
                    <button @click="profileOpen = !profileOpen; notifOpen = false" @click.away="profileOpen = false" class="flex items-center gap-3 pl-2 pr-4 py-1.5 bg-white border border-slate-200/60 rounded-full shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-500 text-white flex items-center justify-center font-bold text-xs shadow-inner">
                            <?php echo e(strtoupper(substr(Auth::user()->name ?? 'K', 0, 1))); ?>

                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[12px] font-bold text-slate-800 leading-none truncate max-w-[120px]"><?php echo e(Auth::user()->name ?? 'Kader Aktif'); ?></p>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300 ml-1" :class="profileOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="profileOpen" x-transition.opacity.scale.95 style="display: none;" class="absolute top-[130%] right-0 w-[240px] bg-white border border-slate-100 shadow-[0_20px_50px_-15px_rgba(0,0,0,0.1)] rounded-[20px] z-50 overflow-hidden origin-top-right p-2">
                        <div class="px-4 py-4 border-b border-slate-50 mb-2 bg-slate-50/50 rounded-t-[14px]">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Akses Sebagai</p>
                            <p class="text-[13px] font-bold text-slate-800 truncate mt-1"><?php echo e(Auth::user()->email ?? 'kader@posyandu.com'); ?></p>
                        </div>
                        
                        <a href="<?php echo e(route('kader.profile.index')); ?>" class="spa-route flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <i class="fas fa-user-cog w-5 text-center"></i> Pengaturan Akun
                        </a>
                        
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0 p-0 mt-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" @click="isNavigating = true" class="w-full flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-rose-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        
        <main :class="{'opacity-50 blur-[2px] pointer-events-none': isNavigating, 'opacity-100 blur-0': !isNavigating}" class="flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-10 relative z-0 custom-scrollbar transition-all duration-300 ease-out">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rootAlpine = document.querySelector('[x-data]').__x.$data;
            document.querySelectorAll('.spa-route').forEach(link => {
                link.addEventListener('click', function(e) { if(!e.ctrlKey && !e.metaKey) rootAlpine.isNavigating = true; });
            });
            window.addEventListener('pageshow', (e) => { if (e.persisted) rootAlpine.isNavigating = false; });

            let currentUnreadCount = <?php echo e($unreadNotifCount ?? 0); ?>;
            function fetchNotificationsBackground() {
                if(!navigator.onLine) return;
                fetch("<?php echo e(route('kader.notifikasi.fetch')); ?>", { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json()).then(data => {
                    const badge = document.getElementById('notifBadge'), countBadge = document.getElementById('notifCount'), list = document.getElementById('notifList');
                    if(data.unreadCount > 0) { badge.classList.remove('hidden'); countBadge.classList.remove('hidden'); countBadge.textContent = data.unreadCount + ' Baru'; } 
                    else { badge.classList.add('hidden'); countBadge.classList.add('hidden'); }
                    if(list && data.html) list.innerHTML = data.html;
                    if (data.unreadCount > currentUnreadCount) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: 'Notifikasi Baru', text: 'Ada pembaruan data sistem.', showConfirmButton: false, timer: 3000 });
                    }
                    currentUnreadCount = data.unreadCount;
                }).catch(err => {});
            }
            fetchNotificationsBackground();
            setInterval(fetchNotificationsBackground, 10000);
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>