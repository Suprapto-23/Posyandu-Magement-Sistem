<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> — PosyanduCare</title>
    
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/%3E%3Cpath d='M12 8v4'/%3E%3Cpath d='M10 10h4'/%3E%3C/svg%3E">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    
    <style type="text/tailwindcss">
        @theme { 
            --font-sans: 'Inter', sans-serif; 
            --font-poppins: 'Poppins', sans-serif;
            --color-obsidian: #0f172a;
        }
        
        body { 
            font-family: var(--font-sans); 
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Loading Bar Animation (Youtube/GitHub Style) */
        @keyframes loadingBar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }
        .animate-loading-bar {
            animation: loadingBar 1.2s infinite ease-in-out;
        }
    </style>
</head>

<body class="text-slate-700 selection:bg-blue-500 selection:text-white" x-data="{ sidebarOpen: false, isNavigating: false }">

    
    <div x-show="isNavigating" class="fixed top-0 left-0 w-full h-1.5 z-[9999] bg-blue-100 overflow-hidden" style="display: none;">
        <div class="h-full bg-blue-600 animate-loading-bar w-1/3 rounded-full"></div>
    </div>

    
    <div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 bg-obsidian/50 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 h-full w-[280px] bg-obsidian border-r border-slate-800 z-50 transform lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl lg:shadow-none">
        
        <div class="h-20 flex items-center px-6 border-b border-slate-800 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-[10px] bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fas fa-shield-alt text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-black text-white tracking-tight truncate font-poppins">Posyandu<span class="text-blue-400">Care</span></h1>
                </div>
                <button @click.stop="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-5 pb-2 shrink-0">
            <div class="p-3 bg-slate-800/50 border border-slate-700/50 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 text-blue-400 rounded-lg flex items-center justify-center font-black shadow-inner shrink-0">
                    <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-white truncate font-poppins"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Root Access
                    </p>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-4 py-2 scroll-smooth">
            <?php echo $__env->make('partials.sidebar.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>
    </aside>

    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300">
        
        
        <header class="h-20 bg-white/90 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-6 lg:px-8">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 rounded-lg transition-all">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <nav class="hidden sm:flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600 transition-colors"><i class="fas fa-home"></i></a>
                    <i class="fas fa-chevron-right text-[9px] opacity-50"></i>
                    <span class="text-slate-600 bg-slate-100 px-3 py-1 rounded-md"><?php echo $__env->yieldContent('page-name', 'Overview'); ?></span>
                </nav>
            </div>
            
            <div class="flex items-center">
                
                <div x-data="{ userOpen: false }" class="relative">
                    <button @click="userOpen = !userOpen" @click.away="userOpen = false" class="flex items-center gap-3 p-1.5 pr-4 rounded-full hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center text-sm">
                            <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                        </div>
                        <span class="text-[13px] font-bold text-slate-700 hidden sm:block"><?php echo e(Auth::user()->name ?? 'Administrator'); ?></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300" :class="userOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="userOpen" x-transition.opacity.scale.95 style="display: none;" class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden">
                        <div class="p-2">
                            <div class="h-px bg-slate-100 my-1 mx-2"></div>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" @click="isNavigating = true" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-all">
                                    <i class="fas fa-sign-out-alt w-4"></i> Keluar Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        
        <main :class="{'opacity-50 blur-[2px] pointer-events-none': isNavigating, 'opacity-100 blur-0': !isNavigating}" class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1400px] mx-auto w-full transition-all duration-300 ease-out">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rootAlpine = document.querySelector('[x-data]').__x.$data;
            document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript"])').forEach(link => {
                link.addEventListener('click', function(e) {
                    if(!e.ctrlKey && !e.metaKey) {
                        rootAlpine.isNavigating = true;
                    }
                });
            });
            window.addEventListener('pageshow', (e) => {
                if (e.persisted) rootAlpine.isNavigating = false;
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success', title: 'Tersalin ke Clipboard!',
                    showConfirmButton: false, timer: 2000, timerProgressBar: true
                });
            });
        }
    </script>
    
    
    <?php if(session('generated_password') || session('reset_password')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let pass = "<?php echo e(session('generated_password') ?? session('reset_password')); ?>";
            let type = "<?php echo e(session('generated_password') ? 'Akun Berhasil Dibuat!' : 'Password Direset!'); ?>";
            
            Swal.fire({
                title: `<span class="text-blue-600 font-black">${type}</span>`,
                html: `
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 mt-2 text-left">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Identitas Akun:</p>
                        <p class="font-black text-slate-800 mb-4 text-base truncate"><?php echo e(session('user_name') ?? session('reset_name')); ?></p>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password Login:</p>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="${pass}" class="w-full bg-white border border-slate-300 text-blue-600 font-mono text-xl font-black px-4 py-3 rounded-xl text-center focus:outline-none">
                            <button onclick="copyToClipboard('${pass}')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition-all shadow-md"><i class="fas fa-copy text-lg"></i></button>
                        </div>
                    </div>
                `,
                icon: 'success', confirmButtonText: 'Selesai', confirmButtonColor: '#2563eb', allowOutsideClick: false,
                customClass: { popup: 'rounded-3xl shadow-xl', title: 'font-poppins' }
            });
        });
    </script>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/admin.blade.php ENDPATH**/ ?>