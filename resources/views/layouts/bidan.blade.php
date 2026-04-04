<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Bidan Workspace') — PosyanduCare</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%2306b6d4'/%3E%3Cstop offset='100%25' stop-color='%230284c7'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='24' height='24' rx='6' fill='url(%23grad)'/%3E%3Cpath d='M12 7v10M8 12h8' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">
    
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
            background-color: #f0fdfa; /* Nuansa higienis medis */
            background-image: radial-gradient(at 0% 0%, rgba(6, 182, 212, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased;
            color: #0f172a;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        
        /* Ultra Clean Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }

        /* Smooth UI Utilities */
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .menu-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .submenu-grid { display: grid; transition: grid-template-rows 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Animasi Transisi Layout (Fluid Sidebar) */
        .layout-shift { transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Toast Animation Spring (Bouncy) */
        @keyframes toastEnter { 0% { opacity: 0; transform: translateX(100%) scale(0.9); } 100% { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes toastLeave { 0% { opacity: 1; transform: translateX(0) scale(1); max-height: 120px; margin-bottom: 12px; } 100% { opacity: 0; transform: translateX(100%) scale(0.9); max-height: 0; margin-bottom: 0; padding: 0; border: 0; } }
        .toast-show { animation: toastEnter 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .toast-hide { animation: toastLeave 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
    </style>
    @stack('styles')
</head>

<body class="flex h-screen overflow-hidden selection:bg-cyan-100 selection:text-cyan-900">

    {{-- 1. OFFLINE BANNER --}}
    <div id="offlineBanner" class="fixed top-0 left-0 right-0 z-[99999] bg-rose-500 text-white text-[11px] font-black uppercase tracking-widest py-2 text-center transform -translate-y-full transition-transform duration-300 flex items-center justify-center gap-2 shadow-lg">
        <i class="fas fa-wifi-slash animate-pulse"></i> Koneksi Terputus. Menunggu Jaringan...
    </div>

    {{-- 2. PREMIUM TOAST CONTAINER (Tempat Pop-up Notifikasi Melayang) --}}
    <div id="toastContainer" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-full max-w-[350px] pointer-events-none"></div>

    {{-- 3. GLOBAL PAGE LOADER --}}
    <div id="globalLoader" class="fixed inset-0 z-[9998] bg-white/80 backdrop-blur-md flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="relative w-20 h-20 flex items-center justify-center mb-5">
            <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-cyan-500 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                <i class="fas fa-stethoscope text-cyan-600 animate-pulse text-xl"></i>
            </div>
        </div>
        <p class="text-[10px] font-black text-cyan-700 uppercase tracking-[0.25em]" id="loaderText">MEMUAT SISTEM...</p>
    </div>

    {{-- 4. MOBILE OVERLAY --}}
    <div id="mobileOverlay" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 xl:hidden"></div>

    @php 
        $route = request()->route()->getName() ?? '';
        
        // GAYA MENU BIDAN (SaaS Premium Indicator)
        $menuAktif = 'bg-cyan-50/80 text-cyan-700 font-bold shadow-[0_2px_10px_rgba(6,182,212,0.06)] relative before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-8 before:w-1 before:bg-cyan-500 before:rounded-r-md';
        $menuPasif = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium border border-transparent';
        $iconAktif = 'text-cyan-600 drop-shadow-sm';
        $iconPasif = 'text-slate-400 group-hover:text-cyan-500 transition-colors';
    @endphp

    {{-- ================================================================= --}}
    {{-- SIDEBAR FLUID (Tarik-Ulur) --}}
    {{-- ================================================================= --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/80 transform -translate-x-full xl:translate-x-0 transition-transform duration-400 ease-[cubic-bezier(0.4,0,0.2,1)] flex flex-col shadow-2xl xl:shadow-none">
        
        {{-- Brand Logo --}}
        <div class="h-[80px] flex items-center px-6 border-b border-slate-100 shrink-0 bg-white relative z-10">
            <div class="flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-[14px] bg-gradient-to-br from-cyan-400 to-sky-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.3)] shrink-0">
                    <i class="fas fa-hand-holding-medical text-[18px]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-[22px] font-black text-slate-800 tracking-tight truncate font-poppins">Medis<span class="text-cyan-500">Care</span></h1>
                </div>
                <button id="closeSidebarBtn" class="xl:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        {{-- Navigasi --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6 custom-scrollbar space-y-7 relative z-0">
            
            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Ruang Bidan</p>
                <a href="{{ route('bidan.dashboard') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ $route == 'bidan.dashboard' ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-chart-pie w-5 text-center text-[16px] {{ $route == 'bidan.dashboard' ? $iconAktif : $iconPasif }}"></i> 
                    <span class="text-[13.5px]">Dashboard Klinis</span>
                </a>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Layanan Medis</p>
                <div class="space-y-1">
                    <a href="{{ route('bidan.pemeriksaan.index') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ Str::startsWith($route, 'bidan.pemeriksaan') ? $menuAktif : $menuPasif }}">
                        <i class="fas fa-stethoscope w-5 text-center text-[16px] {{ Str::startsWith($route, 'bidan.pemeriksaan') ? $iconAktif : $iconPasif }}"></i> 
                        <span class="text-[13.5px]">Validasi Pemeriksaan</span>
                    </a>
                    <a href="{{ route('bidan.imunisasi.index') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ Str::startsWith($route, 'bidan.imunisasi') ? $menuAktif : $menuPasif }}">
                        <i class="fas fa-syringe w-5 text-center text-[16px] {{ Str::startsWith($route, 'bidan.imunisasi') ? $iconAktif : $iconPasif }}"></i> 
                        <span class="text-[13.5px]">Vaksin & Imunisasi</span>
                    </a>
                    <a href="{{ route('bidan.konseling.index') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ Str::startsWith($route, 'bidan.konseling') ? $menuAktif : $menuPasif }}">
                        <i class="fas fa-comments-medical w-5 text-center text-[16px] {{ Str::startsWith($route, 'bidan.konseling') ? $iconAktif : $iconPasif }}"></i> 
                        <span class="text-[13.5px]">Konseling Tertutup</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Database</p>
                <div class="space-y-1">
                    @php
                        $isRekamMedis = request()->routeIs('bidan.rekam-medis.*');
                        $typeParam = request()->get('type', 'balita');
                    @endphp

                    <button onclick="toggleSubmenu('menuPasien', 'iconPasien')" class="w-full group flex items-center justify-between px-4 py-3 rounded-[14px] text-[13.5px] menu-transition {{ $isRekamMedis ? $menuAktif : $menuPasif }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-folder-open w-5 text-center text-[16px] {{ $isRekamMedis ? $iconAktif : $iconPasif }}"></i> 
                            <span class="tracking-wide">Rekam Medis Warga</span>
                        </div>
                        <i id="iconPasien" class="fas fa-chevron-down text-[11px] transition-transform duration-300 {{ $isRekamMedis ? 'rotate-180 text-cyan-600' : 'text-slate-400 group-hover:text-cyan-500' }}"></i>
                    </button>
                    
                    <div id="menuPasien" class="submenu-grid {{ $isRekamMedis ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                        <div class="overflow-hidden">
                            <div class="pl-[48px] pr-2 py-1 space-y-1 relative before:absolute before:left-[26px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                                @foreach([
                                    ['type' => 'balita', 'label' => 'Data Balita'],
                                    ['type' => 'ibu_hamil', 'label' => 'Data Ibu Hamil'],
                                    ['type' => 'remaja', 'label' => 'Data Remaja'],
                                    ['type' => 'lansia', 'label' => 'Data Lansia'],
                                ] as $item)
                                    @php $isActive = $isRekamMedis && $typeParam == $item['type']; @endphp
                                    <a href="{{ route('bidan.rekam-medis.index', ['type' => $item['type']]) }}" class="smooth-route block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full {{ $isActive ? 'font-bold text-cyan-700 bg-white shadow-sm border border-slate-100 before:bg-cyan-500 before:ring-4 before:ring-cyan-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-cyan-300' }}">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Sistem</p>
                <div class="space-y-1">
                    <a href="{{ route('bidan.jadwal.index') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ Str::startsWith($route, 'bidan.jadwal') ? $menuAktif : $menuPasif }}">
                        <i class="fas fa-calendar-alt w-5 text-center text-[16px] {{ Str::startsWith($route, 'bidan.jadwal') ? $iconAktif : $iconPasif }}"></i> 
                        <span class="text-[13.5px]">Jadwal Posyandu</span>
                    </a>
                    <a href="{{ route('bidan.laporan.index') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-[14px] menu-transition {{ Str::startsWith($route, 'bidan.laporan') ? $menuAktif : $menuPasif }}">
                        <i class="fas fa-file-pdf w-5 text-center text-[16px] {{ Str::startsWith($route, 'bidan.laporan') ? $iconAktif : $iconPasif }}"></i> 
                        <span class="text-[13.5px]">Laporan Medis PDF</span>
                    </a>
                </div>
            </div>
            <div class="h-4"></div>
        </nav>

        {{-- Profil Bidan Mini --}}
        <div class="p-4 border-t border-slate-100 bg-white shrink-0">
            <div class="flex items-center gap-3 p-2 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-cyan-200 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-cyan-600 flex items-center justify-center font-black text-sm shadow-sm group-hover:bg-cyan-500 group-hover:text-white group-hover:border-cyan-600 transition-all">
                    {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->name ?? 'Bidan' }}</p>
                    <p class="text-[10px] font-black text-cyan-500 uppercase tracking-widest truncate mt-0.5"><i class="fas fa-certificate mr-1"></i> Verifikator</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ================================================================= --}}
    {{-- MAIN WRAPPER (Bisa Melebar / Shift Layout) --}}
    {{-- ================================================================= --}}
    <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 h-screen relative layout-shift xl:ml-[280px]">
        
        <header class="h-[80px] glass-panel sticky top-0 z-40 flex items-center justify-between px-4 lg:px-8 border-b border-slate-200/60 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-3 lg:gap-5">
                {{-- Tombol Toggle Ajaib --}}
                <button id="toggleSidebarBtn" class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-cyan-600 hover:bg-cyan-50 rounded-[12px] transition-colors bg-white border border-slate-200 shadow-sm">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <div class="hidden md:flex flex-col">
                    <h2 class="text-[18px] font-black text-slate-800 tracking-tight font-poppins leading-none">@yield('page-name', 'Beranda Medis')</h2>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] font-semibold text-slate-400 tracking-wide">
                        <span>Workspace Bidan</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-cyan-500 font-bold">Sistem Aktif</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-4">
                
                {{-- Dropdown Notif --}}
                @php $notifCount = \App\Models\Pemeriksaan::where('status_verifikasi', 'pending')->count() ?? 0; @endphp
                <div class="relative">
                    <button id="notifDropdownBtn" class="relative w-10 h-10 flex items-center justify-center bg-white text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-[12px] transition-all border border-slate-200 shadow-sm group">
                        <i class="fas fa-bell text-[16px] group-hover:animate-wiggle"></i>
                        @if($notifCount > 0)
                            <span id="notifBadge" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-rose-500 border-2 border-white"></span>
                            </span>
                        @endif
                    </button>
                </div>

                <div class="w-px h-6 bg-slate-200 mx-1 hidden sm:block"></div>

                {{-- Dropdown Profil (SaaS Popover) --}}
                <div class="relative group">
                    <button class="flex items-center gap-2.5 pl-1.5 pr-4 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors">
                        <div class="w-7 h-7 rounded-full bg-cyan-600 text-white flex items-center justify-center font-bold text-xs shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                        </div>
                        <span class="hidden md:block text-[13px] font-bold text-slate-700 truncate max-w-[100px]">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    
                    {{-- Tooltip/Popover Keluar --}}
                    <div class="absolute right-0 top-[110%] pt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl p-2 relative">
                            <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white border-l border-t border-slate-200 transform rotate-45"></div>
                            <form method="POST" action="{{ route('logout') }}" class="relative z-10">
                                @csrf
                                <button type="submit" onclick="showGlobalLoader('SEDANG KELUAR...')" class="w-full flex items-center gap-3 px-3 py-2.5 text-[12px] font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <i class="fas fa-sign-out-alt"></i> Akhiri Sesi (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- INJEKSI KONTEN UTAMA --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 relative z-0 custom-scrollbar pb-24 lg:pb-8">
            @yield('content')
        </main>

        {{-- 5. MOBILE BOTTOM NAV (Khusus Layar HP - iOS Style) --}}
        <nav class="xl:hidden fixed bottom-0 left-0 right-0 h-[68px] glass-panel border-t border-slate-200 z-50 flex items-center justify-around px-2 pb-safe shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
            <a href="{{ route('bidan.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ $route == 'bidan.dashboard' ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-chart-pie text-[18px] mb-1 {{ $route == 'bidan.dashboard' ? 'drop-shadow-md' : '' }}"></i> Beranda
            </a>
            <a href="{{ route('bidan.rekam-medis.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.rekam-medis') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-folder-open text-[18px] mb-1 {{ Str::startsWith($route,'bidan.rekam-medis') ? 'drop-shadow-md' : '' }}"></i> Rekam Medis
            </a>
            
            {{-- Tombol Tengah Mengambang (Pemeriksaan) --}}
            <div class="relative w-full h-full flex justify-center">
                <a href="{{ route('bidan.pemeriksaan.index') }}" class="absolute -top-5 flex flex-col items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 text-white shadow-[0_8px_20px_rgba(6,182,212,0.4)] border-4 border-white transition-transform active:scale-95">
                    <i class="fas fa-stethoscope text-[20px]"></i>
                </a>
            </div>

            <a href="{{ route('bidan.imunisasi.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.imunisasi') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-syringe text-[18px] mb-1 {{ Str::startsWith($route,'bidan.imunisasi') ? 'drop-shadow-md' : '' }}"></i> Vaksin
            </a>
            <a href="{{ route('bidan.jadwal.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.jadwal') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-calendar-alt text-[18px] mb-1 {{ Str::startsWith($route,'bidan.jadwal') ? 'drop-shadow-md' : '' }}"></i> Jadwal
            </a>
        </nav>

    </div>

    {{-- ================================================================= --}}
    {{-- JS ENGINE: TOAST, LOADER, SIDEBAR (Murni tanpa jQuery) --}}
    {{-- ================================================================= --}}
    <script>
        // 1. SUPER TOAST NOTIFICATION ENGINE (SPRING ANIMATION)
        const showToast = (type, title, message) => {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
            const color = type === 'success' ? 'emerald' : 'rose';
            
            const toastHtml = `
                <div class="toast-item pointer-events-auto bg-white p-4 rounded-[20px] shadow-[0_15px_40px_rgba(0,0,0,0.08)] border border-slate-100 flex items-start gap-3 toast-show relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-${color}-500"></div>
                    <div class="w-9 h-9 rounded-full bg-${color}-50 text-${color}-500 flex items-center justify-center shrink-0 ml-1 shadow-inner">${icon}</div>
                    <div class="flex-1 pt-1.5"><h4 class="text-[13px] font-black text-slate-800 leading-none mb-1">${title}</h4><p class="text-[11px] font-medium text-slate-500 line-clamp-2">${message}</p></div>
                    <button onclick="removeToast(this)" class="text-slate-300 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100"><i class="fas fa-times"></i></button>
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

        // Otomatis Panggil Toast dari Session Controller (Ini untuk login berhasil, dll)
        @if(session('success')) document.addEventListener('DOMContentLoaded', () => showToast('success', 'Aksi Berhasil', "{{ session('success') }}")); @endif
        @if(session('error')) document.addEventListener('DOMContentLoaded', () => showToast('error', 'Peringatan', "{{ session('error') }}")); @endif

        // 2. LOADER CONTROLS
        const showGlobalLoader = (text = 'MEMUAT SISTEM...') => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                document.getElementById('loaderText').innerText = text;
                loader.style.display = 'flex';
                loader.offsetHeight; 
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        };
        const hideGlobalLoader = () => { const l = document.getElementById('globalLoader'); if(l) { l.classList.remove('opacity-100'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); } };
        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideGlobalLoader();
            
            // Aktifkan loader saat menu diklik
            document.querySelectorAll('.smooth-route').forEach(el => el.addEventListener('click', e => { 
                if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) { showGlobalLoader(); }
            }));

            // 3. FLUID SIDEBAR LOGIC (Bisa Melebar ke Kiri)
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const overlay = document.getElementById('mobileOverlay');
            const toggleBtns = document.querySelectorAll('#toggleSidebarBtn, #closeSidebarBtn');
            
            let isDesktop = window.innerWidth >= 1280;
            let isSidebarOpenDesktop = true;
            window.addEventListener('resize', () => { isDesktop = window.innerWidth >= 1280; });

            const toggleSidebar = () => {
                if(isDesktop) {
                    // Logika Laptop: Hilangkan margin mainWrapper, Geser Sidebar
                    isSidebarOpenDesktop = !isSidebarOpenDesktop;
                    if(isSidebarOpenDesktop) { 
                        sidebar.classList.remove('xl:-translate-x-full'); sidebar.classList.add('xl:translate-x-0'); 
                        mainWrapper.classList.remove('xl:ml-0'); mainWrapper.classList.add('xl:ml-[280px]'); 
                    } else { 
                        sidebar.classList.remove('xl:translate-x-0'); sidebar.classList.add('xl:-translate-x-full'); 
                        mainWrapper.classList.remove('xl:ml-[280px]'); mainWrapper.classList.add('xl:ml-0'); 
                    }
                } else {
                    // Logika HP: Pakai Overlay Hitam Blur
                    if (sidebar.classList.contains('-translate-x-full')) { 
                        sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); 
                        setTimeout(() => overlay.classList.add('opacity-100'), 10); document.body.classList.add('overflow-hidden'); 
                    } else { 
                        sidebar.classList.add('-translate-x-full'); overlay.classList.remove('opacity-100'); 
                        setTimeout(() => overlay.classList.add('hidden'), 300); document.body.classList.remove('overflow-hidden'); 
                    }
                }
            };
            toggleBtns.forEach(btn => btn.addEventListener('click', toggleSidebar));
            if(overlay) overlay.addEventListener('click', toggleSidebar);

            // 4. OFFLINE DETECTOR
            const offlineBanner = document.getElementById('offlineBanner');
            window.addEventListener('offline', () => { offlineBanner.classList.remove('-translate-y-full'); showToast('error', 'Koneksi Terputus', 'Anda sedang offline. Data tidak akan tersimpan ke server.'); });
            window.addEventListener('online', () => { offlineBanner.classList.add('-translate-y-full'); showToast('success', 'Kembali Online', 'Koneksi server terhubung.'); });
            if(!navigator.onLine) offlineBanner.classList.remove('-translate-y-full');
        });

        // 5. ACCORDION LOGIC
        function toggleSubmenu(menuId, iconId) {
            const menu = document.getElementById(menuId), icon = document.getElementById(iconId);
            if (menu.classList.contains('grid-rows-[0fr]')) {
                menu.classList.remove('grid-rows-[0fr]'); menu.classList.add('grid-rows-[1fr]');
                icon.classList.add('rotate-180', 'text-cyan-600'); icon.classList.remove('text-slate-400');
            } else {
                menu.classList.remove('grid-rows-[1fr]'); menu.classList.add('grid-rows-[0fr]');
                icon.classList.remove('rotate-180', 'text-cyan-600'); icon.classList.add('text-slate-400');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>