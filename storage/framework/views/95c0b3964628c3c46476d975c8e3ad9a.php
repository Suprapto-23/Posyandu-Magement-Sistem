

<?php $__env->startSection('title', 'Register Imunisasi Terpadu'); ?>
<?php $__env->startSection('page-name', 'Log Vaksinasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.005]; box-shadow: 0 10px 30px -10px rgba(6, 182, 212, 0.15); border-radius: 16px; z-index: 10; position: relative; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto space-y-6 lg:space-y-8 animate-slide-up pb-10">

    
    <div class="bg-gradient-to-br from-cyan-600 via-cyan-700 to-blue-800 rounded-[32px] p-8 md:p-10 text-white relative overflow-hidden shadow-[0_15px_40px_rgba(6,182,212,0.3)] border border-cyan-500">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none hidden lg:block transform rotate-12">
            <i class="fas fa-shield-virus text-[120px]"></i>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-[24px] bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl shrink-0 shadow-inner">
                    <i class="fas fa-syringe text-cyan-300"></i>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-cyan-900/50 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2 backdrop-blur-md border border-cyan-500/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span> Log Register Terpadu
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-none mb-2 font-poppins">Buku Imunisasi Warga</h1>
                    <p class="text-[13px] font-medium text-cyan-100 max-w-xl leading-relaxed">
                        Pusat pencatatan riwayat injeksi vaksin Balita dan Ibu Hamil (TT). Terintegrasi otomatis ke dalam EMR KIA.
                    </p>
                </div>
            </div>
            
            <a href="<?php echo e(route('bidan.imunisasi.create')); ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-cyan-700 text-[12px] font-black uppercase tracking-widest rounded-2xl hover:bg-cyan-50 transition-all shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 group whitespace-nowrap">
                <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform"></i> Tambah Injeksi
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col overflow-hidden">
        
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <h3 class="text-[16px] font-black text-slate-800 font-poppins flex items-center gap-2">
                <i class="fas fa-clipboard-list text-cyan-500"></i> Riwayat Injeksi Medis
            </h3>
            
            <form method="GET" action="<?php echo e(route('bidan.imunisasi.index')); ?>" class="relative w-full sm:w-[400px] group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama warga atau jenis vaksin..." class="w-full bg-white border-2 border-slate-200 rounded-[16px] pl-12 pr-4 py-3.5 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
            </form>
        </div>

        <div class="flex-1 overflow-x-auto custom-scrollbar p-2 md:p-4 min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pasien & Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Vaksin / Injeksi</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pelaksanaan</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $imunisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $pasien = $imu->kunjungan->pasien ?? null;
                        $nama = $pasien->nama_lengkap ?? 'Anonim / Dihapus';
                        $kategoriRaw = strtolower(class_basename($imu->kunjungan->pasien_type ?? ''));
                        
                        if($kategoriRaw == 'balita') { $nCol = 'sky'; $nIco = 'baby'; $kat = 'Balita'; }
                        elseif(in_array($kategoriRaw, ['ibu_hamil','ibuhamil','bumil'])) { $nCol = 'pink'; $nIco = 'female'; $kat = 'Ibu Hamil'; }
                        else { $nCol = 'slate'; $nIco = 'user'; $kat = 'Umum';}
                    ?>
                    
                    <tr class="table-row-hover transition-all duration-300 border-b border-slate-50 last:border-0 group">
                        
                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[16px] bg-<?php echo e($nCol); ?>-50 text-<?php echo e($nCol); ?>-600 flex items-center justify-center shrink-0 border border-<?php echo e($nCol); ?>-100 shadow-inner group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-<?php echo e($nIco); ?> text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14.5px] mb-1 group-hover:text-cyan-600 transition-colors font-poppins"><?php echo e($nama); ?></p>
                                    <span class="text-[9px] font-black text-<?php echo e($nCol); ?>-600 uppercase tracking-widest bg-white border border-<?php echo e($nCol); ?>-200 px-2 py-0.5 rounded shadow-sm"><?php echo e($kat); ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="py-5 px-6 align-middle">
                            <div>
                                <p class="font-black text-cyan-700 text-[15px] bg-cyan-50 inline-block px-3 py-1 rounded-lg border border-cyan-100"><?php echo e($imu->vaksin); ?></p>
                                <p class="text-[11px] font-medium text-slate-400 mt-2 max-w-[250px] truncate" title="<?php echo e($imu->keterangan); ?>">
                                    <?php if($imu->keterangan && $imu->keterangan != '-'): ?>
                                        <i class="fas fa-exclamation-circle text-amber-400"></i> <span class="text-amber-600 font-bold"><?php echo e($imu->keterangan); ?></span>
                                    <?php else: ?>
                                        <span class="italic text-slate-300"><i class="fas fa-check-circle text-emerald-400"></i> Tanpa KIPI</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>

                        <td class="py-5 px-6 align-middle">
                            <div class="flex items-center gap-2 mb-1.5">
                                <i class="far fa-calendar-check text-cyan-500"></i>
                                <span class="font-bold text-slate-700 text-[13px]"><?php echo e(\Carbon\Carbon::parse($imu->tanggal_imunisasi)->translatedFormat('d M Y')); ?></span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <i class="fas fa-user-nurse"></i> <?php echo e(Str::words($imu->kunjungan->petugas->name ?? 'Sistem', 2, '')); ?>

                            </div>
                        </td>

                        <td class="py-5 px-6 text-right align-middle">
                            <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="<?php echo e(route('bidan.imunisasi.show', $imu->id)); ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-cyan-600 hover:border-cyan-300 hover:bg-cyan-50 transition-all shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-expand-alt text-[14px]"></i>
                                </a>
                                <form action="<?php echo e(route('bidan.imunisasi.destroy', $imu->id)); ?>" method="POST" onsubmit="return confirm('Hapus catatan vaksin ini secara permanen?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Data">
                                        <i class="fas fa-trash-alt text-[14px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-24 text-center">
                            <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-slate-50 border-2 border-dashed border-slate-200 text-slate-300 mb-6 relative">
                                <i class="fas fa-syringe text-5xl relative z-10"></i>
                            </div>
                            <h3 class="text-[18px] font-black text-slate-800 font-poppins tracking-wide mb-2">Buku Register Kosong</h3>
                            <p class="text-[13px] font-medium text-slate-500 max-w-md mx-auto leading-relaxed">
                                Klik tombol <b class="text-cyan-600">Tambah Injeksi</b> di atas untuk mencatat riwayat imunisasi.
                            </p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(isset($imunisasis) && $imunisasis->hasPages()): ?>
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
            <?php echo e($imunisasis->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/index.blade.php ENDPATH**/ ?>