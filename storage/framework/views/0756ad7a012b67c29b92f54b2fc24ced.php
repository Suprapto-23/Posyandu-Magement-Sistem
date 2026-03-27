

<?php $__env->startSection('title', 'Jadwal Posyandu'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .date-badge { background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-slide-up space-y-6 pb-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight">Agenda Posyandu</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Jangan lewatkan jadwal pemeriksaan kesehatan Anda.</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-2xl shadow-sm">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    <div class="overflow-x-auto pb-2 custom-scrollbar">
        <div class="flex bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm w-max min-w-full">
            <a href="?filter=semua" class="smooth-route flex-1 text-center px-4 py-2.5 rounded-lg text-xs font-extrabold transition-colors <?php echo e($filterTarget == 'semua' ? 'bg-sky-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">Semua (<?php echo e($summary['semua'] ?? 0); ?>)</a>
            
            <?php if(in_array('balita', $hakAkses)): ?>
            <a href="?filter=balita" class="smooth-route flex-1 text-center px-4 py-2.5 rounded-lg text-xs font-extrabold transition-colors <?php echo e($filterTarget == 'balita' ? 'bg-rose-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">Balita (<?php echo e($summary['balita'] ?? 0); ?>)</a>
            <?php endif; ?>

            <?php if(in_array('remaja', $hakAkses)): ?>
            <a href="?filter=remaja" class="smooth-route flex-1 text-center px-4 py-2.5 rounded-lg text-xs font-extrabold transition-colors <?php echo e($filterTarget == 'remaja' ? 'bg-indigo-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">Remaja (<?php echo e($summary['remaja'] ?? 0); ?>)</a>
            <?php endif; ?>

            <?php if(in_array('lansia', $hakAkses)): ?>
            <a href="?filter=lansia" class="smooth-route flex-1 text-center px-4 py-2.5 rounded-lg text-xs font-extrabold transition-colors <?php echo e($filterTarget == 'lansia' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">Lansia (<?php echo e($summary['lansia'] ?? 0); ?>)</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $jadwalKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $tgl = \Carbon\Carbon::parse($jadwal->tanggal);
                $isPast = $tgl->isPast() && !$tgl->isToday();
            ?>

            <div class="bg-white border border-slate-100 rounded-[20px] p-4 sm:p-5 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex gap-4 sm:gap-5 hover:shadow-md transition-shadow relative overflow-hidden <?php echo e($isPast ? 'opacity-60 grayscale-[50%]' : ''); ?>">
                
                <div class="<?php echo e($isPast ? 'bg-slate-500' : 'date-badge'); ?> w-16 h-20 sm:w-20 sm:h-24 rounded-2xl flex flex-col items-center justify-center text-white shadow-inner shrink-0">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-widest opacity-90 mb-0.5"><?php echo e($tgl->translatedFormat('M')); ?></span>
                    <span class="text-2xl sm:text-3xl font-black font-poppins leading-none"><?php echo e($tgl->format('d')); ?></span>
                    <span class="text-[10px] font-medium mt-1 opacity-80"><?php echo e($tgl->format('Y')); ?></span>
                </div>

                <div class="flex-1 min-w-0 py-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                        <span class="px-2 py-0.5 <?php echo e($isPast ? 'bg-slate-100 text-slate-500' : 'bg-teal-50 text-teal-600 border border-teal-100'); ?> rounded text-[9px] font-black uppercase tracking-widest"><?php echo e($jadwal->kategori); ?></span>
                        <?php if($tgl->isToday()): ?>
                            <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[9px] font-black uppercase tracking-widest animate-pulse">Hari Ini!</span>
                        <?php endif; ?>
                        <?php if($isPast): ?>
                            <span class="text-[10px] font-bold text-rose-500"><i class="fas fa-history"></i> Telah Berlalu</span>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="text-base sm:text-lg font-black text-slate-800 font-poppins leading-tight mb-2 truncate"><?php echo e($jadwal->judul); ?></h3>
                    
                    <div class="flex flex-col gap-1.5 text-xs font-semibold text-slate-500">
                        <p><i class="fas fa-clock text-slate-400 w-4 text-center"></i> <?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($jadwal->waktu_selesai))); ?> WIB</p>
                        <p class="truncate"><i class="fas fa-map-marker-alt text-rose-400 w-4 text-center"></i> <?php echo e($jadwal->lokasi); ?></p>
                        <p><i class="fas fa-users text-sky-400 w-4 text-center"></i> Target: <span class="text-slate-700 font-bold uppercase"><?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?></span></p>
                    </div>

                    <?php if($jadwal->deskripsi): ?>
                        <div class="mt-3 p-2.5 bg-slate-50 rounded-xl text-[11px] text-slate-600 leading-relaxed border border-slate-100">
                            <strong><i class="fas fa-info-circle text-slate-400 mr-1"></i> Info:</strong> <?php echo e($jadwal->deskripsi); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-16 px-4 bg-white border border-slate-100 rounded-[24px] shadow-sm">
                <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl text-slate-300">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h4 class="text-base font-black text-slate-700 font-poppins mb-1">Agenda Kosong</h4>
                <p class="text-xs font-medium text-slate-500">Belum ada jadwal posyandu untuk kategori ini.</p>
            </div>
        <?php endif; ?>

        <?php if($jadwalKegiatan->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($jadwalKegiatan->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/jadwal/index.blade.php ENDPATH**/ ?>