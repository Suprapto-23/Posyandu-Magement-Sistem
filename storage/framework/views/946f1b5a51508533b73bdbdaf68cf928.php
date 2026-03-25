

<?php $__env->startSection('title', 'Tambah Data Balita'); ?>
<?php $__env->startSection('page-name', 'Pendaftaran Balita'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up {
        opacity: 0;
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* CSS Murni untuk Form Input agar dijamin berjalan 100% tanpa error Tailwind Build */
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: block;
        font-size: 0.70rem;
        font-weight: 800;
        color: #64748b; /* slate-500 */
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        background-color: #f8fafc; /* slate-50 */
        border: 2px solid #e2e8f0; /* slate-200 */
        color: #0f172a; /* slate-900 */
        font-size: 0.875rem;
        border-radius: 0.75rem; /* 12px */
        padding: 0.75rem 1rem;
        outline: none;
        transition: all 0.3s ease;
        font-weight: 600;
        box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); /* Inner shadow agar tidak flat */
    }
    .form-input:focus {
        background-color: #ffffff;
        border-color: #6366f1; /* Indigo-500 */
        box-shadow: 0 4px 12px -3px rgba(99, 102, 241, 0.15); /* Shadow luar saat aktif */
        transform: translateY(-1px);
    }
    .form-input::placeholder {
        color: #94a3b8; /* slate-400 */
        font-weight: 500;
    }
    .form-error {
        border-color: #f43f5e !important; /* Rose-500 */
        background-color: #fff1f2 !important; /* Rose-50 */
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto animate-slide-up">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 mb-4 shadow-inner">
            <i class="fas fa-baby text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Pendaftaran Balita</h1>
        <p class="text-slate-500 mt-2 font-medium text-sm max-w-lg mx-auto">Silakan lengkapi formulir di bawah ini dengan data yang valid. Pastikan NIK Ibu sesuai untuk integrasi akun.</p>
    </div>

    <form action="<?php echo e(route('kader.data.balita.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col mb-8">
            
            <div class="p-6 sm:p-10 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                    <h3 class="text-lg font-extrabold text-slate-800">Identitas Anak</h3>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Anak <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required placeholder="Contoh: Muhammad Al-Fatih" class="form-input <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs font-bold mt-1.5"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                    <div class="form-group">
                        <label class="form-label">NIK Balita (Opsional)</label>
                        <input type="number" name="nik" value="<?php echo e(old('nik')); ?>" placeholder="16 Digit Angka" class="form-input <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs font-bold mt-1.5"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="form-input <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" <?php echo e(old('jenis_kelamin') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('jenis_kelamin') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir')); ?>" required placeholder="Kota Kelahiran" class="form-input <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" required class="form-input <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 mb-0">
                    <div class="form-group mb-0 sm:mb-0">
                        <label class="form-label">Berat Lahir (kg)</label>
                        <input type="number" step="0.01" name="berat_lahir" value="<?php echo e(old('berat_lahir')); ?>" placeholder="Contoh: 3.2" class="form-input">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Panjang Lahir (cm)</label>
                        <input type="number" step="0.01" name="panjang_lahir" value="<?php echo e(old('panjang_lahir')); ?>" placeholder="Contoh: 50" class="form-input">
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-slate-50/50">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold text-sm">2</span>
                    <h3 class="text-lg font-extrabold text-slate-800">Data Orang Tua</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                    <div class="form-group">
                        <label class="form-label">NIK Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="<?php echo e(old('nik_ibu')); ?>" required placeholder="16 Digit NIK Ibu" class="form-input bg-white <?php $__errorArgs = ['nik_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['nik_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs font-bold mt-1.5"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu')); ?>" required placeholder="Nama Lengkap Ibu" class="form-input bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                    <div class="form-group">
                        <label class="form-label">NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="<?php echo e(old('nik_ayah')); ?>" placeholder="16 Digit NIK Ayah" class="form-input bg-white">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Ayah (Opsional)</label>
                        <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah')); ?>" placeholder="Nama Lengkap Ayah" class="form-input bg-white">
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" rows="3" required placeholder="Alamat lengkap saat ini..." class="form-input bg-white resize-none"><?php echo e(old('alamat')); ?></textarea>
                </div>
            </div>
            
            <div class="p-6 sm:px-10 sm:py-6 bg-white border-t border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    <a href="<?php echo e(route('kader.data.balita.index')); ?>" class="w-full py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 hover:text-slate-800 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="w-full py-3.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Data Balita
                    </button>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/balita/create.blade.php ENDPATH**/ ?>