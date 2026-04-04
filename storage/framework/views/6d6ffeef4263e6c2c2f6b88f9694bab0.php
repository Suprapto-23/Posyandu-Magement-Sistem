
<?php $__env->startSection('title', 'Detail Imunisasi'); ?>
<?php $__env->startSection('page-name', 'Detail Imunisasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-500 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-eye text-cyan-500 text-2xl animate-pulse"></i>
    </div>
    <p class="text-cyan-800 font-black font-poppins tracking-widest text-[11px] animate-pulse uppercase">Memuat Data...</p>
</div>

<div class="max-w-4xl mx-auto animate-slide-up">

    <div class="mb-6 flex justify-between items-center">
        <a href="<?php echo e(route('bidan.imunisasi.index')); ?>" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Register
        </a>
        <a href="<?php echo e(route('bidan.imunisasi.edit', $imunisasi->id)); ?>" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 border border-amber-200 text-amber-600 font-bold text-[13px] rounded-xl hover:bg-amber-500 hover:text-white transition-colors shadow-sm">
            <i class="fas fa-pen"></i> Edit Data
        </a>
    </div>

    <div class="bg-white rounded-[32px] p-8 md:p-12 mb-8 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col items-center justify-center text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-40 pointer-events-none" style="background-image: radial-gradient(#06b6d4 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-500/10 blur-3xl rounded-full pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 mx-auto rounded-[24px] bg-gradient-to-br from-cyan-400 to-sky-500 text-white flex items-center justify-center text-4xl mb-5 shadow-[0_8px_20px_rgba(6,182,212,0.3)] transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fas fa-prescription-bottle-alt"></i>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-800 font-poppins mb-2 tracking-tight"><?php echo e($imunisasi->vaksin); ?></h2>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-cyan-50 border border-cyan-100 rounded-lg">
                <span class="text-cyan-700 font-black uppercase tracking-widest text-[11px]"><?php echo e($imunisasi->jenis_imunisasi); ?></span>
                <span class="text-cyan-300 text-xs">|</span>
                <span class="text-cyan-700 font-bold text-[11px]">DOSIS <strong class="font-black text-cyan-900"><?php echo e($imunisasi->dosis); ?></strong></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden mb-10">
        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-file-invoice"></i></div>
            <h3 class="font-black text-slate-800 text-[15px]">Rekam Jejak Eksekusi</h3>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100 shrink-0"><i class="fas fa-child"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Identitas Pasien</p>
                        <p class="text-[15px] font-bold text-slate-800 leading-tight"><?php echo e($imunisasi->kunjungan->pasien->nama_lengkap ?? 'Tidak diketahui'); ?></p>
                        <p class="text-[11px] font-bold text-cyan-500 mt-1 uppercase"><?php echo e(class_basename($imunisasi->kunjungan->pasien_type)); ?></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100 shrink-0"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Waktu Pemberian Vaksin</p>
                        <p class="text-[15px] font-bold text-slate-800 leading-tight"><?php echo e(\Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->translatedFormat('l, d F Y')); ?></p>
                        <p class="text-[11px] font-bold text-slate-400 mt-1"><i class="far fa-clock"></i> Tercatat pada <?php echo e($imunisasi->created_at->format('H:i')); ?> WIB</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100 shrink-0"><i class="fas fa-user-md"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Petugas Medis (Bidan)</p>
                        <p class="text-[15px] font-bold text-slate-800 leading-tight"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-[11px] font-bold text-emerald-500 mt-1"><i class="fas fa-check-circle"></i> Tervalidasi Sistem</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const showLoader = () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.style.display = 'flex';
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/show.blade.php ENDPATH**/ ?>