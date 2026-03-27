

<?php $__env->startSection('title', 'Pengaturan Profil'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    // LOGIKA PINTAR: Melacak NIK Warga dari berbagai sumber data
    $nikAktif = $user->nik ?? ($user->profile->nik ?? null);
    
    // Jika masih kosong, ambil dari username login (karena user login pakai NIK)
    if (empty($nikAktif) && !empty($user->username) && is_numeric($user->username)) {
        $nikAktif = $user->username;
    }
?>

<div class="animate-fade-in pb-6 space-y-6">

    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-[14px] bg-gradient-to-br from-sky-500 to-teal-500 text-white flex items-center justify-center text-xl shadow-lg shrink-0">
            <i class="fas fa-user-cog"></i>
        </div>
        <div>
            <h1 class="text-xl font-black text-slate-800 font-poppins leading-tight">Pengaturan Akun</h1>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Kelola identitas dan keamanan akun Anda.</p>
        </div>
    </div>

    <?php if(empty($nikAktif)): ?>
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-5 rounded-[20px] flex items-start sm:items-center gap-4 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-white text-amber-500 flex items-center justify-center text-xl shrink-0 shadow-sm mt-0.5 sm:mt-0">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-black text-amber-800 font-poppins">Status Akun: Belum Terhubung</h4>
            <p class="text-xs font-medium text-amber-700 mt-1 leading-snug">Silakan isi Nomor Induk Kependudukan (NIK) Anda di bawah ini agar data kesehatan Posyandu dapat diakses.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white border border-slate-100 rounded-[24px] shadow-[0_8px_30px_rgba(13,148,136,0.04)] overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <i class="fas fa-id-card text-teal-500"></i>
                <h3 class="font-extrabold text-slate-800 font-poppins">Informasi Identitas</h3>
            </div>
            
            <form action="<?php echo e(route('user.profile.update')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">NIK (16 Digit) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-fingerprint text-slate-400"></i>
                            </div>
                            <input type="text" name="nik" value="<?php echo e(old('nik', $nikAktif)); ?>" placeholder="Contoh: 33261xxxxxxxxxxx" maxlength="16" pattern="\d{16}" inputmode="numeric" class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-inner">
                        </div>
                        <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 font-medium mt-1.5 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1.5 ml-1"><i class="fas fa-info-circle mr-1"></i> Kunci utama untuk menarik rekam medis.</p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-slate-400"></i>
                            </div>
                            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-inner">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 font-medium mt-1.5 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Alamat Email <span class="text-slate-400 normal-case tracking-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-slate-400"></i>
                            </div>
                            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-inner">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 font-medium mt-1.5 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" onclick="showUserLoader()" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-teal-500 to-sky-500 hover:from-teal-600 hover:to-sky-600 text-white text-sm font-black rounded-xl shadow-[0_8px_20px_rgba(13,148,136,0.25)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-slate-100 rounded-[24px] shadow-[0_8px_30px_rgba(13,148,136,0.04)] overflow-hidden h-max">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                <i class="fas fa-shield-alt text-amber-500"></i>
                <h3 class="font-extrabold text-slate-800 font-poppins">Keamanan Akun</h3>
            </div>
            
            <form action="<?php echo e(route('password.change.post')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Password Saat Ini</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-unlock-alt text-slate-400"></i></div>
                            <input type="password" name="current_password" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-key text-slate-400"></i></div>
                            <input type="password" name="new_password" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Ulangi Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-check-double text-slate-400"></i></div>
                            <input type="password" name="new_password_confirmation" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl outline-none font-semibold focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all shadow-inner">
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" onclick="showUserLoader()" class="w-full sm:w-auto px-6 py-3.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-black rounded-xl shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/profile/edit.blade.php ENDPATH**/ ?>