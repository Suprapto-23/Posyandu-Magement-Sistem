@php 
    $route = request()->route()->getName() ?? '';
    $isDataWarga = Str::startsWith($route, 'kader.data.');
    $isAbsensi = in_array($route, ['kader.absensi.index', 'kader.absensi.riwayat']);
    
    // ========================================================================
    // NEXUS ACTIVE STATE ENGINE
    // ========================================================================
    $menuAktif = 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-black shadow-[0_10px_20px_-5px_rgba(99,102,241,0.4)] border border-indigo-400/30 transform scale-[1.02]';
    $menuPasif = 'text-slate-500 font-bold hover:bg-slate-50 hover:text-indigo-600 transition-all border border-transparent hover:border-slate-100';
    $iconAktif = 'text-white drop-shadow-md';
    $iconPasif = 'text-slate-400 group-hover:text-indigo-500 transition-colors';
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white/95 backdrop-blur-xl border-r border-slate-100 transform xl:translate-x-0 transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) flex flex-col shadow-[20px_0_50px_-20px_rgba(0,0,0,0.1)] xl:shadow-none">
    
    {{-- 1. BRAND LOGO PREMIUM --}}
    <div class="h-[85px] flex items-center px-8 border-b border-slate-100/60 shrink-0 bg-transparent">
        <div class="flex items-center gap-4 w-full cursor-pointer group" onclick="window.location.href='{{ route('kader.dashboard') }}'">
            <div class="w-[42px] h-[42px] rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center shadow-[0_8px_15px_rgba(99,102,241,0.3)] shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                <svg class="w-6 h-6 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h1 class="text-[22px] font-black text-slate-900 tracking-tight truncate font-poppins leading-none">Kader<span class="text-indigo-600">Care</span></h1>
            </div>
            <button @click.stop="sidebarOpen = false" class="xl:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>
    
    {{-- 2. NAVIGASI UTAMA (DENGAN MICRO-INTERACTIONS) --}}
    <nav class="flex-1 overflow-y-auto px-6 py-8 custom-scrollbar space-y-8 bg-transparent">
        
        {{-- Grup: Workspace --}}
        <div>
            <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 font-poppins">Workspace Utama</p>
            <a href="{{ route('kader.dashboard') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 {{ $route == 'kader.dashboard' ? $menuAktif : $menuPasif }}">
                <div class="w-6 flex justify-center shrink-0"><i class="fas fa-layer-group text-[18px] {{ $route == 'kader.dashboard' ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                <span class="transition-transform duration-300 {{ $route != 'kader.dashboard' ? 'group-hover:translate-x-1' : '' }}">Dashboard Operasional</span>
            </a>
        </div>

        {{-- Grup: Manajemen Data --}}
        <div>
            <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 font-poppins">Manajemen Data</p>
            
            {{-- Dropdown Induk Database Warga --}}
            <div class="space-y-1.5" x-data="{ openWarga: {{ $isDataWarga ? 'true' : 'false' }} }">
                <button @click="openWarga = !openWarga" class="w-full group flex items-center justify-between px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 border {{ $isDataWarga ? 'bg-indigo-50 text-indigo-700 font-black shadow-inner border-indigo-100/50' : $menuPasif }}">
                    <div class="flex items-center gap-4">
                        <div class="w-6 flex justify-center shrink-0"><i class="fas fa-users-viewfinder text-[18px] {{ $isDataWarga ? 'text-indigo-600' : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                        <span class="transition-transform duration-300 {{ !$isDataWarga ? 'group-hover:translate-x-1' : '' }}">Database Warga</span>
                    </div>
                    <i class="fas fa-chevron-down text-[11px] transition-transform duration-300" :class="openWarga ? 'rotate-180 text-indigo-600' : 'text-slate-400'"></i>
                </button>
                
                {{-- Submenu Anak --}}
                <div x-show="openWarga" x-collapse.duration.300ms class="overflow-hidden">
                    <div class="pl-[54px] pr-2 py-2 space-y-1.5 relative before:absolute before:left-[32px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                        @foreach([
                            ['route' => 'kader.data.balita.index', 'label' => 'Bayi & Balita', 'active' => Str::startsWith($route, 'kader.data.balita')],
                            ['route' => 'kader.data.ibu-hamil.index', 'label' => 'Ibu Hamil', 'active' => Str::startsWith($route, 'kader.data.ibu-hamil')],
                            ['route' => 'kader.data.remaja.index', 'label' => 'Remaja', 'active' => Str::startsWith($route, 'kader.data.remaja')],
                            ['route' => 'kader.data.lansia.index', 'label' => 'Lansia', 'active' => Str::startsWith($route, 'kader.data.lansia')],
                        ] as $item)
                            <a href="{{ route($item['route']) }}" class="spa-route group/sub block px-4 py-3 text-[12.5px] rounded-[14px] transition-all relative before:absolute before:left-[calc(-25px)] before:top-1/2 before:-translate-y-1/2 before:w-[6px] before:h-[6px] before:rounded-full before:transition-all {{ $item['active'] ? 'font-black text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-bold text-slate-500 hover:text-indigo-600 hover:bg-slate-50/80 before:bg-slate-200 hover:before:bg-indigo-400 border border-transparent' }}">
                                <span class="inline-block transition-transform duration-300 {{ !$item['active'] ? 'group-hover/sub:translate-x-1' : '' }}">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('kader.absensi.index') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] mt-2 text-[13.5px] transition-all duration-300 {{ $isAbsensi ? $menuAktif : $menuPasif }}">
                <div class="w-6 flex justify-center shrink-0"><i class="fas fa-clipboard-check text-[18px] {{ $isAbsensi ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                <span class="transition-transform duration-300 {{ !$isAbsensi ? 'group-hover:translate-x-1' : '' }}">Registrasi Kehadiran</span>
            </a>
        </div>

        {{-- Grup: Tugas Lapangan --}}
        <div>
            <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 font-poppins">Tugas Lapangan</p>
            <div class="space-y-2">
                <a href="{{ route('kader.pemeriksaan.index') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 {{ Str::startsWith($route, 'kader.pemeriksaan') ? $menuAktif : $menuPasif }}">
                    <div class="w-6 flex justify-center shrink-0"><i class="fas fa-stethoscope text-[18px] {{ Str::startsWith($route, 'kader.pemeriksaan') ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                    <span class="transition-transform duration-300 {{ !Str::startsWith($route, 'kader.pemeriksaan') ? 'group-hover:translate-x-1' : '' }}">Pemeriksaan Fisik</span>
                </a>
                <a href="{{ route('kader.imunisasi.index') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 {{ Str::startsWith($route, 'kader.imunisasi') ? $menuAktif : $menuPasif }}">
                    <div class="w-6 flex justify-center shrink-0"><i class="fas fa-syringe text-[18px] {{ Str::startsWith($route, 'kader.imunisasi') ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                    <span class="transition-transform duration-300 {{ !Str::startsWith($route, 'kader.imunisasi') ? 'group-hover:translate-x-1' : '' }}">Catatan Imunisasi</span>
                </a>
            </div>
        </div>

        {{-- Grup: Manajemen & Pelaporan --}}
        <div>
            <p class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 font-poppins">Manajemen & Pelaporan</p>
            <div class="space-y-2">
                <a href="{{ route('kader.jadwal.index') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 {{ Str::startsWith($route, 'kader.jadwal') ? $menuAktif : $menuPasif }}">
                    <div class="w-6 flex justify-center shrink-0"><i class="fas fa-calendar-day text-[18px] {{ Str::startsWith($route, 'kader.jadwal') ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                    <span class="transition-transform duration-300 {{ !Str::startsWith($route, 'kader.jadwal') ? 'group-hover:translate-x-1' : '' }}">Jadwal Kegiatan</span>
                </a>
                <a href="{{ route('kader.laporan.index') }}" class="spa-route group flex items-center gap-4 px-4 py-3.5 rounded-[18px] text-[13.5px] transition-all duration-300 {{ Str::startsWith($route, 'kader.laporan') ? $menuAktif : $menuPasif }}">
                    <div class="w-6 flex justify-center shrink-0"><i class="fas fa-file-invoice text-[18px] {{ Str::startsWith($route, 'kader.laporan') ? $iconAktif : $iconPasif }} transition-transform duration-300 group-hover:scale-110"></i></div>
                    <span class="transition-transform duration-300 {{ !Str::startsWith($route, 'kader.laporan') ? 'group-hover:translate-x-1' : '' }}">Laporan Kemenkes</span>
                </a>
            </div>
        </div>
    </nav>
    
    {{-- 3. BOTTOM INDICATOR (ONLINE STATUS) --}}
    <div class="p-6 bg-transparent border-t border-slate-100/60 flex justify-center items-center shrink-0">
        <div class="px-5 py-2.5 rounded-full bg-emerald-50/50 border border-emerald-100/50 flex items-center gap-2.5 shadow-sm hover:shadow-md hover:bg-emerald-50 transition-all cursor-default">
            <div class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </div>
            <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest">Sistem Online</p>
        </div>
    </div>
</aside>