
<?php $__env->startSection('title', 'Masuk Sistem | PosyanduCare'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* CSS Animasi Maksmal */
    .fade-in-up { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .fade-in-left { opacity: 0; animation: fadeInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
    
    .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; } .delay-400 { animation-delay: 0.4s; }

    /* Animasi Blobs Latar Belakang */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 15s infinite alternate; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }

    /* Animasi Floating Card Panel Kiri */
    @keyframes float-widget {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(1deg); }
    }
    .float-widget-1 { animation: float-widget 6s ease-in-out infinite; }
    .float-widget-2 { animation: float-widget 7s ease-in-out infinite reverse; }

    /* Premium Input Fields */
    .input-premium {
        width: 100%; background: #f8fafc; border: 2px solid transparent; color: #0f172a;
        font-size: 0.875rem; font-weight: 700; border-radius: 1.25rem; padding: 1.2rem 1.2rem 1.2rem 3.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none;
    }
    .input-premium::placeholder { color: #94a3b8; font-weight: 600; }
    .input-premium:focus {
        background: #ffffff; border-color: #3b82f6; box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }
    .input-icon {
        position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%);
        color: #94a3b8; transition: all 0.3s ease; font-size: 1.1rem; z-index: 10;
    }
    .input-premium:focus ~ .input-icon { color: #3b82f6; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="relative w-full h-full max-w-[1200px] flex items-center justify-center p-4 sm:p-8 z-10 fade-in-up">
    
    
    <div class="w-full bg-white rounded-[2.5rem] sm:rounded-[3rem] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.1)] border border-white flex flex-col md:flex-row overflow-hidden min-h-[600px] md:min-h-[700px]">
        
        
        <div class="hidden md:flex md:w-5/12 lg:w-1/2 relative p-12 flex-col justify-center overflow-hidden bg-gradient-to-br from-[#0f172a] via-[#1e3a8a] to-[#0ea5e9]">
            
            
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500/30 blur-[80px] rounded-full"></div>
            
            <div class="relative z-10 fade-in-left delay-100">
                <div class="w-16 h-16 rounded-[1.2rem] bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-3xl text-sky-400 mb-8 shadow-2xl">
                    <i class="fas fa-shield-heart"></i>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1] mb-6 font-poppins tracking-tight">Kesehatan<br>Digital<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-teal-300">Posyandu.</span></h2>
                <p class="text-sm font-medium text-blue-100/80 leading-relaxed max-w-sm mb-10">Sistem pendataan terpadu generasi baru. Pantau gizi balita, kehamilan, dan lansia secara *real-time* & terenkripsi.</p>
                
                
                <div class="relative h-32 w-full mt-4 hidden lg:block">
                    
                    <div class="absolute top-0 left-0 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 shadow-xl flex items-center gap-4 w-64 float-widget-1">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center"><i class="fas fa-check"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Sistem Server</p>
                            <p class="text-sm font-black text-white">Online & Aman</p>
                        </div>
                    </div>
                    
                    <div class="absolute top-12 left-32 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 shadow-xl flex items-center gap-4 w-56 float-widget-2">
                        <div class="w-10 h-10 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center"><i class="fas fa-users"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Data Terpadu</p>
                            <p class="text-sm font-black text-white">Terintegrasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="w-full md:w-7/12 lg:w-1/2 p-8 sm:p-12 lg:px-20 flex flex-col justify-center bg-white relative">
            
            <div class="text-center md:text-left mb-10 fade-in-up delay-100">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-sky-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-6 mx-auto md:mx-0 shadow-sm border border-blue-200 md:hidden">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins mb-2">Masuk Sistem</h1>
                <p class="text-[13px] font-semibold text-slate-500">Masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            <form method="POST" action="<?php echo e(route('login.post')); ?>" id="loginForm" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div class="fade-in-up delay-200 relative">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kredensial Identitas <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <input type="text" name="login" value="<?php echo e(old('login')); ?>" class="input-premium" placeholder="NIK, Email, atau Username" required autofocus autocomplete="off">
                        <i class="fas fa-id-badge input-icon"></i>
                    </div>
                </div>

                <div class="fade-in-up delay-300 relative">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" class="input-premium pr-14" placeholder="Masukkan password rahasia" required autocomplete="current-password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between fade-in-up delay-300 pl-1 pt-2">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative w-[22px] h-[22px] rounded-[7px] border-2 border-slate-300 bg-white group-hover:border-blue-500 transition-all overflow-hidden flex items-center justify-center shadow-sm">
                            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> class="peer absolute w-full h-full opacity-0 cursor-pointer z-20">
                            <div class="absolute inset-0 bg-blue-600 scale-0 peer-checked:scale-100 transition-transform duration-200 ease-out"></div>
                            <i class="fas fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100 relative z-10 transition-opacity delay-100"></i>
                        </div>
                        <span class="ml-3 text-[13px] font-bold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat sesi login saya</span>
                    </label>
                </div>

                <div class="pt-6 fade-in-up delay-400">
                    <button type="submit" id="btnSubmit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-[13px] font-black tracking-[0.2em] uppercase rounded-[1.25rem] transition-all duration-300 shadow-[0_10px_30px_rgba(37,99,235,0.3)] hover:shadow-[0_15px_35px_rgba(37,99,235,0.4)] hover:-translate-y-1 flex items-center justify-center gap-3 overflow-hidden relative">
                        <span id="btnText" class="relative z-10">Otentikasi Masuk</span>
                        <i class="fas fa-arrow-right text-[11px] relative z-10" id="btnIcon"></i>
                        <div class="absolute inset-0 bg-white/20 translate-y-full hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    </button>
                </div>
            </form>
            
            <div class="mt-12 text-center fade-in-up delay-400">
                <p class="text-[11px] font-black text-slate-400/80 uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt text-slate-300"></i> End-to-End Encryption
                </p>
            </div>
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Fitur Toggle Show/Hide Password
    function togglePassword() {
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            eyeIcon.classList.add('text-blue-500');
        } else {
            pwdInput.type = 'password';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            eyeIcon.classList.remove('text-blue-500');
        }
    }

    // Animasi Tombol Canggih saat Loading
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        
        btn.disabled = true;
        btn.classList.add('opacity-90', 'cursor-wait');
        btnIcon.classList.replace('fa-arrow-right', 'fa-spinner');
        btnIcon.classList.add('fa-spin', 'text-sm');
        btnText.innerText = 'MEMVERIFIKASI...';
    });
</script>


<?php if($errors->any() || session('info')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        <?php if($errors->any()): ?>
            // Jika Salah Password / NIK Tidak Ditemukan
            Swal.fire({
                toast: true, position: 'top', icon: 'error',
                title: 'Akses Ditolak!', text: '<?php echo e($errors->first()); ?>',
                showConfirmButton: false, timer: 4500, timerProgressBar: true,
                background: '#fff1f2', color: '#be123c', iconColor: '#e11d48',
                customClass: { popup: 'rounded-2xl border border-rose-100 shadow-xl font-sans' }
            });
        <?php endif; ?>

        <?php if(session('info')): ?>
            // Jika Berhasil Logout
            Swal.fire({
                toast: true, position: 'top', icon: 'success',
                title: 'Informasi', text: '<?php echo e(session('info')); ?>',
                showConfirmButton: false, timer: 3500, timerProgressBar: true,
                background: '#f0fdf4', color: '#15803d', iconColor: '#16a34a',
                customClass: { popup: 'rounded-2xl border border-emerald-100 shadow-xl font-sans' }
            });
        <?php endif; ?>
        
    });
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/auth/login.blade.php ENDPATH**/ ?>