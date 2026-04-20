<?php $__env->startSection('title', 'Detail Rekam Medis'); ?>
<?php $__env->startSection('page-name', 'Buku KIA / Riwayat Medis'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    .timeline-line { position: absolute; left: 35px; top: 20px; bottom: -20px; width: 2px; background: #e2e8f0; z-index: 0; }
    @media (max-width: 640px) { .timeline-line { left: 24px; } }
</style>

<div class="max-w-[1200px] mx-auto animate-slide-up pb-10">

    <div class="mb-6">
        <a href="<?php echo e(route('bidan.rekam-medis.index', ['type' => $pasien_type])); ?>" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Pencarian
        </a>
    </div>

    <?php
        // Tema warna dinamis berdasarkan kategori
        if($pasien_type == 'balita') { $bCol='rose'; $bBg='from-rose-500 to-pink-600'; $icn='fa-baby'; }
        elseif($pasien_type == 'remaja') { $bCol='indigo'; $bBg='from-indigo-500 to-violet-600'; $icn='fa-user-graduate'; }
        elseif($pasien_type == 'ibu_hamil') { $bCol='pink'; $bBg='from-pink-500 to-rose-500'; $icn='fa-female'; }
        else { $bCol='emerald'; $bBg='from-emerald-500 to-teal-600'; $icn='fa-wheelchair'; }
    ?>

    
    <div class="bg-gradient-to-br <?php echo e($bBg); ?> rounded-[32px] shadow-[0_12px_30px_rgba(0,0,0,0.1)] mb-10 overflow-hidden relative p-8 md:p-10 border border-<?php echo e($bCol); ?>-400">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8 text-white text-center md:text-left">
            <div class="w-32 h-32 rounded-[28px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-6xl font-black shadow-xl shrink-0 transform -rotate-3 hover:rotate-0 transition-transform">
                <i class="fas <?php echo e($icn); ?>"></i>
            </div>
            
            <div class="flex-1 w-full">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/20 backdrop-blur rounded-lg text-[10px] font-black uppercase tracking-widest mb-3 border border-white/30 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Kategori <?php echo e(ucfirst(str_replace('_', ' ', $pasien_type))); ?>

                </div>
                
                <h2 class="text-3xl md:text-[42px] font-black tracking-tight mb-5 font-poppins drop-shadow-md leading-none"><?php echo e($pasien->nama_lengkap); ?></h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-black/15 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div><p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-0.5">NIK KTP</p><p class="font-bold text-[13px] tracking-wider"><?php echo e($pasien->nik ?? '-'); ?></p></div>
                    <div><p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-0.5">Usia Sistem</p><p class="font-bold text-[13px]"><?php echo e(\Carbon\Carbon::parse($pasien->tanggal_lahir)->age); ?> Tahun</p></div>
                    <div><p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-0.5">Tanggal Lahir</p><p class="font-bold text-[13px]"><?php echo e(\Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y')); ?></p></div>
                    <div><p class="text-white/70 text-[10px] font-black uppercase tracking-widest mb-0.5">Riwayat Kunjungan</p><p class="font-bold text-[13px]"><?php echo e(count($kunjungans)); ?> Kali Periksa</p></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="flex items-center gap-3 mb-8 px-2">
        <div class="w-10 h-10 bg-white border border-slate-200 text-cyan-600 rounded-xl flex items-center justify-center shadow-sm"><i class="fas fa-notes-medical text-lg"></i></div>
        <h3 class="text-xl font-black text-slate-800 tracking-tight font-poppins">Riwayat Medis Longitudinal</h3>
    </div>

    <div class="relative bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-6 sm:p-10 overflow-hidden">
        
        <?php if(count($kunjungans) > 0): ?>
            <div class="timeline-line"></div>
        <?php endif; ?>

        <?php $__empty_1 = true; $__currentLoopData = $kunjungans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="relative pl-14 sm:pl-20 mb-10 last:mb-0 group">
                
                
                <div class="absolute left-[18px] sm:left-[18px] top-0 w-9 h-9 rounded-full bg-white border-[3px] border-cyan-500 shadow-md flex items-center justify-center text-cyan-600 z-10 transition-transform duration-300 group-hover:scale-125 group-hover:bg-cyan-50">
                    <i class="fas fa-stethoscope text-[12px]"></i>
                </div>

                
                <div class="bg-slate-50/50 border border-slate-100 rounded-[24px] p-6 sm:p-8 transition-all duration-300 hover:bg-white hover:shadow-[0_15px_40px_rgba(0,0,0,0.06)] hover:border-cyan-200">
                    
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-200/80">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white border border-slate-200 rounded-xl flex flex-col items-center justify-center shadow-sm shrink-0">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5"><?php echo e(\Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('M')); ?></span>
                                <span class="text-[20px] font-black text-slate-800 leading-none"><?php echo e(\Carbon\Carbon::parse($pem->tanggal_periksa)->format('d')); ?></span>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-800 text-[16px]"><?php echo e(\Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('l, Y')); ?></h4>
                                <span class="text-[11px] font-bold text-slate-400 mt-1 flex items-center gap-1.5"><i class="far fa-clock"></i> Pukul <?php echo e($pem->created_at->format('H:i')); ?> WIB</span>
                            </div>
                        </div>

                        <?php if($pem->keluhan): ?>
                        <div class="inline-flex items-start gap-2.5 bg-rose-50 border border-rose-100 px-5 py-3 rounded-xl max-w-sm">
                            <i class="fas fa-comment-medical text-rose-500 mt-0.5 text-lg"></i>
                            <div>
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest block mb-0.5">Keluhan Pasien:</span>
                                <span class="text-[12px] font-bold text-rose-700 leading-tight italic">"<?php echo e($pem->keluhan); ?>"</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                        
                        
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2 flex items-center gap-2"><i class="fas fa-ruler-combined text-slate-300 text-sm"></i> Antropometri (Meja 2)</p>
                            
                            <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm"><span class="block text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1">Berat Badan</span><strong class="text-slate-800 text-[15px] font-black"><?php echo e($pem->berat_badan ?? '-'); ?> <span class="text-xs font-bold text-slate-400">kg</span></strong></div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm"><span class="block text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1">Tinggi Badan</span><strong class="text-slate-800 text-[15px] font-black"><?php echo e($pem->tinggi_badan ?? '-'); ?> <span class="text-xs font-bold text-slate-400">cm</span></strong></div>
                                
                                <?php if($pem->lingkar_kepala || $pem->lingkar_lengan): ?>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm"><span class="block text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1">LiKA / LiLA</span><strong class="text-slate-800 text-[15px] font-black"><?php echo e($pem->lingkar_kepala ?? $pem->lingkar_lengan); ?> <span class="text-xs font-bold text-slate-400">cm</span></strong></div>
                                <?php endif; ?>
                                
                                <?php if($pem->tekanan_darah): ?>
                                    <div class="bg-rose-50 p-3 rounded-xl border border-rose-100 shadow-sm"><span class="block text-rose-400 font-bold text-[10px] uppercase tracking-widest mb-1">Tensi Darah</span><strong class="text-rose-600 text-[15px] font-black"><?php echo e($pem->tekanan_darah); ?> <span class="text-[10px]">mmHg</span></strong></div>
                                <?php endif; ?>
                            </div>

                            <?php if($pasien_type == 'ibu_hamil'): ?>
                                <div class="mt-4 bg-pink-50 p-4 rounded-xl border border-pink-100">
                                    <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest mb-2">Pemeriksaan Kandungan</p>
                                    <div class="flex gap-4 text-[12px] font-bold text-pink-800">
                                        <span>TFU: <?php echo e($pem->tfu ?? '-'); ?> cm</span>
                                        <span>DJJ: <?php echo e($pem->djj ?? '-'); ?> /m</span>
                                        <span>Pos: <?php echo e($pem->posisi_janin ?? '-'); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <p class="text-[10px] font-bold text-slate-400 mt-4 flex items-center gap-1.5"><i class="fas fa-user-edit"></i> Diukur oleh: <?php echo e($pem->pemeriksa->name ?? 'Kader (Sistem)'); ?></p>
                        </div>

                        
                        <div class="bg-cyan-50/50 border border-cyan-100/60 rounded-[20px] p-6 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 text-[80px] text-cyan-500/5 pointer-events-none"><i class="fas fa-stethoscope"></i></div>
                            
                            <p class="text-[10px] font-black text-cyan-600 uppercase tracking-widest mb-4 border-b border-cyan-100 pb-2 relative z-10 flex items-center gap-2"><i class="fas fa-notes-medical text-sm"></i> Keputusan Medis (Meja 5)</p>
                            
                            <?php if($pem->status_verifikasi == 'verified'): ?>
                                <div class="relative z-10 space-y-4">
                                    <div>
                                        <span class="text-[10px] font-bold text-cyan-700/70 uppercase tracking-widest block mb-1">Hasil Diagnosa</span>
                                        <p class="text-[14px] font-black text-cyan-900 leading-relaxed">"<?php echo e($pem->diagnosa); ?>"</p>
                                    </div>
                                    
                                    <?php if($pem->tindakan): ?>
                                        <div class="bg-white p-4 rounded-xl border border-cyan-100 shadow-sm">
                                            <span class="text-[10px] font-black text-cyan-600 uppercase tracking-widest block mb-1.5"><i class="fas fa-pills mr-1"></i> Tindakan & Resep Medis</span> 
                                            <p class="text-[13px] font-bold text-slate-700 leading-relaxed"><?php echo e($pem->tindakan); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="pt-2 border-t border-cyan-100 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-cyan-600 text-white flex items-center justify-center text-[9px] font-bold shadow-inner">
                                            <?php echo e(strtoupper(substr($pem->verifikator->name ?? 'B', 0, 1))); ?>

                                        </div>
                                        <span class="text-[10px] font-bold text-cyan-700 uppercase tracking-widest">Divalidasi Oleh: <?php echo e($pem->verifikator->name ?? 'Bidan'); ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center py-6 relative z-10 text-center">
                                    <div class="w-12 h-12 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center text-xl mb-3"><i class="fas fa-hourglass-half"></i></div>
                                    <p class="text-[13px] font-bold text-amber-600">Menunggu Validasi</p>
                                    <p class="text-[11px] font-medium text-amber-600/70 mt-1 max-w-[200px]">Data ini belum diperiksa oleh Bidan di Meja 5.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-5 text-5xl shadow-inner border border-slate-100"><i class="fas fa-folder-open"></i></div>
                <h4 class="font-black text-slate-800 text-[22px] font-poppins tracking-tight mb-2">Belum Ada Rekam Jejak</h4>
                <p class="text-[14px] font-medium text-slate-500 max-w-md mx-auto">Pasien ini baru terdaftar di database dan belum pernah melakukan kunjungan fisik ke Posyandu.</p>
            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/rekam-medis/show.blade.php ENDPATH**/ ?>