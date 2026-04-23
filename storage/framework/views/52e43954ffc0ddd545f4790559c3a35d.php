

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="mb-8">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-sky-50 rounded-full shadow-sm border border-sky-100 mb-4">
            <i class="fas fa-baby-carriage text-sky-500"></i>
            <span class="text-[11px] font-black tracking-widest uppercase text-sky-700">Tumbuh Kembang Anak</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Kesehatan Bayi & Balita 👶</h1>
        <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Pilih profil anak Anda untuk melihat Kartu Menuju Sehat (KMS) digital dan riwayat pengukuran terverifikasi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $dataBalita; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Tentukan warna berdasarkan kategori medis yang sudah kita buat di Controller
                $isBayi = $anak->kategori_medis == 'Bayi';
                $badgeBg = $isBayi ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700';
                $icon = $anak->jenis_kelamin == 'L' ? 'fa-child text-blue-500' : 'fa-child text-pink-500';
            ?>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-sky-200 transition-all p-6 flex flex-col group relative overflow-hidden">
                
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-sky-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                
                <div class="relative z-10 flex items-start justify-between mb-5">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-3xl shadow-inner shrink-0">
                        <i class="fas <?php echo e($icon); ?>"></i>
                    </div>
                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg <?php echo e($badgeBg); ?>">
                        <?php echo e($anak->kategori_medis); ?> (<?php echo e($anak->usia_tahun); ?> Thn <?php echo e($anak->usia_bulan); ?> Bln)
                    </span>
                </div>

                <div class="relative z-10 flex-1">
                    <h3 class="text-lg font-black text-slate-800 line-clamp-1 group-hover:text-sky-600 transition-colors"><?php echo e($anak->nama_lengkap); ?></h3>
                    <p class="text-xs font-medium text-slate-500 mt-1"><i class="far fa-calendar-alt mr-1"></i> Lhr: <?php echo e(\Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d M Y')); ?></p>
                    
                    <div class="mt-4 p-4 bg-sky-50/50 rounded-2xl border border-sky-100">
                        <p class="text-[10px] font-black text-sky-600 uppercase tracking-wider mb-2">Pengukuran Terakhir</p>
                        <?php
                            $terakhir = $anak->riwayatPemeriksaan->first();
                        ?>
                        
                        <?php if($terakhir): ?>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-black text-slate-800"><?php echo e($terakhir->berat_badan ?? '-'); ?> <span class="text-[10px] text-slate-500 font-medium">kg</span></p>
                                    <p class="text-[10px] font-bold text-slate-400">Berat</p>
                                </div>
                                <div class="w-px h-6 bg-sky-200"></div>
                                <div>
                                    <p class="text-sm font-black text-slate-800"><?php echo e($terakhir->tinggi_badan ?? '-'); ?> <span class="text-[10px] text-slate-500 font-medium">cm</span></p>
                                    <p class="text-[10px] font-bold text-slate-400">Tinggi</p>
                                </div>
                                <div class="w-px h-6 bg-sky-200"></div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded"><i class="fas fa-check"></i> Valid</span>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-xs font-medium text-slate-500 italic">Belum ada data tervalidasi</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="relative z-10 mt-5 pt-5 border-t border-slate-100">
                    <a href="<?php echo e(route('user.balita.show', $anak->id)); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-sky-500 text-white text-xs font-bold rounded-xl hover:bg-sky-600 transition-colors shadow-sm">
                        Buka Buku KIA / KMS <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-sky-50 text-sky-400 rounded-full flex items-center justify-center text-4xl mb-4 shadow-sm">
                    <i class="fas fa-baby-carriage"></i>
                </div>
                <h3 class="text-xl font-black text-slate-700 mb-2">Belum Ada Data Anak</h3>
                <p class="text-sm font-medium text-slate-500 text-center max-w-md leading-relaxed">
                    <?php echo e($pesan ?? 'Kami tidak menemukan data bayi atau balita yang terhubung dengan NIK Anda. Silakan hubungi Kader Posyandu untuk pendaftaran.'); ?>

                </p>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="mt-6 text-xs font-bold text-sky-600 bg-sky-50 px-5 py-2.5 rounded-xl hover:bg-sky-100 transition-colors">Cek NIK di Profil Anda</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/balita/index.blade.php ENDPATH**/ ?>