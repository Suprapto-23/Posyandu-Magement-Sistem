
<?php $__env->startSection('title', 'Data Pemeriksaan Fisik'); ?>
<?php $__env->startSection('page-name', 'Log Pemeriksaan Pasien'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .search-input { width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 3rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); }
    .search-input:focus { border-color: #4f46e5; background: #ffffff; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-stethoscope text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-sm font-black tracking-widest text-indigo-500 uppercase animate-pulse">Memuat Log Pemeriksaan...</p>
</div>

<div class="max-w-[1400px] mx-auto animate-slide-up">
    
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0">
                <i class="fas fa-notes-medical"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-1">Pemeriksaan Fisik</h1>
                <p class="text-sm font-bold text-slate-500">Antropometri, Tensi, dan log kesehatan dasar warga.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="<?php echo e(route('kader.pemeriksaan.create')); ?>" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-black text-[13px] rounded-xl hover:bg-indigo-700 shadow-[0_8px_15px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-widest">
                <i class="fas fa-plus-circle text-lg"></i> Input Ukur Baru
            </a>
        </div>
    </div>

    
    <div class="glass-card rounded-[24px] p-3 mb-6 flex flex-col xl:flex-row items-center gap-4 justify-between relative z-20 shadow-sm">
        
        <form action="<?php echo e(route('kader.pemeriksaan.index')); ?>" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
            <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <div class="bg-slate-100 p-1.5 rounded-full flex flex-wrap w-full sm:w-max border border-slate-200">
                <?php
                    $tabs = [
                        'semua' => ['label' => 'Semua', 'icon' => 'fa-layer-group', 'color' => 'text-slate-600'],
                        'balita' => ['label' => 'Balita', 'icon' => 'fa-baby', 'color' => 'text-sky-600'],
                        'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female', 'color' => 'text-pink-600'],
                        'remaja' => ['label' => 'Remaja', 'icon' => 'fa-user-graduate', 'color' => 'text-indigo-600'],
                        'lansia' => ['label' => 'Lansia', 'icon' => 'fa-user-clock', 'color' => 'text-emerald-600'],
                    ];
                    $currentKat = request('kategori', 'semua');
                ?>
                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="submit" name="kategori" value="<?php echo e($key); ?>" class="flex-1 sm:flex-none px-4 py-2 rounded-full font-extrabold text-[11px] uppercase tracking-wider transition-all <?php echo e($currentKat == $key ? 'bg-white shadow-sm ' . $tab['color'] : 'text-slate-400 hover:text-slate-600 hover:bg-slate-200/50'); ?>">
                        <i class="fas <?php echo e($tab['icon']); ?> mr-1"></i> <span class="hidden sm:inline"><?php echo e($tab['label']); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto bg-white border border-slate-200 text-slate-600 font-bold text-[11px] uppercase tracking-wider rounded-full px-4 py-2.5 outline-none cursor-pointer focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 transition-all">
                <option value="">Semua Status Validasi</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>⏳ Menunggu Bidan</option>
                <option value="verified" <?php echo e(request('status') == 'verified' ? 'selected' : ''); ?>>✅ Selesai Divalidasi</option>
            </select>
        </form>
        
        <div class="w-full xl:w-80 relative group">
            <input type="text" id="liveSearchInput" placeholder="Ketik Nama Warga..." 
                   class="search-input">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center bg-slate-100 rounded-full group-focus-within:bg-indigo-100 transition-colors">
                <i class="fas fa-search text-xs text-slate-400 group-focus-within:text-indigo-500"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden mb-8" id="tableContainer">
        <div class="custom-scrollbar overflow-x-auto max-h-[65vh]">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                <thead class="sticky top-0 z-10 bg-slate-50/90 backdrop-blur-sm border-b border-slate-200 shadow-sm">
                    <tr class="text-[10px] uppercase tracking-widest text-slate-500 font-black">
                        <th class="px-6 py-4 border-r border-slate-200/50">Tgl & Kategori</th>
                        <th class="px-6 py-4 border-r border-slate-200/50">Identitas Warga</th>
                        <th class="px-6 py-4 border-r border-slate-200/50">Fisik Dasar</th>
                        <th class="px-6 py-4 text-center border-r border-slate-200/50">Status Bidan</th>
                        <th class="px-6 py-4 text-center">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $pemeriksaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // UI Dinamis berdasarkan Kategori
                        $badgeColors = [
                            'balita'    => 'bg-sky-50 text-sky-600 border-sky-200',
                            'ibu_hamil' => 'bg-pink-50 text-pink-600 border-pink-200',
                            'remaja'    => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                            'lansia'    => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        ];
                        $icons = ['balita'=>'fa-baby', 'ibu_hamil'=>'fa-female', 'remaja'=>'fa-user-graduate', 'lansia'=>'fa-user-clock'];
                        
                        $kat = $item->kategori_pasien;
                        $color = $badgeColors[$kat] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                        $icon = $icons[$kat] ?? 'fa-user';
                        $nama = $item->nama_pasien; // Didapat dari accessor model
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors pasien-row" data-search="<?php echo e(strtolower($nama)); ?>">
                        
                        
                        <td class="px-6 py-4 border-r border-slate-200/50">
                            <p class="font-black text-slate-800 text-[14px]"><?php echo e(\Carbon\Carbon::parse($item->tanggal_periksa)->translatedFormat('d M Y')); ?></p>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-1 rounded border text-[9px] font-black uppercase tracking-widest <?php echo e($color); ?>">
                                <i class="fas <?php echo e($icon); ?>"></i> <?php echo e(str_replace('_', ' ', $kat)); ?>

                            </span>
                        </td>

                        
                        <td class="px-6 py-4 border-r border-slate-200/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm shrink-0 border border-white shadow-sm <?php echo e($color); ?>">
                                    <?php echo e(strtoupper(substr($nama, 0, 1))); ?>

                                </div>
                                <div>
                                    <p class="text-[13px] font-black text-slate-800"><?php echo e($nama); ?></p>
                                    <p class="text-[10px] font-bold text-slate-500 font-mono tracking-wide">ID Kunjungan: <?php echo e($item->kunjungan->kode_kunjungan ?? '-'); ?></p>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-6 py-4 border-r border-slate-200/50">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">BB: <span class="text-indigo-600 font-black"><?php echo e($item->berat_badan ?? '-'); ?> kg</span></span>
                                <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">TB/PB: <span class="text-emerald-600 font-black"><?php echo e($item->tinggi_badan ?? '-'); ?> cm</span></span>
                            </div>
                            <?php if($item->imt): ?>
                                <p class="text-[9px] font-black text-slate-400 mt-1.5 uppercase tracking-widest">IMT: <?php echo e($item->imt); ?></p>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4 text-center border-r border-slate-200/50">
                            <?php if($item->status_verifikasi == 'verified'): ?>
                                <div class="inline-flex flex-col items-center gap-1">
                                    <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> Tervalidasi
                                    </span>
                                    <span class="text-[9px] font-bold text-emerald-600">Oleh Bidan</span>
                                </div>
                            <?php else: ?>
                                <div class="inline-flex flex-col items-center gap-1">
                                    <span class="bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm animate-pulse">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                    <span class="text-[9px] font-bold text-amber-600">Validasi Medis</span>
                                </div>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('kader.pemeriksaan.show', $item->id)); ?>" onclick="window.showLoader()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Lihat Rekam Medis"><i class="fas fa-file-medical-alt"></i></a>
                                
                                
                                <?php if($item->status_verifikasi == 'pending'): ?>
                                    <a href="<?php echo e(route('kader.pemeriksaan.edit', $item->id)); ?>" onclick="window.showLoader()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:text-amber-600 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Koreksi Input Kader"><i class="fas fa-edit"></i></a>
                                    <form action="<?php echo e(route('kader.pemeriksaan.destroy', $item->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmDelete('<?php echo e($item->id); ?>')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" onclick="lockedAlert()" class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 text-slate-300 cursor-not-allowed flex items-center justify-center shadow-sm" title="Terkunci (Sudah Divallidasi Bidan)"><i class="fas fa-lock"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100"><i class="fas fa-folder-open"></i></div>
                            <h3 class="font-black text-slate-800 text-lg">Belum Ada Pemeriksaan</h3>
                            <p class="text-sm text-slate-500 mt-1">Gunakan filter pencarian lain atau input data pengukuran baru.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($pemeriksaans->hasPages()): ?>
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            <?php echo e($pemeriksaans->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script>
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

    // Ini kunci agar tidak stuck saat tombol "Back" ditekan
    window.addEventListener('pageshow', function(event) {
        window.hideLoader();
    });
    document.addEventListener('DOMContentLoaded', window.hideLoader);
    setTimeout(window.hideLoader, 2000); // Failsafe

    // LIVE SEARCH JS (Client Side untuk kecepatan maksimal pada data yang tertampil)
    const searchInput = document.getElementById('liveSearchInput');
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.pasien-row');
            rows.forEach(row => {
                const dataSearch = row.getAttribute('data-search');
                if (dataSearch.includes(filter)) row.style.display = '';
                else row.style.display = 'none';
            });
        });
    }

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
        timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });
    <?php if(session('success')): ?> Toast.fire({ icon: 'success', title: 'Berhasil!', text: "<?php echo addslashes(session('success')); ?>" }); <?php endif; ?>
    <?php if(session('error')): ?> Toast.fire({ icon: 'error', title: 'Oops...', text: "<?php echo addslashes(session('error')); ?>" }); <?php endif; ?>

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Pemeriksaan?',
            html: "Data ukuran fisik pasien ini akan dihapus. Lanjutkan?",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', reverseButtons: true,
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }

    function lockedAlert() {
        Swal.fire({
            icon: 'info', title: 'Akses Terkunci',
            text: 'Pemeriksaan ini telah divalidasi oleh Bidan dan masuk ke rekam medis permanen. Kader tidak dapat mengubahnya lagi.',
            confirmButtonColor: '#4f46e5', confirmButtonText: 'Mengerti', customClass: { popup: 'rounded-3xl' }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/pemeriksaan/index.blade.php ENDPATH**/ ?>