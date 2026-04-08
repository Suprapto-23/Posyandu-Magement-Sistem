@php 
    $route = request()->route()->getName();
    $isDataWarga = Str::startsWith($route, 'kader.data.');
    $isAbsensi = in_array($route, ['kader.absensi.index', 'kader.absensi.riwayat', 'kader.absensi.show']);
    
    $menuAktif = 'bg-indigo-50/80 text-indigo-700 font-bold shadow-[0_2px_10px_rgba(79,70,229,0.06)] border border-indigo-100';
    $menuPasif = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium border border-transparent';
    $iconAktif = 'text-indigo-600';
    $iconPasif = 'text-slate-400 group-hover:text-indigo-500 transition-colors';
@endphp

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/80 layout-transition flex flex-col shadow-2xl lg:shadow-none">
    
    {{-- Brand Logo --}}
    <div class="h-[76px] flex items-center justify-between px-6 border-b border-slate-100 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-[14px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(79,70,229,0.3)] shrink-0">
                <i class="fas fa-heart-pulse text-[18px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-[21px] font-black text-slate-800 tracking-tight truncate font-poppins">Kader<span class="text-indigo-600">Care</span></h1>
            </div>
        </div>
        <button id="closeSidebarMobile" class="lg:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    {{-- Navigasi Utama --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 custom-scrollbar space-y-7">
        
        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Overview</p>
            <a href="{{ route('kader.dashboard') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ $route == 'kader.dashboard' ? $menuAktif : $menuPasif }}">
                <i class="fas fa-chart-pie w-5 text-center text-[16px] {{ $route == 'kader.dashboard' ? $iconAktif : $iconPasif }}"></i> 
                <span>Dashboard Utama</span>
            </a>
        </div>

        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Buku Induk Warga (Meja 1)</p>
            <div class="space-y-1">
                <button onclick="toggleSubmenu('menuPasien', 'iconPasien')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ $isDataWarga ? $menuAktif : $menuPasif }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users w-5 text-center text-[16px] {{ $isDataWarga ? $iconAktif : $iconPasif }}"></i> 
                        <span>Database Peserta</span>
                    </div>
                    <i id="iconPasien" class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isDataWarga ? 'rotate-180 text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }}"></i>
                </button>
                
                <div id="menuPasien" class="submenu-grid {{ $isDataWarga ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                    <div class="overflow-hidden">
                        <div class="pl-[48px] pr-2 py-1 space-y-1 relative before:absolute before:left-[26px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                            @foreach([
                                ['route' => 'kader.data.balita.index', 'label' => 'Balita & Anak', 'active' => Str::startsWith($route, 'kader.data.balita')],
                                ['route' => 'kader.data.ibu-hamil.index', 'label' => 'Ibu Hamil', 'active' => Str::startsWith($route, 'kader.data.ibu-hamil')],
                                ['route' => 'kader.data.remaja.index', 'label' => 'Remaja', 'active' => Str::startsWith($route, 'kader.data.remaja')],
                                ['route' => 'kader.data.lansia.index', 'label' => 'Lansia', 'active' => Str::startsWith($route, 'kader.data.lansia')],
                            ] as $item)
                                <a href="{{ route($item['route']) }}" class="loader-trigger block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full {{ $item['active'] ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Layanan Posyandu (Meja 2-4)</p>
            <div class="space-y-1">
                <button onclick="toggleSubmenu('menuAbsensi', 'iconAbsensi')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ $isAbsensi ? $menuAktif : $menuPasif }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clipboard-user w-5 text-center text-[16px] {{ $isAbsensi ? $iconAktif : $iconPasif }}"></i> 
                        <span>Presensi Kedatangan</span>
                    </div>
                    <i id="iconAbsensi" class="fas fa-chevron-down text-[10px] transition-transform duration-300 {{ $isAbsensi ? 'rotate-180 text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }}"></i>
                </button>
                
                <div id="menuAbsensi" class="submenu-grid {{ $isAbsensi ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]' }}">
                    <div class="overflow-hidden">
                        <div class="pl-[48px] pr-2 py-1 space-y-1 relative before:absolute before:left-[26px] before:top-3 before:bottom-3 before:w-[2px] before:bg-slate-100 before:rounded-full">
                            <a href="{{ route('kader.absensi.index') }}" class="loader-trigger block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full {{ $route == 'kader.absensi.index' ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300' }}">Buka Sesi Absen</a>
                            <a href="{{ route('kader.absensi.riwayat') }}" class="loader-trigger block px-4 py-2.5 text-[12.5px] rounded-xl menu-transition relative before:absolute before:left-[-25.5px] before:top-1/2 before:-translate-y-1/2 before:w-[7px] before:h-[7px] before:rounded-full {{ in_array($route, ['kader.absensi.riwayat', 'kader.absensi.show']) ? 'font-bold text-indigo-700 bg-white shadow-sm border border-slate-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-100' : 'font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 before:bg-slate-200 hover:before:bg-indigo-300' }}">Riwayat Arsip</a>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('kader.pemeriksaan.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.pemeriksaan') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-balance-scale w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.pemeriksaan') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Pengukuran Fisik</span>
                </a>

                <a href="{{ route('kader.imunisasi.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.imunisasi') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-shield-virus w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.imunisasi') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Tracker Imunisasi Bidan</span>
                </a>

                <a href="{{ route('kader.kunjungan.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.kunjungan') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-book-medical w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.kunjungan') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Buku Induk Kunjungan</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-poppins">Administrasi & Laporan</p>
            <div class="space-y-1">
                <a href="{{ route('kader.jadwal.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.jadwal') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-calendar-alt w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.jadwal') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Jadwal Posyandu</span>
                </a>
                <a href="{{ route('kader.import.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.import') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-cloud-upload-alt w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.import') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Import Data Excel</span>
                </a>
                <a href="{{ route('kader.laporan.index') }}" class="loader-trigger group flex items-center gap-3 px-4 py-3 rounded-2xl text-[13.5px] menu-transition {{ Str::startsWith($route, 'kader.laporan') ? $menuAktif : $menuPasif }}">
                    <i class="fas fa-file-pdf w-5 text-center text-[16px] {{ Str::startsWith($route, 'kader.laporan') ? $iconAktif : $iconPasif }}"></i> 
                    <span>Cetak Laporan PDF</span>
                </a>
            </div>
        </div>
        
    </nav>

    <div class="p-4 border-t border-slate-100 bg-white shrink-0">
        <a href="{{ route('kader.profile.index') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors group loader-trigger">
            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center font-black text-sm group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all">
                {{ strtoupper(substr(Auth::user()->profile->full_name ?? Auth::user()->name ?? 'K', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->profile->full_name ?? Auth::user()->name ?? 'Kader' }}</p>
                <p class="text-[10px] font-medium text-slate-400 truncate mt-0.5">Pengaturan Akun</p>
            </div>
            <i class="fas fa-cog text-slate-300 text-sm group-hover:text-indigo-500 transition-colors"></i>
        </a>
    </div>
</aside>

<script>
    function toggleSubmenu(menuId, iconId) {
        const menu = document.getElementById(menuId), icon = document.getElementById(iconId);
        if (menu.classList.contains('grid-rows-[0fr]')) {
            menu.classList.remove('grid-rows-[0fr]'); menu.classList.add('grid-rows-[1fr]');
            icon.classList.add('rotate-180', 'text-indigo-600'); icon.classList.remove('text-slate-400');
        } else {
            menu.classList.remove('grid-rows-[1fr]'); menu.classList.add('grid-rows-[0fr]');
            icon.classList.remove('rotate-180', 'text-indigo-600'); icon.classList.add('text-slate-400');
        }
    }
</script>