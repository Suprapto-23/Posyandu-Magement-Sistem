

<?php $__env->startSection('title', 'Kelola Jadwal Posyandu'); ?>
<?php $__env->startSection('page-name', 'Manajemen Jadwal'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.002]; transition: all 0.2s ease-in-out; box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.08); z-index: 10; position: relative; border-radius: 16px;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-calendar-alt text-cyan-600 text-xl animate-pulse"></i>
        </div>
    </div>
    <div class="bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100 flex items-center gap-2">
        <div class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></div>
        <p class="text-[10px] font-black text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MEMUAT DATA...</p>
    </div>
</div>

<div class="max-w-[1300px] mx-auto animate-slide-up pb-10">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 bg-white p-6 md:p-8 rounded-[28px] border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.03)] relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-cyan-50 rounded-bl-full pointer-events-none opacity-50"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-cyan-400 to-sky-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(6,182,212,0.25)] border border-cyan-300 shrink-0">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Jadwal Kegiatan</h1>
                <p class="text-slate-500 font-medium text-[13px] max-w-lg leading-relaxed">Kelola agenda posyandu dan sistem akan menginformasikannya otomatis ke aplikasi warga melalui push notification.</p>
            </div>
        </div>
        <a href="<?php echo e(route('bidan.jadwal.create')); ?>" class="smooth-route inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[12px] uppercase tracking-widest rounded-2xl hover:shadow-[0_10px_20px_rgba(6,182,212,0.3)] hover:-translate-y-1 transition-all duration-300 shrink-0 relative z-10">
            <i class="fas fa-plus-circle text-lg"></i> Buat Jadwal Baru
        </a>
    </div>

    
    <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col overflow-hidden">
        
        <div class="px-6 md:px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-[12px] bg-cyan-100 text-cyan-600 flex items-center justify-center shadow-inner"><i class="fas fa-list-ul"></i></div>
                <h3 class="font-black text-slate-800 text-[16px] font-poppins">Daftar Agenda Tersimpan</h3>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar flex-1 p-2 md:p-4">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu & Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Informasi Kegiatan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Target Layanan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Manajemen Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="table-row-hover group">
                        <td class="px-6 py-5 text-sm font-black text-slate-400 align-middle"><?php echo e($jadwals->firstItem() + $index); ?></td>
                        
                        <td class="px-6 py-5 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm group-hover:border-cyan-200 transition-colors">
                                    <span class="text-[10px] font-black text-cyan-600 uppercase"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                                    <span class="text-[20px] font-black text-slate-800 leading-none"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-1 group-hover:text-cyan-600 transition-colors"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, Y')); ?></p>
                                    <p class="text-[11px] font-bold text-slate-500 bg-white inline-flex items-center px-2 py-0.5 rounded-md border border-slate-200 shadow-sm">
                                        <i class="fas fa-clock text-cyan-500 mr-1.5"></i> <?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($jadwal->waktu_selesai))); ?>

                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 align-middle">
                            <p class="font-black text-slate-800 text-[15px] mb-1 text-wrap"><?php echo e($jadwal->judul); ?></p>
                            <p class="text-[12px] font-medium text-slate-500 line-clamp-1 mb-2 max-w-sm"><?php echo e($jadwal->deskripsi ?? 'Tidak ada deskripsi tambahan.'); ?></p>
                            <p class="text-[11px] font-bold text-slate-600 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-rose-500"></i> <?php echo e($jadwal->lokasi); ?></p>
                        </td>

                        <td class="px-6 py-5 align-middle">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg border border-indigo-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-tags text-indigo-400"></i> <?php echo e($jadwal->kategori); ?>

                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-users text-emerald-400"></i> <?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?>

                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center align-middle">
                            <?php
                                $statusConf = match($jadwal->status) {
                                    'aktif' => ['bg-cyan-50 text-cyan-700 border-cyan-200', 'Agenda Aktif', 'fa-calendar-check'],
                                    'selesai' => ['bg-slate-100 text-slate-500 border-slate-200', 'Selesai', 'fa-check-circle'],
                                    'dibatalkan' => ['bg-rose-50 text-rose-600 border-rose-200', 'Dibatalkan', 'fa-times-circle'],
                                    default => ['bg-slate-100 text-slate-600', $jadwal->status, 'fa-info-circle']
                                };
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-sm <?php echo e($statusConf[0]); ?>">
                                <i class="fas <?php echo e($statusConf[2]); ?>"></i> <?php echo e($statusConf[1]); ?>

                            </span>
                        </td>

                        <td class="px-6 py-5 text-right align-middle">
                            <div class="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="<?php echo e(route('bidan.jadwal.edit', $jadwal->id)); ?>" class="smooth-route w-10 h-10 rounded-[12px] bg-white text-amber-500 flex items-center justify-center hover:bg-amber-50 hover:border-amber-300 transition-all border border-slate-200 shadow-sm" title="Edit Jadwal">
                                    <i class="fas fa-edit text-[14px]"></i>
                                </a>
                                <form action="<?php echo e(route('bidan.jadwal.destroy', $jadwal->id)); ?>" method="POST" onsubmit="return confirm('Menghapus jadwal akan menghilangkan data ini dari HP Warga secara permanen. Lanjutkan?');" class="m-0 p-0">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="showLoader('MENGHAPUS JADWAL...')" class="w-10 h-10 rounded-[12px] bg-white text-rose-500 flex items-center justify-center hover:bg-rose-50 hover:border-rose-300 transition-all border border-slate-200 shadow-sm" title="Hapus Jadwal">
                                        <i class="fas fa-trash-alt text-[14px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-20">
                            <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-5 text-5xl shadow-inner border border-slate-100"><i class="fas fa-calendar-times"></i></div>
                            <h4 class="font-black text-slate-800 text-[18px] font-poppins mb-1">Database Jadwal Kosong</h4>
                            <p class="text-[13px] font-medium text-slate-500 mt-1 max-w-sm mx-auto">Klik tombol "Buat Jadwal Baru" di atas untuk mulai merencanakan kegiatan medis.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($jadwals->hasPages()): ?>
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
            <?php echo e($jadwals->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };
    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });
    document.querySelectorAll('.smooth-route, .pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                showLoader('MEMUAT HALAMAN...');
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/jadwal/index.blade.php ENDPATH**/ ?>