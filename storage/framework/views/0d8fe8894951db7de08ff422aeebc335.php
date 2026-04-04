<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Bidan Workspace'); ?> — PosyanduCare</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><path fill=%22%230891b2%22 d=%22M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z%22/><path fill=%22white%22 d=%22M11 7h2v3h3v2h-3v3h-2v-3H8v-2h3V7z%22/></svg>">
    
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
            background-image: radial-gradient(at 0% 0%, rgba(8, 145, 178, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        @keyframes menuPop { 0% { opacity: 0; transform: scale(0.95) translateY(-10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-pop { animation: menuPop 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; transform-origin: top right; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="text-slate-800 antialiased selection:bg-cyan-100 selection:text-cyan-900">

    <div id="globalLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-150 opacity-0 pointer-events-none">
        <div class="relative w-20 h-20 flex items-center justify-center mb-5">
            <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-12 h-12 bg-cyan-50 rounded-full flex items-center justify-center shadow-inner">
                <i class="fas fa-stethoscope text-cyan-600 text-2xl animate-pulse"></i>
            </div>
        </div>
        <p class="text-cyan-800 font-poppins font-black tracking-[0.2em] text-xs" id="loaderText">MEMUAT SISTEM...</p>
    </div>

    <div id="mobileOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-full w-[280px] bg-white border-r border-slate-200/80 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        
        <div class="h-20 flex items-center px-6 border-b border-slate-100/80 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(8,145,178,0.3)] shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-hand-holding-medical text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-black text-slate-900 tracking-tight truncate font-poppins">Medis<span class="text-cyan-600">Care</span></h1>
                </div>
                <button id="closeSidebar" class="lg:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-5 pb-2 shrink-0">
            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-3 hover:border-cyan-200 hover:bg-cyan-50/50 transition-colors cursor-pointer group" onclick="document.getElementById('userDropdownBtn').click()">
                <div class="w-11 h-11 bg-gradient-to-br from-cyan-100 to-blue-100 text-cyan-700 rounded-xl flex items-center justify-center font-black border border-white shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                    <?php echo e(strtoupper(substr(Auth::user()->name ?? 'B', 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate font-poppins"><?php echo e(Auth::user()->name ?? 'Bidan Desa'); ?></p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_0_2px_rgba(16,185,129,0.2)] animate-pulse"></span>
                        Validator Medis
                    </p>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-4 py-4 scroll-smooth custom-scrollbar">
            <?php echo $__env->make('partials.sidebar.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>

    </aside>

    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300 relative">
        
        <header class="h-20 glass-header border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm">
            
            <div class="flex items-center gap-4">
                <button id="menuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-600 hover:text-cyan-600 hover:bg-cyan-50 rounded-xl transition-colors">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <nav class="hidden sm:flex items-center gap-2 text-[13px] font-bold text-slate-400 uppercase tracking-widest">
                    <a href="<?php echo e(route('bidan.dashboard')); ?>" class="hover:text-cyan-600 transition-colors"><i class="fas fa-home"></i></a>
                    <i class="fas fa-chevron-right text-[9px] text-slate-300"></i>
                    <span class="text-cyan-700"><?php echo $__env->yieldContent('page-name', 'Dashboard'); ?></span>
                </nav>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-4 relative">
                
                <?php $notifCount = \App\Models\Pemeriksaan::where('status_verifikasi', 'pending')->count(); ?>

                <div class="static sm:relative">
                    <button id="notifDropdownBtn" class="relative w-10 h-10 flex items-center justify-center text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-full transition-all border border-transparent hover:border-cyan-100">
                        <i class="fas fa-bell text-[19px]"></i>
                        <?php if($notifCount > 0): ?>
                            <span id="notifBadge" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                        <?php endif; ?>
                    </button>
                    
                    <div id="notifDropdown" class="hidden absolute top-20 right-4 w-[calc(100vw-2rem)] sm:top-auto sm:right-0 sm:mt-3 sm:w-80 bg-white rounded-3xl shadow-[0_12px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-100 z-50 animate-pop overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/80">
                            <h3 class="text-sm font-black text-slate-800 font-poppins">Pusat Notifikasi</h3>
                        </div>
                        <div id="notifList" class="max-h-80 overflow-y-auto custom-scrollbar">
                            <?php if($notifCount > 0): ?>
                                <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>?status=pending" class="smooth-route flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-50 group">
                                    <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                                        <i class="fas fa-file-medical-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-slate-800 leading-tight font-poppins">Antrian Validasi Baru</p>
                                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Terdapat <?php echo e($notifCount); ?> data hasil ukur kader yang menunggu diagnosa.</p>
                                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-2"><i class="fas fa-exclamation-circle mr-1"></i> Tindakan Diperlukan</p>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                                    <div class="w-16 h-16 bg-emerald-50 text-emerald-400 rounded-full flex items-center justify-center text-3xl mb-3 border border-emerald-100">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600 uppercase tracking-widest">Antrian Bersih</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="static sm:relative ml-1">
                    <button id="userDropdownBtn" class="flex items-center gap-2 p-1 pr-3 rounded-full hover:bg-slate-100 transition-colors border border-transparent hover:border-slate-200 group">
                        <div class="w-8 h-8 rounded-full ring-2 ring-white overflow-hidden flex items-center justify-center bg-cyan-100 text-cyan-700 font-bold shadow-sm group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                            <?php echo e(strtoupper(substr(Auth::user()->name ?? 'B', 0, 1))); ?>

                        </div>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600"></i>
                    </button>
                    
                    <div id="userDropdown" class="hidden absolute top-20 right-4 w-[calc(100vw-2rem)] sm:top-auto sm:right-0 sm:mt-4 sm:w-64 bg-white rounded-3xl shadow-[0_12px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-100 z-50 animate-pop overflow-hidden">
                        <div class="px-5 py-5 border-b border-slate-50 bg-gradient-to-r from-cyan-50 to-blue-50">
                            <p class="text-[15px] font-black text-slate-800 truncate font-poppins"><?php echo e(Auth::user()->name ?? 'Bidan Posyandu'); ?></p>
                            <p class="text-[11px] text-cyan-700 font-bold uppercase tracking-widest mt-1">Akun Bidan Desa</p>
                        </div>
                        <div class="p-2 border-t border-slate-50">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="showGlobalLoader('SEDANG KELUAR...')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 rounded-2xl transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center"><i class="fas fa-sign-out-alt"></i></div>
                                    Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full relative z-0">
            <?php $__currentLoopData = ['success' => ['bg-emerald-50', 'text-emerald-800', 'text-emerald-500', 'fa-check-circle', 'border-emerald-200'], 
                       'error' => ['bg-rose-50', 'text-rose-800', 'text-rose-500', 'fa-circle-exclamation', 'border-rose-200']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg => $classes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($msg)): ?>
                    <div class="mb-6 px-5 py-4 <?php echo e($classes[0]); ?> border <?php echo e($classes[4]); ?> rounded-2xl flex items-center justify-between shadow-sm animate-[slideDown_0.4s_ease-out]">
                        <div class="flex items-center gap-3">
                            <i class="fas <?php echo e($classes[3]); ?> <?php echo e($classes[2]); ?> text-xl"></i>
                            <span class="<?php echo e($classes[1]); ?> text-[13px] sm:text-sm font-bold"><?php echo e(session($msg)); ?></span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="<?php echo e($classes[2]); ?> hover:opacity-70 transition-opacity p-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        // --- LOGIKA LOADER SAT-SET (SUPER CEPAT) ---
        const showGlobalLoader = (text = 'MEMUAT SISTEM...') => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                const textEl = document.getElementById('loaderText');
                if(textEl) textEl.innerText = text;
                loader.style.display = 'flex';
                loader.offsetHeight; 
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        };

        const hideGlobalLoader = () => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0', 'pointer-events-none');
                // Sembunyikan elemen dalam 150ms (sangat cepat)
                setTimeout(() => loader.style.display = 'none', 150);
            }
        };

        // Eksekusi hide seketika saat kerangka HTML/DOM siap (Tidak nunggu gambar lambat)
        document.addEventListener('DOMContentLoaded', hideGlobalLoader);
        
        // Eksekusi juga saat user pakai tombol Back/Forward di browser
        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            // Animasi Loader hanya saat klik menu link biasa
            document.querySelectorAll('.smooth-route').forEach(link => {
                link.addEventListener('click', function(e) {
                    if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                        showGlobalLoader();
                    }
                });
            });

            // --- Logika Sidebar Mobile ---
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const menuToggle = document.getElementById('menuToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            
            const toggleSidebar = () => {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.remove('opacity-100');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    setTimeout(() => overlay.classList.add('opacity-100'), 10);
                    document.body.classList.add('overflow-hidden');
                }
            };

            if (menuToggle) menuToggle.addEventListener('click', toggleSidebar);
            if (closeSidebar) closeSidebar.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);

            // --- Logika Dropdown Navbar ---
            const userBtn = document.getElementById('userDropdownBtn');
            const userMenu = document.getElementById('userDropdown');
            const notifBtn = document.getElementById('notifDropdownBtn');
            const notifMenu = document.getElementById('notifDropdown');
            
            if (userBtn && userMenu) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                    if(notifMenu) notifMenu.classList.add('hidden'); 
                });
            }

            if (notifBtn && notifMenu) {
                notifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notifMenu.classList.toggle('hidden');
                    if(userMenu) userMenu.classList.add('hidden'); 
                });
            }

            document.addEventListener('click', (e) => {
                if (userMenu && !userMenu.contains(e.target) && !userBtn.contains(e.target)) userMenu.classList.add('hidden');
                if (notifMenu && !notifMenu.contains(e.target) && !notifBtn.contains(e.target)) notifMenu.classList.add('hidden');
            });
            
            if(notifMenu) notifMenu.addEventListener('click', (e) => e.stopPropagation());
            if(userMenu) userMenu.addEventListener('click', (e) => e.stopPropagation());
        });

        // --- Logika Dropdown Submenu Sidebar ---
        function toggleSubmenu(menuId, iconId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);
            if (menu.classList.contains('grid-rows-[0fr]')) {
                menu.classList.remove('grid-rows-[0fr]', 'opacity-0');
                menu.classList.add('grid-rows-[1fr]', 'opacity-100');
                icon.classList.add('rotate-180', 'text-cyan-600');
                icon.classList.remove('text-slate-400');
            } else {
                menu.classList.add('grid-rows-[0fr]', 'opacity-0');
                menu.classList.remove('grid-rows-[1fr]', 'opacity-100');
                icon.classList.remove('rotate-180', 'text-cyan-600');
                icon.classList.add('text-slate-400');
            }
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/bidan.blade.php ENDPATH**/ ?>