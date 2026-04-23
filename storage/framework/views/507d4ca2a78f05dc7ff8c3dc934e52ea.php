

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-teal-50 rounded-full shadow-sm border border-teal-100 mb-4">
                <i class="fas fa-folder-open text-teal-500"></i>
                <span class="text-[11px] font-black tracking-widest uppercase text-teal-700">Arsip Kesehatan Terpadu</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Rekam Medis Keluarga 🏥</h1>
            <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Pusat arsip seluruh hasil pemeriksaan Anda dan keluarga yang telah divalidasi resmi oleh Bidan Posyandu.</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 mb-8">
        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4"><i class="fas fa-users mr-1.5"></i> Anggota Keluarga Terhubung</h3>
        
        <div class="flex flex-wrap gap-3">
            <?php $__empty_1 = true; $__currentLoopData = $targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $kat = $target['kat'] ?? $target['kategori'] ?? 'umum';
                    $bgBadge = 'bg-slate-50 text-slate-600 border-slate-200';
                    $icon = 'fa-user';
                    
                    if($kat == 'balita') { $bgBadge = 'bg-sky-50 text-sky-700 border-sky-100'; $icon = 'fa-baby'; }
                    if($kat == 'ibu_hamil' || $kat == 'bumil') { $bgBadge = 'bg-pink-50 text-pink-700 border-pink-100'; $icon = 'fa-female'; }
                    if($kat == 'remaja') { $bgBadge = 'bg-indigo-50 text-indigo-700 border-indigo-100'; $icon = 'fa-user-graduate'; }
                    if($kat == 'lansia') { $bgBadge = 'bg-orange-50 text-orange-700 border-orange-100'; $icon = 'fa-wheelchair'; }
                ?>
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl border <?php echo e($bgBadge); ?>">
                    <i class="fas <?php echo e($icon); ?> opacity-70"></i>
                    <span class="text-xs font-bold"><?php echo e($target['nama']); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs font-medium text-slate-500 italic">Belum ada anggota keluarga yang terhubung dengan NIK ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="space-y-5">
        <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Mencari nama dan kategori pasien dari array targets
                $pasien = collect($targets)->first(function($t) use ($item) {
                    $kat = $t['kat'] ?? $t['kategori'] ?? '';
                    return $t['id'] == $item->pasien_id && $kat == $item->kategori_pasien;
                });
                
                $namaPasien = $pasien ? $pasien['nama'] : 'Pasien Tidak Diketahui';
                $katPasien = $pasien ? ($pasien['kat'] ?? $pasien['kategori'] ?? 'umum') : $item->kategori_pasien;

                // Set warna dan ikon berdasarkan kategori
                $cardBorder = 'border-slate-100 hover:border-slate-300';
                $iconBg = 'bg-slate-50 text-slate-500';
                $icon = 'fa-file-medical';
                $kategoriLabel = 'Umum';

                if($katPasien == 'balita') { 
                    $cardBorder = 'border-sky-100 hover:border-sky-300 shadow-[0_4px_15px_-5px_rgba(14,165,233,0.1)]'; 
                    $iconBg = 'bg-sky-50 text-sky-500'; 
                    $icon = 'fa-baby'; 
                    $kategoriLabel = 'Pemeriksaan Balita'; 
                }
                elseif($katPasien == 'ibu_hamil' || $katPasien == 'bumil') { 
                    $cardBorder = 'border-pink-100 hover:border-pink-300 shadow-[0_4px_15px_-5px_rgba(236,72,153,0.1)]'; 
                    $iconBg = 'bg-pink-50 text-pink-500'; 
                    $icon = 'fa-female'; 
                    $kategoriLabel = 'Pemeriksaan Ibu Hamil'; 
                }
                elseif($katPasien == 'remaja') { 
                    $cardBorder = 'border-indigo-100 hover:border-indigo-300 shadow-[0_4px_15px_-5px_rgba(99,102,241,0.1)]'; 
                    $iconBg = 'bg-indigo-50 text-indigo-500'; 
                    $icon = 'fa-user-graduate'; 
                    $kategoriLabel = 'Cek Fisik Remaja'; 
                }
                elseif($katPasien == 'lansia') { 
                    $cardBorder = 'border-orange-100 hover:border-orange-300 shadow-[0_4px_15px_-5px_rgba(249,115,22,0.1)]'; 
                    $iconBg = 'bg-orange-50 text-orange-500'; 
                    $icon = 'fa-wheelchair'; 
                    $kategoriLabel = 'Pemantauan Lansia'; 
                }
            ?>

            <div class="bg-white rounded-[2rem] border <?php echo e($cardBorder); ?> p-6 transition-all flex flex-col lg:flex-row gap-6 relative overflow-hidden group">
                
                <div class="absolute -right-10 -bottom-10 opacity-[0.03] group-hover:scale-110 transition-transform pointer-events-none">
                    <i class="fas <?php echo e($icon); ?> text-[10rem] text-slate-800"></i>
                </div>

                <div class="lg:w-1/3 shrink-0 lg:border-r border-slate-100 lg:pr-6 relative z-10 flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl <?php echo e($iconBg); ?> flex items-center justify-center text-2xl shadow-sm">
                            <i class="fas <?php echo e($icon); ?>"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($kategoriLabel); ?></p>
                            <h3 class="text-base font-black text-slate-800"><?php echo e($namaPasien); ?></h3>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-2">
                        <i class="far fa-calendar-alt text-slate-400 w-4 text-center"></i>
                        <span class="text-sm font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($item->tanggal_periksa)->translatedFormat('l, d F Y')); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500 w-4 text-center"></i>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Verified Bidan</span>
                    </div>
                </div>

                <div class="flex-1 relative z-10">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        
                        <?php if($item->berat_badan): ?>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Berat Badan</p>
                                <p class="text-sm font-bold text-slate-800"><?php echo e($item->berat_badan); ?> <span class="text-[10px] text-slate-500 font-medium">kg</span></p>
                            </div>
                        <?php endif; ?>

                        <?php if($item->tinggi_badan): ?>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tinggi Badan</p>
                                <p class="text-sm font-bold text-slate-800"><?php echo e($item->tinggi_badan); ?> <span class="text-[10px] text-slate-500 font-medium">cm</span></p>
                            </div>
                        <?php endif; ?>

                        <?php if($item->tekanan_darah): ?>
                            <div class="bg-rose-50 p-3 rounded-xl border border-rose-100">
                                <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Tekanan Darah</p>
                                <p class="text-sm font-bold text-rose-700"><?php echo e($item->tekanan_darah); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($item->gula_darah): ?>
                            <div class="bg-sky-50 p-3 rounded-xl border border-sky-100">
                                <p class="text-[9px] font-black text-sky-500 uppercase tracking-widest mb-1">Gula Darah</p>
                                <p class="text-sm font-bold text-sky-700"><?php echo e($item->gula_darah); ?> <span class="text-[10px] text-sky-500 font-medium">mg/dL</span></p>
                            </div>
                        <?php endif; ?>

                        <?php if($item->hemoglobin || $item->hb): ?>
                            <div class="bg-violet-50 p-3 rounded-xl border border-violet-100">
                                <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Hemoglobin</p>
                                <p class="text-sm font-bold text-violet-700"><?php echo e($item->hemoglobin ?? $item->hb); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($item->tfu): ?>
                            <div class="bg-amber-50 p-3 rounded-xl border border-amber-100">
                                <p class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1">TFU</p>
                                <p class="text-sm font-bold text-amber-700"><?php echo e($item->tfu); ?> <span class="text-[10px] text-amber-600 font-medium">cm</span></p>
                            </div>
                        <?php endif; ?>

                    </div>

                    <?php if($item->keterangan || $item->status_gizi): ?>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mt-2">
                            <?php if($item->status_gizi): ?>
                                <div class="mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Status Gizi:</span>
                                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded shadow-sm"><?php echo e($item->status_gizi); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($item->keterangan): ?>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Catatan Bidan:</p>
                                <p class="text-xs font-medium text-slate-600 italic leading-relaxed">"<?php echo e($item->keterangan); ?>"</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 flex flex-col items-center justify-center text-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-4xl shadow-sm mb-5">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-black text-slate-700 mb-2">Belum Ada Rekam Medis</h3>
                <p class="text-sm font-medium text-slate-500 max-w-md leading-relaxed">
                    Kami belum menemukan riwayat pemeriksaan yang tervalidasi untuk Anda maupun anggota keluarga Anda.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <?php if($riwayat->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($riwayat->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/riwayat/index.blade.php ENDPATH**/ ?>