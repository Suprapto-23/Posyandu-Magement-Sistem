
<?php $__env->startSection('title', 'Laporan Medis'); ?>
<?php $__env->startSection('page-name', 'Laporan PDF'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .glass-card { background: #ffffff; border: 1px solid #f1f5f9; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>

<div class="max-w-[1000px] mx-auto animate-slide-up pb-10">

    <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_10px_30px_rgba(6,182,212,0.3)] flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="relative z-10 w-full md:w-auto text-center md:text-left">
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight mb-2">Pusat Laporan Medis</h2>
            <p class="text-cyan-100 text-[13px] font-medium max-w-md mx-auto md:mx-0 leading-relaxed">Filter data kunjungan bulan ini, tinjau ringkasan statistik klinis, dan unduh dokumen rekapitulasi resmi dalam format PDF.</p>
        </div>
        <div class="w-24 h-24 rounded-[24px] bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center text-5xl shrink-0 shadow-inner relative z-10">
            <i class="fas fa-file-pdf"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="glass-card p-6">
                <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-4">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center"><i class="fas fa-filter"></i></div>
                    <h3 class="text-[15px] font-black text-slate-800 font-poppins">Filter Dokumen</h3>
                </div>

                <form action="<?php echo e(route('bidan.laporan.index')); ?>" method="GET" class="space-y-5">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Bulan</label>
                        <select name="bulan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-700 outline-none focus:border-cyan-500 cursor-pointer">
                            <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $namaBln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($idx+1); ?>" <?php echo e($bulan == ($idx+1) ? 'selected' : ''); ?>><?php echo e($namaBln); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tahun</label>
                        <select name="tahun" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-700 outline-none focus:border-cyan-500 cursor-pointer">
                            <?php for($y = now()->year; $y >= now()->year - 2; $y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Kategori Layanan</label>
                        <select name="jenis" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-[13px] font-bold text-slate-700 outline-none focus:border-cyan-500 cursor-pointer">
                            <option value="semua" <?php echo e($jenis == 'semua' ? 'selected' : ''); ?>>Semua Layanan</option>
                            <option value="balita" <?php echo e($jenis == 'balita' ? 'selected' : ''); ?>>Kesehatan Balita</option>
                            <option value="ibu_hamil" <?php echo e($jenis == 'ibu_hamil' ? 'selected' : ''); ?>>Ibu Hamil (KIA)</option>
                            <option value="remaja" <?php echo e($jenis == 'remaja' ? 'selected' : ''); ?>>Remaja</option>
                            <option value="lansia" <?php echo e($jenis == 'lansia' ? 'selected' : ''); ?>>Lansia (Manula)</option>
                        </select>
                    </div>
                    <button type="submit" onclick="showGlobalLoader('MENYIAPKAN DATA...')" class="w-full py-3.5 bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            
            <div class="glass-card p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 text-8xl pointer-events-none"><i class="fas fa-chart-bar"></i></div>
                
                <h3 class="text-[18px] font-black text-slate-800 font-poppins mb-1">Pratinjau Periode: <?php echo e($periode->translatedFormat('F Y')); ?></h3>
                <p class="text-[12px] font-bold text-cyan-600 uppercase tracking-widest mb-6 bg-cyan-50 inline-block px-3 py-1 rounded-md border border-cyan-100">Kategori: <?php echo e(ucfirst(str_replace('_', ' ', $jenis))); ?></p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-center">
                        <span class="block text-[28px] font-black text-slate-800 leading-none mb-1"><?php echo e($ringkasan['total']); ?></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pasien</span>
                    </div>
                    <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl text-center">
                        <span class="block text-[28px] font-black text-rose-600 leading-none mb-1"><?php echo e($ringkasan['balita']); ?></span>
                        <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Balita</span>
                    </div>
                    <div class="bg-pink-50 border border-pink-100 p-4 rounded-2xl text-center">
                        <span class="block text-[28px] font-black text-pink-600 leading-none mb-1"><?php echo e($ringkasan['ibu_hamil']); ?></span>
                        <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Bumil</span>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-center">
                        <span class="block text-[28px] font-black text-emerald-600 leading-none mb-1"><?php echo e($ringkasan['lansia']); ?></span>
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Lansia</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl bg-amber-50 border border-amber-200">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-lg shrink-0"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <p class="text-[11px] font-black text-amber-600/70 uppercase tracking-widest mb-0.5">Catatan Risiko Tinggi Bulan Ini</p>
                        <p class="text-[13px] font-bold text-amber-800">Ditemukan <strong class="text-rose-600"><?php echo e($ringkasan['stunting']); ?> Kasus Stunting/Gizi Buruk</strong> dan <strong class="text-rose-600"><?php echo e($ringkasan['hipertensi']); ?> Kasus Hipertensi</strong> pada filter yang Anda pilih.</p>
                    </div>
                </div>
            </div>

            
            <?php if($ringkasan['total'] > 0): ?>
                <form action="<?php echo e(route('bidan.laporan.cetak')); ?>" method="GET" class="w-full" target="_blank">
                    <input type="hidden" name="bulan" value="<?php echo e($bulan); ?>">
                    <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                    <input type="hidden" name="jenis" value="<?php echo e($jenis); ?>">
                    
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white rounded-2xl shadow-[0_8px_20px_rgba(6,182,212,0.3)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <i class="fas fa-cloud-download-alt text-2xl"></i>
                        <div class="text-left">
                            <span class="block text-[15px] font-black uppercase tracking-widest leading-none">Unduh Dokumen PDF</span>
                            <span class="block text-[11px] font-medium text-cyan-100 mt-1">Siap cetak. Tervalidasi dengan nama Bidan.</span>
                        </div>
                    </button>
                </form>
            <?php else: ?>
                <div class="w-full py-6 bg-slate-100 border border-slate-200 border-dashed rounded-2xl flex flex-col items-center justify-center text-slate-400">
                    <i class="fas fa-file-excel text-3xl mb-2 opacity-50"></i>
                    <p class="text-[12px] font-bold uppercase tracking-widest">Tidak ada data untuk dicetak</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/laporan/index.blade.php ENDPATH**/ ?>