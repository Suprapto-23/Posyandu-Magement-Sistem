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
    
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* ====================================================================
           NEXUS GLOBAL ENGINE STYLES
           ==================================================================== */
        :root {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-poppins: 'Poppins', sans-serif;
        }
        body, html, input, button, select, textarea { 
            font-family: var(--font-sans) !important; 
            background-color: #f8fafc;
            color: #0f172a;
        }
        h1, h2, h3, h4, h5, h6, .font-poppins { 
            font-family: var(--font-poppins) !important; 
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; transition: all 0.3s; }
        ::-webkit-scrollbar-thumb:hover { background: #818cf8; }
        
        @keyframes loadingBar { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
        .animate-loading-bar { animation: loadingBar 1.5s infinite cubic-bezier(0.4, 0, 0.2, 1); }
        
        .nexus-glass-header { 
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.6); 
            box-shadow: 0 4px 30px -10px rgba(0,0,0,0.05);
        }

        .swal2-container.nexus-backdrop { backdrop-filter: blur(10px) !important; background: rgba(15, 23, 42, 0.5) !important; z-index: 99999 !important;}
        .swal2-popup.nexus-popup {
            border-radius: 36px !important; padding: 2.5rem 2rem !important;
            background: rgba(255, 255, 255, 0.98) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 25px 60px -15px rgba(0,0,0,0.2) !important;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="flex h-screen overflow-hidden selection:bg-indigo-500 selection:text-white" 
      x-data="{ sidebarOpen: false, isNavigating: false, notifOpen: false, profileOpen: false }"
      @spa-start.window="isNavigating = true"
      @spa-stop.window="isNavigating = false">

    
    <div x-show="isNavigating" class="fixed top-0 left-0 w-full h-[3px] z-[9999] bg-slate-100 overflow-hidden" style="display: none;">
        <div class="h-full bg-gradient-to-r from-indigo-500 via-violet-500 to-rose-500 animate-loading-bar w-1/2 rounded-r-full shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
    </div>

    
    <div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 xl:hidden" style="display: none;"></div>

    
    <?php echo $__env->make('partials.sidebar.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen relative xl:ml-[280px] transition-all duration-300 bg-[#f4f7fe]">
        
        
        <header class="h-[75px] sm:h-[85px] nexus-glass-header sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-10">
            
            <div class="flex items-center gap-4 sm:gap-5">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-white rounded-[14px] transition-all shadow-sm border border-slate-200/50 xl:hidden">
                    <i class="fas fa-bars-staggered"></i>
                </button>

                <div class="hidden md:flex flex-col">
                    <h2 class="text-[22px] font-black text-slate-800 tracking-tight font-poppins leading-none"><?php echo $__env->yieldContent('page-name', 'Beranda Utama'); ?></h2>
                    <div class="flex items-center gap-2 mt-1.5 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">
                        <span>Workspace</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-indigo-600">Sistem Berjalan</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-5 relative">
                
                <?php $unreadNotifCount = class_exists('\App\Models\Notifikasi') ? \App\Models\Notifikasi::where('user_id', Auth::id() ?? 1)->where('is_read', false)->count() : 0; ?>

                
                <div class="relative">
                    <button @click="notifOpen = !notifOpen; profileOpen = false" @click.away="notifOpen = false" class="relative w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center bg-white text-slate-500 hover:text-indigo-600 rounded-[14px] sm:rounded-[16px] transition-all border border-slate-200/60 shadow-sm hover:shadow-lg hover:-translate-y-0.5 group">
                        <i class="fas fa-bell text-[16px] sm:text-[18px] group-hover:animate-shake"></i>
                        <span id="notifBadge" class="absolute -top-1 -right-1 flex h-3.5 w-3.5 sm:h-4 sm:w-4 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?>">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 sm:h-4 sm:w-4 bg-rose-500 border-2 border-white"></span>
                        </span>
                    </button>
                    
                    
                    <div x-show="notifOpen" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2" 
                         style="display: none;" 
                         class="fixed sm:absolute top-[85px] sm:top-[130%] left-4 right-4 sm:left-auto sm:right-0 w-auto sm:w-[360px] bg-white/95 backdrop-blur-2xl border border-white shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] rounded-[24px] sm:rounded-[32px] z-50 overflow-hidden flex flex-col origin-top sm:origin-top-right">
                        
                        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h3 class="text-[14px] sm:text-[15px] font-black text-slate-800 font-poppins">Pusat Sinyal</h3>
                            <span id="notifCount" class="text-[9px] sm:text-[10px] font-black px-3 py-1 rounded-full text-rose-600 bg-rose-50 border border-rose-100 <?php echo e($unreadNotifCount > 0 ? '' : 'hidden'); ?> shadow-sm"><?php echo e($unreadNotifCount); ?> Baru</span>
                        </div>
                        
                        <div id="notifList" class="max-h-[300px] sm:max-h-[350px] overflow-y-auto custom-scrollbar flex-1 bg-white">
                            <div class="py-10 text-center text-slate-400">
                                <div class="w-10 h-10 rounded-full border-[3px] border-slate-100 border-t-indigo-500 animate-spin mx-auto mb-3"></div>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Sinkronisasi Data...</p>
                            </div>
                        </div>
                        
                        <div class="p-3 sm:p-4 border-t border-slate-100 bg-slate-50/80">
                            <a href="<?php echo e(route('kader.notifikasi.index')); ?>" class="spa-route w-full py-3 sm:py-3.5 text-[10px] sm:text-[11px] font-black tracking-widest uppercase text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-xl sm:rounded-2xl transition-all block text-center shadow-sm border border-indigo-100">
                                Lihat Seluruh Arsip
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="relative">
                    <button @click="profileOpen = !profileOpen; notifOpen = false" @click.away="profileOpen = false" class="flex items-center gap-3 pl-1.5 pr-1.5 sm:pr-4 py-1.5 bg-white border border-slate-200/60 rounded-[14px] sm:rounded-[18px] shadow-sm hover:shadow-lg transition-all hover:-translate-y-0.5 group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-[10px] sm:rounded-[12px] bg-gradient-to-tr from-indigo-500 to-violet-500 text-white flex items-center justify-center font-black text-xs sm:text-sm shadow-inner group-hover:scale-105 transition-transform">
                            <?php echo e(strtoupper(substr(Auth::user()->name ?? 'K', 0, 1))); ?>

                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[13px] font-black text-slate-800 leading-none truncate max-w-[130px] font-poppins"><?php echo e(Auth::user()->name ?? 'Kader Aktif'); ?></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Petugas Medis</p>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300 ml-1 hidden sm:block" :class="profileOpen ? 'rotate-180 text-indigo-500' : ''"></i>
                    </button>

                    
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2" 
                         style="display: none;" 
                         class="fixed sm:absolute top-[85px] sm:top-[130%] left-4 right-4 sm:left-auto sm:right-0 w-auto sm:w-[260px] bg-white/95 backdrop-blur-2xl border border-white shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] rounded-[24px] sm:rounded-[28px] z-50 overflow-hidden origin-top sm:origin-top-right p-2 sm:p-3">
                        
                        <div class="px-4 py-3 sm:px-5 sm:py-4 border-b border-slate-100 mb-2 bg-slate-50/80 rounded-t-[20px]">
                            <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">Sesi Aktif</p>
                            <p class="text-[12px] sm:text-[13px] font-bold text-slate-800 truncate mt-1"><?php echo e(Auth::user()->email ?? 'kader@posyandu.com'); ?></p>
                        </div>
                        
                        <a href="<?php echo e(route('kader.profile.index')); ?>" class="spa-route flex items-center gap-3 sm:gap-4 px-4 py-3 sm:px-5 sm:py-3.5 text-[11px] sm:text-[12px] font-black uppercase tracking-wider text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-400 border border-slate-100"><i class="fas fa-id-badge"></i></div>
                            Identity Center
                        </a>
                        
                        <div class="h-px w-full bg-slate-100 my-1"></div>

                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0 p-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" @click="window.dispatchEvent(new CustomEvent('spa-start'))" class="w-full flex items-center gap-3 sm:gap-4 px-4 py-3 sm:px-5 sm:py-3.5 text-[11px] sm:text-[12px] font-black uppercase tracking-wider text-slate-500 hover:text-white hover:bg-rose-500 rounded-xl transition-all group">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-400 group-hover:text-white transition-colors"><i class="fas fa-sign-out-alt"></i></div>
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        
        <main :class="{'opacity-50 blur-[4px] pointer-events-none scale-[0.99]': isNavigating, 'opacity-100 blur-0 scale-100': !isNavigating}" class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-10 relative z-0 custom-scrollbar transition-all duration-500 ease-out">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Penanganan Navigasi SPA Halus (Bebas Crash)
            document.querySelectorAll('.spa-route').forEach(link => {
                link.addEventListener('click', function(e) { 
                    if(!e.ctrlKey && !e.metaKey) window.dispatchEvent(new CustomEvent('spa-start')); 
                });
            });
            window.addEventListener('pageshow', (e) => { 
                if (e.persisted) window.dispatchEvent(new CustomEvent('spa-stop')); 
            });

            // MESIN SINKRONISASI NOTIFIKASI
            let currentUnreadCount = <?php echo e($unreadNotifCount ?? 0); ?>;
            
            function fetchNotificationsBackground() {
                if(!navigator.onLine) return;
                
                fetch("<?php echo e(route('kader.notifikasi.fetch')); ?>", { 
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } 
                })
                .then(r => {
                    if (!r.ok) throw new Error('Server Error: ' + r.status);
                    return r.json();
                })
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const countBadge = document.getElementById('notifCount');
                    const list = document.getElementById('notifList');
                    
                    if(data.unreadCount > 0) { 
                        badge.classList.remove('hidden'); 
                        countBadge.classList.remove('hidden'); 
                        countBadge.textContent = data.unreadCount + ' Baru'; 
                    } else { 
                        badge.classList.add('hidden'); 
                        countBadge.classList.add('hidden'); 
                    }
                    
                    if(list && data.html) list.innerHTML = data.html;
                    
                    // Pemicu Toast UI (Juga dibuat responsive untuk HP)
                    if (data.unreadCount > currentUnreadCount) {
                        Swal.fire({ 
                            toast: true, position: 'top-end', 
                            html: `
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100"><i class="fas fa-bell animate-shake"></i></div>
                                    <div class="text-left">
                                        <p class="text-[12px] sm:text-[13px] font-black text-slate-800 font-poppins">${data.latest_title || 'Sinyal Baru Masuk'}</p>
                                        <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 mt-0.5 line-clamp-1">${data.latest_body || 'Ada pembaruan data di sistem.'}</p>
                                    </div>
                                </div>
                            `,
                            showConfirmButton: false, timer: 4500, timerProgressBar: true,
                            customClass: { popup: '!rounded-[20px] sm:!rounded-[24px] !border !border-white !shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] !bg-white/95 !backdrop-blur-xl !p-3 sm:!p-4 !mx-4 sm:!ml-0 sm:!mr-4 !mt-4 !w-auto' }
                        });
                    }
                    currentUnreadCount = data.unreadCount;
                })
                .catch(err => {
                    const list = document.getElementById('notifList');
                    if(list) {
                        list.innerHTML = `
                            <div class="py-10 text-center text-rose-500">
                                <div class="w-10 h-10 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center mx-auto mb-3"><i class="fas fa-exclamation-triangle text-lg"></i></div>
                                <p class="text-[10px] font-black uppercase tracking-widest">Koneksi / Server Terputus</p>
                                <p class="text-[9px] font-medium text-slate-400 mt-1 max-w-[180px] mx-auto">Sistem gagal menarik data. Cek tab Network (F12).</p>
                            </div>
                        `;
                    }
                    console.error('🚨 Nexus Notification Engine Error:', err);
                });
            }
            
            fetchNotificationsBackground();
            setInterval(fetchNotificationsBackground, 10000);
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/kader.blade.php ENDPATH**/ ?>