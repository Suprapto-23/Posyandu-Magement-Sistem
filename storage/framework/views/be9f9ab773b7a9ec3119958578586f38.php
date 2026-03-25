<?php 
    // MENGGUNAKAN VARIABEL (Sangat Aman & Anti Error 'Cannot Redeclare')
    $activeClass = 'bg-indigo-50 text-indigo-700 font-bold';
    $inactiveClass = 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800';
    
    $activeIconClass = 'text-indigo-600';
    $inactiveIconClass = 'text-slate-400 group-hover:text-slate-500';

    // Helper penanda menu dropdown aktif
    $isDataWargaActive = request()->routeIs('kader.data.*');
    $isLaporanActive = request()->routeIs('kader.laporan.*');
?>

<div class="space-y-6 pb-10">

    <div>
        <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 font-sans">Overview</p>
        <a href="<?php echo e(route('kader.dashboard')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14px] transition-colors <?php echo e(request()->routeIs('kader.dashboard') ? $activeClass : $inactiveClass); ?>">
            <i class="fas fa-th-large w-5 text-center text-[18px] transition-colors <?php echo e(request()->routeIs('kader.dashboard') ? $activeIconClass : $inactiveIconClass); ?>"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div>
        <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 font-sans">Data Warga</p>
        <div class="space-y-1">
            <button onclick="toggleSubmenu('menuPasien', 'iconPasien')" class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-300 text-[14px] <?php echo e($isDataWargaActive ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800'); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users w-5 text-center text-[18px] transition-colors <?php echo e($isDataWargaActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-500'); ?>"></i>
                    <span>Database Pasien</span>
                </div>
                <i id="iconPasien" class="fas fa-chevron-down text-[12px] transition-transform duration-300 <?php echo e($isDataWargaActive ? 'rotate-180 text-indigo-600' : 'text-slate-400'); ?>"></i>
            </button>
            
            <div id="menuPasien" class="grid transition-all duration-300 ease-in-out <?php echo e($isDataWargaActive ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'); ?>">
                <div class="overflow-hidden">
                    <div class="pl-11 pr-2 py-1 space-y-1 mt-1">
                        <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.data.balita*') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.data.balita*') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Balita
                        </a>
                        <a href="<?php echo e(route('kader.data.remaja.index')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.data.remaja*') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.data.remaja*') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Remaja
                        </a>
                        <a href="<?php echo e(route('kader.data.lansia.index')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.data.lansia*') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.data.lansia*') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Lansia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 font-sans">Layanan Medis</p>
        <div class="space-y-1">
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14px] transition-colors <?php echo e(request()->routeIs('kader.pemeriksaan*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-stethoscope w-5 text-center text-[18px] transition-colors <?php echo e(request()->routeIs('kader.pemeriksaan*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span>Pemeriksaan</span>
            </a>
            
            <a href="<?php echo e(route('kader.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14px] transition-colors <?php echo e(request()->routeIs('kader.imunisasi*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-syringe w-5 text-center text-[18px] transition-colors <?php echo e(request()->routeIs('kader.imunisasi*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span>Imunisasi</span>
            </a>
            
            <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14px] transition-colors <?php echo e(request()->routeIs('kader.kunjungan*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-clipboard-list w-5 text-center text-[18px] transition-colors <?php echo e(request()->routeIs('kader.kunjungan*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span>Buku Tamu</span>
            </a>
        </div>
    </div>

    <div>
        <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 font-sans">Manajemen</p>
        <div class="space-y-1">
            <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14px] transition-colors <?php echo e(request()->routeIs('kader.jadwal*') ? $activeClass : $inactiveClass); ?>">
                <i class="fas fa-calendar-alt w-5 text-center text-[18px] transition-colors <?php echo e(request()->routeIs('kader.jadwal*') ? $activeIconClass : $inactiveIconClass); ?>"></i>
                <span>Jadwal Posyandu</span>
            </a>

            <button onclick="toggleSubmenu('menuLaporan', 'iconLaporan')" class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-300 text-[14px] <?php echo e($isLaporanActive ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:bg-slate-50 hover:text-slate-800'); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-contract w-5 text-center text-[18px] transition-colors <?php echo e($isLaporanActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-500'); ?>"></i>
                    <span>Laporan Data</span>
                </div>
                <i id="iconLaporan" class="fas fa-chevron-down text-[12px] transition-transform duration-300 <?php echo e($isLaporanActive ? 'rotate-180 text-indigo-600' : 'text-slate-400'); ?>"></i>
            </button>
            
            <div id="menuLaporan" class="grid transition-all duration-300 ease-in-out <?php echo e($isLaporanActive ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'); ?>">
                <div class="overflow-hidden">
                    <div class="pl-11 pr-2 py-1 space-y-1 mt-1">
                        <a href="<?php echo e(route('kader.laporan.index')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.laporan.index') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.laporan.index') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Rekap Laporan
                        </a>
                        <a href="<?php echo e(route('kader.laporan.balita')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.laporan.balita') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.laporan.balita') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Balita
                        </a>
                        <a href="<?php echo e(route('kader.laporan.remaja')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.laporan.remaja') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.laporan.remaja') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Remaja
                        </a>
                        <a href="<?php echo e(route('kader.laporan.lansia')); ?>" class="smooth-route flex items-center gap-3 py-2 text-[13px] rounded-lg transition-colors <?php echo e(request()->routeIs('kader.laporan.lansia') ? 'text-indigo-700 font-bold' : 'text-slate-500 font-medium hover:text-slate-800'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 <?php echo e(request()->routeIs('kader.laporan.lansia') ? 'bg-indigo-600' : 'bg-slate-300'); ?>"></span> Lansia
                        </a>
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