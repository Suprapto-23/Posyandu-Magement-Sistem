

<?php $__env->startSection('title', 'Laporan Medis Posyandu'); ?>
<?php $__env->startSection('page-name', 'Rekapitulasi EMR'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ANIMASI MASUK HALUS */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* NEXUS GLASS CARD (PRESISI) */
    .nexus-card { 
        background: #ffffff; border-radius: 32px; border: 1px solid #f1f5f9; 
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.03); position: relative; overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nexus-card:hover {
        box-shadow: 0 15px 50px -10px rgba(6, 182, 212, 0.08);
        border-color: #e0f2fe;
    }
    
    /* SELECT INPUT PREMIUM */
    .nexus-select {
        width: 100%; background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 16px; 
        padding: 16px 20px; color: #0f172a; font-weight: 700; font-size: 13px; 
        transition: all 0.3s ease; outline: none; cursor: pointer; appearance: none;
    }
    .nexus-select:focus { 
        background: #ffffff; border-color: #0ea5e9; 
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15); 
    }

    /* KARTU STATISTIK MELAYANG */
    .stat-box { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid transparent; }
    .stat-box:hover { transform: translateY(-4px); z-index: 10; position: relative; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-file-pdf text-cyan-600 text-xl animate-pulse"></i>
        </div>
    </div>
    <div class="bg-white px-6 py-2.5 rounded-full shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></div>
        <p class="text-[11px] font-black text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MENYIAPKAN DOKUMEN...</p>
    </div>
</div>

<div class="max-w-[1250px] mx-auto animate-slide-up pb-16">

    
    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-[36px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(6,182,212,0.4)] flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="absolute -right-10 -top-10 w-64 h-64 border-[30px] border-white/10 rounded-full pointer-events-none transform hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute right-32 -bottom-20 w-40 h-40 border-[15px] border-white/10 rounded-full pointer-events-none"></div>
        
        <div class="relative z-10 w-full md:w-auto text-center md:text-left flex-1">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-black uppercase tracking-widest mb-4 shadow-sm">
                <i class="fas fa-shield-alt text-cyan-200"></i> Otoritas Puskesmas
            </div>
            <h2 class="text-3xl md:text-[32px] font-black text-white font-poppins tracking-tight mb-2 leading-none">Pusat Laporan Medis</h2>
            <p class="text-cyan-50 text-[13.5px] font-medium max-w-lg mx-auto md:mx-0 leading-relaxed opacity-95">Eksplorasi data rekam medis tervalidasi dan unduh dokumen rekapitulasi klinis bulanan resmi untuk pelaporan Dinas Kesehatan.</p>
        </div>
        <div class="w-28 h-28 rounded-[24px] bg-white/10 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-5xl shrink-0 shadow-[0_10px_25px_rgba(0,0,0,0.1)] relative z-10 transform -rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-file-contract"></i>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
        
        
        <div class="lg:col-span-4">
            <div class="nexus-card p-8 h-full flex flex-col">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <div class="w-12 h-12 rounded-[14px] bg-slate-50 border border-slate-100 text-cyan-600 flex items-center justify-center text-xl shadow-inner shrink-0"><i class="fas fa-filter"></i></div>
                    <div>
                        <h3 class="text-[18px] font-black text-slate-800 font-poppins leading-none mb-1">Filter Dokumen</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sortir Parameter EMR</p>
                    </div>
                </div>

                
                <form action="<?php echo e(route('bidan.laporan.index')); ?>" method="GET" class="flex flex-col flex-1">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Periode Bulan</label>
                            <div class="relative">
                                <select name="bulan" class="nexus-select">
                                    <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $namaBln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($idx+1); ?>" <?php echo e($bulan == ($idx+1) ? 'selected' : ''); ?>><?php echo e($namaBln); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[14px]"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tahun Anggaran</label>
                            <div class="relative">
                                <select name="tahun" class="nexus-select">
                                    <?php for($y = now()->year; $y >= now()->year - 2; $y--): ?>
                                        <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                    <?php endfor; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[14px]"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Kluster Layanan Medis</label>
                            <div class="relative">
                                <select name="jenis" class="nexus-select border-cyan-100 bg-cyan-50/30 text-cyan-800 focus:border-cyan-400 focus:ring-cyan-50">
                                    <option value="semua" <?php echo e($jenis == 'semua' ? 'selected' : ''); ?>>Semua Layanan (Terpadu)</option>
                                    <option value="balita" <?php echo e($jenis == 'balita' ? 'selected' : ''); ?>>Kesehatan Anak & Balita</option>
                                    <option value="ibu_hamil" <?php echo e($jenis == 'ibu_hamil' ? 'selected' : ''); ?>>Ibu Hamil (Kandungan)</option>
                                    <option value="remaja" <?php echo e($jenis == 'remaja' ? 'selected' : ''); ?>>Kesehatan Remaja</option>
                                    <option value="lansia" <?php echo e($jenis == 'lansia' ? 'selected' : ''); ?>>Lansia (Manula)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-cyan-500 pointer-events-none text-[14px]"></i>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="pt-6 mt-auto">
                        <div class="border-t border-slate-50 pt-6">
                            <button type="submit" onclick="showLoader('MEMUAT ANALITIK DATA...')" class="w-full py-4 bg-[#0f172a] hover:bg-cyan-600 text-white text-[12px] font-black uppercase tracking-widest rounded-[16px] transition-all duration-300 shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:shadow-[0_10px_20px_rgba(6,182,212,0.3)] hover:-translate-y-1 flex items-center justify-center gap-2.5">
                                <i class="fas fa-sync-alt text-[14px]"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="lg:col-span-8 flex flex-col gap-6 lg:gap-8">
            
            
            <div class="nexus-card p-8 md:p-10 flex-1 flex flex-col justify-center">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-[20px] font-black text-slate-800 font-poppins mb-1 tracking-tight">Pratinjau Statistik Medis</h3>
                        <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Periode Laporan: <span class="text-cyan-600"><?php echo e($periode->translatedFormat('F Y')); ?></span></p>
                    </div>
                    <span class="inline-flex px-4 py-2 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-[12px] border border-indigo-100 items-center gap-2 shadow-sm">
                        <i class="fas fa-tags text-indigo-400"></i> <?php echo e(str_replace('_', ' ', $jenis)); ?>

                    </span>
                </div>

                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 md:gap-5 mb-8">
                    <div class="stat-box bg-slate-50 hover:bg-white hover:border-slate-200 border border-slate-100 p-5 rounded-[24px] text-center shadow-[0_4px_10px_rgba(0,0,0,0.02)]">
                        <span class="block text-[34px] font-black text-slate-800 font-poppins leading-none mb-2"><?php echo e($ringkasan['total']); ?></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pasien</span>
                    </div>
                    <div class="stat-box bg-cyan-50 hover:bg-white hover:border-cyan-200 border border-cyan-100 p-5 rounded-[24px] text-center shadow-[0_4px_10px_rgba(6,182,212,0.05)]">
                        <span class="block text-[34px] font-black text-cyan-600 font-poppins leading-none mb-2"><?php echo e($ringkasan['balita']); ?></span>
                        <span class="text-[10px] font-black text-cyan-500 uppercase tracking-widest">Anak / Balita</span>
                    </div>
                    <div class="stat-box bg-pink-50 hover:bg-white hover:border-pink-200 border border-pink-100 p-5 rounded-[24px] text-center shadow-[0_4px_10px_rgba(236,72,153,0.05)]">
                        <span class="block text-[34px] font-black text-pink-600 font-poppins leading-none mb-2"><?php echo e($ringkasan['ibu_hamil']); ?></span>
                        <span class="text-[10px] font-black text-pink-500 uppercase tracking-widest">Ibu Hamil</span>
                    </div>
                    <div class="stat-box bg-emerald-50 hover:bg-white hover:border-emerald-200 border border-emerald-100 p-5 rounded-[24px] text-center shadow-[0_4px_10px_rgba(16,185,129,0.05)]">
                        <span class="block text-[34px] font-black text-emerald-600 font-poppins leading-none mb-2"><?php echo e($ringkasan['lansia']); ?></span>
                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Lansia</span>
                    </div>
                </div>

                
                <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-6 rounded-[24px] bg-rose-50/50 border border-rose-100">
                    <div class="w-14 h-14 rounded-[16px] bg-rose-100 border border-rose-200 text-rose-500 flex items-center justify-center text-2xl shrink-0 shadow-sm"><i class="fas fa-heartbeat animate-pulse"></i></div>
                    <div>
                        <p class="text-[11px] font-black text-rose-500 uppercase tracking-widest mb-1.5">Sistem Peringatan Dini (Early Warning)</p>
                        <p class="text-[13px] font-bold text-slate-700 leading-relaxed">
                            Berdasarkan EMR yang tervalidasi bulan ini, terdeteksi <strong class="text-rose-600 px-1.5 bg-rose-100 rounded-md"><?php echo e($ringkasan['stunting']); ?> Kasus Stunting/Gizi Buruk</strong> dan <strong class="text-rose-600 px-1.5 bg-rose-100 rounded-md"><?php echo e($ringkasan['hipertensi']); ?> Kasus Hipertensi</strong>.
                        </p>
                    </div>
                </div>
            </div>

            
            <?php if($ringkasan['total'] > 0): ?>
                <form action="<?php echo e(route('bidan.laporan.cetak')); ?>" method="GET" class="w-full" target="_blank">
                    <input type="hidden" name="bulan" value="<?php echo e($bulan); ?>">
                    <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                    <input type="hidden" name="jenis" value="<?php echo e($jenis); ?>">
                    
                    <button type="submit" onclick="showLoader('MENGENERATE PDF...', 2000)" class="w-full p-6 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white rounded-[32px] shadow-[0_15px_35px_rgba(6,182,212,0.3)] hover:shadow-[0_20px_40px_rgba(6,182,212,0.4)] hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-5 group border border-cyan-400/50">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner">
                            <i class="fas fa-file-download"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <span class="block text-[18px] sm:text-[20px] font-black font-poppins tracking-tight mb-1">Unduh Dokumen PDF Resmi</span>
                            <span class="block text-[12px] font-medium text-cyan-100">Format standar Puskesmas. Tervalidasi dengan nama Bidan (<?php echo e(Auth::user()->name); ?>).</span>
                        </div>
                    </button>
                </form>
            <?php else: ?>
                <div class="w-full py-12 bg-white border-2 border-slate-100 border-dashed rounded-[32px] flex flex-col items-center justify-center text-slate-400 shadow-sm shrink-0">
                    <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-4xl mb-4 border border-slate-100 shadow-inner"><i class="fas fa-folder-open text-slate-300"></i></div>
                    <p class="text-[16px] font-black text-slate-700 font-poppins mb-1">Data EMR Kosong</p>
                    <p class="text-[13px] font-medium max-w-sm text-center leading-relaxed">Belum ada pemeriksaan medis yang tervalidasi pada periode ini. Laporan tidak dapat dicetak.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const showLoader = (text = 'MEMPROSES...', autoClose = 0) => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');

            if(autoClose > 0) {
                setTimeout(() => {
                    loader.classList.remove('opacity-100');
                    loader.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => loader.style.display = 'none', 300);
                }, autoClose);
            }
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/laporan/index.blade.php ENDPATH**/ ?>