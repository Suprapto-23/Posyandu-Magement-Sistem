

<?php $__env->startSection('title', 'Detail Remaja'); ?>
<?php $__env->startSection('page-name', 'Detail Profil'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto animate-slide-up">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('kader.data.remaja.index')); ?>" class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Pasien Remaja</h1>
        </div>
        <a href="<?php echo e(route('kader.data.remaja.edit', $remaja->id)); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-extrabold text-sm rounded-xl hover:bg-amber-600 shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4">
            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden sticky top-24">
                
                <div class="bg-gradient-to-b from-indigo-500 to-indigo-700 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-28 h-28 mx-auto bg-white rounded-[24px] shadow-xl flex items-center justify-center text-indigo-500 text-5xl font-black relative z-10 border-4 border-indigo-200/30 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <?php echo e(strtoupper(substr($remaja->nama_lengkap, 0, 1))); ?>

                    </div>
                    
                    <h2 class="text-2xl font-extrabold text-white mt-5 relative z-10"><?php echo e($remaja->nama_lengkap); ?></h2>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-900/30 text-indigo-50 text-xs font-bold rounded-lg mt-2 relative z-10 backdrop-blur-sm border border-white/10">
                        <i class="fas fa-id-card"></i> <?php echo e($remaja->kode_remaja); ?>

                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Usia</p>
                            <p class="text-base font-black text-indigo-600"><?php echo e($usia); ?> Tahun</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Gender</p>
                            <p class="text-base font-black text-slate-800">
                                <?php if($remaja->jenis_kelamin == 'L'): ?> <i class="fas fa-mars text-sky-500 mr-1"></i> Laki-laki
                                <?php else: ?> <i class="fas fa-venus text-rose-500 mr-1"></i> Perempuan <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">NIK Integrasi</p>
                            <p class="text-sm font-bold text-slate-800 font-mono"><?php echo e($remaja->nik); ?></p>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center"><i class="fas fa-calendar-alt text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tanggal Lahir</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800"><?php echo e($remaja->tanggal_lahir->format('d M Y')); ?></span>
                        </div>
                        
                        <hr class="border-slate-100 my-2">

                        <div class="p-3">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center"><i class="fas fa-school text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Pendidikan</span>
                            </div>
                            <p class="text-sm font-bold text-slate-800 pl-11"><?php echo e($remaja->sekolah ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 pl-11 mt-0.5">Kelas: <?php echo e($remaja->kelas ?? '-'); ?></p>
                        </div>

                        <div class="p-3">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center"><i class="fas fa-users text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Orang Tua / Wali</span>
                            </div>
                            <p class="text-sm font-bold text-slate-800 pl-11"><?php echo e($remaja->nama_ortu ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 pl-11 mt-0.5"><i class="fas fa-phone-alt"></i> <?php echo e($remaja->telepon_ortu ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden h-full flex flex-col">
                
                <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-[16px] bg-sky-50 text-sky-600 flex items-center justify-center text-xl"><i class="fas fa-notes-medical"></i></div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800">Riwayat Kesehatan</h3>
                            <p class="text-xs font-medium text-slate-400 mt-0.5">Catatan pemeriksaan posyandu remaja</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('kader.pemeriksaan.create')); ?>?kategori=remaja&pasien_id=<?php echo e($remaja->id); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-700 font-bold text-sm rounded-xl hover:bg-indigo-100 transition-colors shrink-0 border border-indigo-100">
                        <i class="fas fa-plus"></i> Periksa Baru
                    </a>
                </div>

                <div class="p-6 sm:p-8 flex-1 overflow-y-auto custom-scrollbar">
                    <?php $__empty_1 = true; $__currentLoopData = $remaja->kunjungans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunjungan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="relative pl-8 sm:pl-10 mb-8 last:mb-0">
                            <div class="absolute left-[15px] sm:left-[19px] top-8 bottom-[-32px] w-[2px] bg-slate-100 last:hidden"></div>
                            
                            <div class="absolute left-0 top-1 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-50 border-4 border-white flex items-center justify-center text-indigo-500 shadow-sm z-10">
                                <i class="fas fa-stethoscope text-[10px] sm:text-xs"></i>
                            </div>

                            <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm hover:border-indigo-100 transition-colors group">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                                    <div>
                                        <h6 class="font-bold text-slate-800 text-sm"><?php echo e(ucfirst($kunjungan->jenis_kunjungan)); ?></h6>
                                        <p class="text-xs text-slate-400 mt-0.5">Keluhan: <?php echo e($kunjungan->keluhan ?? 'Tidak ada keluhan'); ?></p>
                                    </div>
                                    <div class="text-left sm:text-right shrink-0">
                                        <span class="inline-block px-2.5 py-1 bg-slate-50 text-slate-500 text-[10px] font-extrabold rounded-md uppercase tracking-wider">
                                            <?php echo e($kunjungan->tanggal_kunjungan->format('d M Y')); ?>

                                        </span>
                                    </div>
                                </div>

                                <?php if($kunjungan->pemeriksaan): ?>
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs">
                                            <span class="text-slate-400 font-bold mr-1">BB:</span>
                                            <span class="text-slate-700 font-black"><?php echo e($kunjungan->pemeriksaan->berat_badan); ?> kg</span>
                                        </div>
                                        <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs">
                                            <span class="text-slate-400 font-bold mr-1">TB:</span>
                                            <span class="text-slate-700 font-black"><?php echo e($kunjungan->pemeriksaan->tinggi_badan); ?> cm</span>
                                        </div>
                                        <div class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg text-xs">
                                            <span class="text-slate-400 font-bold mr-1">Tensi:</span>
                                            <span class="text-slate-700 font-black"><?php echo e($kunjungan->pemeriksaan->tekanan_darah ?? '-'); ?></span>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <a href="<?php echo e(route('kader.kunjungan.show', $kunjungan->id)); ?>" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                            Lihat Detail Lengkap &rarr;
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex flex-col items-center justify-center py-12 text-center h-full">
                            <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4 class="font-black text-slate-800 text-lg">Belum Ada Pemeriksaan</h4>
                            <p class="text-sm font-medium text-slate-500 mt-1 max-w-sm mx-auto">Remaja ini belum memiliki catatan riwayat kesehatan atau kunjungan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/remaja/show.blade.php ENDPATH**/ ?>