<?php
    $userAuth = auth()->user();
    
    // Inisialisasi default role
    $isOrangTua = false; 
    $isRemaja   = false; 
    $isLansia   = false; 
    $isIbuHamil = false;

    if ($userAuth) {
        // MENGGUNAKAN TRAIT: Performa super cepat, tidak ada query N+1 di dalam View
        $roleChecker = new class {
            use \App\Traits\DetectsUserPeran;
        };
        
        $ctx = $roleChecker->getUserContext($userAuth);
        $peran = $ctx['peran'] ?? [];
        
        $isOrangTua = in_array('orang_tua', $peran);
        $isRemaja   = in_array('remaja', $peran);
        $isLansia   = in_array('lansia', $peran);
        // Mendukung penamaan 'bumil' atau 'ibu_hamil' dari controller
        $isIbuHamil = in_array('bumil', $peran) || in_array('ibu_hamil', $peran); 
    }

    // Helper untuk styling menu aktif yang lebih "Bold" dan jelas
    if (!function_exists('user_nav_active')) {
        function user_nav_active($route) {
            return request()->routeIs($route) 
                ? 'bg-teal-50 text-teal-800 font-bold border-r-4 border-teal-500 shadow-sm' 
                : 'text-slate-600 font-medium hover:bg-slate-50 hover:text-teal-600 transition-colors border-r-4 border-transparent';
        }
    }
    
    // Helper untuk styling icon
    if (!function_exists('user_nav_icon')) {
        function user_nav_icon($route) {
            return request()->routeIs($route) ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-500 transition-colors';
        }
    }
?>

<div class="flex flex-col h-full bg-white relative font-poppins border-r border-slate-100">
    
    <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0">
        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm border border-teal-100 mr-3">
            <i class="fas fa-heartbeat text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">Posyandu<span class="text-teal-600">Care</span></h2>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Portal Warga</p>
        </div>
    </div>

    <div class="p-5 pb-2 shrink-0">
        <div class="p-4 bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-full bg-teal-600 text-white flex items-center justify-center text-lg font-black shrink-0 shadow-inner">
                <?php echo e(strtoupper(substr($userAuth->name ?? 'U', 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate"><?php echo e($userAuth->name ?? 'Pengguna'); ?></p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <p class="text-[11px] font-bold text-slate-500">Akses Terhubung</p>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-7 custom-scrollbar">
        
        <div>
            <p class="px-3 text-[11px] font-black text-slate-400 uppercase tracking-wider mb-3">Layanan Utama</p>
            <div class="space-y-1.5">
                <a href="<?php echo e(route('user.dashboard')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.dashboard')); ?>">
                    <i class="fas fa-home w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.dashboard')); ?>"></i>
                    <span class="text-sm tracking-wide">Beranda Saya</span>
                </a>
                <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.jadwal.*')); ?>">
                    <i class="fas fa-calendar-check w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.jadwal.*')); ?>"></i>
                    <span class="text-sm tracking-wide">Jadwal Posyandu</span>
                </a>
                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.notifikasi.*')); ?>">
                    <i class="fas fa-envelope-open-text w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.notifikasi.*')); ?>"></i>
                    <span class="text-sm tracking-wide flex-1">Pesan Bidan</span>
                    <span id="sidebar-notif-badge" class="hidden bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Baru</span>
                </a>
            </div>
        </div>

        <?php if($isOrangTua || $isRemaja || $isLansia || $isIbuHamil): ?>
            <div>
                <p class="px-3 text-[11px] font-black text-slate-400 uppercase tracking-wider mb-3">Kesehatan Keluarga</p>
                <div class="space-y-1.5">
                    
                    <?php if($isIbuHamil): ?>
                        <a href="<?php echo e(route('user.ibu-hamil.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.ibu-hamil.*')); ?>">
                            <i class="fas fa-female w-6 text-center text-[1.15rem] text-pink-500 group-hover:text-pink-600 transition-colors"></i>
                            <span class="text-sm tracking-wide">Buku KIA Kandungan</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isOrangTua): ?>
                        <a href="<?php echo e(route('user.balita.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.balita.*')); ?>">
                            <i class="fas fa-baby w-6 text-center text-[1.15rem] text-sky-500 group-hover:text-sky-600 transition-colors"></i>
                            <span class="text-sm tracking-wide">Kesehatan Anak</span>
                        </a>
                        <a href="<?php echo e(route('user.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.imunisasi.*')); ?>">
                            <i class="fas fa-shield-virus w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.imunisasi.*')); ?>"></i>
                            <span class="text-sm tracking-wide">Riwayat Imunisasi</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isRemaja): ?>
                        <a href="<?php echo e(route('user.remaja.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.remaja.*')); ?>">
                            <i class="fas fa-user-graduate w-6 text-center text-[1.15rem] text-indigo-500 group-hover:text-indigo-600 transition-colors"></i>
                            <span class="text-sm tracking-wide">Cek Fisik Remaja</span>
                        </a>
                        <a href="<?php echo e(route('user.konseling.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.konseling.*')); ?>">
                            <i class="fas fa-comments-medical w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.konseling.*')); ?>"></i>
                            <span class="text-sm tracking-wide">Tanya Bidan (Chat)</span>
                        </a>
                    <?php endif; ?>

                    <?php if($isLansia): ?>
                        <a href="<?php echo e(route('user.lansia.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.lansia.*')); ?>">
                            <i class="fas fa-wheelchair w-6 text-center text-[1.15rem] text-orange-500 group-hover:text-orange-600 transition-colors"></i>
                            <span class="text-sm tracking-wide">Pantau Kesehatan Lansia</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="mx-3 mt-4 p-5 bg-orange-50 border border-orange-200 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4 class="text-sm font-bold text-orange-800 tracking-wide">Fitur Terkunci</h4>
                </div>
                <p class="text-xs font-medium text-orange-700 leading-relaxed mb-4">Silakan masukkan NIK Anda di menu profil agar kami dapat menampilkan data kesehatan Anda.</p>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-orange-500 text-white text-xs font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-sm">
                    <span>Lengkapi NIK</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        <?php endif; ?>

        <div>
            <p class="px-3 text-[11px] font-black text-slate-400 uppercase tracking-wider mb-3">Sistem</p>
            <div class="space-y-1.5">
                <a href="<?php echo e(route('user.riwayat.index')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.riwayat.*')); ?>">
                    <i class="fas fa-notes-medical w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.riwayat.*')); ?>"></i>
                    <span class="text-sm tracking-wide">Rekam Medis Keluarga</span>
                </a>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route group flex items-center gap-3.5 px-3 py-3.5 rounded-xl <?php echo e(user_nav_active('user.profile.*')); ?>">
                    <i class="fas fa-user-cog w-6 text-center text-[1.15rem] <?php echo e(user_nav_icon('user.profile.*')); ?>"></i>
                    <span class="text-sm tracking-wide">Pengaturan Akun</span>
                </a>
            </div>
        </div>

    </nav>
    
    <div class="p-5 border-t border-slate-100 shrink-0 bg-slate-50">
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0 p-0">
            <?php echo csrf_field(); ?>
            <button type="submit" onclick="showGlobalLoader()" class="w-full flex items-center justify-center gap-2.5 px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-rose-500 font-bold hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                <i class="fas fa-sign-out-alt text-lg"></i>
                <span class="text-sm tracking-wide">Keluar (Logout)</span>
            </button>
        </form>
    </div>
</div><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/partials/sidebar/user.blade.php ENDPATH**/ ?>