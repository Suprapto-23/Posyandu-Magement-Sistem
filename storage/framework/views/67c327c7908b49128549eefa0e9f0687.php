

<?php $__env->startSection('title', 'Antrian Pemeriksaan Klinis'); ?>
<?php $__env->startSection('page-name', 'Manajemen Antrian'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* NEXUS CLINICAL ANIMATION SYSTEM */
    .fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* GLASS PANEL UI */
    .nexus-glass { 
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); 
        border: 1px solid rgba(226, 232, 240, 0.8); 
        box-shadow: 0 10px 40px -10px rgba(6, 182, 212, 0.05); 
        border-radius: 28px; transition: all 0.4s ease;
    }

    /* TAB SYSTEM PREMIUM */
    .tab-nexus { position: relative; transition: all 0.3s ease; }
    .tab-nexus.active { background: #ffffff; color: #0891b2; box-shadow: 0 4px 15px rgba(8, 145, 178, 0.1); border-color: #cffafe; }
    .tab-nexus.inactive { color: #64748b; border-color: transparent; }
    .tab-nexus.inactive:hover { background: rgba(255,255,255,0.5); color: #0e7490; }

    /* TABLE MICRO-INTERACTION & LIVE SEARCH */
    .tr-nexus { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-bottom: 1px solid #f1f5f9; }
    .tr-nexus:hover { background-color: #f0fdfa; transform: scale(1.002); z-index: 10; position: relative; border-color: transparent; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(6,182,212,0.1); }
    
    /* Menyembunyikan elemen Alpine sebelum dimuat */
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto relative pb-16 fade-in-up">

    
    <div class="bg-gradient-to-r from-cyan-600 to-blue-700 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(8,145,178,0.4)] border border-cyan-400/50 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHBhdGggZD0iTTAgMGgyMHYyMEgwem0xMCAxMGgxMHYxMEgxMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMSIvPjwvc3ZnPg==')]"></div>
        <div class="absolute -right-10 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-6 relative z-10 text-white w-full">
            <div class="w-20 h-20 rounded-[22px] bg-white/20 backdrop-blur-md flex items-center justify-center text-4xl shrink-0 shadow-inner border border-white/30 transform -rotate-3">
                <i class="fas fa-microscope text-white"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1.5">
                    <h1 class="text-3xl font-black tracking-tight font-poppins">Antrian Meja 5</h1>
                    <span class="px-3 py-1 bg-cyan-900/40 border border-cyan-400/30 rounded-lg text-[10px] font-black uppercase tracking-widest backdrop-blur-sm shadow-sm">Otoritas Bidan</span>
                </div>
                <p class="text-[14px] font-medium text-cyan-100 max-w-2xl leading-relaxed">
                    Pusat Validasi Medis. Verifikasi data pengukuran fisik yang dikirim oleh Kader lapangan dan berikan diagnosa klinis yang presisi.
                </p>
            </div>
        </div>
    </div>

    
    <div class="nexus-glass overflow-hidden flex flex-col" x-data="{ searchQuery: '<?php echo e(request('search')); ?>' }">
        
        <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            
            
            <div class="flex items-center p-1.5 bg-slate-200/60 rounded-[18px] w-full lg:w-auto">
                <a href="<?php echo e(route('bidan.pemeriksaan.index', ['tab' => 'pending'])); ?>" class="tab-nexus flex-1 lg:flex-none flex items-center justify-center gap-2.5 px-6 py-3 rounded-[14px] text-[11px] font-black uppercase tracking-widest border-2 <?php echo e($tab == 'pending' ? 'active' : 'inactive'); ?>">
                    <i class="fas fa-clock text-sm"></i> Antrian Aktif
                    <?php if($pendingCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-md bg-rose-500 text-white text-[9px] shadow-sm animate-pulse ml-1"><?php echo e($pendingCount); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('bidan.pemeriksaan.index', ['tab' => 'verified'])); ?>" class="tab-nexus flex-1 lg:flex-none flex items-center justify-center gap-2.5 px-6 py-3 rounded-[14px] text-[11px] font-black uppercase tracking-widest border-2 <?php echo e($tab == 'verified' ? 'active' : 'inactive'); ?>">
                    <i class="fas fa-check-double text-sm"></i> Arsip Terverifikasi
                </a>
            </div>
            
            
            <form id="searchForm" method="GET" action="<?php echo e(route('bidan.pemeriksaan.index')); ?>" class="relative w-full lg:w-[380px]">
                <input type="hidden" name="tab" value="<?php echo e($tab); ?>">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-cyan-500" :class="searchQuery.length > 0 ? 'animate-bounce' : ''"></i>
                </div>
                
                
                <input type="text" name="search" x-model="searchQuery" 
                       placeholder="Ketik nama, NIK, atau nama kader..." 
                       class="w-full bg-white border border-slate-200 rounded-[16px] pl-12 pr-12 py-3.5 text-[12px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
                
                
                <button type="button" x-show="searchQuery.length > 0" @click="searchQuery = ''; document.getElementById('searchForm').submit();" x-cloak
                        class="absolute inset-y-0 right-0 pr-5 flex items-center text-rose-400 hover:text-rose-600 transition-colors">
                    <i class="fas fa-times-circle text-lg"></i>
                </button>
            </form>
        </div>

        
        <div class="overflow-x-auto custom-scrollbar p-2">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Identitas Warga</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Parameter Fisik (Kader)</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Waktu & Petugas</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100 text-right">Aksi Klinis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pemeriksaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Memanfaatkan Accessor Model untuk data aman
                        $namaPasien = $pem->nama_pasien;
                        $kategori = strtolower($pem->kategori_pasien ?? 'umum');
                        
                        // Mapping Visual
                        $config = match($kategori) {
                            'balita', 'bayi' => ['col' => 'sky', 'ico' => 'fa-baby'],
                            'remaja'         => ['col' => 'violet', 'ico' => 'fa-user-graduate'],
                            'ibu_hamil'      => ['col' => 'pink', 'ico' => 'fa-female'],
                            'lansia'         => ['col' => 'emerald', 'ico' => 'fa-user-clock'],
                            default          => ['col' => 'slate', 'ico' => 'fa-user'],
                        };
                    ?>
                    
                    
                    <tr class="tr-nexus group" 
                        x-show="searchQuery === '' || $el.textContent.toLowerCase().includes(searchQuery.toLowerCase())" 
                        x-transition.opacity.duration.300ms>
                        
                        
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[14px] flex items-center justify-center shrink-0 border shadow-sm group-hover:scale-110 transition-transform bg-<?php echo e($config['col']); ?>-50 text-<?php echo e($config['col']); ?>-500 border-<?php echo e($config['col']); ?>-100">
                                    <i class="fas <?php echo e($config['ico']); ?> text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-1 group-hover:text-cyan-600 transition-colors font-poppins"><?php echo e($namaPasien); ?></p>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-<?php echo e($config['col']); ?>-100 text-<?php echo e($config['col']); ?>-700 text-[9px] font-black uppercase tracking-wider rounded border border-<?php echo e($config['col']); ?>-200 shadow-sm">
                                            <?php echo e(ucfirst($kategori)); ?>

                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400">NIK: <?php echo e($pem->nik_pasien); ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="py-5 px-6">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <div class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-weight-hanging text-slate-400 text-[10px]"></i>
                                    <span class="text-[11px] font-black text-slate-700"><?php echo e($pem->berat_badan ?? '0'); ?> <small class="text-[9px] text-slate-400">kg</small></span>
                                </div>
                                <div class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-ruler-vertical text-slate-400 text-[10px]"></i>
                                    <span class="text-[11px] font-black text-slate-700"><?php echo e($pem->tinggi_badan ?? '0'); ?> <small class="text-[9px] text-slate-400">cm</small></span>
                                </div>
                            </div>
                            <?php if($pem->tekanan_darah): ?>
                                <p class="text-[10px] font-bold text-rose-500 flex items-center gap-1.5 ml-1">
                                    <i class="fas fa-heartbeat animate-pulse"></i> Tensi: <?php echo e($pem->tekanan_darah); ?> mmHg
                                </p>
                            <?php elseif($pem->lingkar_kepala): ?>
                                <p class="text-[10px] font-bold text-sky-500 flex items-center gap-1.5 ml-1">
                                    <i class="fas fa-brain"></i> L. Kepala: <?php echo e($pem->lingkar_kepala); ?> cm
                                </p>
                            <?php endif; ?>
                        </td>

                        
                        <td class="py-5 px-6">
                            <p class="font-bold text-slate-700 text-[11px] flex items-center gap-2">
                                <i class="far fa-calendar-check text-cyan-500"></i> <?php echo e(\Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('d M Y')); ?>

                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-[10px] font-medium text-slate-500 ml-4 border-r border-slate-200 pr-2"><?php echo e($pem->created_at->format('H:i')); ?> WIB</p>
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-indigo-50 border border-indigo-100 rounded text-[9px] font-black text-indigo-700 uppercase tracking-widest">
                                    <i class="fas fa-id-badge text-indigo-400"></i> <?php echo e(Str::words($pem->pemeriksa->name ?? 'System', 1, '')); ?>

                                </div>
                            </div>
                        </td>

                        
                        <td class="py-5 px-6 text-right">
                            <?php if($tab == 'pending'): ?>
                                <a href="<?php echo e(route('bidan.pemeriksaan.show', $pem->id)); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:shadow-[0_8px_15px_rgba(244,63,94,0.3)] hover:-translate-y-0.5 transition-all active:scale-95 shadow-md">
                                    <i class="fas fa-stethoscope text-sm"></i> Validasi Medis
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('bidan.pemeriksaan.show', $pem->id)); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-emerald-600 border-2 border-emerald-100 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-50 transition-all shadow-sm">
                                    <i class="fas fa-file-invoice text-sm"></i> Lihat EMR
                                </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-24 text-center">
                            <div class="w-28 h-28 rounded-full bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center mx-auto mb-5 text-slate-300 shadow-inner">
                                <i class="fas fa-<?php echo e($tab == 'pending' ? 'mug-hot' : 'archive'); ?> text-4xl opacity-40"></i>
                            </div>
                            <h3 class="text-[16px] font-black text-slate-800 font-poppins mb-1.5"><?php echo e($tab == 'pending' ? 'Meja 5 Kosong' : 'Arsip Masih Kosong'); ?></h3>
                            <p class="text-[12px] font-medium text-slate-500 max-w-sm mx-auto leading-relaxed">
                                <?php echo e($tab == 'pending' ? 'Belum ada data pemeriksaan warga dari Kader yang perlu divalidasi saat ini. Silakan bersantai sejenak!' : 'Anda belum menyelesaikan validasi pemeriksaan untuk periode ini.'); ?>

                            </p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            
            <div x-show="searchQuery.length > 0 && Array.from($el.previousElementSibling.querySelectorAll('tbody tr')).every(row => row.style.display === 'none')" 
                 x-cloak class="py-16 text-center">
                 <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-300 flex items-center justify-center mx-auto mb-3 text-2xl"><i class="fas fa-search-minus"></i></div>
                 <h4 class="text-[14px] font-black text-slate-700">Pencarian Tidak Ditemukan</h4>
                 <p class="text-[11px] text-slate-500 mt-1">Tekan Enter untuk mencari data di halaman lain, atau periksa ejaan Anda.</p>
            </div>
        </div>
        
        
        <?php if($pemeriksaans->hasPages()): ?>
        <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">
                Menampilkan <span class="text-slate-800"><?php echo e($pemeriksaans->firstItem()); ?></span> - <span class="text-slate-800"><?php echo e($pemeriksaans->lastItem()); ?></span> dari <span class="text-slate-800"><?php echo e($pemeriksaans->total()); ?></span> Antrian
            </p>
            <div class="nexus-pagination">
                <?php echo e($pemeriksaans->withQueryString()->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pemeriksaan/index.blade.php ENDPATH**/ ?>