
<?php $__env->startSection('title', 'Input Imunisasi Baru'); ?>
<?php $__env->startSection('page-name', 'Catat Imunisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto animate-[slideDown_0.4s_ease-out]">

    <div class="mb-6">
        <a href="<?php echo e(route('bidan.imunisasi.index')); ?>" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden mb-8 flex flex-col md:flex-row">
        <div class="md:w-5/12 bg-gradient-to-br from-cyan-500 to-sky-600 p-8 flex flex-col justify-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 border-[20px] border-white/10 rounded-full"></div>
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-[16px] border border-white/30 flex items-center justify-center text-3xl text-white mb-6 shadow-sm"><i class="fas fa-syringe"></i></div>
            <h2 class="text-2xl font-black text-white font-poppins tracking-tight mb-2">Injeksi Medis</h2>
            <p class="text-cyan-50 text-[13px] font-medium leading-relaxed">Catat pemberian vaksin ke Balita, Remaja, atau TT untuk Ibu Hamil. Data akan otomatis dikunci dengan nama Anda sebagai petugas validasi.</p>
        </div>

        <form action="<?php echo e(route('bidan.imunisasi.store')); ?>" method="POST" id="imunisasiForm" class="md:w-7/12 flex flex-col">
            <?php echo csrf_field(); ?>
            <div class="p-8 space-y-6 flex-1">
                
                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Antrian Warga <span class="text-rose-500">*</span></label>
                    <select name="kunjungan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-slate-700 focus:bg-white focus:border-cyan-400 outline-none transition-colors cursor-pointer">
                        <option value="">-- Pilih Dari Antrian Hari Ini --</option>
                        <?php $__currentLoopData = $kunjungans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>">
                                <?php echo e(\Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d M')); ?> — <?php echo e($k->pasien->nama_lengkap ?? 'Unknown'); ?> (<?php echo e(class_basename($k->pasien_type)); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Nama Vaksin <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-vial absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="vaksin" required placeholder="Contoh: Polio 1 / TT Ibu Hamil" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 text-[13px] font-medium text-slate-800 focus:bg-white focus:border-cyan-400 outline-none transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Kategori <span class="text-rose-500">*</span></label>
                        <select name="jenis_imunisasi" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-slate-700 focus:bg-white focus:border-cyan-400 outline-none transition-colors cursor-pointer">
                            <option value="Dasar">Imunisasi Dasar</option>
                            <option value="Lanjutan">Imunisasi Lanjutan</option>
                            <option value="Tambahan">Tambahan (Booster)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Dosis <span class="text-rose-500">*</span></label>
                        <input type="text" name="dosis" required placeholder="Ex: 0.5 ml" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-medium text-slate-800 focus:bg-white focus:border-cyan-400 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tgl Eksekusi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_imunisasi" value="<?php echo e(date('Y-m-d')); ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-[13px] font-bold text-slate-800 focus:bg-white focus:border-cyan-400 outline-none transition-colors cursor-pointer">
                </div>

            </div>
            
            <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 flex justify-end rounded-br-[24px]">
                <button type="submit" onclick="document.getElementById('globalLoader').style.display='flex'; document.getElementById('globalLoader').classList.remove('opacity-0');" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-black text-white bg-cyan-600 hover:bg-cyan-700 shadow-[0_4px_15px_rgba(6,182,212,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-wide flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Record
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/create.blade.php ENDPATH**/ ?>