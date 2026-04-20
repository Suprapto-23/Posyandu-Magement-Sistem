<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Bidan Workspace') — PosyanduCare</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%2306b6d4'/%3E%3Cstop offset='100%25' stop-color='%230284c7'/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' fill='url(%23grad)'/%3E%3Cpath d='M12 7v6M9 10h6' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style type="text/tailwindcss">
        @theme { 
            --font-sans: 'Inter', sans-serif; 
            --font-poppins: 'Poppins', sans-serif; 
        }
        body { 
            font-family: var(--font-sans); 
            background-color: #f0fdfa; 
            background-image: radial-gradient(at 0% 0%, rgba(6, 182, 212, 0.05) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
            -webkit-font-smoothing: antialiased;
            color: #0f172a;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .menu-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>

{{-- Alpine Data Wrapper --}}
<body x-data="{ sidebarOpen: window.innerWidth >= 1280 }" @resize.window="sidebarOpen = window.innerWidth >= 1280" class="flex h-screen overflow-hidden selection:bg-cyan-100 selection:text-cyan-900">

    {{-- GLOBAL PAGE LOADER --}}
    <div id="globalLoader" class="fixed inset-0 z-[9998] bg-white/80 backdrop-blur-md flex flex-col items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
        <div class="relative w-20 h-20 flex items-center justify-center mb-5">
            <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-cyan-500 rounded-full border-t-transparent animate-spin"></div>
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-[0_4px_15px_rgba(6,182,212,0.3)]">
                <i class="fas fa-stethoscope text-cyan-600 animate-pulse text-xl"></i>
            </div>
        </div>
        <p class="text-[10px] font-black text-cyan-700 uppercase tracking-[0.25em]" id="loaderText">MEMUAT SISTEM...</p>
    </div>

    {{-- MOBILE OVERLAY (Alpine.js) --}}
    <div x-show="sidebarOpen && window.innerWidth < 1280" x-transition.opacity 
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 xl:hidden">
    </div>

    {{-- PEMANGGILAN FILE SIDEBAR --}}
    @include('partials.sidebar.bidan')

    {{-- MAIN WRAPPER --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen relative transition-all duration-300 ease-in-out"
         :class="sidebarOpen ? 'xl:ml-[280px]' : 'ml-0'">
        
        {{-- TOP NAVBAR --}}
        <header class="h-[80px] glass-panel sticky top-0 z-40 flex items-center justify-between px-4 lg:px-8 border-b border-slate-200/60 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-3 lg:gap-5">
                {{-- Tombol Toggle Sidebar --}}
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-cyan-600 hover:bg-cyan-50 rounded-[12px] transition-colors bg-white border border-slate-200 shadow-sm focus:outline-none">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <div class="hidden md:flex flex-col">
                    <h2 class="text-[18px] font-black text-slate-800 tracking-tight font-poppins leading-none">@yield('page-name', 'Beranda Medis')</h2>
                    <div class="flex items-center gap-1.5 mt-1 text-[11px] font-semibold text-slate-400 tracking-wide">
                        <span>Workspace Bidan</span> <i class="fas fa-chevron-right text-[8px] opacity-50"></i> <span class="text-cyan-500 font-bold">Sistem Aktif</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-5">
                
                {{-- WIDGET NOTIFIKASI ANTRIAN (Alpine.js Dropdown) --}}
                @php 
                    $pendingNotifs = \App\Models\Pemeriksaan::with(['balita', 'remaja', 'lansia', 'ibuHamil'])->where('status_verifikasi', 'pending')->latest()->take(5)->get();
                    $notifCount = \App\Models\Pemeriksaan::where('status_verifikasi', 'pending')->count() ?? 0; 
                @endphp
                
                <div x-data="{ openNotif: false }" class="relative">
                    <button @click="openNotif = !openNotif" @click.away="openNotif = false" class="relative w-11 h-11 flex items-center justify-center bg-white text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 rounded-[14px] transition-all border border-slate-200 shadow-sm group focus:outline-none">
                        <i class="fas fa-bell text-[18px]"></i>
                        @if($notifCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 border-2 border-white text-[8px] font-black text-white items-center justify-center"></span>
                            </span>
                        @endif
                    </button>

                    <div x-show="openNotif" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-3 w-[320px] sm:w-[360px] bg-white rounded-3xl shadow-xl border border-slate-100 z-50 overflow-hidden origin-top-right">
                        
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                            <h3 class="text-[14px] font-black text-slate-800 font-poppins flex items-center gap-2"><i class="fas fa-procedures text-cyan-500"></i> Antrian Medis</h3>
                            @if($notifCount > 0)
                                <span class="bg-rose-100 text-rose-600 text-[9px] font-black px-2.5 py-1 rounded-lg uppercase tracking-widest">{{ $notifCount }} Menunggu</span>
                            @endif
                        </div>

                        <div class="max-h-[320px] overflow-y-auto custom-scrollbar bg-white">
                            @forelse($pendingNotifs as $notif)
                                @php
                                    $namaPasien = $notif->balita->nama_lengkap ?? $notif->remaja->nama_lengkap ?? $notif->lansia->nama_lengkap ?? $notif->ibuHamil->nama_lengkap ?? 'Pasien Anonim';
                                    $kat = strtolower(class_basename($notif->kategori_pasien ?? $notif->pasien_type));
                                @endphp
                                <a href="{{ route('bidan.pemeriksaan.show', $notif->id) }}" class="flex items-start gap-4 p-4 border-b border-slate-50 hover:bg-cyan-50 transition-colors group">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-bold text-slate-800 truncate group-hover:text-cyan-600">{{ $namaPasien }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[9px] font-black text-cyan-500 uppercase">{{ $kat }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-[10px] text-slate-300 mt-2"></i>
                                </a>
                            @empty
                                <div class="p-8 text-center text-slate-500 text-[12px]">Belum ada antrian.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>

                {{-- Dropdown Profil --}}
                <div x-data="{ openProfile: false }" class="relative">
                    <button @click="openProfile = !openProfile" @click.away="openProfile = false" class="flex items-center gap-2.5 pl-1.5 pr-4 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name ?? 'B', 0, 1)) }}
                        </div>
                        <span class="hidden md:block text-[13px] font-bold text-slate-700 truncate max-w-[120px]">{{ Str::words(Auth::user()->name, 2, '') }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400" :class="{'rotate-180': openProfile}"></i>
                    </button>
                    
                    <div x-show="openProfile" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 shadow-xl rounded-2xl p-2 z-50 origin-top-right">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="showGlobalLoader('SEDANG KELUAR...')" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] font-bold text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                <i class="fas fa-sign-out-alt text-sm"></i> Akhiri Sesi
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- INJEKSI KONTEN UTAMA (RATA TENGAH & LEBAR MAKSIMAL) --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 relative z-0 custom-scrollbar pb-24 lg:pb-8">
            <div class="max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </main>

        {{-- MOBILE BOTTOM NAV --}}
        <nav class="xl:hidden fixed bottom-0 left-0 right-0 h-[68px] glass-panel border-t border-slate-200 z-50 flex items-center justify-around px-2 pb-safe shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
            @php $route = request()->route()->getName() ?? ''; @endphp
            
            <a href="{{ route('bidan.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ $route == 'bidan.dashboard' ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-chart-pie text-[18px] mb-1"></i> Beranda
            </a>
            
            <a href="{{ route('bidan.rekam-medis.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.rekam-medis') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-folder-open text-[18px] mb-1"></i> EMR
            </a>
            
            <div class="relative w-full h-full flex justify-center">
                <a href="{{ route('bidan.pemeriksaan.index') }}" class="absolute -top-5 flex flex-col items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 text-white shadow-[0_8px_20px_rgba(6,182,212,0.4)] border-4 border-white transition-transform active:scale-95 hover:-translate-y-1">
                    <i class="fas fa-stethoscope text-[20px]"></i>
                    @if($notifCount > 0)
                        <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-rose-500 border-2 border-white rounded-full"></span>
                    @endif
                </a>
            </div>

            <a href="{{ route('bidan.imunisasi.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.imunisasi') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-syringe text-[18px] mb-1"></i> Vaksin
            </a>
            
            <a href="{{ route('bidan.jadwal.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] font-bold transition-colors {{ Str::startsWith($route,'bidan.jadwal') ? 'text-cyan-600' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-calendar-alt text-[18px] mb-1"></i> Jadwal
            </a>
        </nav>
    </div>

    {{-- SYSTEM SCRIPTS --}}
    <script>
        const showGlobalLoader = (text = 'MEMUAT SISTEM...') => {
            const loader = document.getElementById('globalLoader');
            if(loader) {
                document.getElementById('loaderText').innerText = text;
                loader.classList.remove('opacity-0', 'pointer-events-none');
                loader.classList.add('opacity-100');
            }
        };

        const hideGlobalLoader = () => { 
            const l = document.getElementById('globalLoader'); 
            if(l) { l.classList.remove('opacity-100'); l.classList.add('opacity-0', 'pointer-events-none'); } 
        };

        window.addEventListener('pageshow', hideGlobalLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideGlobalLoader();
            document.querySelectorAll('.smooth-route').forEach(el => el.addEventListener('click', e => { 
                if(!el.classList.contains('target-blank') && el.target !== '_blank' && !e.ctrlKey) { showGlobalLoader(); }
            }));
        });

        // ==========================================
        // SWEETALERT2 GLOBAL NOTIFICATION
        // ==========================================
        const showToast = (icon, title, text) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                text: text,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    title: 'text-[14px] font-bold font-poppins',
                }
            });
        };

        @if(session('success')) showToast('success', 'Berhasil!', "{{ session('success') }}"); @endif
        @if(session('error')) showToast('error', 'Peringatan!', "{{ session('error') }}"); @endif
    </script>
    @stack('scripts')
</body>
</html>