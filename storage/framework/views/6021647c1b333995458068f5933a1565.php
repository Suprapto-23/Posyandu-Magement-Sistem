

<?php $__env->startSection('title', 'Data Pemeriksaan'); ?>
<?php $__env->startSection('page-name', 'Pemeriksaan Pasien'); ?>

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
        background-color: #ffffff; border-color: #6366f1;
        box-shadow: 0 4px 12px -3px rgba(99, 102, 241, 0.15);
    }
    .search-input::placeholder { color: #94a3b8; font-weight: 500; }
    
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    /* Animasi Loading Transisi Table */
    .table-loading { opacity: 0.4; pointer-events: none; filter: grayscale(50%); transition: all 0.3s ease; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-[18px] bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-inner border border-indigo-200/50 transform -rotate-3">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Data Pemeriksaan</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Cari dan filter data secara *real-time*.</p>
            </div>
        </div>
        <a href="<?php echo e(route('kader.pemeriksaan.create')); ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus"></i> Input Pemeriksaan
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-1 sm:pb-0">
            <?php $currentCat = request('kategori', 'semua'); ?>
            <?php $__currentLoopData = ['semua'=>'Semua Data', 'balita'=>'🍼 Balita', 'remaja'=>'🎓 Remaja', 'lansia'=>'👴 Lansia']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(request()->fullUrlWithQuery(['kategori'=>$k, 'page'=>1])); ?>" 
                   data-kategori="<?php echo e($k); ?>"
                   class="kategori-btn px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap transition-all duration-300 border 
                   <?php echo e($currentCat == $k ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800'); ?>">
                    <?php echo e($l); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="w-full lg:w-1/3 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" value="<?php echo e($search ?? ''); ?>" placeholder="Ketik nama pasien secara cepat..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                <i class="fas fa-spinner fa-spin text-indigo-500"></i>
            </div>
        </div>
    </div>

    <div id="table-container" class="transition-opacity duration-300">
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Tgl / Kategori</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Nama Pasien</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Pengukuran Fisik</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Status Data</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $pemeriksaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <p class="font-black text-slate-800 text-sm mb-1"><?php echo e(\Carbon\Carbon::parse($p->tanggal_periksa)->format('d M Y')); ?></p>
                                <?php if($p->kategori_pasien == 'balita'): ?> <span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-extrabold rounded-md uppercase">Balita</span>
                                <?php elseif($p->kategori_pasien == 'remaja'): ?> <span class="px-2 py-0.5 bg-sky-100 text-sky-700 text-[10px] font-extrabold rounded-md uppercase">Remaja</span>
                                <?php else: ?> <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-extrabold rounded-md uppercase">Lansia</span> <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm"><?php echo e($p->nama_pasien); ?></p>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate max-w-[200px]"><i class="fas fa-comment-medical text-slate-300 mr-1"></i> <?php echo e($p->keluhan ?? 'Tidak ada keluhan'); ?></p>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase mr-1">BB</span>
                                        <span class="text-xs font-black text-slate-700"><?php echo e($p->berat_badan ?? '-'); ?></span>
                                    </div>
                                    <div class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase mr-1">TB</span>
                                        <span class="text-xs font-black text-slate-700"><?php echo e($p->tinggi_badan ?? '-'); ?></span>
                                    </div>
                                    <?php if($p->kategori_pasien != 'balita'): ?>
                                    <div class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center shadow-sm">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase mr-1">Tensi</span>
                                        <span class="text-xs font-black text-slate-700"><?php echo e($p->tekanan_darah ?? '-'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <?php if($p->status_verifikasi == 'verified'): ?>
                                    <div class="inline-flex flex-col items-center justify-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-extrabold border border-emerald-200">
                                            <i class="fas fa-check-double text-emerald-500"></i> Verified
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="inline-flex flex-col items-center justify-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-extrabold border border-amber-200">
                                            <i class="fas fa-hourglass-half text-amber-500 animate-spin-slow"></i> Pending
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e(route('kader.pemeriksaan.show', $p->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if($p->status_verifikasi == 'pending'): ?>
                                    <a href="<?php echo e(route('kader.pemeriksaan.edit', $p->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-amber-50 shadow-sm transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('kader.pemeriksaan.destroy', $p->id)); ?>" method="POST" onsubmit="return confirm('Yakin hapus data pemeriksaan ini?');" class="inline-block">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 shadow-sm transition-all">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg">Data Tidak Ditemukan</h3>
                                <p class="text-sm text-slate-500 mt-1">Coba gunakan kata kunci pencarian lain atau input data baru.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($pemeriksaans->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                <?php echo e($pemeriksaans->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const doneTypingInterval = 400; // Waktu tunggu setelah ngetik (400ms)
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('table-container');
    const spinner = document.getElementById('searchSpinner');

    // FUNGSI UTAMA UNTUK FETCH DATA (AJAX DOM PARSING)
    async function fetchRealTimeData(url) {
        // Efek loading visual
        tableContainer.classList.add('table-loading');
        spinner.classList.remove('hidden');

        try {
            // Ambil HTML dari server
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            // Ekstrak bagian '#table-container' dari respon HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableContent = doc.getElementById('table-container').innerHTML;
            
            // Ganti isi tabel saat ini dengan yang baru
            tableContainer.innerHTML = newTableContent;
            
            // Perbarui URL browser agar tidak rusak kalau direfresh / tombol back (History API)
            window.history.pushState({}, '', url);

        } catch (error) {
            console.error('Terjadi kesalahan saat mengambil data:', error);
        } finally {
            // Hilangkan efek loading
            tableContainer.classList.remove('table-loading');
            spinner.classList.add('hidden');
        }
    }

    // 1. EVENT: Saat Mengetik di Kolom Pencarian (Auto-Search)
    searchInput.addEventListener('input', function(e) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', e.target.value);
            url.searchParams.set('page', 1); // Reset ke halaman 1 setiap mencari
            fetchRealTimeData(url.toString());
        }, doneTypingInterval);
    });

    // 2. EVENT: Saat Klik Tombol Kategori (Balita, Remaja, Lansia)
    const categoryButtons = document.querySelectorAll('.kategori-btn');
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Cegah pindah halaman biasa
            
            // Ganti warna tombol aktif
            categoryButtons.forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');
                b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
            });
            this.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
            this.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');

            // Ambil URL dari link yang diklik, pertahankan search query jika ada
            const url = new URL(this.href);
            const currentSearch = searchInput.value;
            if(currentSearch) url.searchParams.set('search', currentSearch);

            fetchRealTimeData(url.toString());
        });
    });

    // 3. EVENT: Saat Klik Pagination (Nomor Halaman)
    // Menggunakan Event Delegation karena tombol pagination di-render ulang
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination-wrapper a'); // Asumsi tailwind default pagenation pake tag a
        if (pageLink) {
            e.preventDefault();
            fetchRealTimeData(pageLink.href);
            // Scroll sedikit ke atas (opsional agar rapi)
            window.scrollTo({ top: document.getElementById('table-container').offsetTop - 100, behavior: 'smooth' });
        }
    });

    // 4. EVENT: Support tombol "Back/Forward" di browser
    window.addEventListener('popstate', function() {
        // Jika user klik back, fetch ulang data dari url yang aktif saat itu
        fetchRealTimeData(window.location.href);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/pemeriksaan/index.blade.php ENDPATH**/ ?>