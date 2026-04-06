

<?php $__env->startSection('title', 'Masuk | PosyanduCare'); ?>

<?php $__env->startPush('styles'); ?>
<style type="text/tailwindcss">
    /* 1. Animasi Latar Belakang Bergerak Halus (Aurora) */
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

    /* 2. Kartu Glassmorphism (Wadah Utama) */
    .glass-card {
        @apply bg-white/80 backdrop-blur-2xl border border-white/50 shadow-[0_20px_60px_rgba(13,148,136,0.08)];
    }

    /* 3. Input Super Modern & Enak Dipencet */
    .input-premium {
        @apply w-full bg-slate-50/50 border-2 border-slate-100 text-slate-800 text-sm font-bold rounded-2xl py-4 pl-12 pr-12 transition-all duration-300 outline-none;
    }
    .input-premium:focus {
        @apply bg-white border-teal-400 ring-4 ring-teal-400/10 shadow-sm transform -translate-y-0.5;
    }
    .input-premium::placeholder {
        @apply font-medium text-slate-400;
    }
    .input-icon {
        @apply absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors duration-300;
    }
    .group-focus-within .input-icon {
        @apply text-teal-500;
    }

    /* 4. Animasi Masuk Bergelombang (Staggered Fade Up) */
    .fade-up { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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
                <div class="w-14 h-14 rounded-[18px] bg-gradient-to-br from-teal-400 to-sky-500 text-white flex items-center justify-center shadow-lg shadow-teal-500/30 mx-auto lg:mx-0 mb-6 transform hover:scale-105 transition-transform">
                    <i class="fas fa-heartbeat text-3xl"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 font-poppins mb-3 tracking-tight">Selamat Datang</h1>
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
                    <div class="relative group group-focus-within">
                        <i class="fas fa-id-badge input-icon"></i>
                        <input type="text" name="login" value="<?php echo e(old('login')); ?>" class="input-premium" placeholder="NIK, Email, atau Username" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="fade-up delay-3">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="relative group group-focus-within">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="input-premium" placeholder="Masukkan kata sandi rahasia" required autocomplete="current-password">
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-teal-600 transition-colors">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 pl-1 fade-up delay-3">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded-lg checked:bg-teal-500 checked:border-teal-500 cursor-pointer transition-all">
                            <i class="fas fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                        </div>
                        <span class="ml-3 text-[13px] font-bold text-slate-500 group-hover:text-slate-800 transition-colors">Ingat sesi saya</span>
                    </label>
                </div>

                <div class="pt-4 fade-up delay-4">
                    <button type="submit" id="btnSubmitAuth" class="w-full py-4 bg-slate-900 hover:bg-teal-600 text-white text-[14px] font-black rounded-2xl shadow-[0_8px_20px_rgba(0,0,0,0.1)] hover:shadow-[0_15px_30px_rgba(13,148,136,0.3)] transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 tracking-widest uppercase overflow-hidden relative group">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
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
                    <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-8 shadow-inner">
                        <i class="fas fa-shield-heart text-3xl text-white"></i>
                    </div>
                    
                    <h2 class="text-3xl xl:text-4xl font-black font-poppins text-white leading-tight mb-5 tracking-tight">Kesehatan<br>Digital<br>Terpadu.</h2>
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
    // Konfigurasi Tailwind untuk Animasi Kilau Tombol
    tailwind.config = {
        theme: {
            extend: {
                keyframes: {
                    shimmer: {
                        '100%': { transform: 'translateX(100%)' }
                    }
                }
            }
        }
    }

    // 👁️ Fitur Tampil/Sembunyi Password
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

    // 🌟 ANIMASI TRANSAKSI LOGIN SUKSES
    document.getElementById('premiumLoginForm').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        const form = this;
        const btn = document.getElementById('btnSubmitAuth');
        const overlay = document.getElementById('loginSuccessOverlay');
        const successCircle = document.getElementById('successCircle');

        // 1. Ubah tombol jadi status loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> <span class="ml-2">VERIFIKASI...</span>';
        btn.classList.add('bg-teal-600', 'opacity-90', 'cursor-wait');
        
        // 2. Munculkan Layar Animasi Centang setelah sedikit jeda
        setTimeout(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            
            // Animasi Lingkaran Putih melebar menutupi layar
            successCircle.classList.remove('w-0', 'h-0');
            successCircle.classList.add('w-[300vh]', 'h-[300vh]');

            // 3. Submit data asli ke Laravel Controller
            setTimeout(() => {
                form.submit();
            }, 1800);

        }, 700);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/auth/login.blade.php ENDPATH**/ ?>