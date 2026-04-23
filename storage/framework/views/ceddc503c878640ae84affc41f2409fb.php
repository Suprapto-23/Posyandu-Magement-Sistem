
<?php $__env->startSection('title', 'Pengaturan Sistem'); ?>
<?php $__env->startSection('page-name', 'Pengaturan Web'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .fade-in-up { animation: fadeInUp 0.4s ease-out forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .delay-100 { animation-delay: 0.1s; }
</style>

<div class="max-w-6xl mx-auto space-y-8 fade-in-up">

    
    <div class="bg-blue-600 rounded-[2rem] p-8 md:p-10 relative overflow-hidden shadow-lg flex flex-col items-center justify-center text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center text-3xl shadow-inner mb-4 transition-transform hover:rotate-180 duration-700">
                <i class="fas fa-cog"></i>
            </div>
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight">
                Konfigurasi Sistem Utama
            </h2>
            <p class="text-blue-100 text-sm font-medium mt-2 max-w-lg">
                Kelola identitas Posyandu untuk keperluan <strong class="text-white">Kop Surat Laporan PDF</strong> dan perbarui kata sandi keamanan Administrator Anda secara berkala.
            </p>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-sm font-bold text-emerald-700 flex justify-center items-center text-center gap-3 shadow-sm fade-in-up">
        <i class="fas fa-check-circle text-xl"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-sm font-bold text-rose-600 flex justify-center items-center text-center gap-3 shadow-sm fade-in-up">
        <i class="fas fa-exclamation-circle text-xl"></i> Terdapat kesalahan pada input form. Silakan periksa kembali isian Anda.
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10 fade-in-up delay-100">
        
        
        
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest font-poppins">Profil Kop Surat PDF</h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-0.5">Data identitas untuk Laporan Export</p>
                </div>
            </div>
            
            <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" id="formProfil" class="p-8 flex-1 flex flex-col">
                <?php echo csrf_field(); ?> 
                <?php echo method_field('PUT'); ?>
                
                <div class="space-y-5 flex-1">
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-3 text-xs font-medium text-blue-700 flex gap-3 items-start mb-2">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <p>Data di bawah ini akan tercetak otomatis sebagai <strong>Header / Kop Surat</strong> saat Anda atau Bidan mengunduh laporan bulanan.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Instansi Posyandu <span class="text-rose-500">*</span></label>
                        <input type="text" name="posyandu_name" value="<?php echo e(old('posyandu_name', $settings['posyandu_name'] ?? '')); ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" placeholder="Contoh: Posyandu Melati 1">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email Instansi</label>
                            <input type="email" name="posyandu_email" value="<?php echo e(old('posyandu_email', $settings['posyandu_email'] ?? '')); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" placeholder="email@posyandu.com">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Telepon CS</label>
                            <input type="text" name="posyandu_telepon" value="<?php echo e(old('posyandu_telepon', $settings['posyandu_telepon'] ?? '')); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" placeholder="08xx...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Kelurahan / Desa</label>
                        <input type="text" name="posyandu_kelurahan" value="<?php echo e(old('posyandu_kelurahan', $settings['posyandu_kelurahan'] ?? '')); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" placeholder="Nama Desa">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="posyandu_alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none" placeholder="Jalan, RT/RW, Kecamatan"><?php echo e(old('posyandu_alamat', $settings['posyandu_alamat'] ?? '')); ?></textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" id="btnProfil" class="w-full py-3.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 transition-all shadow-md text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Profil & Kop Surat
                    </button>
                </div>
            </form>
        </div>

        
        
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest font-poppins">Keamanan Root Admin</h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-0.5">Ganti kata sandi utama Anda</p>
                </div>
            </div>
            
            <form action="<?php echo e(route('admin.settings.change-password')); ?>" method="POST" id="formPassword" class="p-8 flex-1 flex flex-col">
                <?php echo csrf_field(); ?> 
                <?php echo method_field('PUT'); ?>
                
                <div class="space-y-5 flex-1">
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Password Saat Ini</label>
                        <input type="password" name="current_password" required 
                               class="w-full bg-slate-50 border <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 bg-rose-50 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-slate-800 focus:ring-2 focus:ring-slate-800/20 outline-none transition-all">
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[10px] text-rose-500 font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> <?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Password Baru (Min 8 Karakter)</label>
                        <input type="password" name="new_password" required 
                               class="w-full bg-slate-50 border <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 bg-rose-50 <?php else: ?> border-slate-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-slate-800 focus:ring-2 focus:ring-slate-800/20 outline-none transition-all">
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[10px] text-rose-500 font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> <?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Ulangi Password Baru</label>
                        <input type="password" name="new_password_confirmation" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-slate-800 focus:ring-2 focus:ring-slate-800/20 outline-none transition-all">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" id="btnPassword" class="w-full py-3.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 hover:-translate-y-0.5 transition-all shadow-md text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i> Perbarui Password Keamanan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>


<script>
    document.getElementById('formProfil').addEventListener('submit', function() {
        const btn = document.getElementById('btnProfil');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });

    document.getElementById('formPassword').addEventListener('submit', function() {
        const btn = document.getElementById('btnPassword');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memvalidasi...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>