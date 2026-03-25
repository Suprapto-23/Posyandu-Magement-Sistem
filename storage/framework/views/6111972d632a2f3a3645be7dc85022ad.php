
<?php $__env->startSection('title', 'Manajemen User Warga'); ?>
<?php $__env->startSection('page-name', 'Data Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    
    <div class="bg-gradient-to-br from-obsidian-900 to-slate-800 rounded-[32px] p-10 mb-8 relative overflow-hidden shadow-[0_20px_40px_-10px_rgba(15,23,42,0.4)] border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-amber-500/20 blur-[80px] rounded-full pointer-events-none transition-all duration-700 group-hover:bg-amber-500/30"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 text-amber-400 text-[11px] font-black px-4 py-1.5 rounded-full mb-4 uppercase tracking-widest shadow-sm">
                <i class="fas fa-users"></i> Manajemen Data
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-3 font-poppins tracking-tight">Daftar Akun Warga</h2>
            <p class="text-slate-400 text-sm font-medium max-w-lg mx-auto mb-6">Kelola seluruh akun warga Posyandu. Gunakan NIK 16 digit yang valid sebagai kredensial utama sistem.</p>
            
            <a href="<?php echo e(route('admin.users.create')); ?>" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-obsidian-900 font-bold px-6 py-3 rounded-xl transition-all shadow-[0_4px_20px_rgba(245,158,11,0.4)] hover:shadow-[0_4px_25px_rgba(245,158,11,0.6)] hover:-translate-y-1 smooth-route">
                <i class="fas fa-plus"></i> Daftarkan Warga Baru
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-8 flex items-center justify-center text-center gap-3 text-emerald-700 font-bold shadow-sm">
        <i class="fas fa-check-circle text-xl"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <h3 class="text-lg font-black text-obsidian-900 font-poppins flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white text-obsidian-900 border border-slate-200 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-list"></i></div>
                Direktori Warga
            </h3>
            
            <form method="GET" class="flex relative w-full sm:w-auto">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari NIK / Nama..." class="w-full sm:w-72 bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all shadow-sm">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">
                        <th class="py-4 px-6">Informasi Warga</th>
                        <th class="py-4 px-6">NIK KTP</th>
                        <th class="py-4 px-6">Kontak / No. Telp</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors text-center">
                        
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center md:justify-start gap-3 w-max mx-auto">
                                <div class="w-10 h-10 rounded-full bg-obsidian-900 text-amber-500 flex items-center justify-center font-black shadow-sm shrink-0">
                                    <?php echo e(strtoupper(substr($u->profile->full_name ?? $u->name, 0, 1))); ?>

                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-slate-900"><?php echo e($u->profile->full_name ?? $u->name); ?></div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-envelope text-slate-400"></i> <?php echo e($u->email); ?>

                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="py-4 px-6">
                            <span class="font-mono text-xs font-bold bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 tracking-wider">
                                <?php echo e($u->nik ?? $u->profile?->nik ?? '-'); ?>

                            </span>
                        </td>

                        
                        <td class="py-4 px-6 text-slate-500">
                            <?php echo e($u->profile?->telepon ?? '-'); ?>

                        </td>

                        
                        <td class="py-4 px-6">
                            <?php if($u->status === 'active'): ?>
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                            <?php else: ?>
                                <span class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('admin.users.show', $u->id)); ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all smooth-route" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo e(route('admin.users.edit', $u->id)); ?>" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all smooth-route" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="<?php echo e(route('admin.users.destroy', $u->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Hapus data warga ini?')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 border border-slate-100"><i class="fas fa-users-slash text-2xl"></i></div>
                            <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest">Tidak ada data warga</h4>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    
    <?php if(isset($users) && $users->hasPages()): ?>
    <div class="mt-6 flex justify-center">
        <?php echo e($users->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/admin/users/index.blade.php ENDPATH**/ ?>