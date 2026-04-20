

<?php $__env->startSection('title', 'Antrian Pemeriksaan Klinis'); ?>
<?php $__env->startSection('page-name', 'Manajemen Antrian'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Styling khusus untuk Tab Navigasi */
    .tab-btn { position: relative; overflow: hidden; transition: all 0.3s ease; }
    .tab-btn.active { background: #ffffff; color: #0891b2; box-shadow: 0 4px 15px -3px rgba(8, 145, 178, 0.15); border-color: #cffafe; }
    .tab-btn.inactive { background: transparent; color: #64748b; border-color: transparent; }
    .tab-btn.inactive:hover { background: #f8fafc; color: #334155; }
    
    /* Efek hover pada baris tabel */
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.002]; box-shadow: 0 4px 10px -5px rgba(0,0,0,0.05); border-radius: 16px; }
</style>

<div class="space-y-6 lg:space-y-8 animate-slide-up">

    
    <div class="bg-white rounded-[32px] p-6 md:p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-gradient-to-tl from-cyan-100 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-inner border border-cyan-100">
                <i class="fas fa-procedures"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight font-poppins mb-1">Antrian Meja 5</h1>
                <p class="text-[13px] font-medium text-slate-500">Menerima data fisik dari Kader secara *real-time* untuk divalidasi.</p>
            </div>
        </div>

        
        <a href="<?php echo e(route('bidan.pemeriksaan.create')); ?>" class="relative z-10 inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 text-white text-[12px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 w-full sm:w-auto justify-center">
            <i class="fas fa-plus-circle text-lg text-cyan-400"></i> Input Mandiri
        </a>
    </div>

    
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col overflow-hidden">
        
        
        <div class="p-5 md:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            
            
            <div class="flex items-center p-1.5 bg-slate-200/60 rounded-[20px] w-full lg:w-auto">
                <a href="<?php echo e(route('bidan.pemeriksaan.index', ['tab' => 'pending'])); ?>" class="tab-btn flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-[16px] text-[11px] font-black uppercase tracking-widest border <?php echo e($tab == 'pending' ? 'active' : 'inactive'); ?>">
                    <i class="fas fa-clock text-sm"></i> Perlu Validasi
                    <?php if($pendingCount > 0): ?>
                        <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shadow-sm ml-1 animate-pulse"><?php echo e($pendingCount); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('bidan.pemeriksaan.index', ['tab' => 'verified'])); ?>" class="tab-btn flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-[16px] text-[11px] font-black uppercase tracking-widest border <?php echo e($tab == 'verified' ? 'active' : 'inactive'); ?>">
                    <i class="fas fa-check-double text-sm"></i> Riwayat Selesai
                </a>
            </div>
            
            
            <form method="GET" action="<?php echo e(route('bidan.pemeriksaan.index')); ?>" class="relative w-full lg:w-96">
                <input type="hidden" name="tab" value="<?php echo e($tab); ?>">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama warga atau NIK..." class="w-full bg-white border border-slate-200 rounded-[16px] pl-11 pr-4 py-3.5 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('bidan.pemeriksaan.index', ['tab' => $tab])); ?>" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="flex-1 overflow-x-auto custom-scrollbar p-2 md:p-4 min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pasien & Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Fisik Awal (Kader)</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu Masuk</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pemeriksaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Deteksi Kategori & Pewarnaan (ILP System)
                        $namaPasien = $pem->balita->nama_lengkap ?? $pem->remaja->nama_lengkap ?? $pem->lansia->nama_lengkap ?? $pem->ibuHamil->nama_lengkap ?? 'Anonim';
                        $kategoriRaw = strtolower(class_basename($pem->kategori_pasien ?? $pem->pasien_type));
                        
                        if($kategoriRaw == 'balita') { $nCol = 'sky'; $nIco = 'baby'; $kategori = 'Balita'; }
                        elseif($kategoriRaw == 'remaja') { $nCol = 'indigo'; $nIco = 'user-graduate'; $kategori = 'Remaja'; }
                        elseif(in_array($kategoriRaw, ['ibu_hamil','ibuhamil','bumil'])) { $nCol = 'pink'; $nIco = 'female'; $kategori = 'Ibu Hamil'; }
                        else { $nCol = 'emerald'; $nIco = 'user-clock'; $kategori = 'Lansia';}
                    ?>
                    
                    <tr class="table-row-hover transition-all duration-200 group border-b border-slate-50 last:border-0">
                        
                        
                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[14px] bg-<?php echo e($nCol); ?>-50 text-<?php echo e($nCol); ?>-600 flex items-center justify-center shrink-0 border border-<?php echo e($nCol); ?>-100 shadow-inner group-hover:scale-110 transition-transform">
                                    <i class="fas fa-<?php echo e($nIco); ?> text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-1 group-hover:text-cyan-600 transition-colors"><?php echo e($namaPasien); ?></p>
                                    <span class="text-[9px] font-black text-<?php echo e($nCol); ?>-600 uppercase tracking-widest bg-white border border-<?php echo e($nCol); ?>-200 px-2 py-0.5 rounded shadow-sm"><?php echo e($kategori); ?></span>
                                </div>
                            </div>
                        </td>

                        
                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg shadow-sm">BB: <?php echo e($pem->berat_badan ?? '-'); ?>kg</span>
                                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg shadow-sm">TB: <?php echo e($pem->tinggi_badan ?? '-'); ?>cm</span>
                            </div>
                            <?php if(in_array($kategoriRaw, ['remaja', 'lansia', 'ibu_hamil']) && $pem->tekanan_darah): ?>
                                <p class="text-[10px] font-bold text-rose-500 mt-2"><i class="fas fa-heartbeat"></i> Tensi: <?php echo e($pem->tekanan_darah); ?></p>
                            <?php endif; ?>
                        </td>

                        
                        <td class="py-4 px-6 align-middle">
                            <p class="font-bold text-slate-700 text-[13px]"><i class="far fa-calendar-alt text-slate-400 mr-1"></i> <?php echo e(\Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('d M Y')); ?></p>
                            <p class="text-[11px] font-medium text-slate-500 mt-1"><i class="far fa-clock text-slate-400 mr-1"></i> Pukul <?php echo e($pem->created_at->format('H:i')); ?> WIB</p>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 border-t border-slate-100 pt-1 inline-block">Kader: <?php echo e(Str::words($pem->pemeriksa->name ?? 'Sistem', 2, '')); ?></p>
                        </td>

                        
                        <td class="py-4 px-6 text-right align-middle">
                            <?php if($tab == 'pending'): ?>
                                <a href="<?php echo e(route('bidan.pemeriksaan.show', $pem->id)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm transform hover:-translate-y-0.5">
                                    <i class="fas fa-stethoscope"></i> Validasi Medis
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('bidan.pemeriksaan.show', $pem->id)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white border border-emerald-200 hover:border-emerald-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-file-medical"></i> Lihat Hasil
                                </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 border border-slate-100 text-slate-300 mb-4 shadow-inner">
                                <i class="fas fa-<?php echo e($tab == 'pending' ? 'procedures' : 'check-double'); ?> text-5xl"></i>
                            </div>
                            <h3 class="text-[16px] font-black text-slate-800 font-poppins mb-1"><?php echo e($tab == 'pending' ? 'Hore! Antrian Kosong' : 'Belum Ada Riwayat'); ?></h3>
                            <p class="text-[12px] font-medium text-slate-500 max-w-sm mx-auto">
                                <?php echo e($tab == 'pending' ? 'Tidak ada warga yang menunggu validasi saat ini. Anda bisa bersantai sejenak.' : 'Belum ada data pemeriksaan yang diselesaikan pada periode ini.'); ?>

                            </p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if($pemeriksaans->hasPages()): ?>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            <?php echo e($pemeriksaans->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pemeriksaan/index.blade.php ENDPATH**/ ?>