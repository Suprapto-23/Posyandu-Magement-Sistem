

<?php $__env->startSection('title', 'Jadwal Posyandu'); ?>
<?php $__env->startSection('page-name', 'Kelola Jadwal'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .search-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 600; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .search-input:focus { background-color: #ffffff; border-color: #8b5cf6; box-shadow: 0 4px 12px -3px rgba(139, 92, 246, 0.15); }
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
            <div class="w-14 h-14 rounded-[18px] bg-violet-100 text-violet-600 flex items-center justify-center text-2xl shadow-inner border border-violet-200/50 transform -rotate-3">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Jadwal Posyandu</h1>
                <p class="text-slate-500 mt-1 font-medium text-sm">Buat acara dan umumkan jadwal ke warga.</p>
            </div>
        </div>
        <a href="<?php echo e(route('kader.jadwal.create')); ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-violet-600 text-white font-extrabold text-sm rounded-xl hover:bg-violet-700 shadow-[0_4px_12px_rgba(139,92,246,0.3)] hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus"></i> Buat Jadwal Baru
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4">
        
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-1 sm:pb-0">
            <?php $currentStatus = request('status', 'semua'); ?>
            <?php $__currentLoopData = ['semua'=>'Semua Jadwal', 'aktif'=>'📅 Aktif/Akan Datang', 'selesai'=>'✅ Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(request()->fullUrlWithQuery(['status'=>$k, 'page'=>1])); ?>" 
                   data-status="<?php echo e($k); ?>"
                   class="status-btn px-5 py-2.5 rounded-xl font-bold text-sm whitespace-nowrap transition-all duration-300 border 
                   <?php echo e($currentStatus == $k ? 'bg-violet-600 text-white border-violet-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800'); ?>">
                    <?php echo e($l); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="w-full lg:w-1/3 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" value="<?php echo e($search ?? ''); ?>" placeholder="Cari nama kegiatan / lokasi..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                <i class="fas fa-spinner fa-spin text-violet-500"></i>
            </div>
        </div>
    </div>

    <div id="table-container" class="transition-opacity duration-300">
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu & Tanggal</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Detail Kegiatan</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Target & Lokasi</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex flex-col items-center justify-center font-bold shadow-sm border border-violet-100 leading-none">
                                        <span class="text-xs"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('M')); ?></span>
                                        <span class="text-lg font-black"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-sm"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('Y')); ?></p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5"><i class="fas fa-clock mr-1"></i> <?php echo e(\Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')); ?></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800 text-sm"><?php echo e($jadwal->judul); ?></p>
                                <p class="text-[11px] font-semibold text-slate-400 mt-0.5 truncate max-w-[200px]"><?php echo e($jadwal->deskripsi ?? 'Tidak ada deskripsi tambahan'); ?></p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-md mb-1"><?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?></span>
                                <p class="text-xs font-bold text-slate-500"><i class="fas fa-map-marker-alt text-rose-400 mr-1"></i> <?php echo e($jadwal->lokasi); ?></p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <?php if($jadwal->status == 'aktif'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-50 text-violet-700 text-[11px] font-extrabold border border-violet-200">
                                        <i class="fas fa-bullhorn animate-pulse"></i> Aktif
                                    </span>
                                <?php elseif($jadwal->status == 'selesai'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-extrabold border border-emerald-200">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-[11px] font-extrabold border border-rose-200">
                                        <i class="fas fa-times-circle"></i> Batal
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e(route('kader.jadwal.show', $jadwal->id)); ?>" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-violet-600 hover:border-violet-300 hover:bg-violet-50 shadow-sm transition-all" title="Detail & Broadcast">
                                        <i class="fas fa-paper-plane"></i>
                                    </a>
                                    <a href="<?php echo e(route('kader.jadwal.edit', $jadwal->id)); ?>" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-amber-600 hover:border-amber-300 hover:bg-amber-50 shadow-sm transition-all" title="Edit Jadwal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('kader.jadwal.destroy', $jadwal->id)); ?>" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" class="inline-block">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 shadow-sm transition-all">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg">Belum Ada Jadwal</h3>
                                <p class="text-sm text-slate-500 mt-1">Buat jadwal kegiatan posyandu agar warga mendapat notifikasi.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($jadwals->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                <?php echo e($jadwals->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Copy-paste script SPA Javascript dari halaman sebelumnya (Ubah '.kategori-btn' jadi '.status-btn')
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

        const statusButtons = document.querySelectorAll('.status-btn');
        statusButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                statusButtons.forEach(b => {
                    b.classList.remove('bg-violet-600', 'text-white', 'border-violet-600', 'shadow-md');
                    b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
                });
                this.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
                this.classList.add('bg-violet-600', 'text-white', 'border-violet-600', 'shadow-md');

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
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/jadwal/index.blade.php ENDPATH**/ ?>