

<?php $__env->startSection('title', 'Register Imunisasi Terpadu'); ?>
<?php $__env->startSection('page-name', 'Log Vaksinasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.005]; box-shadow: 0 10px 30px -10px rgba(6, 182, 212, 0.15); border-radius: 16px; z-index: 10; position: relative; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto space-y-6 lg:space-y-8 animate-slide-up pb-10">

    
    <div class="bg-gradient-to-br from-cyan-600 via-cyan-700 to-blue-800 rounded-[32px] p-8 md:p-10 text-white relative overflow-hidden shadow-[0_15px_40px_rgba(6,182,212,0.25)]">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-2">
                    <span class="w-2 h-2 rounded-full bg-cyan-300 animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-100">Layanan Preventif</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black font-poppins tracking-tight">Data Vaksinasi <span class="text-cyan-300">&</span> Imunisasi</h1>
                <p class="text-cyan-50/70 text-[14px] font-medium max-w-xl leading-relaxed">Kelola dan pantau riwayat imunisasi dasar balita serta vaksinasi ibu hamil secara terpusat untuk mewujudkan desa sehat.</p>
            </div>
            
            <a href="<?php echo e(route('bidan.imunisasi.create')); ?>" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-cyan-700 rounded-[20px] font-black shadow-xl hover:bg-cyan-50 transition-all active:scale-95 shrink-0 group">
                <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-poppins">Catat Injeksi Baru</span>
            </a>
        </div>
        <i class="fas fa-syringe absolute -bottom-10 -right-10 text-[240px] text-white/5 -rotate-12"></i>
    </div>

    
    <div class="bg-white rounded-[32px] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <h2 class="text-[18px] font-black text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shadow-inner">
                    <i class="fas fa-book-medical"></i>
                </div>
                Log Aktivitas Vaksinasi
            </h2>
            
            <form action="<?php echo e(route('bidan.imunisasi.index')); ?>" method="GET" class="relative group max-w-md w-full">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" placeholder="Cari Nama Warga atau NIK..." 
                       class="w-full bg-slate-50 border-2 border-slate-100 rounded-[18px] py-3.5 pl-12 pr-6 text-[13.5px] font-bold text-slate-700 outline-none focus:bg-white focus:border-cyan-500/30 focus:ring-4 focus:ring-cyan-500/5 transition-all">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-5 px-8 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Informasi Pasien</th>
                        <th class="py-5 px-8 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Vaksin & Dosis</th>
                        <th class="py-5 px-8 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Jadwal Pemberian</th>
                        <th class="py-5 px-8 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $imunisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $pasien = $imu->kunjungan->pasien ?? null;
                        $nama = $pasien->nama_lengkap ?? 'Anonim';
                        $kategoriRaw = strtolower(class_basename($imu->kunjungan->pasien_type ?? ''));
                        
                        // Perbaikan: Class Tailwind utuh agar tidak terhapus saat build
                        if($kategoriRaw == 'balita') { 
                            $theme = 'bg-sky-50 text-sky-600 border-sky-100'; 
                            $badgeTheme = 'text-sky-600 border-sky-200';
                            $nIco = 'fa-baby'; $kat = 'Balita'; 
                        } elseif(in_array($kategoriRaw, ['ibuhamil', 'ibu_hamil', 'bumil'])) { 
                            $theme = 'bg-pink-50 text-pink-600 border-pink-100'; 
                            $badgeTheme = 'text-pink-600 border-pink-200';
                            $nIco = 'fa-female'; $kat = 'Ibu Hamil'; 
                        } else { 
                            $theme = 'bg-slate-50 text-slate-600 border-slate-100'; 
                            $badgeTheme = 'text-slate-600 border-slate-200';
                            $nIco = 'fa-user'; $kat = 'Umum';
                        }
                    ?>

                    <tr class="table-row-hover transition-all duration-300 border-b border-slate-50 last:border-0 group">
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[16px] <?php echo e($theme); ?> flex items-center justify-center shrink-0 border shadow-inner group-hover:scale-110 transition-transform">
                                    <i class="fas <?php echo e($nIco); ?> text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14.5px] mb-1 group-hover:text-cyan-600 transition-colors"><?php echo e($nama); ?></p>
                                    <span class="text-[9px] font-black uppercase tracking-widest bg-white border px-2 py-0.5 rounded shadow-sm <?php echo e($badgeTheme); ?>"><?php echo e($kat); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-bold text-[14px]"><?php echo e($imu->vaksin); ?></span>
                                <span class="text-slate-400 text-[11px] font-medium italic">Dosis: <?php echo e($imu->dosis); ?></span>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                    <i class="far fa-calendar-check"></i>
                                </div>
                                <span class="text-slate-700 font-black text-[13px] font-poppins">
                                    <?php echo e(\Carbon\Carbon::parse($imu->tanggal_imunisasi)->translatedFormat('d F Y')); ?>

                                </span>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('bidan.imunisasi.show', $imu->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 hover:bg-cyan-600 hover:text-white transition-all shadow-sm group/btn">
                                    <i class="fas fa-eye text-[14px]"></i>
                                </a>
                                <form action="<?php echo e(route('bidan.imunisasi.destroy', $imu->id)); ?>" method="POST" onsubmit="return confirm('Hapus data imunisasi ini?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-[14px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-24 text-center text-slate-400">
                            <i class="fas fa-syringe text-5xl mb-4 opacity-20"></i>
                            <p class="font-bold">Belum ada riwayat imunisasi.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/index.blade.php ENDPATH**/ ?>