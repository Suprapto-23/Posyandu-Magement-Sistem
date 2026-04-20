<?php 
    $route = request()->route()->getName() ?? '';
    
    // GAYA PREMIUM SAAS (Menggunakan basis font Poppins)
    $menuAktif = 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white shadow-md shadow-indigo-200/50 rounded-[14px] font-semibold tracking-wide scale-[1.02] transition-all duration-300';
    $menuPasif = 'text-slate-500 hover:bg-indigo-50/50 hover:text-indigo-600 rounded-[14px] font-medium transition-all duration-300';
    $iconAktif = 'text-white drop-shadow-sm';
    $iconPasif = 'text-slate-400 group-hover:text-indigo-500 transition-colors duration-300';

    $isBukuInduk = in_array($route, [
        'kader.data.balita.index', 
        'kader.data.remaja.index', 
        'kader.data.lansia.index', 
        'kader.data.ibu-hamil.index'
    ]);
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white/95 backdrop-blur-xl border-r border-slate-100 transform -translate-x-full xl:translate-x-0 transition-transform duration-400 ease-[cubic-bezier(0.2,0.8,0.2,1)] flex flex-col shadow-[10px_0_40px_rgba(0,0,0,0.03)] xl:shadow-none" style="font-family: 'Poppins', sans-serif;">
    
    <div class="h-[80px] flex items-center px-6 shrink-0 border-b border-slate-50/80">
        <a href="<?php echo e(route('kader.dashboard')); ?>" class="flex items-center gap-3.5 group w-full">
            <div class="w-10 h-10 rounded-[12px] bg-gradient-to-br from-indigo-600 to-indigo-800 flex items-center justify-center text-white shadow-lg shadow-indigo-200/50 group-hover:rotate-[10deg] group-hover:scale-105 transition-all duration-300">
                <i class="fas fa-heartbeat text-[18px]"></i>
            </div>
            <div class="flex flex-col">
                <h1 class="font-bold text-[19px] text-slate-800 tracking-tight leading-none mb-1">Posyandu<span class="text-indigo-600">Care</span></h1>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest leading-none">Panel Kader</p>
            </div>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6">
        
        <div class="mb-8">
            <a href="<?php echo e(route('kader.dashboard')); ?>" class="group flex items-center gap-3.5 px-4 py-3.5 <?php echo e($route === 'kader.dashboard' ? $menuAktif : $menuPasif); ?>">
                <div class="w-5 flex justify-center"><i class="fas fa-grid-2 text-[16px] <?php echo e($route === 'kader.dashboard' ? $iconAktif : $iconPasif); ?>"></i></div>
                <span class="text-[13.5px]">Beranda Utama</span>
            </a>
        </div>

        <div class="mb-8">
            <p class="px-4 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3">Pelayanan Aktif</p>
            <div class="space-y-1.5">
                <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-calendar-day text-[16px] <?php echo e(Str::startsWith($route, 'kader.jadwal') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Jadwal Posyandu</span>
                </a>

                <a href="<?php echo e(route('kader.absensi.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.absensi') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-user-check text-[16px] <?php echo e(Str::startsWith($route, 'kader.absensi') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Absensi Kehadiran</span>
                </a>

                <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-weight text-[16px] <?php echo e(Str::startsWith($route, 'kader.pemeriksaan') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Pengukuran Fisik</span>
                </a>
            </div>
        </div>

        <div class="mb-8">
            <p class="px-4 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3">Administrasi Pasien</p>
            <div class="space-y-1.5">
                
                
                <button id="btnBukuInduk" type="button" class="w-full group flex items-center justify-between px-4 py-3 <?php echo e($isBukuInduk ? 'bg-indigo-50/80 text-indigo-700 font-semibold rounded-[14px]' : $menuPasif); ?>">
                    <div class="flex items-center gap-3.5">
                        <div class="w-5 flex justify-center"><i class="fas fa-folder-open text-[16px] <?php echo e($isBukuInduk ? 'text-indigo-600' : $iconPasif); ?>"></i></div>
                        <span class="text-[13.5px]">Buku Induk Data</span>
                    </div>
                    <i id="iconBukuInduk" class="fas fa-chevron-right text-[11px] transition-transform duration-300 <?php echo e($isBukuInduk ? 'rotate-90 text-indigo-600' : 'text-slate-400'); ?>"></i>
                </button>
                
                <div id="menuBukuInduk" class="overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]" style="max-height: <?php echo e($isBukuInduk ? '500px' : '0px'); ?>; opacity: <?php echo e($isBukuInduk ? '1' : '0'); ?>;">
                    <div class="pl-12 mt-1 space-y-1 border-l-2 border-indigo-50 ml-[26px] py-2">
                        <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="block px-4 py-2 rounded-xl text-[13px] transition-colors <?php echo e(Str::startsWith($route, 'kader.data.balita') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50'); ?>">Database Balita</a>
                        <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" class="block px-4 py-2 rounded-xl text-[13px] transition-colors <?php echo e(Str::startsWith($route, 'kader.data.ibu-hamil') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50'); ?>">Database Ibu Hamil</a>
                        <a href="<?php echo e(route('kader.data.remaja.index')); ?>" class="block px-4 py-2 rounded-xl text-[13px] transition-colors <?php echo e(Str::startsWith($route, 'kader.data.remaja') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50'); ?>">Database Remaja</a>
                        <a href="<?php echo e(route('kader.data.lansia.index')); ?>" class="block px-4 py-2 rounded-xl text-[13px] transition-colors <?php echo e(Str::startsWith($route, 'kader.data.lansia') ? 'text-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50'); ?>">Database Lansia</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="mb-4">
            <p class="px-4 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3">Laporan & Sistem</p>
            <div class="space-y-1.5">
                <a href="<?php echo e(route('kader.kunjungan.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-history text-[16px] <?php echo e(Str::startsWith($route, 'kader.kunjungan') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Riwayat Kunjungan</span>
                </a>

                <a href="<?php echo e(route('kader.laporan.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.laporan') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-print text-[16px] <?php echo e(Str::startsWith($route, 'kader.laporan') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Ekspor Laporan</span>
                </a>

                <a href="<?php echo e(route('kader.import.index')); ?>" class="group flex items-center gap-3.5 px-4 py-3 <?php echo e(Str::startsWith($route, 'kader.import') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-5 flex justify-center"><i class="fas fa-file-excel text-[16px] <?php echo e(Str::startsWith($route, 'kader.import') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px]">Import Data Warga</span>
                </a>
            </div>
        </div>
    </div>
</aside>

<script>
    // Logika Dropdown Akordion Murni (Sangat Ringan & Bebas Bug)
    document.addEventListener('DOMContentLoaded', function () {
        const btnBuku = document.getElementById('btnBukuInduk');
        const menuBuku = document.getElementById('menuBukuInduk');
        const iconBuku = document.getElementById('iconBukuInduk');

        if(btnBuku && menuBuku && iconBuku) {
            btnBuku.addEventListener('click', function () {
                const isClosed = menuBuku.style.maxHeight === '0px' || menuBuku.style.maxHeight === '';
                
                if (isClosed) {
                    menuBuku.style.maxHeight = menuBuku.scrollHeight + 'px';
                    menuBuku.style.opacity = '1';
                    iconBuku.classList.add('rotate-90', 'text-indigo-600');
                    iconBuku.classList.remove('text-slate-400');
                    btnBuku.classList.add('bg-indigo-50/80', 'text-indigo-700', 'font-semibold', 'rounded-[14px]');
                    btnBuku.classList.remove('text-slate-500');
                } else {
                    menuBuku.style.maxHeight = '0px';
                    menuBuku.style.opacity = '0';
                    iconBuku.classList.remove('rotate-90', 'text-indigo-600');
                    iconBuku.classList.add('text-slate-400');
                    
                    // Jangan hapus background jika URL saat ini memang berada di dalam menu buku induk
                    if (!<?php echo e($isBukuInduk ? 'true' : 'false'); ?>) {
                        btnBuku.classList.remove('bg-indigo-50/80', 'text-indigo-700', 'font-semibold', 'rounded-[14px]');
                        btnBuku.classList.add('text-slate-500');
                    }
                }
            });
        }
    });
</script><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/partials/sidebar/kader.blade.php ENDPATH**/ ?>