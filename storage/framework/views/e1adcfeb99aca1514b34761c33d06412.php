

<?php $__env->startSection('title', 'Absensi Posyandu'); ?>
<?php $__env->startSection('page-name', 'Presensi Warga'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* Animasi Halus */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.6s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Navigasi Tab Horizontal */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .kat-tab { 
        padding: 0.75rem 1.5rem; border-radius: 100px; font-size: 0.75rem; font-weight: 800; 
        letter-spacing: 0.05em; text-transform: uppercase; border: 2px solid transparent; 
        color: #64748b; transition: all 0.3s ease; white-space: nowrap; 
    }
    .kat-tab:hover { background: #f1f5f9; color: #334155; }
    .kat-tab.active { background: #4f46e5; color: white; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }

    /* Floating Dock Styling */
    .floating-dock-wrapper {
        position: fixed; bottom: 2rem; left: 0; right: 0; z-index: 50;
        display: flex; justify-content: center; pointer-events: none; padding: 0 1rem;
    }
    @media (min-width: 1024px) { .floating-dock-wrapper { left: 280px; } } /* Menyesuaikan lebar sidebar */
    
    .floating-dock {
        pointer-events: auto; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px);
        border: 1px solid #e2e8f0; border-radius: 2rem; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
        width: 100%; max-width: 800px; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pb-40 animate-fade-in" x-data="{ searchQuery: '' }">
    
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 font-poppins tracking-tight mb-1">Presensi Warga</h1>
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <i class="far fa-calendar-check text-indigo-500"></i> Pertemuan: <b class="text-indigo-600"><?php echo e(\Carbon\Carbon::parse($tanggal ?? now())->translatedFormat('d F Y')); ?></b>
            </p>
        </div>
        <div class="bg-white px-5 py-2.5 rounded-full border border-slate-200 shadow-sm text-center">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Sesi Posyandu Ke</span>
            <span class="text-lg font-black text-indigo-600">#<?php echo e($pertemuanBerikutnya ?? 1); ?></span>
        </div>
    </div>

    
    <div class="flex gap-2 overflow-x-auto no-scrollbar mb-8 pb-2">
        <?php $tabs = ['bayi'=>'Bayi (0-11 Bln)', 'balita'=>'Balita (1-5 Thn)', 'remaja'=>'Remaja', 'ibu_hamil'=>'Ibu Hamil', 'lansia'=>'Lansia']; ?>
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('kader.absensi.index', ['kategori' => $k])); ?>" 
               class="kat-tab <?php echo e($kategori == $k ? 'active' : 'bg-white border-slate-200 shadow-sm'); ?>">
               <?php echo e($label); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if(isset($pasiens) && count($pasiens) > 0): ?>
        
        
        <div class="mb-6 relative max-w-md ml-auto">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400"></i>
            </div>
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari nama warga di daftar ini..." 
                   class="w-full bg-white border border-slate-200 rounded-[100px] pl-11 pr-4 py-3 text-[13px] font-bold text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition-all shadow-sm">
            <button type="button" x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-rose-500" x-cloak>
                <i class="fas fa-times-circle text-lg"></i>
            </button>
        </div>

        
        <form action="<?php echo e(route('kader.absensi.store')); ?>" method="POST" id="formAbsensi">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="kategori" value="<?php echo e($kategori); ?>">
            <input type="hidden" name="tanggal_posyandu" value="<?php echo e($tanggal ?? date('Y-m-d')); ?>">
            <input type="hidden" name="pertemuan_ke" value="<?php echo e($pertemuanBerikutnya ?? 1); ?>">
            
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden mb-10">
                <div class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $pasiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pasienId = $p->id;
                        $pasienType = get_class($p);
                        $hadirCheck = isset($absensiData[$pasienId]) && $absensiData[$pasienId]['status_kehadiran'] == 'hadir' ? 'checked' : '';
                        $absenCheck = isset($absensiData[$pasienId]) && $absensiData[$pasienId]['status_kehadiran'] == 'absen' ? 'checked' : '';
                        $keterangan = isset($absensiData[$pasienId]) ? $absensiData[$pasienId]['keterangan'] : '';
                    ?>
                    
                    
                    <div class="p-5 md:p-6 hover:bg-slate-50/50 transition-colors" x-show="'<?php echo e(strtolower($p->nama_lengkap)); ?>'.includes(searchQuery.toLowerCase())">
                        
                        <input type="hidden" name="absensi[<?php echo e($loop->index); ?>][pasien_id]" value="<?php echo e($pasienId); ?>">
                        <input type="hidden" name="absensi[<?php echo e($loop->index); ?>][pasien_type]" value="<?php echo e($pasienType); ?>">
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                            
                            
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 border border-slate-200 font-black text-sm">
                                    <?php echo e($loop->iteration); ?>

                                </div>
                                <div>
                                    <p class="text-[15px] font-black text-slate-800 leading-tight mb-0.5"><?php echo e($p->nama_lengkap); ?></p>
                                    <p class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">NIK: <?php echo e($p->nik ?? '-'); ?></p>
                                </div>
                            </div>
                            
                            
                            <div class="flex flex-col md:items-end gap-3 shrink-0">
                                <div class="flex items-center gap-2 w-full md:w-auto">
                                    
                                    
                                    <label class="cursor-pointer flex-1 md:flex-none">
                                        <input type="radio" name="absensi[<?php echo e($loop->index); ?>][status_kehadiran]" value="hadir" class="peer sr-only radio-hadir" id="hadir_<?php echo e($loop->index); ?>" <?php echo e($hadirCheck); ?> onchange="toggleKet(<?php echo e($loop->index); ?>)">
                                        <div class="text-center px-6 py-3 rounded-full border-2 border-slate-100 text-slate-400 font-black text-[11px] uppercase tracking-widest hover:border-indigo-200 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all">
                                            Hadir
                                        </div>
                                    </label>
                                    
                                    
                                    <label class="cursor-pointer flex-1 md:flex-none">
                                        <input type="radio" name="absensi[<?php echo e($loop->index); ?>][status_kehadiran]" value="absen" class="peer sr-only radio-absen" id="absen_<?php echo e($loop->index); ?>" <?php echo e($absenCheck); ?> onchange="toggleKet(<?php echo e($loop->index); ?>)">
                                        <div class="text-center px-6 py-3 rounded-full border-2 border-slate-100 text-slate-400 font-black text-[11px] uppercase tracking-widest hover:border-rose-200 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 transition-all">
                                            Absen
                                        </div>
                                    </label>

                                </div>

                                
                                <div class="w-full md:w-64 <?php echo e($absenCheck ? '' : 'hidden'); ?>" id="ketBox_<?php echo e($loop->index); ?>">
                                    <input type="text" name="absensi[<?php echo e($loop->index); ?>][keterangan]" id="keterangan_<?php echo e($loop->index); ?>" value="<?php echo e($keterangan); ?>" placeholder="Tulis alasan absen..." class="w-full bg-rose-50 border border-rose-200 rounded-xl px-4 py-2.5 text-[11px] font-bold text-rose-700 placeholder-rose-300 outline-none focus:ring-2 focus:ring-rose-200 transition-all">
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="floating-dock-wrapper">
                <div class="floating-dock flex-col sm:flex-row gap-4 sm:gap-0">
                    
                    
                    <div class="flex items-center justify-center gap-6 sm:gap-10 w-full sm:w-auto px-4">
                        <div class="text-center">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah Diisi</p>
                            <p class="text-2xl font-black text-indigo-600 leading-none" id="countFilled">0</p>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Belum</p>
                            <p class="text-2xl font-black text-rose-500 leading-none" id="countEmpty">0</p>
                        </div>
                    </div>
                    
                    
                    <button type="submit" id="btnSimpan" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 text-white rounded-full text-[12px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fas fa-save text-base"></i> Simpan Presensi Hari Ini
                    </button>
                    
                </div>
            </div>

        </form>
    <?php else: ?>
        
        <div class="bg-white rounded-[32px] border border-slate-200 py-24 text-center shadow-sm">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 text-slate-300 text-4xl border border-slate-100">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 font-poppins mb-2">Belum Ada Warga</h3>
            <p class="text-[13px] text-slate-500 max-w-sm mx-auto leading-relaxed">Sistem tidak menemukan daftar warga untuk kategori layanan ini. Silakan daftarkan warga terlebih dahulu.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalWarga = <?php echo e(isset($pasiens) ? count($pasiens) : 0); ?>;
    
    // Fungsi untuk memunculkan kotak alasan jika "Absen" diklik
    function toggleKet(index) {
        const isAbsen = document.getElementById('absen_'+index).checked;
        const ketBox = document.getElementById('ketBox_'+index);
        const ketInput = document.getElementById('keterangan_'+index);
        
        if (isAbsen) {
            ketBox.classList.remove('hidden');
            ketInput.focus();
        } else {
            ketBox.classList.add('hidden');
            ketInput.value = ''; 
        }
        updateCounters();
    }

    // Fungsi menghitung angka di Floating Dock rata tengah
    function updateCounters() {
        const filled = document.querySelectorAll('.radio-hadir:checked, .radio-absen:checked').length;
        document.getElementById('countFilled').innerText = filled;
        document.getElementById('countEmpty').innerText = totalWarga - filled;
    }

    document.addEventListener('DOMContentLoaded', () => {
        if(totalWarga > 0) updateCounters();
    });

    // Validasi sebelum form dikirim
    document.getElementById('formAbsensi')?.addEventListener('submit', function(e) {
        const filled = document.querySelectorAll('.radio-hadir:checked, .radio-absen:checked').length;
        
        if(filled < totalWarga) {
            e.preventDefault();
            Swal.fire({ 
                icon: 'warning', 
                title: 'Data Belum Lengkap', 
                text: `Masih ada ${totalWarga - filled} warga yang belum Anda tandai Hadir/Absen.`,
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'rounded-[24px]' }
            });
            return;
        }

        const btn = document.getElementById('btnSimpan');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/absensi/index.blade.php ENDPATH**/ ?>