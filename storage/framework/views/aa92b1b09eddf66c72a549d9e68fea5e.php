
<?php $__env->startSection('title', 'Manajemen Akun Bidan'); ?>
<?php $__env->startSection('page-name', 'Data Bidan'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-pop-in { animation: popIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
</style>

<div class="max-w-6xl mx-auto space-y-8">

    
    <div class="bg-gradient-to-br from-sky-500 to-teal-500 rounded-[2.5rem] p-10 relative overflow-hidden shadow-[0_20px_40px_-10px_rgba(14,165,233,0.3)] border border-white/20 flex flex-col items-center justify-center text-center group animate-pop-in">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/10 blur-[80px] rounded-full pointer-events-none transition-all duration-700 group-hover:bg-white/20"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md border border-white/30 text-white text-[11px] font-black px-4 py-1.5 rounded-full mb-4 uppercase tracking-widest shadow-sm">
                <i class="fas fa-user-md"></i> Otoritas Medis
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-3 font-poppins tracking-tight text-shadow-sm">Daftar Akun Bidan</h2>
            <p class="text-sky-50 text-sm font-medium max-w-lg mx-auto mb-8 leading-relaxed">Kelola akses tenaga kesehatan. Bidan memiliki otoritas untuk memverifikasi data medis dan pemeriksaan lanjutan di Posyandu.</p>
            
            <a href="<?php echo e(route('admin.bidans.create')); ?>" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-sky-600 font-black px-7 py-3.5 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 smooth-route">
                <i class="fas fa-plus"></i> Tambah Bidan Baru
            </a>
        </div>
    </div>

    
    <?php if(isset($stats)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-pop-in delay-100">
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-2xl"><i class="fas fa-user-md"></i></div>
            <div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Bidan</div>
                <div class="text-3xl font-black text-slate-700 font-poppins"><?php echo e($stats['total'] ?? 0); ?></div>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center text-2xl"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="text-[10px] font-black text-teal-500 uppercase tracking-widest mb-1">Bidan Aktif</div>
                <div class="text-3xl font-black text-slate-700 font-poppins"><?php echo e($stats['aktif'] ?? 0); ?></div>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl"><i class="fas fa-ban"></i></div>
            <div>
                <div class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Nonaktif</div>
                <div class="text-3xl font-black text-slate-700 font-poppins"><?php echo e($stats['nonaktif'] ?? 0); ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
    <div class="bg-teal-50 border border-teal-200 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-center text-center gap-3 text-teal-700 font-bold shadow-sm animate-pop-in delay-100">
        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-xl"></i> <?php echo e(session('success')); ?></div>
        <?php if(session('reset_password')): ?>
            <span class="bg-white px-3 py-1 rounded-lg border border-teal-200 text-xs font-mono text-slate-700 shadow-sm">Pass Baru: <span class="text-sky-600"><?php echo e(session('reset_password')); ?></span></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden animate-pop-in delay-200">
        <div class="px-8 py-6 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="text-lg font-black text-slate-800 font-poppins flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center text-sm shadow-inner"><i class="fas fa-list"></i></div>
                Direktori Bidan
            </h3>
            
            <form method="GET" class="flex relative w-full sm:w-auto">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama / NIK..." class="w-full sm:w-72 bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:bg-white focus:border-sky-400 focus:ring-4 focus:ring-sky-500/10 outline-none transition-all shadow-sm">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">
                        <th class="py-4 px-6">Profil Bidan</th>
                        <th class="py-4 px-6">NIK KTP</th>
                        <th class="py-4 px-6">Kontak</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-600">
                    <?php $__empty_1 = true; $__currentLoopData = $bidans ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bidan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors text-center">
                        
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center md:justify-start gap-3 w-max mx-auto">
                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-black shadow-sm shrink-0 border border-sky-200/50">
                                    <?php echo e(strtoupper(substr($bidan->profile->full_name ?? $bidan->name, 0, 1))); ?>

                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-slate-800"><?php echo e($bidan->profile->full_name ?? $bidan->name); ?></div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-envelope"></i> <?php echo e($bidan->email); ?>

                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td class="py-4 px-6">
                            <span class="font-mono text-xs font-bold bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 tracking-wider">
                                <?php echo e($bidan->nik ?? $bidan->profile?->nik ?? '-'); ?>

                            </span>
                        </td>

                        
                        <td class="py-4 px-6 text-slate-500">
                            <?php echo e($bidan->profile?->telepon ?? '-'); ?>

                        </td>

                        
                        <td class="py-4 px-6">
                            <?php if($bidan->status === 'active'): ?>
                                <span class="bg-teal-50 text-teal-600 border border-teal-100 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                            <?php else: ?>
                                <span class="bg-rose-50 text-rose-500 border border-rose-100 px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('admin.bidans.show', $bidan->id)); ?>" class="w-8 h-8 rounded-lg bg-sky-50 text-sky-500 hover:bg-sky-500 hover:text-white flex items-center justify-center transition-all smooth-route" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="<?php echo e(route('admin.bidans.edit', $bidan->id)); ?>" class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 hover:bg-teal-500 hover:text-white flex items-center justify-center transition-all smooth-route" title="Edit"><i class="fas fa-edit"></i></a>
                                
                                
                                <form action="<?php echo e(route('admin.bidans.reset-password', $bidan->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" onclick="return confirm('Reset password bidan ini ke default?')" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-600 hover:text-white flex items-center justify-center transition-all" title="Reset Password"><i class="fas fa-key"></i></button>
                                </form>

                                <form action="<?php echo e(route('admin.bidans.destroy', $bidan->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Hapus data bidan ini secara permanen?')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4 border border-slate-100"><i class="fas fa-user-md text-3xl opacity-50"></i></div>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Belum Ada Data Bidan</h4>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if(isset($bidans) && $bidans->hasPages()): ?>
    <div class="mt-6 flex justify-center">
        <?php echo e($bidans->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/admin/bidans/index.blade.php ENDPATH**/ ?>