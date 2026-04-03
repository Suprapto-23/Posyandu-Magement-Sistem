
<?php $__env->startSection('title', 'Detail Ibu Hamil'); ?>
<?php $__env->startSection('page-name', 'Detail Profil'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity:0; animation:slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .progress-bar { height:10px; border-radius:9999px; background:#fce7f3; overflow:hidden; }
    .progress-fill { height:100%; border-radius:9999px; background:linear-gradient(to right,#f9a8d4,#ec4899); transition:width 1s ease; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto animate-slide-up">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>"
               class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-black text-slate-900">Profil Ibu Hamil</h1>
        </div>
        <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $ibuHamil->id)); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-extrabold text-sm rounded-xl hover:bg-amber-600 shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit"></i> Edit Data
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        
        <div class="lg:col-span-4 space-y-5">

            
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-b from-pink-500 to-rose-600 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px"></div>
                    <div class="w-24 h-24 mx-auto bg-white rounded-[20px] shadow-xl flex items-center justify-center text-pink-500 text-5xl font-black border-4 border-pink-200/30 relative z-10">
                        <?php echo e(strtoupper(substr($ibuHamil->nama_lengkap,0,1))); ?>

                    </div>
                    <h2 class="text-xl font-extrabold text-white mt-4 relative z-10"><?php echo e($ibuHamil->nama_lengkap); ?></h2>
                    <?php if($ibuHamil->kode_hamil): ?>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-pink-900/30 text-pink-50 text-xs font-bold rounded-lg mt-2 relative z-10 border border-white/10">
                        <i class="fas fa-id-card"></i> <?php echo e($ibuHamil->kode_hamil); ?>

                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-6 space-y-3">
                    <?php if($ibuHamil->nama_suami): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suami</span>
                        <span class="text-sm font-bold text-slate-800"><?php echo e($ibuHamil->nama_suami); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->nik): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">NIK</span>
                        <span class="text-sm font-mono font-bold text-slate-800"><?php echo e($ibuHamil->nik); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->telepon_ortu): ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon</span>
                        <span class="text-sm font-bold text-slate-800"><?php echo e($ibuHamil->telepon_ortu); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->golongan_darah): ?>
                    <div class="flex items-center justify-between p-3 bg-rose-50 rounded-xl border border-rose-100">
                        <span class="text-xs font-bold text-rose-400 uppercase tracking-wider">Gol. Darah</span>
                        <span class="text-lg font-black text-rose-600"><?php echo e($ibuHamil->golongan_darah); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->alamat): ?>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="text-sm font-bold text-slate-800 leading-snug"><?php echo e($ibuHamil->alamat); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->riwayat_penyakit): ?>
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl">
                        <p class="text-[10px] font-extrabold text-amber-500 uppercase tracking-widest mb-1">Riwayat Penyakit</p>
                        <p class="text-sm font-bold text-amber-800"><?php echo e($ibuHamil->riwayat_penyakit); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
                <h6 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Data Fisik (Kader)</h6>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-3 bg-slate-50 rounded-xl text-center">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">BB</p>
                        <p class="text-lg font-black text-slate-800"><?php echo e($ibuHamil->berat_badan ?? '-'); ?> <span class="text-xs font-medium text-slate-400">kg</span></p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl text-center">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">TB</p>
                        <p class="text-lg font-black text-slate-800"><?php echo e($ibuHamil->tinggi_badan ?? '-'); ?> <span class="text-xs font-medium text-slate-400">cm</span></p>
                    </div>
                </div>
                <?php if($ibuHamil->imt): ?>
                <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                    <p class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-widest mb-0.5">IMT</p>
                    <p class="text-2xl font-black text-emerald-700"><?php echo e($ibuHamil->imt); ?></p>
                    <?php
                        $imt = $ibuHamil->imt;
                        $imtKat = $imt < 18.5 ? 'Kurus' : ($imt < 25 ? 'Normal' : ($imt < 27 ? 'Gemuk Ringan' : 'Obesitas'));
                    ?>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5"><?php echo e($imtKat); ?></p>
                </div>
                <?php else: ?>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center">
                    <p class="text-sm text-slate-400 font-medium">IMT belum tersedia</p>
                    <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $ibuHamil->id)); ?>" class="text-xs text-pink-600 font-bold mt-0.5 inline-block hover:underline">Isi BB/TB →</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="lg:col-span-8 space-y-5">

            
            <?php
                $minggu   = $ibuHamil->usia_kehamilan;
                $trimNo   = $ibuHamil->trimester_angka;
                $sisaHari = $ibuHamil->sisa_hari;
                $pct      = $minggu ? min(100, round($minggu / 40 * 100)) : 0;
                $trimColor = match($trimNo) { 1=>'sky', 2=>'violet', 3=>'rose', default=>'slate' };
            ?>
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 md:p-8">
                <h3 class="font-extrabold text-slate-800 text-base mb-5 flex items-center gap-2">
                    <i class="fas fa-baby-carriage text-pink-400"></i> Status Kehamilan
                </h3>

                <?php if($minggu !== null): ?>
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex-1 min-w-[120px] p-4 bg-<?php echo e($trimColor); ?>-50 border border-<?php echo e($trimColor); ?>-100 rounded-2xl text-center">
                        <p class="text-3xl font-black text-<?php echo e($trimColor); ?>-600"><?php echo e($minggu); ?></p>
                        <p class="text-[10px] font-extrabold text-<?php echo e($trimColor); ?>-400 uppercase tracking-widest mt-0.5">Minggu</p>
                    </div>
                    <div class="flex-1 min-w-[120px] p-4 bg-pink-50 border border-pink-100 rounded-2xl text-center">
                        <p class="text-lg font-black text-pink-600"><?php echo e($ibuHamil->trimester); ?></p>
                        <p class="text-[10px] font-extrabold text-pink-400 uppercase tracking-widest mt-0.5">Status</p>
                    </div>
                    <?php if($sisaHari !== null): ?>
                    <div class="flex-1 min-w-[120px] p-4 <?php echo e($sisaHari <= 30 ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100'); ?> border rounded-2xl text-center">
                        <p class="text-3xl font-black <?php echo e($sisaHari <= 30 ? 'text-amber-600' : 'text-slate-700'); ?>">
                            <?php echo e($sisaHari > 0 ? $sisaHari : '≈ 0'); ?>

                        </p>
                        <p class="text-[10px] font-extrabold <?php echo e($sisaHari <= 30 ? 'text-amber-400' : 'text-slate-400'); ?> uppercase tracking-widest mt-0.5">Hari Menuju HPL</p>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="mb-2 flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>0 minggu</span>
                    <span class="text-pink-600 font-black"><?php echo e($minggu); ?> / 40 minggu</span>
                    <span>40 minggu</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo e($pct); ?>%"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium mt-1.5 text-right"><?php echo e($pct); ?>% perjalanan kehamilan</p>

                <?php else: ?>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                    <p class="text-sm text-slate-400 font-medium">HPHT belum diisi — usia kehamilan tidak dapat dihitung</p>
                    <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $ibuHamil->id)); ?>" class="text-xs text-pink-600 font-bold mt-1 inline-block hover:underline">Lengkapi data →</a>
                </div>
                <?php endif; ?>

                <?php if($ibuHamil->hpht || $ibuHamil->hpl): ?>
                <div class="grid grid-cols-2 gap-4 mt-5">
                    <?php if($ibuHamil->hpht): ?>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">HPHT</p>
                        <p class="text-sm font-bold text-slate-800"><?php echo e($ibuHamil->hpht->translatedFormat('d F Y')); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($ibuHamil->hpl): ?>
                    <div class="p-3 bg-pink-50 border border-pink-100 rounded-xl">
                        <p class="text-[10px] font-extrabold text-pink-400 uppercase tracking-widest mb-0.5">HPL (Perkiraan Lahir)</p>
                        <p class="text-sm font-bold text-pink-800"><?php echo e($ibuHamil->hpl->translatedFormat('d F Y')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-indigo-50 border border-indigo-100 rounded-[24px] p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-indigo-900 text-sm mb-1">Pemeriksaan Mendalam oleh Bidan</h4>
                        <p class="text-xs text-indigo-700 leading-relaxed">
                            Data fisik dasar sudah dicatat oleh kader. Untuk pemeriksaan lebih lanjut seperti
                            <strong>tekanan darah, detak jantung janin, vaksin TT, USG, dan lab darah</strong>
                            akan dilakukan oleh bidan pada halaman Pemeriksaan Medis.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/ibu-hamil/show.blade.php ENDPATH**/ ?>