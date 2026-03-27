

<?php $__env->startSection('title', 'Beranda Utama'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 sm:space-y-8 pb-4">

    <div class="animate-slide-up bg-white rounded-[24px] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-teal-50 rounded-full blur-3xl opacity-60"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 text-teal-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-3 border border-teal-100">
                <i class="fas fa-shield-alt"></i> E-Posyandu Aktif
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight leading-tight">Halo, <?php echo e(explode(' ', Auth::user()->name)[0]); ?>! 👋</h2>
            <p class="text-sm font-medium text-slate-500 mt-2 max-w-md">Pantau jadwal imunisasi, rekam medis, dan perkembangan kesehatan keluarga Anda secara mudah dalam satu genggaman.</p>
        </div>
    </div>

    <?php if(isset($pesanError) && $pesanError): ?>
    <div class="animate-slide-up-delay bg-gradient-to-r from-rose-50 to-orange-50 border border-rose-200 p-5 rounded-[20px] flex items-start sm:items-center gap-4 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-white text-rose-500 flex items-center justify-center text-xl shrink-0 shadow-sm mt-0.5 sm:mt-0">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-black text-rose-800">Lengkapi Data Diri Anda</h4>
            <p class="text-xs font-medium text-rose-600 mt-1 mb-2.5 leading-snug"><?php echo e($pesanError); ?></p>
            <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route inline-flex items-center gap-1.5 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                Isi NIK Sekarang <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
        
        <div class="animate-slide-up-delay">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-black text-slate-800 text-base"><i class="fas fa-bullhorn text-teal-500 mr-2"></i> Info & Notifikasi Bidan</h3>
                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route text-[11px] font-bold text-teal-600 hover:text-teal-800 bg-teal-50 px-3 py-1.5 rounded-lg">Semua Info</a>
            </div>
            
            <div class="bg-white border border-slate-100 rounded-[24px] shadow-sm overflow-hidden p-2">
                <?php $__empty_1 = true; $__currentLoopData = $notifikasiTerbaru ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors rounded-xl <?php echo e(!$notif['is_read'] ? 'bg-sky-50/50' : ''); ?>">
                        <div class="w-10 h-10 rounded-full <?php echo e(!$notif['is_read'] ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400'); ?> flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2 mb-1">
                                <h4 class="text-sm font-bold text-slate-800 truncate"><?php echo e($notif['judul']); ?></h4>
                                <?php if(!$notif['is_read']): ?> <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0 mt-1.5"></span> <?php endif; ?>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed"><?php echo e($notif['pesan']); ?></p>
                            <span class="text-[10px] font-bold text-slate-400 mt-2 block"><i class="fas fa-clock mr-1"></i> <?php echo e($notif['waktu']); ?></span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-10 px-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl text-slate-300">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700 mb-1">Belum Ada Pesan Baru</h4>
                        <p class="text-xs font-medium text-slate-500">Anda sudah membaca semua pemberitahuan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="animate-slide-up-delay-2 space-y-6 sm:space-y-8">
            
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-slate-800 text-base"><i class="fas fa-calendar-check text-sky-500 mr-2"></i> Jadwal Anda Terdekat</h3>
                    <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route text-[11px] font-bold text-sky-600 hover:text-sky-800 bg-sky-50 px-3 py-1.5 rounded-lg">Cek Kalender</a>
                </div>
                
                <?php $__empty_1 = true; $__currentLoopData = $jadwalTerdekat ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gradient-to-r from-teal-600 to-sky-600 rounded-[24px] p-6 text-white shadow-[0_12px_30px_rgba(13,148,136,0.3)] relative overflow-hidden group mb-4 last:mb-0 transform transition-transform hover:-translate-y-1">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    <i class="fas fa-stethoscope absolute right-6 top-6 text-5xl opacity-20"></i>
                    
                    <div class="relative z-10">
                        <span class="inline-block px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-black tracking-widest uppercase mb-3 border border-white/20 shadow-sm"><?php echo e($jadwal->kategori); ?></span>
                        <h4 class="font-black text-xl leading-tight mb-4 tracking-tight pr-10"><?php echo e($jadwal->judul); ?></h4>
                        
                        <div class="bg-black/10 backdrop-blur-sm rounded-xl p-3.5 border border-white/10 flex flex-col gap-2">
                            <div class="flex items-center gap-3 text-xs font-semibold text-teal-50">
                                <div class="w-6 h-6 rounded-md bg-white/20 flex items-center justify-center shrink-0"><i class="fas fa-calendar-day"></i></div>
                                <span><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y')); ?> • <?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> WIB</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs font-semibold text-teal-50">
                                <div class="w-6 h-6 rounded-md bg-white/20 flex items-center justify-center shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                                <span class="truncate"><?php echo e($jadwal->lokasi); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white border border-slate-100 rounded-[24px] p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-slate-300 text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700 mb-1">Jadwal Kosong</h4>
                    <p class="text-xs font-medium text-slate-500">Belum ada agenda posyandu yang diumumkan oleh Bidan Desa dalam waktu dekat.</p>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <h3 class="font-black text-slate-800 text-base mb-4"><i class="fas fa-heart text-rose-500 mr-2"></i> Kesehatan Keluarga</h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    
                    <a href="<?php echo e(route('user.balita.index')); ?>" class="smooth-route bg-white border border-slate-100 p-5 rounded-[20px] shadow-sm hover:shadow-[0_8px_20px_rgba(244,63,94,0.15)] hover:border-rose-200 transition-all flex flex-col justify-center items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-baby"></i></div>
                        <h4 class="text-sm font-black text-slate-800">Balita</h4>
                        <p class="text-[10px] text-slate-500 font-bold mt-1 uppercase tracking-wider"><?php echo e(isset($dataAnak) ? $dataAnak->count() : 0); ?> Terdaftar</p>
                    </a>

                    <a href="<?php echo e(route('user.remaja.index')); ?>" class="smooth-route bg-white border border-slate-100 p-5 rounded-[20px] shadow-sm hover:shadow-[0_8px_20px_rgba(14,165,233,0.15)] hover:border-sky-200 transition-all flex flex-col justify-center items-center text-center group">
                        <div class="w-14 h-14 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-user-graduate"></i></div>
                        <h4 class="text-sm font-black text-slate-800">Remaja</h4>
                        <p class="text-[10px] text-slate-500 font-bold mt-1 uppercase tracking-wider">Pemantauan</p>
                    </a>

                    <a href="<?php echo e(route('user.lansia.index')); ?>" class="smooth-route bg-white border border-slate-100 p-5 rounded-[20px] shadow-sm hover:shadow-[0_8px_20px_rgba(245,158,11,0.15)] hover:border-amber-200 transition-all flex flex-col justify-center items-center text-center group col-span-2">
                        <div class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-wheelchair"></i></div>
                        <h4 class="text-sm font-black text-slate-800">Lansia</h4>
                        <p class="text-[10px] text-slate-500 font-bold mt-1 uppercase tracking-wider">Riwayat Hipertensi & Kesehatan</p>
                    </a>

                </div>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/dashboard.blade.php ENDPATH**/ ?>