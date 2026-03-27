<?php
    $userAuth = auth()->user();
    $nikAuth = $userAuth->nik ?? ($userAuth->profile->nik ?? null);
    
    $isOrangTua = false; $isRemaja = false; $isLansia = false;

    if ($nikAuth) {
        $isOrangTua = \App\Models\Balita::where('nik_ibu', $nikAuth)->orWhere('nik_ayah', $nikAuth)->exists();
        $isRemaja = \App\Models\Remaja::where('nik', $nikAuth)->exists();
        $isLansia = \App\Models\Lansia::where('nik', $nikAuth)->exists();
    }

    if (!function_exists('user_nav_active')) {
        function user_nav_active($route) {
            return request()->routeIs($route) 
                ? 'bg-teal-50 text-teal-700 font-bold border-r-4 border-teal-500 shadow-sm' 
                : 'text-slate-500 font-semibold hover:bg-slate-50 hover:text-teal-600 transition-colors border-r-4 border-transparent';
        }
    }
    
    if (!function_exists('user_nav_icon')) {
        function user_nav_icon($route) {
            return request()->routeIs($route) ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-500 transition-colors';
        }
    }
?>

<div class="flex flex-col h-full bg-white relative">
    
    <div class="h-20 flex items-center px-6 border-b border-slate-100 shrink-0">
        <div class="w-10 h-10 rounded-[12px] bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm border border-teal-100 mr-3">
            <i class="fas fa-hand-holding-medical text-lg"></i>
        </div>
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none font-poppins">Posyandu<span class="text-teal-600">Care</span></h2>
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mt-1">Portal Warga</p>
        </div>
    </div>

    <div class="p-6 pb-2 shrink-0">
        <div class="p-4 bg-white border border-slate-200 rounded-2xl flex items-center gap-3 shadow-sm group hover:border-teal-200 transition-colors">
            <div class="w-11 h-11 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-lg font-black shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                <?php echo e(strtoupper(substr($userAuth->name ?? 'U', 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate"><?php echo e($userAuth->name ?? 'Pengguna'); ?></p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Akses Terhubung</p>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6 custom-scrollbar">
        
        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Layanan Utama</p>
            <div class="space-y-1">
                <a href="<?php echo e(route('user.dashboard')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.dashboard')); ?>">
                    <i class="fas fa-home w-5 text-center text-lg <?php echo e(user_nav_icon('user.dashboard')); ?>"></i>
                    <span class="text-[13px]">Beranda Utama</span>
                </a>
                <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.jadwal.*')); ?>">
                    <i class="fas fa-calendar-alt w-5 text-center text-lg <?php echo e(user_nav_icon('user.jadwal.*')); ?>"></i>
                    <span class="text-[13px]">Agenda & Jadwal</span>
                </a>
                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.notifikasi.*')); ?>">
                    <i class="fas fa-envelope-open-text w-5 text-center text-lg <?php echo e(user_nav_icon('user.notifikasi.*')); ?>"></i>
                    <span class="text-[13px] flex-1">Pesan dari Bidan</span>
                </a>
            </div>
        </div>

        <?php if($isOrangTua || $isRemaja || $isLansia): ?>
            <div>
                <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kesehatan Keluarga</p>
                <div class="space-y-1">
                    <?php if($isOrangTua): ?>
                        <a href="<?php echo e(route('user.balita.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.balita.*')); ?>">
                            <i class="fas fa-baby w-5 text-center text-lg <?php echo e(user_nav_icon('user.balita.*')); ?>"></i>
                            <span class="text-[13px]">Tumbuh Kembang Anak</span>
                        </a>
                        <a href="<?php echo e(route('user.imunisasi.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.imunisasi.*')); ?>">
                            <i class="fas fa-syringe w-5 text-center text-lg <?php echo e(user_nav_icon('user.imunisasi.*')); ?>"></i>
                            <span class="text-[13px]">Riwayat Imunisasi</span>
                        </a>
                    <?php endif; ?>
                    <?php if($isRemaja): ?>
                        <a href="<?php echo e(route('user.remaja.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.remaja.*')); ?>">
                            <i class="fas fa-user-graduate w-5 text-center text-lg <?php echo e(user_nav_icon('user.remaja.*')); ?>"></i>
                            <span class="text-[13px]">Kesehatan Remaja</span>
                        </a>
                        <a href="<?php echo e(route('user.konseling.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.konseling.*')); ?>">
                            <i class="fas fa-comments-medical w-5 text-center text-lg <?php echo e(user_nav_icon('user.konseling.*')); ?>"></i>
                            <span class="text-[13px]">Ruang Konseling</span>
                        </a>
                    <?php endif; ?>
                    <?php if($isLansia): ?>
                        <a href="<?php echo e(route('user.lansia.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.lansia.*')); ?>">
                            <i class="fas fa-wheelchair w-5 text-center text-lg <?php echo e(user_nav_icon('user.lansia.*')); ?>"></i>
                            <span class="text-[13px]">Pemantauan Lansia</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="mx-3 mt-2 p-4 bg-rose-50 border border-rose-200 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-id-card text-rose-500"></i>
                    <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wide">Data Terkunci</h4>
                </div>
                <p class="text-[11px] font-medium text-rose-600 leading-relaxed mb-3">Lengkapi NIK di profil Anda agar sistem dapat memuat rekam medis otomatis.</p>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="inline-block w-full text-center py-2 bg-rose-500 text-white text-[10px] font-bold rounded-lg hover:bg-rose-600 transition-colors shadow-sm">Lengkapi NIK</a>
            </div>
        <?php endif; ?>

        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pengaturan</p>
            <div class="space-y-1">
                <a href="<?php echo e(route('user.riwayat.index')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.riwayat.*')); ?>">
                    <i class="fas fa-file-medical w-5 text-center text-lg <?php echo e(user_nav_icon('user.riwayat.*')); ?>"></i>
                    <span class="text-[13px]">Buku Rekam Medis</span>
                </a>
                <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route group flex items-center gap-3 px-3 py-3 rounded-xl <?php echo e(user_nav_active('user.profile.*')); ?>">
                    <i class="fas fa-user-cog w-5 text-center text-lg <?php echo e(user_nav_icon('user.profile.*')); ?>"></i>
                    <span class="text-[13px]">Data Profil Anda</span>
                </a>
            </div>
        </div>

    </nav>
    
    <div class="p-5 border-t border-slate-100 shrink-0 bg-slate-50/50">
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0 p-0">
            <?php echo csrf_field(); ?>
            <button type="submit" onclick="showGlobalLoader()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl text-rose-500 font-bold hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span class="text-[13px]">Keluar Akun</span>
            </button>
        </form>
    </div>
</div><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/partials/sidebar/user.blade.php ENDPATH**/ ?>