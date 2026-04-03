<?php 
    $activeClass = 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-[0_8px_16px_rgba(79,70,229,0.25)] transform scale-[1.02] transition-all';
    $inactiveClass = 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700 transition-all border border-transparent hover:border-slate-100';
    
    $activeIconClass = 'text-white';
    $inactiveIconClass = 'text-slate-400 group-hover:text-indigo-500';

    $isDataWargaActive = request()->routeIs('kader.data.*');
    $isIbuHamilActive  = request()->routeIs('kader.data.ibu-hamil*');
    $isLaporanActive = request()->routeIs('kader.laporan.*');
    $isAbsensiActive = request()->routeIs('kader.absensi.*'); // 👈 Tambahkan variabel ini
?>

<div class="space-y-7 pb-10">

    <div>
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 opacity-80 font-poppins">Overview</p>
        <a href="<?php echo e(route('kader.dashboard')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.dashboard') ? $activeClass : $inactiveClass); ?>">
            <i class="fas fa-th-large w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.dashboard') ? $activeIconClass : $inactiveIconClass); ?>"></i>
            <span class="font-poppins tracking-wide">Dashboard Utama</span>
        </a>
    </div>

    <div>
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 opacity-80 font-poppins">Data Warga</p>
        <div class="space-y-2">
            <button onclick="toggleSubmenu('menuPasien', 'iconPasien')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl transition-all duration-300 font-bold text-[13px] border border-transparent <?php echo e($isDataWargaActive ? 'bg-indigo-50/80 text-indigo-700 border-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700 hover:border-slate-100'); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users-viewfinder w-6 text-center text-[16px] transition-colors <?php echo e($isDataWargaActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'); ?>"></i>
                    <span class="font-poppins tracking-wide">Database Pasien</span>
                </div>
                <i id="iconPasien" class="fas fa-chevron-down text-[10px] transition-transform duration-300 <?php echo e($isDataWargaActive ? 'rotate-180 text-indigo-600' : 'text-slate-400'); ?>"></i>
            </button>
            
            <div id="menuPasien" class="grid transition-all duration-300 ease-in-out <?php echo e($isDataWargaActive ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'); ?>">
                <div class="overflow-hidden">
                    <div class="pl-12 pr-2 py-2 space-y-1.5 relative before:absolute before:left-7 before:top-4 before:bottom-4 before:w-px before:bg-slate-200">
                        <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.data.balita*') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Data Balita</a>
                        <a href="<?php echo e(route('kader.data.remaja.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.data.remaja*') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Data Remaja</a>
                        <a href="<?php echo e(route('kader.data.lansia.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.data.lansia*') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Data Lansia</a>
                        <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.data.ibu-hamil*') ? 'text-pink-700 bg-white shadow-sm border border-pink-100 before:bg-pink-500 before:ring-4 before:ring-pink-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">
                            Data Ibu Hamil <span class="ml-1 text-[9px] px-1.5 py-0.5 bg-pink-100 text-pink-600 rounded-full font-black uppercase">Baru</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 opacity-80 font-poppins">Layanan Medis</p>
        <div class="space-y-2">
            <button onclick="toggleSubmenu('menuAbsensi', 'iconAbsensi')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl transition-all duration-300 font-bold text-[13px] border border-transparent <?php echo e($isAbsensiActive ? 'bg-indigo-50/80 text-indigo-700 border-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700 hover:border-slate-100'); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-check w-6 text-center text-[16px] transition-colors <?php echo e($isAbsensiActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'); ?>"></i>
                    <span class="font-poppins tracking-wide">Absensi Kehadiran</span>
                </div>
                <i id="iconAbsensi" class="fas fa-chevron-down text-[10px] transition-transform duration-300 <?php echo e($isAbsensiActive ? 'rotate-180 text-indigo-600' : 'text-slate-400'); ?>"></i>
            </button>
            
            <div id="menuAbsensi" class="grid transition-all duration-300 ease-in-out <?php echo e($isAbsensiActive ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'); ?>">
                <div class="overflow-hidden">
                    <div class="pl-12 pr-2 py-2 space-y-1.5 relative before:absolute before:left-7 before:top-4 before:bottom-4 before:w-px before:bg-slate-200">
                        <a href="<?php echo e(route('kader.absensi.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.absensi.index') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Input Absensi</a>
                        <a href="<?php echo e(route('kader.absensi.riwayat')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.absensi.riwayat') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Riwayat Absensi</a>
                    </div>
                </div>
            </div>
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.pemeriksaan*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-stethoscope w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.pemeriksaan*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span class="font-poppins tracking-wide">Pemeriksaan Medis</span>
            </a>
            
            <a href="<?php echo e(route('kader.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.imunisasi*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-syringe w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.imunisasi*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span class="font-poppins tracking-wide">Imunisasi Vaksin</span>
            </a>
            
            <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.kunjungan*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-clipboard-list w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.kunjungan*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span class="font-poppins tracking-wide">Buku Tamu / Antrian</span>
            </a>
        </div>
    </div>

    <div>
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 opacity-80 font-poppins">Manajemen & Sistem</p>
        <div class="space-y-2">
            <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.jadwal*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-calendar-alt w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.jadwal*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span class="font-poppins tracking-wide">Jadwal Posyandu</span>
            </a>

            <a href="<?php echo e(route('kader.import.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-[13px] <?php echo e(request()->routeIs('kader.import*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-file-import w-6 text-center text-[16px] transition-colors <?php echo e(request()->routeIs('kader.import*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span class="font-poppins tracking-wide">Import Data Masal</span>
            </a>

            <button onclick="toggleSubmenu('menuLaporan', 'iconLaporan')" class="w-full group flex items-center justify-between px-4 py-3 rounded-2xl transition-all duration-300 font-bold text-[13px] border border-transparent <?php echo e($isLaporanActive ? 'bg-indigo-50/80 text-indigo-700 border-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700 hover:border-slate-100'); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-contract w-6 text-center text-[16px] transition-colors <?php echo e($isLaporanActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'); ?>"></i>
                    <span class="font-poppins tracking-wide">Laporan Cetak PDF</span>
                </div>
                <i id="iconLaporan" class="fas fa-chevron-down text-[10px] transition-transform duration-300 <?php echo e($isLaporanActive ? 'rotate-180 text-indigo-600' : 'text-slate-400'); ?>"></i>
            </button>
            
            <div id="menuLaporan" class="grid transition-all duration-300 ease-in-out <?php echo e($isLaporanActive ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'); ?>">
                <div class="overflow-hidden">
                    <div class="pl-12 pr-2 py-2 space-y-1.5 relative before:absolute before:left-7 before:top-4 before:bottom-4 before:w-px before:bg-slate-200">
                        <a href="<?php echo e(route('kader.laporan.index')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.laporan.index') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Rekap Keseluruhan</a>
                        <a href="<?php echo e(route('kader.laporan.balita')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.laporan.balita') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Laporan Balita</a>
                        <a href="<?php echo e(route('kader.laporan.remaja')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.laporan.remaja') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Laporan Remaja</a>
                        <a href="<?php echo e(route('kader.laporan.lansia')); ?>" class="smooth-route block px-4 py-2.5 text-[12px] font-bold rounded-xl transition-all relative before:absolute before:left-[-22px] before:top-1/2 before:-translate-y-1/2 before:w-2 before:h-2 before:rounded-full <?php echo e(request()->routeIs('kader.laporan.lansia') ? 'text-indigo-700 bg-white shadow-sm border border-indigo-100 before:bg-indigo-500 before:ring-4 before:ring-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-slate-50 before:bg-slate-300'); ?>">Laporan Lansia</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSubmenu(menuId, iconId) {
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);
        
        if (menu.classList.contains('grid-rows-[0fr]')) {
            menu.classList.remove('grid-rows-[0fr]', 'opacity-0');
            menu.classList.add('grid-rows-[1fr]', 'opacity-100');
            icon.classList.add('rotate-180', 'text-indigo-600');
            icon.classList.remove('text-slate-400');
        } else {
            menu.classList.add('grid-rows-[0fr]', 'opacity-0');
            menu.classList.remove('grid-rows-[1fr]', 'opacity-100');
            icon.classList.remove('rotate-180', 'text-indigo-600');
            icon.classList.add('text-slate-400');
        }
    }
</script><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/partials/sidebar/kader.blade.php ENDPATH**/ ?>