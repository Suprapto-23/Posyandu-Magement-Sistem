<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — PosyanduCare</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><path fill=%22%23f59e0b%22 d=%22M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z%22/><path fill=%22white%22 d=%22M11 7h2v3h3v2h-3v3h-2v-3H8v-2h3V7z%22/></svg>">
    
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
            --color-obsidian-900: #0f172a;
            --color-obsidian-800: #1e293b;
            --color-gold-500: #f59e0b;
            --color-gold-400: #fbbf24;
        }
        
        body { 
            font-family: var(--font-sans); 
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(15, 23, 42, 0.02) 0px, transparent 40%),
                radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.02) 0px, transparent 40%);
            background-attachment: fixed;
        }

        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        /* Custom Scrollbar Premium */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }

        /* Glassmorphism */
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .glass-dropdown {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Animations */
        @keyframes menuPop { 0% { opacity: 0; transform: scale(0.95) translateY(-10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-pop { animation: menuPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards; transform-origin: top right; }
        
        @keyframes loaderSpin { to { transform: rotate(360deg); } }
        @keyframes loaderPulse { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(0.9); opacity: 0.7; } }
    </style>
    @stack('styles')
</head>

<body class="text-slate-800 antialiased selection:bg-amber-500 selection:text-white">

    <div id="globalLoader" class="fixed inset-0 bg-white/95 backdrop-blur-xl z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
        <div class="relative w-24 h-24 flex items-center justify-center mb-6">
            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent animate-[loaderSpin_1s_linear_infinite]"></div>
            <div class="w-14 h-14 bg-obsidian-900 rounded-full flex items-center justify-center shadow-lg animate-[loaderPulse_2s_ease-in-out_infinite]">
                <i class="fas fa-shield-alt text-amber-500 text-2xl"></i>
            </div>
        </div>
        <p class="text-obsidian-900 font-poppins font-black tracking-[0.25em] text-xs" id="loaderText">MENGINISIASI SISTEM...</p>
    </div>

    <div id="mobileOverlay" class="fixed inset-0 bg-obsidian-900/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-full w-[280px] bg-obsidian-900 border-r border-slate-800 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
        
        <div class="h-20 flex items-center px-6 border-b border-slate-800 shrink-0 bg-obsidian-900/50">
            <div class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-obsidian-900 flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)] shrink-0 transition-transform hover:scale-105">
                    <i class="fas fa-crown text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-black text-white tracking-tight truncate font-poppins">Admin<span class="text-amber-500">Panel</span></h1>
                </div>
                <button id="closeSidebar" class="lg:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-slate-800 rounded-lg transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-5 pb-2 shrink-0">
            <div class="p-4 bg-obsidian-800/40 border border-slate-700/50 rounded-2xl flex items-center gap-3 hover:border-amber-500/50 hover:bg-obsidian-800/80 transition-all cursor-pointer group" onclick="document.getElementById('userDropdownBtn').click()">
                <div class="w-10 h-10 bg-obsidian-900 text-amber-500 border border-slate-700 rounded-xl flex items-center justify-center font-black shadow-inner shrink-0 group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate font-poppins">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_6px_rgba(245,158,11,0.8)] animate-pulse"></span>
                        Root Access
                    </p>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-4 py-4 scroll-smooth custom-scrollbar">
            @include('partials.sidebar.admin')
        </nav>
    </aside>

    <div class="lg:ml-[280px] min-h-screen flex flex-col transition-all duration-300 relative">
        
        <header class="h-20 glass-header sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm">
            
            <div class="flex items-center gap-4">
                <button id="menuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <nav class="hidden sm:flex items-center gap-2 text-[12px] font-bold text-slate-400 uppercase tracking-widest">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-500 transition-colors"><i class="fas fa-home text-sm"></i></a>
                    <i class="fas fa-chevron-right text-[9px] text-slate-300"></i>
                    <span class="text-slate-700">@yield('page-name', 'System Overview')</span>
                </nav>
            </div>
            
            <div class="flex items-center gap-3 relative">
                <button id="userDropdownBtn" class="flex items-center gap-2 p-1.5 pr-4 rounded-full bg-white/50 hover:bg-white border border-transparent hover:border-slate-200 transition-all group shadow-sm">
                    <div class="w-8 h-8 rounded-full ring-2 ring-white overflow-hidden flex items-center justify-center bg-obsidian-900 text-amber-500 font-bold shadow-md group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm font-bold text-slate-700 hidden sm:block">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-amber-500 transition-colors"></i>
                </button>
                
                <div id="userDropdown" class="hidden absolute top-16 right-0 w-[calc(100vw-2rem)] sm:w-72 glass-dropdown rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] z-50 animate-pop overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <p class="text-[16px] font-black text-obsidian-900 truncate font-poppins">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">PosyanduCare Core</p>
                    </div>
                    <div class="p-3">
                        <a href="{{ route('admin.settings.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-obsidian-900 rounded-2xl transition-all">
                            <div class="w-8 h-8 rounded-xl bg-slate-200/70 flex items-center justify-center text-slate-500"><i class="fas fa-cog"></i></div>
                            Pengaturan Sistem
                        </a>
                        <div class="h-px bg-slate-100 my-1 mx-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="showGlobalLoader('MENGAKHIRI SESI...')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700 rounded-2xl transition-all mt-1">
                                <div class="w-8 h-8 rounded-xl bg-rose-100/70 flex items-center justify-center"><i class="fas fa-power-off"></i></div>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full relative z-0">
            @yield('content')
        </main>
    </div>

    <script>
        // --- LOADER LOGIC ---
        const showGlobalLoader = (text = 'OTENTIKASI SISTEM...') => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                const textEl = document.getElementById('loaderText');
                if(textEl) textEl.innerText = text;
                loader.style.display = 'flex';
                loader.offsetHeight; // Force reflow
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        };

        const hideGlobalLoader = () => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => { loader.style.display = 'none'; }, 300); 
            }
        };

        document.addEventListener('DOMContentLoaded', hideGlobalLoader);
        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            // Intercept normal links for smooth loader
            document.querySelectorAll('.smooth-route').forEach(link => {
                link.addEventListener('click', function(e) {
                    if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                        showGlobalLoader();
                    }
                });
            });

            // --- SIDEBAR & MOBILE MENU LOGIC ---
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

            // --- USER DROPDOWN LOGIC ---
            const userBtn = document.getElementById('userDropdownBtn');
            const userMenu = document.getElementById('userDropdown');
            
            if (userBtn && userMenu) {
                userBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', (e) => {
                if (userMenu && !userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
            
            if(userMenu) userMenu.addEventListener('click', (e) => e.stopPropagation());
        });
    </script>
    @stack('scripts')
</body>
</html>