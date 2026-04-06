

<?php $__env->startSection('title', 'Masuk | PosyanduCare'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .bg-aurora {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #f1f5f9;
        overflow: hidden;
        z-index: -1;
    }
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        animation: floatOrb 20s infinite alternate ease-in-out;
    }
    .orb-1 { width: 50vw; height: 50vw; background: #99f6e4; top: -10%; left: -10%; animation-duration: 25s; }
    .orb-2 { width: 60vw; height: 60vw; background: #bae6fd; bottom: -20%; right: -10%; animation-duration: 20s; animation-direction: alternate-reverse; }
    .orb-3 { width: 40vw; height: 40vw; background: #ddd6fe; top: 30%; left: 40%; animation-duration: 30s; }

    @keyframes floatOrb {
        0% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(5%, 10%) scale(1.1); }
        100% { transform: translate(-5%, -5%) scale(0.9); }
    }

    .glass-card {
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 20px 60px rgba(13,148,136,0.08);
    }

    .input-premium {
        width: 100%;
        background: rgba(248,250,252,0.5);
        border: 2px solid #f1f5f9;
        color: #1e293b;
        font-size: 0.875rem;
        font-weight: 700;
        border-radius: 1rem;
        padding: 1rem 3rem;
        transition: all 0.3s;
        outline: none;
    }
    .input-premium:focus {
        background: white;
        border-color: #2dd4bf;
        box-shadow: 0 0 0 4px rgba(45,212,191,0.1);
        transform: translateY(-2px);
    }
    .input-premium::placeholder {
        font-weight: 500;
        color: #94a3b8;
    }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: color 0.3s;
    }

    .fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-aurora">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="min-h-screen flex items-center justify-center p-4 sm:p-8 relative z-10">

    <div class="w-full max-w-[1000px] glass-card rounded-[32px] sm:rounded-[40px] flex flex-col lg:flex-row overflow-hidden fade-up">

        <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-14 flex flex-col justify-center bg-white/60">

            <div class="text-center lg:text-left mb-10 fade-up delay-1">
                <div class="w-14 h-14 rounded-[18px] bg-gradient-to-br from-teal-400 to-sky-500 text-white flex items-center justify-center shadow-lg mx-auto lg:mx-0 mb-6">
                    <i class="fas fa-heartbeat text-3xl"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3 tracking-tight">Selamat Datang</h1>
                <p class="text-sm font-medium text-slate-500 leading-relaxed max-w-sm mx-auto lg:mx-0">Portal layanan rekam medis, gizi balita, dan kesehatan terpadu.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm fade-up delay-2">
                    <i class="fas fa-exclamation-circle text-rose-500 text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <h4 class="text-[11px] font-black text-rose-800 uppercase tracking-widest mb-1">Akses Ditolak</h4>
                        <p class="text-[12.5px] font-semibold text-rose-600"><?php echo e($errors->first()); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-6" id="premiumLoginForm">
                <?php echo csrf_field(); ?>

                <div class="fade-up delay-2">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kredensial Masuk <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-id-badge input-icon"></i>
                        <input type="text" name="login" value="<?php echo e(old('login')); ?>" class="input-premium" placeholder="NIK, Email, atau Username" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="fade-up delay-3">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="input-premium" placeholder="Masukkan kata sandi rahasia" required autocomplete="current-password">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-teal-600 transition-colors">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-2 pl-1 fade-up delay-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>

                            class="w-5 h-5 border-2 border-slate-300 rounded-lg cursor-pointer"
                            style="accent-color: #14b8a6;">
                        <span class="ml-3 text-[13px] font-bold text-slate-500">Ingat sesi saya</span>
                    </label>
                </div>

                <div class="pt-4 fade-up delay-4">
                    <button type="submit" id="btnSubmitAuth"
                        class="w-full py-4 bg-slate-900 hover:bg-teal-600 text-white text-[14px] font-black rounded-2xl shadow-lg hover:shadow-teal-500/30 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 tracking-widest uppercase">
                        <span>Masuk Sistem</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="hidden lg:flex w-1/2 relative overflow-hidden items-center justify-center p-12 bg-gradient-to-br from-teal-500 to-sky-600">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-teal-300/30 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>

            <div class="relative z-10 w-full max-w-sm">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[32px] p-10 shadow-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-8">
                        <i class="fas fa-shield-heart text-3xl text-white"></i>
                    </div>
                    <h2 class="text-3xl xl:text-4xl font-black text-white leading-tight mb-5 tracking-tight">Kesehatan<br>Digital<br>Terpadu.</h2>
                    <p class="text-[14px] text-sky-50 font-medium leading-relaxed opacity-90 mb-10">Pencatatan rekam medis terenkripsi, pemantauan gizi anak, dan jadwal imunisasi yang terintegrasi langsung dengan Desa.</p>
                    <div class="flex items-center gap-4">
                        <div class="flex -space-x-3 mr-2">
                            <div class="w-10 h-10 rounded-full bg-rose-400 border-2 border-teal-500 flex items-center justify-center text-white text-xs shadow-md"><i class="fas fa-baby"></i></div>
                            <div class="w-10 h-10 rounded-full bg-sky-400 border-2 border-teal-500 flex items-center justify-center text-white text-xs shadow-md"><i class="fas fa-user-graduate"></i></div>
                            <div class="w-10 h-10 rounded-full bg-amber-400 border-2 border-teal-500 flex items-center justify-center text-white text-xs shadow-md"><i class="fas fa-wheelchair"></i></div>
                        </div>
                        <span class="text-[10px] font-black text-white tracking-widest uppercase">Semua Warga</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function togglePassword() {
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye', 'text-teal-600');
        } else {
            pwdInput.type = 'password';
            eyeIcon.classList.remove('fa-eye', 'text-teal-600');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }

    document.getElementById('premiumLoginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitAuth');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> <span class="ml-2">VERIFIKASI...</span>';
        btn.style.backgroundColor = '#0d9488';
        // Form submit normal - TIDAK di-preventDefault
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/auth/login.blade.php ENDPATH**/ ?>