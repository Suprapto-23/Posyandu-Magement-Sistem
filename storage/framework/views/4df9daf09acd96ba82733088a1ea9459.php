

<?php $__env->startSection('title', 'Buku Kehadiran'); ?>
<?php $__env->startSection('page-name', 'Riwayat Kunjungan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .search-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 600;
        box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .search-input:focus {
        background-color: #ffffff; border-color: #06b6d4; /* Cyan 500 */
        box-shadow: 0 4px 12px -3px rgba(6, 182, 212, 0.15);
    }
    .search-input::placeholder { color: #94a3b8; font-weight: 500; }
    
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .table-loading { opacity: 0.4; pointer-events: none; filter: grayscale(50%); transition: all 0.3s ease; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-[18px] bg-cyan-100 text-cyan-600 flex items-center justify-center text-2xl shadow-inner border border-cyan-200/50 transform -rotate-3 hover:rotate-0 transition-transform">
                <i class="fas fa-clipboard-user"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Buku Kehadiran</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Daftar riwayat kedatangan warga di Posyandu.</p>
            </div>
        </div>
        
        <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2 shadow-sm">
            <i class="fas fa-robot text-cyan-500"></i>
            <span class="text-xs font-bold text-slate-600">Terisi Otomatis dari Pemeriksaan</span>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-1 sm:pb-0">
            <?php $currentCat = request('kategori', 'semua'); ?>
            <?php $__currentLoopData = ['semua'=>'Semua Hadir', 'balita'=>'🍼 Balita', 'remaja'=>'🎓 Remaja', 'lansia'=>'👴 Lansia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(request()->fullUrlWithQuery(['kategori'=>$k, 'page'=>1])); ?>" 
                   data-kategori="<?php echo e($k); ?>"
                   class="kategori-btn px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap transition-all duration-300 border 
                   <?php echo e($currentCat == $k ? 'bg-cyan-600 text-white border-cyan-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800'); ?>">
                    <?php echo e($l); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="w-full lg:w-1/3 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" value="<?php echo e($search ?? ''); ?>" placeholder="Cari nama pasien yang hadir..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                <i class="fas fa-spinner fa-spin text-cyan-500"></i>
            </div>
        </div>
    </div>

    <div id="table-container" class="transition-opacity duration-300">
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu Kehadiran</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Profil Pasien</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Layanan Diterima</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Petugas Pendaftar</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $kunjungans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kunjungan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold shadow-sm border border-cyan-100">
                                        <?php echo e(\Carbon\Carbon::parse($kunjungan->created_at)->format('d')); ?>

                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-sm"><?php echo e(\Carbon\Carbon::parse($kunjungan->created_at)->format('M Y')); ?></p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5"><i class="fas fa-clock mr-1"></i> <?php echo e(\Carbon\Carbon::parse($kunjungan->created_at)->format('H:i')); ?> WIB</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm"><?php echo e($kunjungan->pasien->nama_lengkap ?? 'Unknown'); ?></p>
                                
                                <?php $tipe = class_basename($kunjungan->pasien_type); ?>
                                <?php if($tipe == 'Balita'): ?> <span class="text-[10px] font-extrabold text-rose-500 uppercase tracking-wider">Balita</span>
                                <?php elseif($tipe == 'Remaja'): ?> <span class="text-[10px] font-extrabold text-sky-500 uppercase tracking-wider">Remaja</span>
                                <?php else: ?> <span class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-wider">Lansia</span> <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <?php if($kunjungan->jenis_kunjungan == 'imunisasi'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold border border-teal-100/50"><i class="fas fa-syringe"></i> Imunisasi</span>
                                <?php elseif($kunjungan->jenis_kunjungan == 'pemeriksaan'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100/50"><i class="fas fa-stethoscope"></i> Pemeriksaan</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200/50"><i class="fas fa-clipboard-check"></i> <?php echo e(ucfirst($kunjungan->jenis_kunjungan)); ?></span>
                                <?php endif; ?>
                                
                                <?php if($kunjungan->keluhan): ?>
                                    <p class="text-[10px] font-medium text-slate-400 mt-1.5 truncate max-w-[180px] italic">"<?php echo e($kunjungan->keluhan); ?>"</p>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        <?php echo e(strtoupper(substr($kunjungan->petugas->name ?? 'K', 0, 1))); ?>

                                    </div>
                                    <p class="font-semibold text-slate-700 text-xs"><?php echo e($kunjungan->petugas->name ?? 'Kader Posyandu'); ?></p>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="<?php echo e(route('kader.kunjungan.show', $kunjungan->id)); ?>" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-400 hover:text-cyan-600 hover:border-cyan-300 hover:bg-cyan-50 shadow-sm transition-all">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg">Belum Ada Kunjungan</h3>
                                <p class="text-sm text-slate-500 mt-1">Buku tamu masih kosong. Input pemeriksaan untuk mengisi daftar ini.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($kunjungans->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                <?php echo e($kunjungans->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('table-container');
    const spinner = document.getElementById('searchSpinner');

    async function fetchRealTimeData(url) {
        tableContainer.classList.add('table-loading');
        if(spinner) spinner.classList.remove('hidden');

        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            tableContainer.innerHTML = doc.getElementById('table-container').innerHTML;
            window.history.pushState({}, '', url);
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            tableContainer.classList.remove('table-loading');
            if(spinner) spinner.classList.add('hidden');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', e.target.value);
                url.searchParams.set('page', 1);
                fetchRealTimeData(url.toString());
            }, 400);
        });
    }

    const categoryButtons = document.querySelectorAll('.kategori-btn');
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            categoryButtons.forEach(b => {
                b.classList.remove('bg-cyan-600', 'text-white', 'border-cyan-600', 'shadow-md');
                b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
            });
            this.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
            this.classList.add('bg-cyan-600', 'text-white', 'border-cyan-600', 'shadow-md');

            const url = new URL(this.href);
            if(searchInput && searchInput.value) url.searchParams.set('search', searchInput.value);
            fetchRealTimeData(url.toString());
        });
    });

    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination-wrapper a');
        if (pageLink) {
            e.preventDefault();
            fetchRealTimeData(pageLink.href);
        }
    });

    window.addEventListener('popstate', function() {
        fetchRealTimeData(window.location.href);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/kunjungan/index.blade.php ENDPATH**/ ?>