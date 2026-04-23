

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-50 rounded-full shadow-sm border border-indigo-100 mb-4">
                <i class="fas fa-inbox text-indigo-500"></i>
                <span class="text-[11px] font-black tracking-widest uppercase text-indigo-700">Pusat Informasi</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Kotak Masuk Bidan 📨</h1>
            <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Semua pesan, pengingat jadwal, dan hasil validasi pemeriksaan dari Bidan dan Kader akan muncul di sini.</p>
        </div>

        <?php if(isset($unreadCount) && $unreadCount > 0): ?>
            <form action="<?php echo e(route('user.notifikasi.markall')); ?>" method="POST" class="m-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="flex overflow-x-auto custom-scrollbar pb-4 mb-6 gap-3">
        <a href="<?php echo e(route('user.notifikasi.index', ['filter' => 'semua'])); ?>" 
           class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filter == 'semua' ? 'bg-slate-800 text-white border-slate-800 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:bg-slate-50'); ?>">
            Semua Pesan
            <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filter == 'semua' ? 'bg-slate-600 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($allCount ?? 0); ?></span>
        </a>

        <a href="<?php echo e(route('user.notifikasi.index', ['filter' => 'belum'])); ?>" 
           class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filter == 'belum' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50'); ?>">
            <i class="fas fa-envelope <?php echo e($filter == 'belum' ? 'text-white' : 'text-indigo-500'); ?>"></i> Belum Dibaca
            <?php if(isset($unreadCount) && $unreadCount > 0): ?>
                <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filter == 'belum' ? 'bg-indigo-400 text-white' : 'bg-rose-500 text-white animate-pulse'); ?>"><?php echo e($unreadCount); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('user.notifikasi.index', ['filter' => 'sudah'])); ?>" 
           class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filter == 'sudah' ? 'bg-teal-600 text-white border-teal-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300 hover:bg-teal-50'); ?>">
            <i class="fas fa-envelope-open <?php echo e($filter == 'sudah' ? 'text-white' : 'text-teal-500'); ?>"></i> Sudah Dibaca
        </a>
    </div>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $notifikasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isUnread = !$notif->is_read;
                // Kustomisasi ikon berdasarkan kata kunci di judul (Opsional, agar lebih cantik)
                $icon = 'fa-envelope';
                $iconBg = 'bg-indigo-50 text-indigo-500';
                $judulLower = strtolower($notif->judul);
                
                if(str_contains($judulLower, 'jadwal') || str_contains($judulLower, 'posyandu')) {
                    $icon = 'fa-calendar-alt'; $iconBg = 'bg-teal-50 text-teal-500';
                } elseif(str_contains($judulLower, 'pemeriksaan') || str_contains($judulLower, 'hasil')) {
                    $icon = 'fa-stethoscope'; $iconBg = 'bg-sky-50 text-sky-500';
                } elseif(str_contains($judulLower, 'imunisasi') || str_contains($judulLower, 'vaksin')) {
                    $icon = 'fa-syringe'; $iconBg = 'bg-pink-50 text-pink-500';
                } elseif(str_contains($judulLower, 'gizi') || str_contains($judulLower, 'darah')) {
                    $icon = 'fa-heartbeat'; $iconBg = 'bg-orange-50 text-orange-500';
                }
                
                if(!$isUnread) {
                    $iconBg = 'bg-slate-100 text-slate-400'; // Redupkan jika sudah dibaca
                }
            ?>

            <div class="group relative bg-white rounded-3xl border <?php echo e($isUnread ? 'border-indigo-200 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.1)]' : 'border-slate-100 shadow-sm'); ?> overflow-hidden transition-all hover:shadow-md">
                
                <?php if($isUnread): ?>
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500"></div>
                <?php endif; ?>

                <div class="p-5 md:p-6 flex flex-col md:flex-row gap-5 items-start">
                    
                    <div class="w-12 h-12 rounded-2xl <?php echo e($iconBg); ?> flex items-center justify-center shrink-0 text-xl shadow-sm">
                        <i class="fas <?php echo e($icon); ?>"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                            <h3 class="text-base font-black <?php echo e($isUnread ? 'text-slate-800' : 'text-slate-600'); ?>"><?php echo e($notif->judul); ?></h3>
                            <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5 whitespace-nowrap">
                                <i class="far fa-clock"></i> <?php echo e($notif->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                        <p class="text-sm font-medium <?php echo e($isUnread ? 'text-slate-700' : 'text-slate-500'); ?> leading-relaxed"><?php echo e($notif->pesan); ?></p>
                    </div>

                    <?php if($isUnread): ?>
                        <div class="shrink-0 mt-3 md:mt-0 self-end md:self-center">
                            <form action="<?php echo e(route('user.notifikasi.read', $notif->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-4 py-2 bg-indigo-50 text-indigo-600 text-[11px] font-bold uppercase tracking-wider rounded-lg hover:bg-indigo-600 hover:text-white transition-colors border border-indigo-100">
                                    <i class="fas fa-check mr-1"></i> Mengerti
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="shrink-0 mt-3 md:mt-0 self-end md:self-center hidden md:block">
                            <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-slate-100">
                                <i class="fas fa-check-double mr-1"></i> Terbaca
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full blur-xl opacity-50"></div>
                    <div class="w-24 h-24 bg-white border border-slate-100 text-slate-300 rounded-full flex items-center justify-center text-5xl relative z-10 shadow-sm">
                        <i class="fas fa-inbox"></i>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-700 mb-2">Kotak Masuk Kosong</h3>
                <p class="text-sm font-medium text-slate-500 text-center max-w-md leading-relaxed">
                    <?php if($filter == 'belum'): ?>
                        Hore! Anda sudah membaca semua pesan dari Bidan. Tidak ada notifikasi baru saat ini.
                    <?php else: ?>
                        Belum ada pesan, pengingat jadwal, atau hasil pemeriksaan dari Bidan Posyandu.
                    <?php endif; ?>
                </p>
                <?php if($filter != 'semua'): ?>
                    <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="mt-6 text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 border border-indigo-100 px-5 py-2.5 rounded-xl transition-colors">Lihat Semua Pesan</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($notifikasis->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($notifikasis->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/notifikasi/index.blade.php ENDPATH**/ ?>