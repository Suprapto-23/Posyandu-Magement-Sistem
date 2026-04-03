

<?php $__env->startSection('title', 'Data Ibu Hamil'); ?>
<?php $__env->startSection('page-name', 'Database Ibu Hamil'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity:0; animation:slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .search-input { width:100%; background:#f8fafc; border:2px solid #e2e8f0; color:#0f172a; font-size:.875rem;
        border-radius:1rem; padding:.75rem 1rem .75rem 2.75rem; outline:none; transition:all .3s; font-weight:600; }
    .search-input:focus { background:#fff; border-color:#ec4899; box-shadow:0 4px 12px -3px rgba(236,72,153,.15); }
    .custom-scrollbar::-webkit-scrollbar{height:8px}
    .custom-scrollbar::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
    .stacked-item { transition:all .3s cubic-bezier(.4,0,.2,1); }
    .stacked-item:hover { transform:scale(1.01) translateY(-2px); box-shadow:0 12px 24px -5px rgba(0,0,0,.07); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto animate-slide-up">

    
    <div class="bg-gradient-to-br from-pink-500 via-rose-500 to-pink-700 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(236,72,153,0.4)] flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:24px 24px"></div>
        <div class="absolute -right-8 -bottom-8 opacity-10 text-[130px] pointer-events-none"><i class="fas fa-baby"></i></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-20 h-20 rounded-[20px] bg-white/20 border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg">
                <i class="fas fa-heart"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-2 uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span> Data Aktif
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Database Ibu Hamil</h1>
                <p class="text-pink-100 text-sm font-medium mt-1">Pantau kondisi kehamilan — trimester, HPL, dan data fisik.</p>
            </div>
        </div>
        <a href="<?php echo e(route('kader.data.ibu-hamil.create')); ?>"
           class="relative z-10 inline-flex items-center gap-2 px-7 py-3.5 bg-white text-pink-600 font-black text-sm rounded-xl hover:bg-pink-50 shadow-lg hover:-translate-y-1 transition-all uppercase tracking-widest w-full md:w-auto justify-center">
            <i class="fas fa-plus"></i> Tambah Data
        </a>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <?php
            $statItems = [
                ['label'=>'Total','val'=>$stats['total'],'color'=>'slate','icon'=>'fa-users'],
                ['label'=>'Trimester I','val'=>$stats['trimester1'],'color'=>'sky','icon'=>'fa-seedling'],
                ['label'=>'Trimester II','val'=>$stats['trimester2'],'color'=>'violet','icon'=>'fa-leaf'],
                ['label'=>'Trimester III','val'=>$stats['trimester3'],'color'=>'rose','icon'=>'fa-tree'],
            ];
        ?>
        <?php $__currentLoopData = $statItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white border border-slate-200 rounded-2xl p-4 text-center shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-<?php echo e($s['color']); ?>-50 text-<?php echo e($s['color']); ?>-600 flex items-center justify-center mx-auto mb-2 text-sm">
                <i class="fas <?php echo e($s['icon']); ?>"></i>
            </div>
            <p class="text-2xl font-black text-slate-800"><?php echo e($s['val']); ?></p>
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mt-0.5"><?php echo e($s['label']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($stats['hampir_lahir'] > 0): ?>
    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3">
        <i class="fas fa-bell text-amber-500 text-lg"></i>
        <p class="font-bold text-amber-800 text-sm">
            <strong><?php echo e($stats['hampir_lahir']); ?> ibu hamil</strong> diperkirakan melahirkan dalam 30 hari ke depan.
            <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>?filter=hampir_lahir" class="underline font-black ml-1">Lihat →</a>
        </p>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <form action="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3 flex-1">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Cari nama ibu, NIK, atau nama suami..." class="search-input">
                <?php if($filter): ?> <input type="hidden" name="filter" value="<?php echo e($filter); ?>"> <?php endif; ?>
            </div>
            <select name="filter" onchange="this.form.submit()"
                    class="border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-pink-400 transition-colors">
                <option value="semua" <?php echo e($filter=='semua'?'selected':''); ?>>Semua</option>
                <option value="aktif" <?php echo e($filter=='aktif'?'selected':''); ?>>Masih Hamil</option>
                <option value="hampir_lahir" <?php echo e($filter=='hampir_lahir'?'selected':''); ?>>Hampir Lahir (30hr)</option>
            </select>
        </form>
    </div>

    
    <div class="flex flex-col gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $ibuHamils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ibu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $minggu   = $ibu->usia_kehamilan;
            $trimNo   = $ibu->trimester_angka;
            $sisaHari = $ibu->sisa_hari;
            $trimColor = match($trimNo) { 1=>'sky', 2=>'violet', 3=>'rose', default=>'slate' };
            $trimLabel = match($trimNo) { 1=>'Trimester I', 2=>'Trimester II', 3=>'Trimester III', default=>'-' };
        ?>
        <div class="stacked-item bg-white rounded-[24px] border border-slate-200 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:border-pink-200">
            <div class="flex items-center gap-4">
                
                <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-500 font-black text-lg flex items-center justify-center border border-pink-100 shrink-0">
                    <?php echo e(strtoupper(substr($ibu->nama_lengkap,0,1))); ?>

                </div>
                <div>
                    <p class="font-black text-slate-800 text-[15px] group-hover:text-pink-600 transition-colors"><?php echo e($ibu->nama_lengkap); ?></p>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-[11px] font-bold text-slate-400">
                        <?php if($ibu->nama_suami): ?>
                        <span><i class="fas fa-user-tie mr-1 text-slate-300"></i><?php echo e($ibu->nama_suami); ?></span>
                        <?php endif; ?>
                        <?php if($ibu->nik): ?>
                        <span><i class="fas fa-id-card mr-1 text-slate-300"></i><?php echo e($ibu->nik); ?></span>
                        <?php endif; ?>
                        <?php if($ibu->telepon_ortu): ?>
                        <span><i class="fas fa-phone mr-1 text-slate-300"></i><?php echo e($ibu->telepon_ortu); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t sm:border-0 border-slate-100 pt-3 sm:pt-0 justify-between sm:justify-end">

                
                <?php if($trimNo): ?>
                <span class="px-3 py-1.5 rounded-xl text-[11px] font-black bg-<?php echo e($trimColor); ?>-50 text-<?php echo e($trimColor); ?>-700 border border-<?php echo e($trimColor); ?>-100">
                    <?php echo e($trimLabel); ?> · <?php echo e($minggu); ?> mgg
                </span>
                <?php endif; ?>

                
                <?php if($ibu->hpl): ?>
                <div class="text-center">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">HPL</p>
                    <p class="text-sm font-black <?php echo e($sisaHari !== null && $sisaHari <= 30 ? 'text-amber-600' : 'text-slate-700'); ?>">
                        <?php echo e($ibu->hpl->format('d M Y')); ?>

                    </p>
                    <?php if($sisaHari !== null && $sisaHari > 0): ?>
                    <p class="text-[10px] text-slate-400 font-medium"><?php echo e($sisaHari); ?> hari lagi</p>
                    <?php elseif($sisaHari !== null && $sisaHari <= 0): ?>
                    <p class="text-[10px] text-amber-500 font-black">Perkiraan sudah lahir</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                
                <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                    <a href="<?php echo e(route('kader.data.ibu-hamil.show', $ibu->id)); ?>"
                       class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-pink-600 hover:border-pink-300 hover:bg-pink-50 shadow-sm transition-all" title="Detail">
                        <i class="fas fa-folder-open text-sm"></i>
                    </a>
                    <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $ibu->id)); ?>"
                       class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-300 hover:bg-amber-50 shadow-sm transition-all" title="Edit">
                        <i class="fas fa-pen text-sm"></i>
                    </a>
                    <form action="<?php echo e(route('kader.data.ibu-hamil.destroy', $ibu->id)); ?>" method="POST"
                          onsubmit="return confirm('Yakin hapus data ini?')" class="inline-block">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 shadow-sm transition-all" title="Hapus">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center text-pink-300 mx-auto mb-4 text-3xl"><i class="fas fa-heart"></i></div>
            <h4 class="font-black text-slate-700 text-lg">Belum Ada Data Ibu Hamil</h4>
            <p class="text-sm text-slate-400 mt-1">Tambahkan data ibu hamil dengan menekan tombol di atas.</p>
            <a href="<?php echo e(route('kader.data.ibu-hamil.create')); ?>"
               class="inline-flex items-center gap-2 mt-5 px-6 py-2.5 bg-pink-500 text-white font-black text-sm rounded-xl hover:bg-pink-600 transition-all shadow-sm">
                <i class="fas fa-plus"></i> Tambah Sekarang
            </a>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if($ibuHamils->hasPages()): ?>
    <div class="mt-5"><?php echo e($ibuHamils->appends(request()->query())->links()); ?></div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/ibu-hamil/index.blade.php ENDPATH**/ ?>