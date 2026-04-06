
<?php $__env->startSection('title', 'Jadwal Posyandu'); ?>
<?php $__env->startSection('page-name', 'Manajemen Jadwal'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .jadwal-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .jadwal-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px -10px rgba(6, 182, 212, 0.2); border-color: rgba(6, 182, 212, 0.3); }
</style>

<div class="max-w-[1200px] mx-auto animate-slide-up pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 bg-white p-6 md:p-8 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-50 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-cyan-500 to-sky-600 text-white flex items-center justify-center text-3xl shadow-[0_4px_15px_rgba(6,182,212,0.3)] shrink-0 transform -rotate-3">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight font-poppins mb-1">Jadwal Kegiatan</h1>
                <p class="text-slate-500 font-medium text-[13px] md:text-sm">Rencanakan layanan medis dan broadcast notifikasi ke warga & kader.</p>
            </div>
        </div>
        <a href="<?php echo e(route('bidan.jadwal.create')); ?>" onclick="showGlobalLoader('MEMUAT FORM...')" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-800 text-white font-black text-[13px] uppercase tracking-widest rounded-xl hover:bg-slate-900 transition-all shadow-md shrink-0 relative z-10">
            <i class="fas fa-plus-circle"></i> Buat Jadwal
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isPast = \Carbon\Carbon::parse($jadwal->tanggal)->isPast() && !\Carbon\Carbon::parse($jadwal->tanggal)->isToday();
                $isToday = \Carbon\Carbon::parse($jadwal->tanggal)->isToday();
                
                // Menentukan Tema Warna berdasarkan Kategori
                $kat = strtolower($jadwal->kategori);
                if($kat == 'imunisasi') { $bgC = 'bg-rose-50'; $txC = 'text-rose-600'; $icn = 'fa-syringe'; $bdC = 'border-rose-100'; }
                elseif($kat == 'pemeriksaan') { $bgC = 'bg-emerald-50'; $txC = 'text-emerald-600'; $icn = 'fa-stethoscope'; $bdC = 'border-emerald-100'; }
                elseif($kat == 'konseling') { $bgC = 'bg-indigo-50'; $txC = 'text-indigo-600'; $icn = 'fa-comments'; $bdC = 'border-indigo-100'; }
                else { $bgC = 'bg-cyan-50'; $txC = 'text-cyan-600'; $icn = 'fa-users'; $bdC = 'border-cyan-100'; }
            ?>

            <div class="bg-white rounded-[24px] border <?php echo e($isToday ? 'border-cyan-400 shadow-[0_8px_20px_rgba(6,182,212,0.15)] ring-1 ring-cyan-400' : 'border-slate-200/80 shadow-[0_4px_15px_rgba(0,0,0,0.03)]'); ?> p-6 jadwal-card flex flex-col relative overflow-hidden">
                
                <?php if($isToday): ?>
                    <div class="absolute top-0 right-0 bg-cyan-500 text-white text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl shadow-sm">
                        HARI INI
                    </div>
                <?php elseif($isPast): ?>
                    <div class="absolute inset-0 bg-slate-50/50 backdrop-blur-[1px] z-10 pointer-events-none"></div>
                    <div class="absolute top-0 right-0 bg-slate-400 text-white text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl shadow-sm z-20">
                        SELESAI
                    </div>
                <?php endif; ?>

                <div class="flex items-start gap-4 mb-5 relative z-20">
                    <div class="w-14 h-14 rounded-[16px] flex flex-col items-center justify-center border shadow-sm shrink-0 <?php echo e($isPast ? 'bg-slate-100 border-slate-200 text-slate-400' : 'bg-white border-slate-200 text-slate-700'); ?>">
                        <span class="text-[10px] font-black uppercase tracking-widest leading-none mb-1 text-cyan-600"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                        <span class="text-[22px] font-black leading-none"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <h3 class="text-[16px] font-black text-slate-800 leading-tight mb-1 truncate" title="<?php echo e($jadwal->judul); ?>"><?php echo e($jadwal->judul); ?></h3>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest <?php echo e($isPast ? 'text-slate-400' : $txC); ?>">
                            <div class="w-5 h-5 rounded-md <?php echo e($bgC); ?> <?php echo e($bdC); ?> flex items-center justify-center border"><i class="fas <?php echo e($icn); ?>"></i></div>
                            <?php echo e($jadwal->kategori); ?>

                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-6 flex-1 relative z-20">
                    <div class="flex items-center gap-3 text-[12px] font-semibold text-slate-600">
                        <div class="w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 shrink-0"><i class="far fa-clock"></i></div>
                        <span><?php echo e(\Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')); ?> WIB</span>
                    </div>
                    <div class="flex items-center gap-3 text-[12px] font-semibold text-slate-600">
                        <div class="w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                        <span class="truncate"><?php echo e($jadwal->lokasi); ?></span>
                    </div>
                    <div class="flex items-center gap-3 text-[12px] font-semibold text-slate-600">
                        <div class="w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 shrink-0"><i class="fas fa-users-rays"></i></div>
                        <span>Target: <strong class="text-cyan-700 uppercase"><?php echo e($jadwal->target_peserta); ?></strong></span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2 relative z-20">
                    <div class="flex items-center gap-2">
                        <a href="<?php echo e(route('bidan.jadwal.edit', $jadwal->id)); ?>" onclick="showGlobalLoader('MEMUAT FORM...')" class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors border border-amber-100" title="Edit">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        <form action="<?php echo e(route('bidan.jadwal.destroy', $jadwal->id)); ?>" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" class="m-0">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors border border-rose-100" title="Hapus">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </div>

                    <?php if(!$isPast): ?>
                        <form action="<?php echo e(route('bidan.jadwal.broadcast', $jadwal->id)); ?>" method="POST" class="m-0 flex-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" onclick="showGlobalLoader('MENGIRIM BROADCAST...')" class="w-full h-9 rounded-xl bg-cyan-50 hover:bg-cyan-600 text-cyan-600 hover:text-white border border-cyan-200 hover:border-cyan-600 flex items-center justify-center gap-2 text-[11px] font-black uppercase tracking-widest transition-all">
                                <i class="fas fa-bullhorn"></i> Broadcast
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-20 text-center bg-white rounded-[32px] border border-slate-200/80 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-5 text-5xl shadow-inner border border-slate-100"><i class="fas fa-calendar-times"></i></div>
                <h4 class="font-black text-slate-800 text-xl font-poppins tracking-tight mb-2">Belum Ada Jadwal</h4>
                <p class="text-sm font-medium text-slate-500 max-w-md mx-auto">Klik tombol "Buat Jadwal" untuk merencanakan kegiatan medis Posyandu.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if($jadwals->hasPages()): ?>
        <div class="mt-8 px-6 py-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
            <?php echo e($jadwals->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/jadwal/index.blade.php ENDPATH**/ ?>