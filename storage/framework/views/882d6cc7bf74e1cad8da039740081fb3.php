

<?php $__env->startSection('title', 'Detail Balita'); ?>
<?php $__env->startSection('page-name', 'Detail Balita'); ?>

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
            <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Pasien</h1>
        </div>
        <a href="<?php echo e(route('kader.data.balita.edit', $balita->id)); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-extrabold text-sm rounded-xl hover:bg-amber-600 shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4">
            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden sticky top-24">
                
                <div class="bg-gradient-to-b from-indigo-500 to-indigo-600 px-6 py-10 text-center relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-28 h-28 mx-auto bg-white rounded-[24px] shadow-xl flex items-center justify-center text-indigo-500 text-5xl font-black relative z-10 border-4 border-indigo-200/30 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <?php echo e(strtoupper(substr($balita->nama_lengkap, 0, 1))); ?>

                    </div>
                    
                    <h2 class="text-2xl font-extrabold text-white mt-5 relative z-10"><?php echo e($balita->nama_lengkap); ?></h2>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-900/30 text-indigo-50 text-xs font-bold rounded-lg mt-2 relative z-10 backdrop-blur-sm">
                        <i class="fas fa-id-card"></i> <?php echo e($balita->kode_balita); ?>

                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Usia</p>
                            <p class="text-base font-black text-indigo-600"><?php echo e($usia_tahun); ?> Thn <?php echo e($sisa_bulan); ?> Bln</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Gender</p>
                            <p class="text-base font-black text-slate-800">
                                <?php if($balita->jenis_kelamin == 'L'): ?> <i class="fas fa-mars text-sky-500 mr-1"></i> Laki-laki
                                <?php else: ?> <i class="fas fa-venus text-rose-500 mr-1"></i> Perempuan <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center"><i class="fas fa-calendar-alt text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tanggal Lahir</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800"><?php echo e(\Carbon\Carbon::parse($balita->tanggal_lahir)->format('d M Y')); ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center"><i class="fas fa-weight text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">BB / PB Lahir</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800"><?php echo e($balita->berat_lahir ?? '-'); ?>kg / <?php echo e($balita->panjang_lahir ?? '-'); ?>cm</span>
                        </div>
                        
                        <hr class="border-slate-100 my-1">

                        <div class="p-3">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center"><i class="fas fa-female text-sm"></i></div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nama Ibu</span>
                            </div>
                            <p class="text-sm font-bold text-slate-800 pl-11"><?php echo e($balita->nama_ibu); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 pl-11 mt-0.5"><?php echo e($balita->nik_ibu); ?></p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <?php if($userTerhubung): ?>
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-center">
                                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas fa-check"></i></div>
                                <p class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider mb-1">Akun Warga Terhubung</p>
                                <p class="text-sm font-bold text-emerald-600"><?php echo e($userTerhubung->name); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                <div class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas fa-link-slash"></i></div>
                                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider mb-2">Belum Terhubung Warga</p>
                                <a href="<?php echo e(route('kader.data.balita.sync', $balita->id)); ?>" onclick="return confirm('Coba hubungkan berdasarkan NIK Ibu sekarang?')" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 shadow-sm text-slate-700 text-[11px] font-bold rounded-xl hover:bg-slate-100 transition-colors w-full">
                                    <i class="fas fa-sync mr-2"></i> Sinkronisasi NIK
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-[16px] bg-sky-50 text-sky-600 flex items-center justify-center text-xl"><i class="fas fa-notes-medical"></i></div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800">Riwayat Kunjungan</h3>
                            <p class="text-xs font-medium text-slate-400 mt-0.5">Catatan pemeriksaan posyandu</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('kader.pemeriksaan.create')); ?>?kategori=balita&pasien_id=<?php echo e($balita->id); ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-700 font-bold text-sm rounded-xl hover:bg-indigo-100 transition-colors shrink-0">
                        <i class="fas fa-plus"></i> Kunjungan Baru
                    </a>
                </div>

                <div class="p-0">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-100">Tanggal</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-100">Pelayanan</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-100">Hasil (BB/TB)</th>
                                    <th class="px-6 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $balita->kunjungans ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunjungan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800 text-sm"><?php echo e(\Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y')); ?></p>
                                        <p class="text-xs font-medium text-slate-400 mt-0.5"><?php echo e(\Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('H:i')); ?> WIB</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($kunjungan->jenis_kunjungan == 'imunisasi'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold border border-teal-100/50"><i class="fas fa-syringe"></i> Imunisasi</span>
                                        <?php elseif($kunjungan->jenis_kunjungan == 'pemeriksaan'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100/50"><i class="fas fa-stethoscope"></i> Pemeriksaan</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200/50"><i class="fas fa-user-md"></i> <?php echo e(ucfirst($kunjungan->jenis_kunjungan)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($kunjungan->pemeriksaan): ?>
                                            <div class="flex items-center gap-3">
                                                <div class="text-center bg-white border border-slate-100 rounded-lg px-2 py-1 shadow-sm">
                                                    <p class="text-[10px] text-slate-400 font-bold mb-0.5">BB</p>
                                                    <p class="text-xs font-black text-slate-700"><?php echo e($kunjungan->pemeriksaan->berat_badan ?? '-'); ?></p>
                                                </div>
                                                <div class="text-center bg-white border border-slate-100 rounded-lg px-2 py-1 shadow-sm">
                                                    <p class="text-[10px] text-slate-400 font-bold mb-0.5">TB</p>
                                                    <p class="text-xs font-black text-slate-700"><?php echo e($kunjungan->pemeriksaan->tinggi_badan ?? '-'); ?></p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 font-medium text-sm">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?php echo e(route('kader.kunjungan.show', $kunjungan->id)); ?>" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-2xl"><i class="fas fa-folder-open"></i></div>
                                        <h4 class="font-bold text-slate-700">Belum Ada Kunjungan</h4>
                                        <p class="text-sm font-medium text-slate-500 mt-1">Data pemeriksaan/imunisasi akan tampil di sini.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php $riwayatImunisasi = $balita->kunjungans->where('jenis_kunjungan', 'imunisasi') ?? collect(); ?>
            <?php if($riwayatImunisasi->count() > 0): ?>
            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-4 bg-teal-50/30">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center"><i class="fas fa-shield-virus"></i></div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Detail Vaksin Diterima</h3>
                    </div>
                </div>
                <div class="p-6 pt-2">
                    <div class="space-y-3">
                        <?php $__currentLoopData = $riwayatImunisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kjg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $kjg->imunisasis ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imunisasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-teal-500 font-black text-xs shadow-sm">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-sm"><?php echo e($imunisasi->jenis_imunisasi); ?></p>
                                        <p class="text-xs font-bold text-slate-400 uppercase mt-0.5"><?php echo e($imunisasi->vaksin); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-slate-500"><?php echo e(\Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->format('d M Y')); ?></p>
                                    <span class="inline-block px-2 py-1 bg-white border border-slate-200 text-slate-700 text-[10px] font-black rounded-md mt-1 shadow-sm"><?php echo e($imunisasi->dosis); ?></span>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/balita/show.blade.php ENDPATH**/ ?>