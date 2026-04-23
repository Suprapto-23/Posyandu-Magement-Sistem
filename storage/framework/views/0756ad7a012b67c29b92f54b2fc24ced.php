

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="mb-8">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-white rounded-full shadow-sm border border-slate-100 mb-4">
            <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-pulse"></span>
            <span class="text-[11px] font-black tracking-widest uppercase text-slate-600">Agenda Posyandu</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Jadwal Kegiatan Anda 📅</h1>
        <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Berikut adalah daftar jadwal Posyandu yang relevan dengan Anda dan keluarga. Pastikan untuk datang tepat waktu.</p>
    </div>

    <div class="flex overflow-x-auto custom-scrollbar pb-4 mb-6 gap-3">
        
        <a href="<?php echo e(route('user.jadwal.index', ['target' => 'semua'])); ?>" 
           class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filterTarget == 'semua' ? 'bg-teal-600 text-white border-teal-600 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300 hover:bg-teal-50'); ?>">
            Semua Jadwal
            <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filterTarget == 'semua' ? 'bg-teal-500 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($summary['semua']); ?></span>
        </a>

        <?php if(in_array('balita', $hakAkses)): ?>
            <a href="<?php echo e(route('user.jadwal.index', ['target' => 'balita'])); ?>" 
               class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filterTarget == 'balita' ? 'bg-sky-500 text-white border-sky-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-sky-300 hover:bg-sky-50'); ?>">
                <i class="fas fa-baby <?php echo e($filterTarget == 'balita' ? 'text-white' : 'text-sky-500'); ?>"></i> Balita
                <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filterTarget == 'balita' ? 'bg-sky-400 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($summary['balita']); ?></span>
            </a>
        <?php endif; ?>

        <?php if(in_array('ibu_hamil', $hakAkses)): ?>
            <a href="<?php echo e(route('user.jadwal.index', ['target' => 'ibu_hamil'])); ?>" 
               class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filterTarget == 'ibu_hamil' ? 'bg-pink-500 text-white border-pink-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-pink-300 hover:bg-pink-50'); ?>">
                <i class="fas fa-female <?php echo e($filterTarget == 'ibu_hamil' ? 'text-white' : 'text-pink-500'); ?>"></i> Ibu Hamil
                <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filterTarget == 'ibu_hamil' ? 'bg-pink-400 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($summary['ibu_hamil']); ?></span>
            </a>
        <?php endif; ?>

        <?php if(in_array('remaja', $hakAkses)): ?>
            <a href="<?php echo e(route('user.jadwal.index', ['target' => 'remaja'])); ?>" 
               class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filterTarget == 'remaja' ? 'bg-indigo-500 text-white border-indigo-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50'); ?>">
                <i class="fas fa-user-graduate <?php echo e($filterTarget == 'remaja' ? 'text-white' : 'text-indigo-500'); ?>"></i> Remaja
                <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filterTarget == 'remaja' ? 'bg-indigo-400 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($summary['remaja']); ?></span>
            </a>
        <?php endif; ?>

        <?php if(in_array('lansia', $hakAkses)): ?>
            <a href="<?php echo e(route('user.jadwal.index', ['target' => 'lansia'])); ?>" 
               class="whitespace-nowrap flex items-center gap-2 px-5 py-2.5 rounded-xl border font-bold text-xs transition-all <?php echo e($filterTarget == 'lansia' ? 'bg-orange-500 text-white border-orange-500 shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-orange-300 hover:bg-orange-50'); ?>">
                <i class="fas fa-wheelchair <?php echo e($filterTarget == 'lansia' ? 'text-white' : 'text-orange-500'); ?>"></i> Lansia
                <span class="px-2 py-0.5 rounded-md text-[10px] <?php echo e($filterTarget == 'lansia' ? 'bg-orange-400 text-white' : 'bg-slate-100 text-slate-500'); ?>"><?php echo e($summary['lansia']); ?></span>
            </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $jadwalKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $tgl = \Carbon\Carbon::parse($jadwal->tanggal);
                $isHariIni = $tgl->isToday();
                $isBesok = $tgl->isTomorrow();
                $isTerlewat = $tgl->isPast() && !$isHariIni;
                
                // Styling warna berdasarkan target peserta
                $targetColor = 'text-teal-600 bg-teal-50';
                $targetLabel = 'Umum / Semua';
                if($jadwal->target_peserta == 'balita') { $targetColor = 'text-sky-600 bg-sky-50'; $targetLabel = 'Posyandu Balita'; }
                if($jadwal->target_peserta == 'ibu_hamil') { $targetColor = 'text-pink-600 bg-pink-50'; $targetLabel = 'Ibu Hamil'; }
                if($jadwal->target_peserta == 'remaja') { $targetColor = 'text-indigo-600 bg-indigo-50'; $targetLabel = 'Posyandu Remaja'; }
                if($jadwal->target_peserta == 'lansia') { $targetColor = 'text-orange-600 bg-orange-50'; $targetLabel = 'Posyandu Lansia'; }
            ?>

            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-all flex flex-col relative group <?php echo e($isTerlewat ? 'opacity-70 grayscale-[30%]' : ''); ?>">
                
                <?php if($isHariIni): ?>
                    <div class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl z-10 shadow-sm">
                        HARI INI
                    </div>
                <?php elseif($isBesok): ?>
                    <div class="absolute top-0 right-0 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl z-10 shadow-sm">
                        BESOK
                    </div>
                <?php elseif($isTerlewat): ?>
                    <div class="absolute top-0 right-0 bg-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-bl-xl z-10">
                        Selesai
                    </div>
                <?php endif; ?>

                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="w-16 h-16 rounded-2xl <?php echo e($isHariIni ? 'bg-teal-500 text-white shadow-md' : ($isTerlewat ? 'bg-slate-100 text-slate-400' : 'bg-slate-50 text-slate-700 border border-slate-200')); ?> flex flex-col items-center justify-center shrink-0">
                            <span class="text-[11px] font-black uppercase tracking-wider"><?php echo e($tgl->translatedFormat('M')); ?></span>
                            <span class="text-2xl font-black leading-none mt-0.5"><?php echo e($tgl->format('d')); ?></span>
                        </div>
                        
                        <div>
                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider mb-2 <?php echo e($targetColor); ?>"><?php echo e($targetLabel); ?></span>
                            <h3 class="text-base font-black text-slate-800 leading-tight group-hover:text-teal-600 transition-colors"><?php echo e($jadwal->judul); ?></h3>
                        </div>
                    </div>

                    <div class="space-y-3 mt-auto pt-4 border-t border-slate-100">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="far fa-clock text-[11px]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</p>
                                <p class="text-xs font-bold text-slate-700 mt-0.5"><?php echo e(\Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')); ?> - <?php echo e($jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') : 'Selesai'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="fas fa-map-marker-alt text-[11px]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi / Tempat</p>
                                <p class="text-xs font-bold text-slate-700 mt-0.5"><?php echo e($jadwal->lokasi); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl mb-4 shadow-sm">
                    <i class="far fa-calendar-times"></i>
                </div>
                <h3 class="text-lg font-black text-slate-700 mb-1">Jadwal Kosong</h3>
                <p class="text-sm font-medium text-slate-500 text-center max-w-sm leading-relaxed">Belum ada agenda Posyandu untuk kategori yang Anda pilih saat ini.</p>
                <?php if($filterTarget != 'semua'): ?>
                    <a href="<?php echo e(route('user.jadwal.index')); ?>" class="mt-4 text-xs font-bold text-teal-600 hover:text-teal-700 bg-teal-50 px-4 py-2 rounded-xl">Lihat Semua Jadwal</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($jadwalKegiatan->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($jadwalKegiatan->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/jadwal/index.blade.php ENDPATH**/ ?>