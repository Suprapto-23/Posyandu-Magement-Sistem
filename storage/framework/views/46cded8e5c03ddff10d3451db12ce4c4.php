

<?php $__env->startSection('title', 'Input Pemeriksaan Medis'); ?>
<?php $__env->startSection('page-name', 'Input Pemeriksaan Baru'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .custom-radio input:checked + div { border-color: #06b6d4; background-color: #06b6d4; color: white; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-heartbeat text-cyan-600 text-2xl animate-pulse"></i>
    </div>
    <p class="text-cyan-800 font-extrabold tracking-widest text-sm animate-pulse" id="loaderText">MEMUAT FORMULIR...</p>
</div>

<div class="max-w-5xl mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pemeriksaan Pasien</h1>
            <p class="text-slate-500 mt-1 font-medium text-sm">Input data langsung oleh Bidan akan otomatis berstatus Terverifikasi.</p>
        </div>
        <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>" class="smooth-route inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrian
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
            <div class="flex flex-wrap gap-2 p-1 bg-white border border-slate-200 rounded-xl w-full sm:w-max">
                <a href="?kategori=balita" class="smooth-route flex-1 sm:flex-none text-center px-6 py-2 rounded-lg text-sm font-extrabold transition-colors <?php echo e($kategori == 'balita' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">
                    <i class="fas fa-baby mr-1"></i> Balita
                </a>
                <a href="?kategori=remaja" class="smooth-route flex-1 sm:flex-none text-center px-6 py-2 rounded-lg text-sm font-extrabold transition-colors <?php echo e($kategori == 'remaja' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">
                    <i class="fas fa-user-graduate mr-1"></i> Remaja
                </a>
                <a href="?kategori=lansia" class="smooth-route flex-1 sm:flex-none text-center px-6 py-2 rounded-lg text-sm font-extrabold transition-colors <?php echo e($kategori == 'lansia' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'); ?>">
                    <i class="fas fa-wheelchair mr-1"></i> Lansia
                </a>
            </div>
        </div>

        <form id="formPemeriksaan" action="<?php echo e(route('bidan.pemeriksaan.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="kategori_pasien" value="<?php echo e($kategori); ?>">
            <input type="hidden" name="tanggal_periksa" value="<?php echo e(date('Y-m-d')); ?>">

            <div class="p-6 sm:p-8 space-y-8">
                
                <div>
                    <label class="block text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-3 border-b border-cyan-100 pb-2">1. Identitas Pasien</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="pasien_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl pl-11 pr-4 py-3.5 outline-none font-semibold focus:border-cyan-500 focus:bg-white transition-colors cursor-pointer appearance-none shadow-inner" required>
                            <option value="">-- Pilih Nama Pasien (Kategori: <?php echo e(ucfirst($kategori)); ?>) --</option>
                            <?php $__currentLoopData = $pasien; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>" <?php echo e(old('pasien_id') == $p->id ? 'selected' : ''); ?>><?php echo e($p->nama_lengkap); ?> (NIK: <?php echo e($p->nik); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-3 border-b border-cyan-100 pb-2">2. Pengukuran Fisik</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat_badan" value="<?php echo e(old('berat_badan')); ?>" required class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 outline-none font-bold focus:border-cyan-500 transition-colors shadow-sm" placeholder="0.0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan" value="<?php echo e(old('tinggi_badan')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 outline-none font-bold focus:border-cyan-500 transition-colors shadow-sm" placeholder="0.0">
                        </div>

                        <?php if($kategori == 'balita'): ?>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Lingkar Kepala (cm)</label><input type="number" step="0.01" name="lingkar_kepala" value="<?php echo e(old('lingkar_kepala')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm"></div>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Lingkar Lengan (cm)</label><input type="number" step="0.01" name="lingkar_lengan" value="<?php echo e(old('lingkar_lengan')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm"></div>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Suhu Tubuh (°C)</label><input type="number" step="0.1" name="suhu_tubuh" value="<?php echo e(old('suhu_tubuh')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm" placeholder="36.5"></div>
                        <?php endif; ?>

                        <?php if($kategori == 'remaja' || $kategori == 'lansia'): ?>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Tekanan Darah (mmHg)</label><input type="text" name="tekanan_darah" value="<?php echo e(old('tekanan_darah')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm" placeholder="120/80"></div>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Gula Darah (mg/dL)</label><input type="number" name="gula_darah" value="<?php echo e(old('gula_darah')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm"></div>
                        <?php endif; ?>

                        <?php if($kategori == 'lansia'): ?>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Kolesterol (mg/dL)</label><input type="number" name="kolesterol" value="<?php echo e(old('kolesterol')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm"></div>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Asam Urat (mg/dL)</label><input type="number" step="0.01" name="asam_urat" value="<?php echo e(old('asam_urat')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm"></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-3 border-b border-cyan-100 pb-2">3. Diagnosa & Tindakan Bidan</label>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Status Gizi / Kesehatan</label>
                            <div class="flex flex-wrap gap-3">
                                <?php
                                    $options = $kategori == 'balita' ? ['baik'=>'Normal','kurang'=>'Kurang','stunting'=>'Stunting','obesitas'=>'Obesitas'] : ['baik'=>'Sehat','risiko'=>'Berisiko','buruk'=>'Sakit/Rujuk'];
                                ?>
                                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="custom-radio cursor-pointer">
                                    <input type="radio" name="status_gizi" value="<?php echo e($val); ?>" class="hidden" <?php echo e(old('status_gizi', 'baik') == $val ? 'checked' : ''); ?>>
                                    <div class="px-4 py-2 border-2 border-slate-200 rounded-xl text-xs font-bold text-slate-500 transition-all"><?php echo e($label); ?></div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Keluhan Awal</label><input type="text" name="keluhan" value="<?php echo e(old('keluhan')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm" placeholder="Contoh: Batuk, pilek..."></div>
                            <div><label class="block text-xs font-bold text-slate-600 mb-2">Diagnosa Bidan</label><input type="text" name="hasil_diagnosa" value="<?php echo e(old('hasil_diagnosa')); ?>" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm" placeholder="Kondisi kesehatan pasien..."></div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Tindakan / Resep Medis</label>
                            <textarea name="tindakan" rows="2" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 focus:border-cyan-500 shadow-sm" placeholder="Contoh: Berikan Vitamin A, rujuk ke Puskesmas..."></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button type="submit" id="btnSubmit" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white font-black text-sm rounded-xl hover:from-cyan-600 hover:to-cyan-700 shadow-[0_8px_20px_rgba(8,145,178,0.25)] hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fas fa-save"></i> <span>Simpan Pemeriksaan</span>
                </button>
            </div>
        </form>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        const btn = document.getElementById('btnSubmit');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
        if(btn) {
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Simpan Pemeriksaan</span>';
            btn.classList.remove('opacity-75', 'cursor-wait');
        }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader('MEMUAT FORMULIR...');
        });
    });

    // Cegah layar nge-freeze pas Submit, ganti dengan loading smooth
    document.getElementById('formPemeriksaan').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menyimpan...</span>';
        btn.classList.add('opacity-75', 'cursor-wait');
        showLoader('MENYIMPAN DATA...');
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pemeriksaan/create.blade.php ENDPATH**/ ?>