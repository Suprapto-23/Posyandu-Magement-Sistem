

<?php $__env->startSection('title', 'Jadwal Posyandu'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-fade-in-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Scrollbar horizontal untuk menu filter */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Efek Kartu Jadwal */
    .schedule-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .schedule-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px -10px rgba(20, 184, 166, 0.2); border-color: #99f6e4; }
    
    /* Badge Tanggal Khusus */
    .date-badge-upcoming { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); }
    .date-badge-past { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pb-12 w-full">

    
    <div class="animate-fade-in-up bg-white rounded-[28px] p-6 sm:p-8 shadow-sm border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 font-poppins tracking-tight mb-2">Agenda Posyandu</h2>
            <p class="text-[13px] sm:text-[14px] font-medium text-slate-500 max-w-md leading-relaxed">Pantau jadwal pemeriksaan kesehatan, imunisasi, dan kegiatan posyandu lainnya di desa Anda.</p>
        </div>
        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[18px] bg-teal-50 text-teal-600 flex items-center justify-center text-3xl shadow-sm border border-teal-100 shrink-0 relative z-10">
            <i class="fas fa-calendar-alt"></i>
        </div>
    </div>

    
    <div class="animate-fade-in-up flex gap-2 overflow-x-auto no-scrollbar pb-4 mb-4" style="animation-delay: 0.1s;">
        <a href="?filter=semua" class="smooth-route shrink-0 px-6 py-3 rounded-full text-[12px] font-black tracking-widest uppercase transition-all <?php echo e($filterTarget == 'semua' ? 'bg-teal-600 text-white shadow-[0_8px_20px_rgba(13,148,136,0.3)]' : 'bg-white text-slate-500 border border-slate-200 hover:bg-teal-50 hover:text-teal-600'); ?>">
            Semua <span class="ml-1 opacity-70">(<?php echo e($summary['semua'] ?? 0); ?>)</span>
        </a>
        
        <?php if(in_array('balita', $hakAkses)): ?>
        <a href="?filter=balita" class="smooth-route shrink-0 px-6 py-3 rounded-full text-[12px] font-black tracking-widest uppercase transition-all <?php echo e($filterTarget == 'balita' ? 'bg-rose-500 text-white shadow-[0_8px_20px_rgba(244,63,94,0.3)]' : 'bg-white text-slate-500 border border-slate-200 hover:bg-rose-50 hover:text-rose-600'); ?>">
            Balita <span class="ml-1 opacity-70">(<?php echo e($summary['balita'] ?? 0); ?>)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('remaja', $hakAkses)): ?>
        <a href="?filter=remaja" class="smooth-route shrink-0 px-6 py-3 rounded-full text-[12px] font-black tracking-widest uppercase transition-all <?php echo e($filterTarget == 'remaja' ? 'bg-indigo-500 text-white shadow-[0_8px_20px_rgba(99,102,241,0.3)]' : 'bg-white text-slate-500 border border-slate-200 hover:bg-indigo-50 hover:text-indigo-600'); ?>">
            Remaja <span class="ml-1 opacity-70">(<?php echo e($summary['remaja'] ?? 0); ?>)</span>
        </a>
        <?php endif; ?>

        <?php if(in_array('lansia', $hakAkses)): ?>
        <a href="?filter=lansia" class="smooth-route shrink-0 px-6 py-3 rounded-full text-[12px] font-black tracking-widest uppercase transition-all <?php echo e($filterTarget == 'lansia' ? 'bg-amber-500 text-white shadow-[0_8px_20px_rgba(245,158,11,0.3)]' : 'bg-white text-slate-500 border border-slate-200 hover:bg-amber-50 hover:text-amber-600'); ?>">
            Lansia <span class="ml-1 opacity-70">(<?php echo e($summary['lansia'] ?? 0); ?>)</span>
        </a>
        <?php endif; ?>
    </div>

    
    <div class="space-y-5 animate-fade-in-up" style="animation-delay: 0.2s;">
        <?php $__empty_1 = true; $__currentLoopData = $jadwalKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $tgl = \Carbon\Carbon::parse($jadwal->tanggal);
                $isPast = $tgl->isPast() && !$tgl->isToday();
                $isToday = $tgl->isToday();
                
                // Styling dinamis
                $cardOpacity = $isPast ? 'opacity-70 bg-slate-50 border-slate-200' : 'bg-white border-slate-200 schedule-card';
                $badgeBg = $isPast ? 'date-badge-past' : 'date-badge-upcoming';
            ?>

            <div class="<?php echo e($cardOpacity); ?> border rounded-[24px] p-4 sm:p-5 flex flex-col sm:flex-row gap-5 relative overflow-hidden">
                
                
                <div class="<?php echo e($badgeBg); ?> w-full sm:w-24 h-20 sm:h-28 rounded-[18px] flex flex-row sm:flex-col items-center justify-center text-white shadow-inner shrink-0 gap-3 sm:gap-0 px-6 sm:px-0">
                    <span class="text-[11px] sm:text-[12px] font-black uppercase tracking-widest opacity-80 mb-0 sm:mb-1"><?php echo e($tgl->translatedFormat('M')); ?></span>
                    <span class="text-3xl sm:text-4xl font-black font-poppins leading-none"><?php echo e($tgl->format('d')); ?></span>
                    <div class="hidden sm:block w-8 h-px bg-white/20 my-1.5"></div>
                    <span class="text-[10px] font-bold mt-0 sm:mt-0.5 opacity-80"><?php echo e($tgl->format('Y')); ?></span>
                </div>

                
                <div class="flex-1 min-w-0 py-1 flex flex-col justify-center">
                    
                    
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                            <i class="fas fa-users"></i> <?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?>

                        </span>
                        
                        <?php if($isToday): ?>
                            <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 animate-pulse">
                                <i class="fas fa-exclamation-circle"></i> Hari Ini
                            </span>
                        <?php endif; ?>
                        
                        <?php if($isPast): ?>
                            <span class="px-2.5 py-1 bg-slate-200 text-slate-600 rounded-md text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <i class="fas fa-history"></i> Telah Selesai
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    
                    <h3 class="text-[16px] sm:text-[18px] font-black text-slate-800 font-poppins leading-snug mb-3 pr-4 <?php echo e($isPast ? 'text-slate-600' : ''); ?>">
                        <?php echo e($jadwal->judul); ?>

                    </h3>
                    
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[12px] font-bold text-slate-500">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center shrink-0"><i class="far fa-clock text-slate-400"></i></div>
                            <span><?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($jadwal->waktu_selesai))); ?> WIB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center shrink-0"><i class="fas fa-map-marker-alt text-rose-400"></i></div>
                            <span class="truncate pr-2"><?php echo e($jadwal->lokasi); ?></span>
                        </div>
                    </div>

                    
                    <?php if($jadwal->deskripsi): ?>
                        <div class="mt-4 p-3.5 bg-slate-50 rounded-xl text-[11px] font-medium text-slate-600 leading-relaxed border border-slate-100/80 relative">
                            <i class="fas fa-info-circle absolute left-3.5 top-3.5 text-slate-300"></i>
                            <span class="pl-6 block"><?php echo e($jadwal->deskripsi); ?></span>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <div class="text-center py-20 px-4 bg-white border border-slate-100 rounded-[32px] shadow-sm">
                <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl text-slate-300 shadow-inner">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h4 class="text-[16px] font-black text-slate-800 font-poppins mb-1">Agenda Kosong</h4>
                <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto">Belum ada jadwal kegiatan posyandu yang diterbitkan oleh Bidan Desa untuk saat ini.</p>
            </div>
        <?php endif; ?>

        
        <?php if($jadwalKegiatan->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($jadwalKegiatan->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/jadwal/index.blade.php ENDPATH**/ ?>