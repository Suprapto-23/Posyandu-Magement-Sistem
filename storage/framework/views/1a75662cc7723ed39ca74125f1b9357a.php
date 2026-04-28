

<?php $__env->startSection('title', 'Detail Ibu Hamil'); ?>
<?php $__env->startSection('page-name', 'Buku KIA Ibu Hamil'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* CUSTOM SCROLLBAR (Disempurnakan untuk Vertical & Horizontal) */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(236, 72, 153, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(236, 72, 153, 0.5); }
    
    /* TABEL PRESISI DENGAN STICKY HEADER */
    .crm-table th { 
        background: #fdf2f8; 
        color: #db2777; 
        font-size: 0.65rem; 
        font-weight: 900; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        padding: 1.25rem 1.5rem; 
        border-bottom: 1px solid #fce7f3; 
    }
    .crm-table th:first-child { border-top-left-radius: 24px; }
    .crm-table th:last-child { border-top-right-radius: 24px; }
    .crm-table td { padding: 1.25rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #fdf2f8; transition: all 0.2s ease; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-pink-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-pink-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-folder-open text-pink-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-pink-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MEMBUKA BUKU KIA...</p>
</div>

<div class="max-w-7xl mx-auto relative pb-12 z-10">
    
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-pink-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-rose-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" onclick="window.showLoader()" class="w-14 h-14 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-pink-50 hover:border-pink-300 hover:text-pink-600 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-lg"></i>
            </a>
            <div>
                <div class="inline-flex items-center gap-1.5 text-[9px] font-black text-pink-500 uppercase tracking-widest mb-1 bg-pink-50 px-2.5 py-0.5 rounded border border-pink-100"><i class="fas fa-book-medical"></i> Buku KIA Digital</div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Profil Pasien Ibu</h1>
            </div>
        </div>
        <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $ibuHamil->id)); ?>" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-amber-500 text-white font-black text-[12px] rounded-full hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all hover:-translate-y-1 uppercase tracking-widest w-full md:w-auto">
            <i class="fas fa-pen-nib"></i> Edit Master Data
        </a>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 relative z-10">
        
        
        
        
        <div class="xl:col-span-4 animate-slide-up">
            <div class="bg-white rounded-[32px] overflow-hidden shadow-[0_10px_40px_-10px_rgba(236,72,153,0.08)] border border-rose-100/50 flex flex-col">
                
                
                <div class="bg-gradient-to-br from-pink-500 to-rose-600 px-6 py-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-24 h-24 mx-auto bg-white rounded-[24px] shadow-2xl flex items-center justify-center text-pink-500 text-4xl font-black relative z-10 border-4 border-white/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        <?php echo e(strtoupper(substr($ibuHamil->nama_lengkap, 0, 1))); ?>

                    </div>
                    
                    <h2 class="text-xl font-black text-white mt-4 relative z-10 font-poppins tracking-tight"><?php echo e($ibuHamil->nama_lengkap); ?></h2>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-black/20 text-pink-50 text-[10px] font-black rounded-full mt-2 relative z-10 backdrop-blur-md border border-white/10 tracking-widest uppercase">
                        <i class="fas fa-fingerprint text-pink-300"></i> NIK: <?php echo e($ibuHamil->nik ?? '-'); ?>

                    </div>
                </div>

                
                <div class="p-8 bg-white flex-1 flex flex-col">
                    
                    
                    <?php if($ibuHamil->status == 'aktif' && $ibuHamil->hpl): ?>
                        <?php
                            $totalDays = 280; 
                            $daysLeft = $ibuHamil->sisa_hari;
                            $daysPassed = $totalDays - $daysLeft;
                            $pct = $daysPassed > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;
                            
                            $warnColor = $daysLeft <= 30 ? 'bg-amber-500 shadow-amber-200' : 'bg-pink-500 shadow-pink-200';
                            $textWarn = $daysLeft <= 30 ? 'text-amber-500' : 'text-pink-600';
                        ?>
                        <div class="bg-rose-50/50 p-6 rounded-[24px] border border-rose-100 shadow-sm mb-6 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Kehamilan</p>
                            <p class="text-3xl font-black <?php echo e($textWarn); ?> font-poppins"><?php echo e(max(0, $daysLeft)); ?> <span class="text-xs font-bold text-slate-400">Hari Menuju HPL</span></p>
                            
                            <div class="w-full h-2 bg-rose-100/50 rounded-full mt-4 overflow-hidden relative">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out <?php echo e($warnColor); ?> shadow-[0_0_10px_rgba(236,72,153,0.5)]" style="width:<?php echo e($pct); ?>%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                <span>HPHT: <?php echo e($ibuHamil->hpht->translatedFormat('d M y')); ?></span>
                                <span>HPL: <?php echo e($ibuHamil->hpl->translatedFormat('d M y')); ?></span>
                            </div>
                        </div>
                    <?php elseif($ibuHamil->status == 'selesai'): ?>
                        <div class="bg-slate-50 p-6 rounded-[24px] border border-slate-200 shadow-sm mb-6 text-center">
                            <div class="w-12 h-12 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2 text-xl"><i class="fas fa-check-double"></i></div>
                            <p class="text-lg font-black text-slate-800 font-poppins">Sudah Bersalin</p>
                            <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Masa Kehamilan Selesai</p>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-4 p-5 rounded-[20px] bg-white border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0"><i class="fas fa-male"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Nama Suami</span>
                                <span class="text-[12px] font-black text-slate-800"><?php echo e($ibuHamil->nama_suami ?? '-'); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-5 rounded-[20px] bg-white border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0"><i class="fas fa-weight"></i></div>
                            <div class="flex-1 flex justify-between items-center">
                                <div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Berat (BB)</span>
                                    <span class="text-[12px] font-bold text-slate-800"><?php echo e($ibuHamil->berat_badan ?? '-'); ?> <span class="text-[9px] text-slate-400">kg</span></span>
                                </div>
                                <div class="w-px h-6 bg-slate-200 mx-2"></div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Tinggi</span>
                                    <span class="text-[12px] font-bold text-slate-800"><?php echo e($ibuHamil->tinggi_badan ?? '-'); ?> <span class="text-[9px] text-slate-400">cm</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto border-t border-slate-100 pt-6">
                        <?php if($ibuHamil->user_id): ?>
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-[20px] flex items-center gap-4 shadow-sm">
                                <div class="w-10 h-10 bg-white text-emerald-500 rounded-full flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-check-circle"></i></div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Akun Warga Aktif</p>
                                    <p class="text-[12px] font-bold text-emerald-800">Sinkronisasi Berhasil</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-5 bg-slate-50 border border-slate-100 rounded-[20px] text-center shadow-sm">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Akun Belum Terhubung</p>
                                <form action="<?php echo e(route('kader.data.ibu-hamil.sync', $ibuHamil->id)); ?>" method="POST" class="m-0">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" onclick="return confirm('Pindai ulang dan sambungkan akun dengan NIK Ibu?')" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-slate-200 shadow-sm text-indigo-600 text-[10px] font-black rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all w-full uppercase tracking-widest">
                                        <i class="fas fa-sync-alt mr-2"></i> Pindai NIK Ibu
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        
        
        
        <div class="xl:col-span-8 flex flex-col gap-8 animate-slide-up-delay-1">
            
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-pink-50 text-pink-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-heartbeat"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Fase Kehamilan</p>
                        <p class="text-lg font-black text-slate-800 font-poppins"><?php echo e($ibuHamil->trimester); ?></p>
                    </div>
                </div>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-tint"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Golongan Darah</p>
                        <p class="text-xl font-black text-slate-800 font-poppins"><?php echo e($ibuHamil->golongan_darah ?? '-'); ?></p>
                    </div>
                </div>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">IMT Awal Ibu</p>
                        <p class="text-xl font-black text-slate-800 font-poppins"><?php echo e($ibuHamil->imt ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[32px] overflow-hidden border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] flex-1 flex flex-col">
                <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-white text-pink-600 flex items-center justify-center text-2xl shadow-sm border border-slate-200"><i class="fas fa-stethoscope"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 font-poppins">Riwayat Pemeriksaan (ANC)</h3>
                            <p class="text-[12px] font-medium text-slate-500 mt-1">Log kunjungan dan cek klinis posyandu.</p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('kader.pemeriksaan.create')); ?>?kategori=ibu_hamil&pasien_id=<?php echo e($ibuHamil->id); ?>" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-pink-500 text-white font-black text-[11px] rounded-full hover:bg-pink-600 shadow-[0_4px_15px_rgba(236,72,153,0.3)] transition-all shrink-0 uppercase tracking-widest hover:-translate-y-1 w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Input Kunjungan
                    </a>
                </div>

                
                <div class="p-2 flex-1 bg-white">
                    <div class="overflow-y-auto overflow-x-auto custom-scrollbar max-h-[450px] px-2 pb-2 rounded-2xl">
                        <table class="w-full text-left whitespace-nowrap min-w-[700px] crm-table relative">
                            
                            <thead class="sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="pl-6">Tanggal Periksa</th>
                                    <th>Kondisi Fisik</th>
                                    <th class="text-center">Status Validasi</th>
                                    <th class="text-right pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ibuHamil->kunjungans ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunjungan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-pink-50/40 transition-colors group">
                                    <td class="pl-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-[13px] mb-1"><?php echo e(\Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y')); ?></span>
                                            <span class="text-[9px] font-bold text-slate-400 bg-white border border-slate-100 w-max px-2 py-0.5 rounded shadow-sm"><i class="far fa-clock"></i> <?php echo e(\Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('H:i')); ?> WIB</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($kunjungan->pemeriksaan): ?>
                                        <div class="flex items-center gap-3">
                                            <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Berat</span>
                                                <span class="text-[12px] font-black text-indigo-600"><?php echo e($kunjungan->pemeriksaan->berat_badan ?? '-'); ?><span class="text-[9px] text-slate-400">kg</span></span>
                                            </div>
                                            <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Tensi</span>
                                                <span class="text-[12px] font-black text-emerald-600"><?php echo e($kunjungan->pemeriksaan->tekanan_darah ?? '-'); ?></span>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <span class="text-[10px] font-bold text-slate-400 italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($kunjungan->pemeriksaan): ?>
                                            <?php if($kunjungan->pemeriksaan->status_verifikasi == 'verified' || $kunjungan->pemeriksaan->status_verifikasi == 'tervalidasi'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black border border-emerald-200 uppercase tracking-widest shadow-sm"><i class="fas fa-check-circle"></i> Tervalidasi Bidan</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black border border-amber-200 uppercase tracking-widest shadow-sm"><i class="fas fa-clock"></i> Menunggu Bidan</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-[9px] font-black border border-slate-200 uppercase tracking-widest shadow-sm"><i class="fas fa-ban"></i> Belum Diperiksa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right pr-6">
                                        <a href="<?php echo e(route('kader.kunjungan.show', $kunjungan->id)); ?>" onclick="window.showLoader()" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm hover:shadow-md hover:scale-105">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-8 py-24 text-center border-none">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl border border-slate-100 shadow-inner"><i class="fas fa-clipboard-list"></i></div>
                                        <h4 class="font-black text-slate-800 text-lg font-poppins tracking-tight">Belum Ada Rekam Medis</h4>
                                        <p class="text-[12px] font-medium text-slate-500 mt-1 max-w-sm mx-auto">Riwayat pemeriksaan ANC (Antenatal Care) akan muncul di sini setelah diinput.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.classList.remove('opacity-100', 'pointer-events-auto'); 
            l.classList.add('opacity-0', 'pointer-events-none'); 
            setTimeout(() => l.style.display = 'none', 300); 
        }
    };

    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.style.display = 'flex'; 
            l.classList.remove('opacity-0', 'pointer-events-none'); 
            l.classList.add('opacity-100', 'pointer-events-auto'); 
        }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    
    // Failsafe darurat
    setTimeout(window.hideLoader, 2500); 
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/ibu-hamil/show.blade.php ENDPATH**/ ?>