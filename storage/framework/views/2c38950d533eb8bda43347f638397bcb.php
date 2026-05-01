

<?php $__env->startSection('title', 'Kelola Jadwal Posyandu'); ?>
<?php $__env->startSection('page-name', 'Manajemen Jadwal'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ANIMASI MASUK HALUS */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* NEXUS TABLE ROW (PRESISI & ELEGAN) */
    .nexus-table-row { 
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); 
        border-bottom: 1px solid #f1f5f9; 
        background-color: #ffffff; 
    }
    .nexus-table-row:last-child { border-bottom: none; }
    .nexus-table-row:hover { 
        background-color: #f8fafc; 
        transform: translateY(-1px);
        box-shadow: 0 4px 15px -3px rgba(15, 23, 42, 0.03); 
        z-index: 10; position: relative; 
    }

    /* KARTU TANGGAL MINI (CLEAN) */
    .date-card { transition: all 0.3s ease; }
    .nexus-table-row:hover .date-card { border-color: #bae6fd; background-color: #f0f9ff; }

    /* SWEETALERT NEXUS ULTIMATE */
    .swal2-popup.nexus-swal {
        border-radius: 36px !important; padding: 3rem 2.5rem !important;
        background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255,255,255,0.9) !important; box-shadow: 0 40px 80px -15px rgba(15, 23, 42, 0.15) !important;
    }
    .swal2-title.nexus-title { font-family: 'Poppins', sans-serif !important; font-weight: 800 !important; font-size: 24px !important; color: #0f172a !important; margin-bottom: 0.5rem !important; }
    .swal2-html-container.nexus-text { font-weight: 500 !important; font-size: 14px !important; color: #64748b !important; line-height: 1.6 !important; }

    /* MODIFIKASI IKON SWEETALERT */
    .swal2-icon.swal2-success { border-color: #10b981 !important; color: #10b981 !important; background-color: #ecfdf5 !important; box-shadow: 0 0 0 10px #f0fdf4 !important; margin-bottom: 2rem !important; }
    .swal2-icon.swal2-success [class^=swal2-success-line] { background-color: #10b981 !important; }
    .swal2-icon.swal2-success .swal2-success-ring { border-color: rgba(16, 185, 129, 0.15) !important; }
    .swal2-icon.swal2-warning { border-color: #f43f5e !important; color: #f43f5e !important; background-color: #fff1f2 !important; box-shadow: 0 0 0 10px #fff1f2 !important; margin-bottom: 2rem !important; }

    /* KUSTOMISASI SCROLLBAR TABEL */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    #toast-container, .toast, .alert-success, .alert-danger { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-16 h-16 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
            <i class="fas fa-calendar-alt text-cyan-600 text-lg animate-pulse"></i>
        </div>
    </div>
    <div class="bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100 flex items-center gap-2">
        <div class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-ping"></div>
        <p class="text-[10px] font-bold text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MEMUAT DATA...</p>
    </div>
</div>

<div class="max-w-[1250px] mx-auto animate-slide-up pb-16">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 bg-white p-6 md:px-8 md:py-6 rounded-[24px] border border-slate-200/60 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-cyan-50/50 rounded-bl-full pointer-events-none transition-transform duration-700 hover:scale-110"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-14 h-14 rounded-[16px] bg-gradient-to-br from-cyan-500 to-blue-600 text-white flex items-center justify-center text-2xl shadow-[0_8px_15px_rgba(6,182,212,0.25)] shrink-0">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h1 class="text-[22px] font-black text-slate-800 tracking-tight font-poppins mb-0.5">Jadwal Operasional</h1>
                <p class="text-slate-500 font-medium text-[13px] leading-relaxed">Kelola agenda posyandu dan sistem notifikasi otomatis ke warga.</p>
            </div>
        </div>
        <a href="<?php echo e(route('bidan.jadwal.create')); ?>" class="smooth-route inline-flex items-center justify-center gap-2.5 px-7 py-3.5 bg-slate-900 text-white font-bold text-[11.5px] uppercase tracking-widest rounded-xl hover:bg-cyan-600 hover:shadow-[0_10px_20px_rgba(6,182,212,0.2)] hover:-translate-y-0.5 transition-all duration-300 shrink-0 relative z-10">
            <i class="fas fa-plus text-sm"></i> Buat Agenda Baru
        </a>
    </div>

    
    <div class="bg-white rounded-[28px] border border-slate-200/80 shadow-[0_5px_20px_rgb(0,0,0,0.02)] flex flex-col overflow-hidden relative z-10">
        
        <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-[10px] bg-cyan-100/50 text-cyan-600 flex items-center justify-center shadow-sm text-sm border border-cyan-100"><i class="fas fa-list-ul"></i></div>
                <h3 class="font-bold text-slate-800 text-[15px] font-poppins tracking-tight">Daftar Agenda Tersimpan</h3>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar flex-1 p-2 md:p-3">
            <table class="w-full text-left border-collapse min-w-[1050px]">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu Pelaksanaan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Rincian Kegiatan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Distribusi Sasaran</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="nexus-table-row">
                        
                        <td class="px-6 py-4 text-[13px] font-semibold text-slate-400 align-middle text-center"><?php echo e($jadwals->firstItem() + $index); ?></td>
                        
                        
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="date-card w-12 h-12 rounded-[14px] bg-slate-50 border border-slate-200 flex flex-col items-center justify-center shrink-0">
                                    <span class="text-[9px] font-bold text-cyan-600 uppercase tracking-widest"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                                    <span class="text-[18px] font-black text-slate-800 leading-none mt-0.5"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-[14px] mb-1 font-poppins"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, Y')); ?></p>
                                    <p class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                                        <i class="far fa-clock text-cyan-500"></i> <?php echo e(date('H:i', strtotime($jadwal->waktu_mulai))); ?> - <?php echo e(date('H:i', strtotime($jadwal->waktu_selesai))); ?>

                                    </p>
                                </div>
                            </div>
                        </td>

                        
                        <td class="px-6 py-4 align-middle">
                            <p class="font-bold text-slate-800 text-[14.5px] mb-1 font-poppins text-wrap leading-tight"><?php echo e($jadwal->judul); ?></p>
                            <p class="text-[12px] font-medium text-slate-500 line-clamp-1 mb-1.5 max-w-[260px]"><?php echo e($jadwal->deskripsi ?? 'Tidak ada deskripsi tambahan.'); ?></p>
                            <p class="text-[11px] font-semibold text-slate-600 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-rose-400"></i> <?php echo e($jadwal->lokasi); ?></p>
                        </td>

                        
                        <td class="px-6 py-4 align-middle">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-lg border border-indigo-100 uppercase tracking-widest">
                                    <i class="fas fa-tags text-indigo-400 text-[9px]"></i> <?php echo e($jadwal->kategori); ?>

                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg border border-emerald-100 uppercase tracking-widest">
                                    <i class="fas fa-bullseye text-emerald-400 text-[9px]"></i> <?php echo e(str_replace('_', ' ', $jadwal->target_peserta)); ?>

                                </span>
                            </div>
                        </td>

                        
                        <td class="px-6 py-4 text-center align-middle">
                            <?php
                                $statusConf = match($jadwal->status) {
                                    'aktif' => ['bg-cyan-50 text-cyan-700 border-cyan-200', 'Agenda Aktif', 'fa-check-circle text-cyan-500'],
                                    'selesai' => ['bg-slate-50 text-slate-500 border-slate-200', 'Selesai', 'fa-flag-checkered text-slate-400'],
                                    'dibatalkan' => ['bg-rose-50 text-rose-600 border-rose-200', 'Dibatalkan', 'fa-times-circle text-rose-500'],
                                    default => ['bg-slate-50 text-slate-600', $jadwal->status, 'fa-info-circle']
                                };
                            ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest border <?php echo e($statusConf[0]); ?>">
                                <i class="fas <?php echo e($statusConf[2]); ?> <?php echo e($jadwal->status == 'aktif' ? 'animate-pulse' : ''); ?>"></i> <?php echo e($statusConf[1]); ?>

                            </span>
                        </td>

                        
                        <td class="px-6 py-4 text-right align-middle">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?php echo e(route('bidan.jadwal.edit', $jadwal->id)); ?>" class="smooth-route w-9 h-9 rounded-[10px] bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-amber-50 hover:text-amber-500 hover:border-amber-200 transition-all border border-transparent" title="Edit Jadwal">
                                    <i class="fas fa-edit text-[13px]"></i>
                                </a>
                                <form action="<?php echo e(route('bidan.jadwal.destroy', $jadwal->id)); ?>" method="POST" class="m-0 p-0">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" onclick="confirmDelete(this)" class="w-9 h-9 rounded-[10px] bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 hover:border-rose-200 transition-all border border-transparent" title="Hapus Jadwal">
                                        <i class="fas fa-trash-alt text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-20">
                            <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-100"><i class="fas fa-calendar-times text-4xl"></i></div>
                            <h4 class="font-bold text-slate-800 text-[16px] font-poppins mb-1">Database Jadwal Kosong</h4>
                            <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto">Anda belum membuat agenda medis bulan ini.</p>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // ====================================================================
    // SWEETALERT NEXUS: KONFIRMASI HAPUS
    // ====================================================================
    function confirmDelete(button) {
        const form = button.closest('form');
        
        Swal.fire({
            title: 'Hapus Agenda?',
            text: "Menghapus jadwal ini akan menarik Notifikasi dari HP Warga secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-1.5"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                confirmButton: 'bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-400 hover:to-rose-500 text-white px-8 py-3.5 rounded-[14px] font-bold text-[12px] uppercase tracking-widest transition-all shadow-[0_10px_20px_rgba(244,63,94,0.3)] hover:-translate-y-0.5 outline-none border-none mx-2',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-8 py-3.5 rounded-[14px] font-bold text-[12px] uppercase tracking-widest transition-all outline-none border-none mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader('MENGHAPUS AGENDA...');
                form.submit();
            }
        });
    }

    // ====================================================================
    // SWEETALERT NEXUS: NOTIFIKASI SUKSES & ERROR
    // ====================================================================
    <?php if(session('success')): ?>
        document.querySelectorAll('.alert, .toast').forEach(el => el.remove());

        Swal.fire({
            title: 'Tindakan Berhasil!',
            html: <?php echo json_encode(session('success')); ?>,
            icon: 'success',
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-check-circle mr-1.5"></i> Mengerti',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                confirmButton: 'bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white px-10 py-3.5 rounded-[14px] font-bold text-[12px] uppercase tracking-widest transition-all shadow-[0_10px_20px_rgba(16,185,129,0.3)] hover:-translate-y-0.5 outline-none border-none mt-2'
            }
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        document.querySelectorAll('.alert, .toast').forEach(el => el.remove());
        
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            html: <?php echo json_encode(session('error')); ?>,
            icon: 'error',
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-times-circle mr-1.5"></i> Tutup',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                confirmButton: 'bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-400 hover:to-rose-500 text-white px-10 py-3.5 rounded-[14px] font-bold text-[12px] uppercase tracking-widest transition-all shadow-[0_10px_20px_rgba(244,63,94,0.3)] hover:-translate-y-0.5 outline-none border-none mt-2'
            }
        });
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/jadwal/index.blade.php ENDPATH**/ ?>