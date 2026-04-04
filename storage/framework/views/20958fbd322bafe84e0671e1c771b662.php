

<?php $__env->startSection('title', 'Identity Center'); ?>
<?php $__env->startSection('page-name', 'Pusat Identitas'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.06); transition: all 0.4s ease; }
    .input-premium { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px 16px 14px 44px; width: 100%; font-size: 13px; font-weight: 600; color: #1e293b; transition: all 0.3s ease; outline: none; }
    .input-premium:focus { background-color: #ffffff; border-color: #818cf8; box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15); }
    .input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; transition: all 0.3s ease; }
    .input-group:focus-within .input-icon { color: #6366f1; }
</style>

<div class="max-w-[1200px] mx-auto relative pb-10">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins mb-1">Identity Center</h1>
            <p class="text-slate-500 font-medium text-[14px]">Kelola identitas personal, kredensial, dan kontak Anda.</p>
        </div>
        <a href="<?php echo e(route('kader.profile.password')); ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold text-[13px] rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group">
            <i class="fas fa-shield-alt text-slate-400 group-hover:text-rose-500 transition-colors"></i> Keamanan & Sandi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 animate-slide-up" style="animation-delay: 0.1s;">
        
        
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="glass-card rounded-[32px] p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-indigo-500 to-violet-600"></div>
                
                <div class="relative z-10 w-28 h-28 mx-auto rounded-[1rem] bg-white p-2 shadow-xl mb-4 mt-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                    <div class="w-full h-full rounded-xl bg-slate-100 flex items-center justify-center text-4xl font-black text-indigo-300 border border-slate-200">
                        <?php echo e(strtoupper(substr($user->profile->full_name ?? $user->name ?? 'K', 0, 1))); ?>

                    </div>
                </div>

                <div class="relative z-10">
                    <h3 class="text-xl font-black text-slate-800 font-poppins leading-tight"><?php echo e($user->profile->full_name ?? $user->name); ?></h3>
                    <p class="text-[12px] font-bold text-indigo-500 uppercase tracking-widest mt-1 mb-6">Petugas / Kader Aktif</p>
                    
                    <div class="flex flex-col gap-3 text-left">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0"><i class="fas fa-envelope text-xs"></i></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Email Terdaftar</p>
                                <p class="text-[12px] font-bold text-slate-700 truncate"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="fas fa-calendar-check text-xs"></i></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bergabung Sejak</p>
                                <p class="text-[12px] font-bold text-slate-700 truncate"><?php echo e($user->created_at->translatedFormat('d F Y')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-[24px] p-6 bg-gradient-to-br from-slate-800 to-slate-900 border-none relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-white/5 text-6xl"><i class="fas fa-fingerprint"></i></div>
                <h4 class="text-white font-black font-poppins text-lg mb-2 relative z-10">Jejak Audit</h4>
                <p class="text-slate-400 text-[11px] font-medium leading-relaxed relative z-10">Setiap data medis yang Anda input akan ditandatangani secara digital menggunakan ID ini untuk akuntabilitas sistem.</p>
            </div>
        </div>

        
        <div class="lg:col-span-2 glass-card rounded-[32px] overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-slate-100 bg-white/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg border border-indigo-100"><i class="fas fa-user-edit"></i></div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 font-poppins leading-none">Formulir Identitas Lengkap</h3>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi data Anda di bawah ini</p>
                </div>
            </div>

            <form action="<?php echo e(route('kader.profile.update')); ?>" method="POST" class="flex flex-col flex-1">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                
                <div class="p-8 space-y-6 flex-1">
                    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Nama Lengkap Sesuai KTP</label>
                            <div class="relative input-group">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="name" value="<?php echo e(old('name', $user->profile->full_name ?? $user->name)); ?>" required class="input-premium">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Nomor Induk Kependudukan (NIK)</label>
                            <div class="relative input-group">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="number" name="nik" value="<?php echo e(old('nik', $user->profile->nik ?? '')); ?>" class="input-premium" placeholder="16 Digit NIK">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Alamat Email Akses</label>
                            <div class="relative input-group">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required class="input-premium">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Nomor Handphone / WA</label>
                            <div class="relative input-group">
                                <i class="fas fa-phone-alt input-icon"></i>
                                <input type="number" name="telepon" value="<?php echo e(old('telepon', $user->profile->telepon ?? '')); ?>" class="input-premium" placeholder="Contoh: 08123456789">
                            </div>
                        </div>
                    </div>

                    <div class="h-px w-full bg-slate-100 border-t border-dashed border-slate-200 my-2"></div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tempat Lahir</label>
                            <div class="relative input-group">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $user->profile->tempat_lahir ?? '')); ?>" class="input-premium" placeholder="Kota Lahir">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tanggal Lahir</label>
                            <div class="relative input-group">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', optional($user->profile->tanggal_lahir)->format('Y-m-d') ?? '')); ?>" class="input-premium" style="padding-left: 40px; padding-right: 16px;">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Jenis Kelamin</label>
                            <div class="relative input-group">
                                <i class="fas fa-venus-mars input-icon"></i>
                                <select name="jenis_kelamin" class="input-premium appearance-none cursor-pointer" style="padding-left: 44px; padding-right: 16px;">
                                    <option value="">-- Pilih --</option>
                                    <option value="P" <?php echo e(old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                                    <option value="L" <?php echo e(old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : ''); ?>>Laki-Laki</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Alamat Lengkap</label>
                        <div class="relative input-group">
                            <i class="fas fa-home input-icon" style="top: 24px;"></i>
                            <textarea name="alamat" rows="3" class="input-premium resize-none" placeholder="Tuliskan alamat domisili lengkap..."><?php echo e(old('alamat', $user->profile->alamat ?? '')); ?></textarea>
                        </div>
                    </div>

                </div>

                <div class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 rounded-b-[32px] flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] font-bold text-slate-400"><i class="fas fa-info-circle mr-1"></i> Data yang valid membantu proses pelaporan desa.</p>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-black text-[13px] uppercase tracking-widest rounded-xl hover:from-indigo-600 hover:to-violet-700 shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save"></i> Simpan Pembaruan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/profile/index.blade.php ENDPATH**/ ?>