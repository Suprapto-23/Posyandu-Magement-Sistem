

<?php $__env->startSection('title', 'Kesehatan Remaja'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="animate-slide-up space-y-6 pb-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight">Ruang Remaja</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Pantau kesehatan fisik & mentalmu.</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center text-2xl shadow-sm">
            <i class="fas fa-user-graduate"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[24px] p-6 text-white shadow-[0_12px_30px_rgba(99,102,241,0.3)] relative overflow-hidden">
        <i class="fas fa-fingerprint absolute -right-4 -bottom-4 text-7xl opacity-10"></i>
        <div class="relative z-10">
            <h3 class="text-xl font-black font-poppins mb-1"><?php echo e($remaja->nama_lengkap); ?></h3>
            <p class="text-indigo-100 text-xs font-medium mb-4"><i class="fas fa-id-card mr-1"></i> NIK: <?php echo e($remaja->nik); ?></p>
            
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-[11px] font-bold border border-white/20 shadow-sm"><i class="fas fa-school mr-1"></i> <?php echo e($remaja->sekolah ?? 'Pelajar'); ?></span>
                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-[11px] font-bold border border-white/20 shadow-sm"><i class="fas fa-birthday-cake mr-1"></i> <?php echo e(\Carbon\Carbon::parse($remaja->tanggal_lahir)->age); ?> Tahun</span>
            </div>
        </div>
    </div>

    <div>
        <h3 class="font-black text-slate-800 text-base mb-3 font-poppins">Pemeriksaan Fisik Terakhir</h3>
        <?php if($pemeriksaanTerakhir): ?>
        <div class="grid grid-cols-2 gap-3 sm:gap-4">
            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex flex-col items-center justify-center text-center">
                <i class="fas fa-weight text-indigo-400 text-xl mb-2"></i>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Berat Badan</p>
                <h4 class="text-xl font-black text-slate-800"><?php echo e($pemeriksaanTerakhir->berat_badan ?? '-'); ?><span class="text-xs font-bold text-slate-500 ml-1">kg</span></h4>
            </div>
            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex flex-col items-center justify-center text-center">
                <i class="fas fa-ruler-vertical text-indigo-400 text-xl mb-2"></i>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Tinggi Badan</p>
                <h4 class="text-xl font-black text-slate-800"><?php echo e($pemeriksaanTerakhir->tinggi_badan ?? '-'); ?><span class="text-xs font-bold text-slate-500 ml-1">cm</span></h4>
            </div>
            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex flex-col items-center justify-center text-center">
                <i class="fas fa-heartbeat text-rose-400 text-xl mb-2"></i>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Tensi Darah</p>
                <h4 class="text-lg font-black text-slate-800"><?php echo e($pemeriksaanTerakhir->tekanan_darah ?? '-'); ?></h4>
            </div>
            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex flex-col items-center justify-center text-center">
                <i class="fas fa-tint text-rose-400 text-xl mb-2"></i>
                <p class="text-[10px] font-bold text-slate-400 uppercase">Hemoglobin</p>
                <h4 class="text-lg font-black text-slate-800"><?php echo e($pemeriksaanTerakhir->hemoglobin ?? '-'); ?></h4>
            </div>
        </div>
        
        <?php if($pemeriksaanTerakhir->diagnosa): ?>
        <div class="mt-4 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl shadow-sm">
            <h4 class="text-xs font-black text-indigo-800 uppercase tracking-widest mb-1">Catatan Bidan</h4>
            <p class="text-sm font-semibold text-indigo-900"><?php echo e($pemeriksaanTerakhir->diagnosa); ?></p>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Belum ada riwayat pemeriksaan fisik tercatat.</p>
        </div>
        <?php endif; ?>
    </div>

    <a href="<?php echo e(route('user.konseling.index')); ?>" class="smooth-route block bg-white border border-slate-100 rounded-[24px] p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-comments-medical"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-slate-800 font-poppins">Ruang Konseling</h4>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">Konsultasi pribadi dengan Bidan secara rahasia.</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-slate-300 group-hover:text-teal-500 transition-colors"></i>
        </div>
    </a>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/remaja/index.blade.php ENDPATH**/ ?>