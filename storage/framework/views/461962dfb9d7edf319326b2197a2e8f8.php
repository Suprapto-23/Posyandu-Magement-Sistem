
<?php $__env->startSection('title', 'Laporan Balita'); ?>
<?php $__env->startSection('page-name', 'Laporan Posyandu'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Sembunyikan elemen UI Dashboard saat proses Print manual ke PDF */
    @media print {
        body { background-color: white !important; }
        #sidebar, header, .print\:hidden, .flash-message { display: none !important; }
        .lg\:ml-\[280px\] { margin-left: 0 !important; }
        main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .paper-preview { border: none !important; box-shadow: none !important; margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto animate-slide-up">

    <div class="bg-white rounded-[24px] p-6 md:p-8 mb-6 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-rose-500/5 blur-3xl rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-rose-100">
                <i class="fas fa-baby"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black font-poppins text-slate-800 mb-1">Laporan Balita</h2>
                <p class="text-slate-500 text-sm font-medium">Rekapitulasi data pemeriksaan balita bulanan posyandu.</p>
            </div>
        </div>

        <div class="relative z-10 w-full md:w-auto">
            <a href="<?php echo e(route('kader.laporan.generate', ['type' => 'balita', 'bulan' => $bulan, 'tahun' => $tahun])); ?>" class="w-full md:w-auto flex items-center justify-center gap-2 px-8 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl transition-all shadow-[0_4px_15px_rgba(225,29,72,0.3)] hover:-translate-y-1">
                <i class="fas fa-file-pdf text-lg"></i> Unduh PDF Resmi
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[20px] border border-slate-200/80 shadow-sm p-3 mb-6 relative z-20">
        <form action="<?php echo e(route('kader.laporan.balita')); ?>" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            
            <div class="w-full sm:w-auto flex-1 flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 transition-colors focus-within:border-rose-400 focus-within:bg-white">
                <i class="fas fa-calendar-alt text-slate-400"></i>
                <select name="bulan" class="w-full bg-transparent text-slate-700 text-sm font-bold outline-none cursor-pointer">
                    <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(str_pad($m, 2, '0', STR_PAD_LEFT)); ?>" <?php echo e($bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::create()->month((int)$m)->translatedFormat('F')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="w-full sm:w-auto flex-1 flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 transition-colors focus-within:border-rose-400 focus-within:bg-white">
                <i class="fas fa-calendar-week text-slate-400"></i>
                <select name="tahun" class="w-full bg-transparent text-slate-700 text-sm font-bold outline-none cursor-pointer">
                    <?php $__currentLoopData = range(date('Y')-2, date('Y')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-filter"></i> Filter Laporan
            </button>
        </form>
    </div>

    <div class="bg-slate-100 rounded-[32px] border border-slate-200 p-6 sm:p-10 shadow-inner overflow-x-auto print:bg-white print:p-0 print:border-none print:shadow-none">
        
        <div class="mb-8 flex items-start gap-4 max-w-4xl mx-auto print:hidden">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-sm border border-amber-200">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800 mb-1">Mode Pratinjau (Document Preview)</h4>
                <p class="text-xs font-medium text-slate-500 leading-relaxed">
                    Tampilan di bawah adalah simulasi cetak kertas <span class="font-bold text-slate-700">A4 Landscape</span>. Kop surat resmi, margin, dan ukuran tabel akan otomatis disesuaikan dengan rapi dan sempurna saat Anda menekan tombol <strong class="text-slate-700">Unduh PDF Resmi</strong>.
                </p>
            </div>
        </div>

        <div class="paper-preview bg-white mx-auto shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-200 print:shadow-none print:border-none relative" style="min-width: 1000px; max-width: 1122px; padding: 60px; border-radius: 8px;">
            <?php echo $__env->make('kader.laporan.templates.table-balita', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/laporan/balita.blade.php ENDPATH**/ ?>