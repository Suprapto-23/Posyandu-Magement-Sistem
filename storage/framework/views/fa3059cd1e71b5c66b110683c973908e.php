

<?php $__env->startSection('title', 'Beranda Saya'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards; }
    .animate-slide-up-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Efek Hover Kartu Menu Cerdas */
    .quick-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .quick-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(20, 184, 166, 0.3); }
    .quick-icon { transition: all 0.3s ease; }
    .quick-card:hover .quick-icon { transform: scale(1.15) rotate(5deg); }
    
    /* Scrollbar minimalis untuk Kotak Masuk */
    .notif-scroll::-webkit-scrollbar { width: 4px; }
    .notif-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full pb-10">

    
    <div class="animate-slide-up bg-gradient-to-r from-teal-600 to-emerald-500 rounded-[32px] p-8 md:p-10 shadow-[0_15px_40px_-10px_rgba(20,184,166,0.4)] relative overflow-hidden mb-8 text-white">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none hidden md:block">
            <i class="fas fa-heartbeat text-[150px]"></i>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/30 mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span> E-Posyandu Aktif
                </span>
                <h2 class="text-3xl md:text-4xl font-black tracking-tight leading-tight font-poppins mb-2">
                    Halo, <?php echo e(explode(' ', Auth::user()->name)[0]); ?>! 👋
                </h2>
                <p class="text-teal-50 font-medium text-[13px] md:text-[14px] max-w-lg leading-relaxed">
                    Selamat datang di Portal Warga. Pantau jadwal imunisasi, rekam medis, dan perkembangan kesehatan keluarga Anda secara mudah dalam satu layar.
                </p>
            </div>
        </div>
    </div>

    
    <?php if(isset($pesanError) && $pesanError): ?>
    <div class="animate-slide-up-1 bg-gradient-to-r from-rose-50 to-orange-50 border border-rose-200 p-5 rounded-[24px] flex items-center gap-5 shadow-sm mb-8 relative overflow-hidden">
        <i class="fas fa-id-card-clip absolute -right-4 -bottom-6 text-6xl text-rose-500/10 pointer-events-none"></i>
        <div class="w-12 h-12 rounded-[16px] bg-white text-rose-500 flex items-center justify-center text-2xl shrink-0 shadow-sm border border-rose-100">
            <i class="fas fa-lock animate-pulse"></i>
        </div>
        <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <h4 class="text-[13px] font-black text-rose-800 uppercase tracking-widest">Akses Terbatas</h4>
                <p class="text-[12px] font-medium text-rose-600 mt-0.5 leading-snug"><?php echo e($pesanError); ?></p>
            </div>
            <a href="<?php echo e(route('user.profile.edit')); ?>" class="smooth-route shrink-0 inline-flex items-center gap-2 px-5 py-3 bg-rose-500 hover:bg-rose-600 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                Lengkapi NIK <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="animate-slide-up-1 grid grid-cols-4 md:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-5 mb-10">
        <?php
            $menus = [
                ['icon' => 'calendar-check', 'color' => 'teal', 'label' => 'Jadwal', 'route' => 'user.jadwal.index'],
                ['icon' => 'file-medical', 'color' => 'sky', 'label' => 'Riwayat', 'route' => 'user.riwayat.index'],
            ];
            
            if(in_array('orang_tua', $peranUser)) {
                $menus[] = ['icon' => 'baby', 'color' => 'rose', 'label' => 'KMS Anak', 'route' => 'user.balita.index'];
                $menus[] = ['icon' => 'shield-virus', 'color' => 'indigo', 'label' => 'Imunisasi', 'route' => 'user.imunisasi.index'];
            } elseif(in_array('remaja', $peranUser)) {
                $menus[] = ['icon' => 'user-graduate', 'color' => 'emerald', 'label' => 'Remaja', 'route' => 'user.remaja.index'];
                $menus[] = ['icon' => 'comments', 'color' => 'indigo', 'label' => 'Konsul', 'route' => 'user.konseling.index'];
            } elseif(in_array('lansia', $peranUser)) {
                $menus[] = ['icon' => 'wheelchair', 'color' => 'emerald', 'label' => 'Lansia', 'route' => 'user.lansia.index'];
                $menus[] = ['icon' => 'heartbeat', 'color' => 'rose', 'label' => 'Tensi', 'route' => 'user.riwayat.index'];
            } else {
                $menus[] = ['icon' => 'bell', 'color' => 'amber', 'label' => 'Pesan', 'route' => 'user.notifikasi.index'];
                $menus[] = ['icon' => 'user-cog', 'color' => 'slate', 'label' => 'Profil', 'route' => 'user.profile.edit'];
            }
        ?>

        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($m['route'])); ?>" class="smooth-route bg-white border border-slate-100 rounded-[24px] p-4 flex flex-col items-center justify-center gap-3 quick-card">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-[16px] bg-<?php echo e($m['color']); ?>-50 border border-<?php echo e($m['color']); ?>-100 text-<?php echo e($m['color']); ?>-500 flex items-center justify-center text-xl sm:text-2xl quick-icon">
                <i class="fas fa-<?php echo e($m['icon']); ?>"></i>
            </div>
            <span class="text-[10px] sm:text-[11px] font-black text-slate-600 uppercase tracking-widest text-center leading-tight"><?php echo e($m['label']); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        
        <div class="xl:col-span-7 space-y-8 animate-slide-up-2">
            
            
            <div>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="font-black text-slate-800 text-[15px] uppercase tracking-tight font-poppins"><i class="fas fa-calendar-day text-teal-500 mr-2"></i> Agenda Terdekat</h3>
                    <a href="<?php echo e(route('user.jadwal.index')); ?>" class="smooth-route text-[10px] font-black text-teal-600 bg-teal-50 hover:bg-teal-500 hover:text-white px-4 py-2 rounded-xl transition-all uppercase tracking-widest">Semua Jadwal</a>
                </div>
                
                <?php if($jadwalTerdekat && $jadwalTerdekat->isNotEmpty()): ?>
                    <?php $jadwal = $jadwalTerdekat->first(); ?>
                    <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-teal-300 transition-colors">
                        <div class="absolute right-0 top-0 w-2 h-full bg-teal-500"></div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                            <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-[18px] flex flex-col items-center justify-center shrink-0 border border-teal-100">
                                <span class="text-[10px] font-black uppercase mb-0.5"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                                <span class="text-2xl font-black font-poppins leading-none"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d')); ?></span>
                            </div>
                            <div class="flex-1">
                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-500 rounded-md text-[9px] font-black tracking-widest uppercase mb-2 border border-slate-200">
                                    Target: <?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?>

                                </span>
                                <h4 class="font-black text-lg text-slate-800 leading-snug mb-2"><?php echo e($jadwal->judul); ?></h4>
                                <div class="flex flex-wrap gap-4 text-[12px] font-bold text-slate-500">
                                    <span class="flex items-center gap-1.5"><i class="far fa-clock text-slate-300"></i> Pukul <?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> WIB</span>
                                    <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-slate-300"></i> <?php echo e($jadwal->lokasi); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white border-2 border-dashed border-slate-200 rounded-[24px] p-8 text-center">
                        <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 text-2xl">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4 class="text-[14px] font-black text-slate-700 mb-1">Tidak Ada Jadwal</h4>
                        <p class="text-[12px] font-medium text-slate-500">Belum ada agenda Posyandu dalam waktu dekat.</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if(isset($dataAnak) && $dataAnak->isNotEmpty()): ?>
            <div>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h3 class="font-black text-slate-800 text-[15px] uppercase tracking-tight font-poppins"><i class="fas fa-baby text-rose-500 mr-2"></i> Status Kesehatan Balita</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $dataAnak->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $lastPeriksa = \App\Models\Pemeriksaan::where('pasien_id', $anak->id)->where('kategori_pasien', 'balita')->where('status_verifikasi', 'verified')->latest('tanggal_periksa')->first();
                        $umurThn = \Carbon\Carbon::parse($anak->tanggal_lahir)->diff(now())->y;
                        $umurBln = \Carbon\Carbon::parse($anak->tanggal_lahir)->diff(now())->m;
                    ?>
                    
                    <a href="<?php echo e(route('user.balita.show', $anak->id)); ?>" class="smooth-route block bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm hover:shadow-[0_10px_30px_rgba(244,63,94,0.1)] hover:border-rose-300 transition-all group">
                        <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-lg border border-rose-100"><i class="fas fa-child"></i></div>
                                <div>
                                    <h4 class="text-[13px] font-black text-slate-800 leading-none group-hover:text-rose-600 transition-colors"><?php echo e($anak->nama_lengkap); ?></h4>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1"><?php echo e($umurThn); ?> Thn <?php echo e($umurBln); ?> Bln</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-rose-50 group-hover:text-rose-500 transition-colors"><i class="fas fa-arrow-right text-[10px]"></i></div>
                        </div>
                        
                        <div class="flex justify-between items-center gap-2">
                            <div class="flex-1 text-center">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Berat</p>
                                <p class="text-[14px] font-black text-slate-700"><?php echo e($lastPeriksa->berat_badan ?? '-'); ?> <span class="text-[10px] text-slate-400 font-bold">kg</span></p>
                            </div>
                            <div class="w-px h-6 bg-slate-200"></div>
                            <div class="flex-1 text-center">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tinggi</p>
                                <p class="text-[14px] font-black text-slate-700"><?php echo e($lastPeriksa->tinggi_badan ?? '-'); ?> <span class="text-[10px] text-slate-400 font-bold">cm</span></p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="xl:col-span-5 animate-slide-up-2">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="font-black text-slate-800 text-[15px] uppercase tracking-tight font-poppins"><i class="fas fa-bullhorn text-indigo-500 mr-2"></i> Kotak Masuk Bidan</h3>
                <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route text-[10px] font-black text-indigo-600 bg-indigo-50 hover:bg-indigo-500 hover:text-white px-4 py-2 rounded-xl transition-all uppercase tracking-widest">Semua Pesan</a>
            </div>
            
            
            <div id="main-notif-wrapper" class="bg-white border border-slate-200 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">
                <div class="max-h-[500px] overflow-y-auto notif-scroll p-2 space-y-1">
                    <?php $__empty_1 = true; $__currentLoopData = $notifikasiTerbaru ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('user.notifikasi.index')); ?>" class="smooth-route block p-4 hover:bg-slate-50 transition-colors rounded-[18px] <?php echo e(!$notif['is_read'] ? 'bg-indigo-50/30 border border-indigo-100/50' : 'border border-transparent'); ?>">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full <?php echo e(!$notif['is_read'] ? 'bg-indigo-100 text-indigo-600 border border-indigo-200' : 'bg-slate-100 text-slate-400 border border-slate-200'); ?> flex items-center justify-center text-lg shrink-0">
                                    <i class="fas <?php echo e(!$notif['is_read'] ? 'fa-envelope' : 'fa-envelope-open'); ?>"></i>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <div class="flex justify-between items-start gap-2 mb-1">
                                        <h4 class="text-[13px] font-black text-slate-800 truncate pr-2 <?php echo e(!$notif['is_read'] ? 'text-indigo-900' : ''); ?>"><?php echo e($notif['judul']); ?></h4>
                                        <?php if(!$notif['is_read']): ?> 
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0 mt-1 shadow-sm shadow-rose-200 animate-pulse"></span> 
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-[12px] font-medium text-slate-500 line-clamp-2 leading-relaxed"><?php echo e($notif['pesan']); ?></p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 block"><i class="fas fa-clock mr-1"></i> <?php echo e($notif['waktu']); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-16 px-4">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl text-slate-300">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <h4 class="text-[14px] font-black text-slate-700 mb-1">Kotak Masuk Bersih</h4>
                            <p class="text-[12px] font-medium text-slate-500">Anda sudah membaca semua pemberitahuan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/dashboard.blade.php ENDPATH**/ ?>