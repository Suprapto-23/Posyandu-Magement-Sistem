<?php 
    $route = request()->route()->getName() ?? '';
    
    // TEMA BIDAN (Medical Premium Style)
    $menuAktif = 'bg-cyan-50/80 text-cyan-700 font-bold shadow-[0_2px_10px_rgba(6,182,212,0.06)] relative before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-8 before:w-1.5 before:bg-cyan-500 before:rounded-r-md';
    $menuPasif = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium border border-transparent';
    $iconAktif = 'text-cyan-500 drop-shadow-sm';
    $iconPasif = 'text-slate-400 group-hover:text-cyan-500 transition-colors';

    $pendingCount = \App\Models\Pemeriksaan::where('status_verifikasi', 'pending')->count() ?? 0;
?>

<aside id="sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/80 flex flex-col shadow-2xl xl:shadow-none transition-transform duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    
    <div class="h-[80px] flex items-center px-6 border-b border-slate-100 shrink-0 bg-white relative z-10">
        <div class="flex items-center gap-3 w-full">
            <div class="w-10 h-10 rounded-[14px] bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.3)] shrink-0 transition-transform hover:scale-105">
                <i class="fas fa-user-nurse text-[18px]"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-[22px] font-black text-slate-800 tracking-tight truncate font-poppins">Bidan<span class="text-cyan-500">Care</span></h1>
            </div>
            <button @click="sidebarOpen = false" class="xl:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto px-4 py-6 custom-scrollbar space-y-7 relative z-0">
        
        
        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins opacity-80">Dashboard Utama</p>
            <a href="<?php echo e(route('bidan.dashboard')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e($route == 'bidan.dashboard' ? $menuAktif : $menuPasif); ?>">
                <div class="w-6 flex justify-center"><i class="fas fa-chart-pie text-[16px] <?php echo e($route == 'bidan.dashboard' ? $iconAktif : $iconPasif); ?>"></i></div>
                <span class="text-[13.5px] font-poppins tracking-wide">Ringkasan Medis</span>
            </a>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins opacity-80">Tindakan Klinis (Meja 5)</p>
            <div class="space-y-1">
                <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>" class="smooth-route group flex items-center justify-between px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.pemeriksaan') ? $menuAktif : $menuPasif); ?>">
                    <div class="flex items-center gap-3">
                        <div class="w-6 flex justify-center"><i class="fas fa-stethoscope text-[16px] <?php echo e(Str::startsWith($route, 'bidan.pemeriksaan') ? $iconAktif : $iconPasif); ?>"></i></div>
                        <span class="text-[13.5px] font-poppins tracking-wide">Antrian Pemeriksaan</span>
                    </div>
                    <?php if($pendingCount > 0): ?>
                        <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse shadow-sm"><?php echo e($pendingCount); ?></span>
                    <?php endif; ?>
                </a>

                
                <a href="<?php echo e(route('bidan.rujukan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.rujukan') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-file-medical-alt text-[16px] <?php echo e(Str::startsWith($route, 'bidan.rujukan') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">E-Rujukan Puskesmas</span>
                </a>

                <a href="<?php echo e(route('bidan.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.imunisasi') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-syringe text-[16px] <?php echo e(Str::startsWith($route, 'bidan.imunisasi') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">Vaksin & Imunisasi</span>
                </a>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins opacity-80">Data & Layanan Warga</p>
            <div class="space-y-1">
                <a href="<?php echo e(route('bidan.rekam-medis.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.rekam-medis') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-folder-open text-[16px] <?php echo e(Str::startsWith($route, 'bidan.rekam-medis') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">Rekam Medis (EMR)</span>
                </a>
                
                <a href="<?php echo e(route('bidan.konseling.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.konseling') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-comment-medical text-[16px] <?php echo e(Str::startsWith($route, 'bidan.konseling') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">Catatan Edukasi</span>
                </a>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-poppins opacity-80">Manajemen</p>
            <div class="space-y-1">
                <a href="<?php echo e(route('bidan.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.jadwal') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-calendar-alt text-[16px] <?php echo e(Str::startsWith($route, 'bidan.jadwal') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">Jadwal Posyandu</span>
                </a>
                <a href="<?php echo e(route('bidan.laporan.index')); ?>" class="smooth-route group flex items-center gap-3 px-4 py-3.5 rounded-[14px] menu-transition <?php echo e(Str::startsWith($route, 'bidan.laporan') ? $menuAktif : $menuPasif); ?>">
                    <div class="w-6 flex justify-center"><i class="fas fa-print text-[16px] <?php echo e(Str::startsWith($route, 'bidan.laporan') ? $iconAktif : $iconPasif); ?>"></i></div>
                    <span class="text-[13.5px] font-poppins tracking-wide">Laporan SIP</span>
                </a>
            </div>
        </div>
        <div class="h-4"></div>
    </nav>
</aside><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/partials/sidebar/bidan.blade.php ENDPATH**/ ?>