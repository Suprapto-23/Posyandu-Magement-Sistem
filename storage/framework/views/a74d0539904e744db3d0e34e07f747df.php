
<?php $__env->startSection('title', 'Pesan dari Bidan'); ?>
<?php $__env->startSection('page-name', 'Pesan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto animate-[slideDown_0.5s_ease-out]">

    <div class="bg-white rounded-[32px] p-6 md:p-8 mb-6 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl shadow-sm border border-teal-100">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black font-poppins text-slate-800 tracking-tight">Pesan Masuk</h2>
                <p class="text-slate-500 text-sm font-medium">Anda memiliki <strong id="header-unread-count" class="text-rose-500"><?php echo e(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count()); ?></strong> pesan baru.</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <?php if(\App\Models\Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count() > 0): ?>
            <form action="<?php echo e(route('user.notifikasi.markall')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-5 py-3 bg-teal-600 text-white font-bold text-[13px] rounded-xl hover:bg-teal-700 transition-all shadow-[0_4px_15px_rgba(13,148,136,0.3)] hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <div id="main-notif-wrapper">
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">
            <div class="divide-y divide-slate-100/80">
                <?php $__empty_1 = true; $__currentLoopData = $notifikasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-5 sm:p-6 flex gap-4 transition-colors group <?php echo e($notif->is_read ? 'bg-white hover:bg-slate-50' : 'bg-teal-50/30 border-l-4 border-l-teal-500 hover:bg-teal-50/50'); ?>">
                        
                        <?php
                            $icon = 'envelope'; $iconColor = $notif->is_read ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-teal-100 text-teal-600 border-teal-200';
                            $jdl = strtolower($notif->judul);
                            if (str_contains($jdl, 'jadwal')) { $icon = 'calendar-alt'; $iconColor = $notif->is_read ? 'bg-slate-50 text-slate-400' : 'bg-amber-100 text-amber-600 border-amber-200'; }
                        ?>

                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 border <?php echo e($iconColor); ?>">
                            <i class="fas fa-<?php echo e($icon); ?> text-lg"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 mb-1">
                                <h4 class="text-[15px] font-bold truncate pr-4 <?php echo e($notif->is_read ? 'text-slate-600' : 'text-slate-900'); ?>"><?php echo e($notif->judul); ?></h4>
                                <span class="text-[11px] font-bold text-slate-400 whitespace-nowrap"><i class="fas fa-clock mr-1"></i> <?php echo e($notif->created_at->diffForHumans()); ?></span>
                            </div>
                            <p class="text-[13px] <?php echo e($notif->is_read ? 'text-slate-500' : 'text-slate-600 font-medium'); ?> leading-relaxed mb-3"><?php echo e($notif->pesan); ?></p>
                            
                            <?php if(!$notif->is_read): ?>
                            <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="<?php echo e(route('user.notifikasi.read', $notif->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-[11px] font-bold text-teal-600 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">Tandai Dibaca</button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if(!$notif->is_read): ?>
                            <div class="w-2.5 h-2.5 rounded-full bg-rose-500 mt-2 sm:hidden shrink-0"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-24 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-5 text-4xl shadow-inner border border-slate-100">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 font-poppins">Kotak Masuk Bersih!</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Belum ada pesan atau pengingat jadwal dari Bidan saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($notifikasis->hasPages()): ?>
                <div class="p-5 bg-slate-50/50 border-t border-slate-100">
                    <?php echo e($notifikasis->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/notifikasi/index.blade.php ENDPATH**/ ?>