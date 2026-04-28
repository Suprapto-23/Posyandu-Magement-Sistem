

<?php $__env->startSection('title', 'Data Ibu Hamil'); ?>
<?php $__env->startSection('page-name', 'Database Ibu Hamil'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK HALUS */
    .fade-in-up { animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* CHECKBOX CRM STYLE */
    .crm-checkbox {
        appearance: none; width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #ffffff; cursor: pointer; transition: all 0.2s ease; position: relative;
    }
    .crm-checkbox:hover { border-color: #f472b6; box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1); }
    .crm-checkbox:checked { background: #ec4899; border-color: #ec4899; }
    .crm-checkbox:checked::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
        color: white; font-size: 11px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    /* INPUT PENCARIAN CRM */
    .crm-search {
        width: 100%; background-color: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;
        font-size: 0.85rem; font-weight: 600; border-radius: 9999px; padding: 0.75rem 1.5rem 0.75rem 3rem;
        outline: none; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .crm-search:focus { border-color: #f472b6; box-shadow: 0 4px 20px -3px rgba(236, 72, 153, 0.15); }

    /* TABEL CRM KAPSUL */
    .crm-card { background: #ffffff; border: 1px solid #fdf2f8; border-radius: 28px; box-shadow: 0 10px 40px -10px rgba(236, 72, 153, 0.05); overflow: hidden; padding: 4px; }
    .crm-table { width: 100%; border-collapse: collapse; text-align: left; }
    .crm-table th { background: #fdf2f8; color: #db2777; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem 1rem; border-bottom: 1px solid #fce7f3; }
    .crm-table th:first-child { border-top-left-radius: 24px; }
    .crm-table th:last-child { border-top-right-radius: 24px; }
    .crm-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; transition: all 0.2s ease; }
    
    /* PERBAIKAN: Hover putih bersih / abu sangat halus */
    .crm-table tr:hover td { background-color: #f8fafc; }
    .crm-table tr:last-child td { border-bottom: none; }

    /* CUSTOM SCROLLBAR (Halus dan Menyamar) */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(203, 213, 225, 0.4); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(203, 213, 225, 0.8); }

    /* MINI BADGES */
    .badge-mini { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 9999px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }

    /* TAB SWITCHER KAPSUL */
    .segmented-control { display: inline-flex; background: #ffffff; padding: 6px; border-radius: 9999px; border: 1px solid #fdf2f8; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.02); }
    .segment-btn { padding: 12px 28px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 9999px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: #64748b; display: flex; align-items: center; gap: 8px; border: 1px solid transparent; }
    .segment-btn:hover:not(.active) { background: #fdf2f8; color: #be185d; }
    .segment-btn.active { background: #ec4899; color: #ffffff; box-shadow: 0 8px 25px rgba(236, 72, 153, 0.35); transform: translateY(-1px); border: 1px solid #db2777; }
    
    .badge-counter { background: #fce7f3; color: #db2777; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 900; transition: all 0.3s ease; }
    .segment-btn.active .badge-counter { background: rgba(255,255,255,0.25); color: #ffffff; }

    /* ACTION BUTTONS */
    .action-btn { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; border: 1px solid transparent; background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .action-btn.sync:hover { background: #10b981; color: white; border-color: #059669; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .action-btn.view:hover { background: #ec4899; color: white; border-color: #db2777; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3); transform: translateY(-2px); }
    .action-btn.edit:hover { background: #f59e0b; color: white; border-color: #d97706; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transform: translateY(-2px); }
    .action-btn.delete:hover { background: #f43f5e; color: white; border-color: #e11d48; box-shadow: 0 4px 15px rgba(244, 63, 94, 0.3); transform: translateY(-2px); }

    /* SWEETALERT OVERRIDES */
    div:where(.swal2-container) { z-index: 10000 !important; backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup.swal2-toast { border-radius: 20px !important; padding: 16px 24px !important; background: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(10px) !important; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15) !important; border: 1px solid rgba(226, 232, 240, 0.8) !important; margin-top: 20px !important; margin-right: 20px !important; width: auto !important; max-width: 400px !important; }
    .swal2-toast .swal2-title { font-size: 14px !important; color: #1e293b !important; font-family: 'Poppins', sans-serif !important; }
    .swal2-toast .swal2-html-container { font-size: 12px !important; color: #64748b !important; margin: 4px 0 0 0 !important; text-align: left !important;}
    .swal2-popup:not(.swal2-toast) { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(16px) !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; border: 1px solid rgba(255,255,255,0.5) !important; width: 28em !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
    .swal2-popup .swal2-html-container { font-size: 0.85rem !important; color: #64748b !important; line-height: 1.6 !important; }
    .swal2-actions { margin-top: 2rem !important; gap: 12px !important; }
    .btn-nexus-cancel { background: #f1f5f9 !important; color: #64748b !important; border-radius: 100px !important; padding: 12px 24px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; transition: all 0.3s ease !important; }
    .btn-nexus-cancel:hover { background: #e2e8f0 !important; }
    .btn-nexus-danger { background: #f43f5e !important; color: #ffffff !important; border-radius: 100px !important; padding: 12px 28px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; box-shadow: 0 8px 20px rgba(244,63,94,0.3) !important; transition: all 0.3s ease !important; }
    .btn-nexus-danger:hover { background: #e11d48 !important; transform: translateY(-2px) !important; box-shadow: 0 12px 25px rgba(244,63,94,0.4) !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-500 opacity-100 pointer-events-auto">
    <div class="relative w-16 h-16 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-pink-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-pink-500 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-female text-pink-500 text-xl"></i>
    </div>
    <p class="text-[11px] font-black tracking-[0.2em] text-slate-500 uppercase">Memuat Pangkalan Data</p>
</div>

<div class="max-w-[1400px] mx-auto fade-in-up pb-12 relative z-10">

    
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-pink-400/20 to-rose-400/20 rounded-full blur-[80px] pointer-events-none z-0"></div>

    
    <div class="bg-white/90 backdrop-blur-2xl rounded-[32px] border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 z-10">
        
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-pink-500/10 rounded-full blur-2xl"></div>

        <div class="flex items-center gap-6 relative z-10 w-full md:w-auto">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-pink-500 to-rose-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(236,72,153,0.3)] shrink-0 transform -rotate-3 transition-transform hover:rotate-0">
                <i class="fas fa-female drop-shadow-sm"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest border border-emerald-200 bg-emerald-50 px-2.5 py-1 rounded-md">Automasi Sistem Aktif</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Database Ibu Hamil</h1>
                <p class="text-slate-500 font-medium text-[13px] max-w-md">Kelola arsip identitas, parameter fisik, dan prediksi HPL persalinan.</p>
            </div>
        </div>
        
        <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <a href="<?php echo e(route('kader.import.index')); ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 text-emerald-600 font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-emerald-50 hover:border-emerald-200 transition-all shadow-sm">
                <i class="fas fa-file-import text-sm"></i> Import Massal
            </a>
            <a href="<?php echo e(route('kader.data.ibu-hamil.create')); ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-4 bg-pink-500 text-white font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-pink-600 transition-all shadow-[0_8px_20px_rgba(236,72,153,0.3)] hover:-translate-y-1 hover:shadow-[0_12px_25px_rgba(236,72,153,0.4)]">
                <i class="fas fa-plus text-sm text-pink-200"></i> Registrasi Baru
            </a>
        </div>
    </div>

    
    <div class="flex flex-col xl:flex-row items-center justify-between gap-5 mb-6 relative z-20">
        
        
        <form action="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" method="GET" class="segmented-control w-full xl:w-auto overflow-x-auto" style="scrollbar-width: none;">
            <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <button type="submit" name="filter" value="semua" class="segment-btn flex-shrink-0 <?php echo e(request('filter', 'semua') == 'semua' ? 'active' : ''); ?>">
                <i class="fas fa-list-ul text-[14px]"></i> Semua 
                <span class="badge-counter"><?php echo e($stats['total'] ?? 0); ?></span>
            </button>
            <button type="submit" name="filter" value="aktif" class="segment-btn flex-shrink-0 <?php echo e(request('filter') == 'aktif' ? 'active' : ''); ?>">
                <i class="fas fa-heartbeat text-[14px]"></i> Aktif 
                <span class="badge-counter"><?php echo e($stats['aktif'] ?? 0); ?></span>
            </button>
            <button type="submit" name="filter" value="hampir_lahir" class="segment-btn flex-shrink-0 <?php echo e(request('filter') == 'hampir_lahir' ? 'active' : ''); ?>">
                <i class="fas fa-exclamation-circle text-[14px]"></i> HPL Dekat 
                <span class="badge-counter"><?php echo e($stats['hampir_lahir'] ?? 0); ?></span>
            </button>
        </form>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
            <form action="<?php echo e(route('kader.data.ibu-hamil.bulk-delete')); ?>" method="POST" id="bulkDeleteForm" class="hidden w-full sm:w-auto">
                <?php echo csrf_field(); ?>
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="w-full sm:w-auto px-6 py-3.5 bg-rose-50 border border-rose-200 text-rose-600 font-black text-[11px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white transition-all shadow-sm hover:shadow-[0_8px_20px_rgba(244,63,94,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Eksekusi Hapus (<span id="bulkCount">0</span>)
                </button>
            </form>

            <div class="relative w-full sm:w-[400px] group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-pink-500 transition-colors"></i>
                <input type="text" id="liveSearchInput" placeholder="Ketik Nama, NIK, Suami..." class="crm-search" autocomplete="off">
            </div>
        </div>
    </div>

    
    
    
    <div class="crm-card relative z-10">
        
        <div class="overflow-x-auto custom-scrollbar" style="min-h: 300px;">
            <table class="crm-table min-w-[1200px]">
                <thead>
                    <tr>
                        <th class="w-12 text-center pl-6"><input type="checkbox" class="crm-checkbox select-all-bumil" onclick="toggleSelectAll(this)"></th>
                        <th class="w-48">Identitas Ibu</th>
                        <th class="w-40">Kondisi Kehamilan</th>
                        <th class="w-32">Fisik Dasar</th>
                        <th class="w-32 text-center">Status Warga</th>
                        <th class="w-40 text-center pr-6">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ibuHamils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    
                    <tr class="pasien-row hover:bg-slate-50/50 transition-colors" data-search="<?php echo e(strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->nama_suami)); ?>">
                        
                        <td class="text-center pl-6"><input type="checkbox" name="ids[]" value="<?php echo e($item->id); ?>" class="crm-checkbox row-checkbox bumil-checkbox" onchange="checkBulkStatus()"></td>
                        
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[12px] flex items-center justify-center font-black text-sm shrink-0 border bg-pink-50 text-pink-500 border-pink-200 shadow-sm">
                                    <?php echo e(strtoupper(substr($item->nama_lengkap, 0, 1))); ?>

                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-slate-800 font-poppins truncate max-w-[140px]" title="<?php echo e($item->nama_lengkap); ?>"><?php echo e($item->nama_lengkap); ?></span>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-[10px] font-bold text-slate-400 font-mono"><?php echo e($item->nik ?? '-'); ?></span>
                                        <span class="text-[10px] font-bold text-slate-300">•</span>
                                        <span class="text-[10px] font-black text-sky-500"><i class="fas fa-male"></i> <?php echo e($item->nama_suami ?? '-'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center justify-between bg-slate-50 border border-slate-100 rounded px-2 py-0.5">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">HPHT</span>
                                    <span class="text-[11px] font-bold text-slate-700"><?php echo e($item->hpht ? $item->hpht->translatedFormat('d M Y') : '-'); ?></span>
                                </div>
                                <div class="flex items-center justify-between bg-pink-50 border border-pink-100 rounded px-2 py-0.5">
                                    <span class="text-[9px] font-black text-pink-500 uppercase tracking-widest">HPL</span>
                                    <span class="text-[11px] font-black text-pink-700"><?php echo e($item->hpl ? $item->hpl->translatedFormat('d M Y') : '-'); ?></span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-slate-500">BB: <span class="font-black text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded"><?php echo e($item->berat_badan ?? '-'); ?> kg</span></span>
                                <span class="text-[11px] font-bold text-slate-500">TB: <span class="font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded"><?php echo e($item->tinggi_badan ?? '-'); ?> cm</span></span>
                                
                                <?php
                                    $imtClass = 'bg-slate-100 text-slate-500';
                                    if($item->imt) {
                                        if($item->imt < 18.5) { $imtClass = 'bg-amber-50 text-amber-600 border border-amber-200'; }
                                        elseif($item->imt < 25) { $imtClass = 'bg-emerald-50 text-emerald-600 border border-emerald-200'; }
                                        else { $imtClass = 'bg-rose-50 text-rose-600 border border-rose-200'; }
                                    }
                                ?>
                                <span class="text-[9px] font-black uppercase tracking-widest w-max px-2 py-0.5 rounded mt-0.5 <?php echo e($imtClass); ?>">
                                    IMT: <?php echo e($item->imt ?? '-'); ?>

                                </span>
                            </div>
                        </td>

                        <td class="text-center">
                            <?php if($item->status == 'aktif'): ?>
                                <span class="badge-mini bg-pink-50 text-pink-600 border border-pink-200 mb-1 block w-max mx-auto">
                                    <i class="fas fa-heartbeat"></i> <?php echo e($item->trimester); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge-mini bg-slate-100 text-slate-500 border border-slate-200 mb-1 block w-max mx-auto">
                                    <i class="fas fa-check-double"></i> Bersalin
                                </span>
                            <?php endif; ?>
                            
                            <?php if($item->user_id): ?>
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500"><i class="fas fa-check-circle"></i> Terhubung</span>
                            <?php else: ?>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400"><i class="fas fa-link-slash"></i> Terputus</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center pr-6">
                            <div class="flex items-center justify-center gap-2">
                                <?php if(!$item->user_id): ?>
                                    <form action="<?php echo e(route('kader.data.ibu-hamil.sync', $item->id)); ?>" method="POST" class="m-0 p-0">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="action-btn sync text-emerald-500 border-slate-200" title="Sinkronisasi Akun Warga"><i class="fas fa-link text-[12px]"></i></button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('kader.data.ibu-hamil.show', $item->id)); ?>" class="action-btn view text-pink-500 border-slate-200" title="Lihat Rekam Medis"><i class="fas fa-book-medical text-[12px]"></i></a>
                                <a href="<?php echo e(route('kader.data.ibu-hamil.edit', $item->id)); ?>" class="action-btn edit text-amber-500 border-slate-200" title="Edit Profil"><i class="fas fa-pen text-[12px]"></i></a>
                                <form action="<?php echo e(route('kader.data.ibu-hamil.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>" class="m-0 p-0">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" onclick="confirmSingleDelete('<?php echo e($item->id); ?>', '<?php echo e(addslashes($item->nama_lengkap)); ?>')" class="action-btn delete text-rose-500 border-slate-200" title="Hapus Data"><i class="fas fa-trash-alt text-[12px]"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr class="empty-state-row">
                        <td colspan="6" class="py-24 text-center border-none">
                            <div class="flex flex-col items-center justify-center max-w-md mx-auto bg-slate-50/50 border border-dashed border-slate-300 rounded-[32px] p-10">
                                <div class="mb-4">
                                    <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_bseuqzqz.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                                </div>
                                <h4 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-2 font-poppins">Pangkalan Data Kosong</h4>
                                <p class="text-[13px] text-slate-500 font-medium leading-relaxed">Belum ada data ibu hamil yang tercatat, atau filter yang Anda pilih kosong.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if($ibuHamils->hasPages()): ?>
        <div class="p-4 border-t border-slate-100 bg-white">
            <?php echo e($ibuHamils->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Loader Logic
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 500); }
    };

    // 2. LIVE SEARCH FILTER
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('liveSearchInput');
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('.pasien-row');
                
                rows.forEach(row => {
                    const dataSearch = row.getAttribute('data-search');
                    if (dataSearch.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                        const cb = row.querySelector('.row-checkbox');
                        if(cb && cb.checked) { cb.checked = false; checkBulkStatus(); }
                    }
                });
            });
        }
    });

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true
    });

    <?php if(session('success')): ?> Toast.fire({ icon: 'success', title: 'Berhasil!', text: "<?php echo addslashes(session('success')); ?>" }); <?php endif; ?>
    <?php if(session('warning')): ?> Toast.fire({ icon: 'warning', title: 'Perhatian', text: "<?php echo addslashes(session('warning')); ?>" }); <?php endif; ?>
    <?php if(session('error')): ?> Toast.fire({ icon: 'error', title: 'Gagal', text: "<?php echo addslashes(session('error')); ?>" }); <?php endif; ?>

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `Data profil dan catatan rekam medis kandungan untuk <b>${name}</b> akan <b style="color:#f43f5e">dihapus permanen</b> dari sistem.`,
            icon: 'warning', iconColor: '#f43f5e', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Eksekusi Hapus', cancelButtonText: 'Batalkan',
            reverseButtons: true, buttonsStyling: false, 
            customClass: { confirmButton: 'btn-nexus-danger', cancelButton: 'btn-nexus-cancel' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        });
    }

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if(row.style.display !== 'none') { cb.checked = source.checked; }
        });
        checkBulkStatus();
    }

    function checkBulkStatus() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkDeleteForm');
        const bulkCountSpan = document.getElementById('bulkCount');
        
        if (checkedBoxes.length > 0) {
            bulkForm.classList.remove('hidden');
            bulkForm.classList.add('fade-in-up');
            bulkCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkForm.classList.add('hidden');
            bulkForm.classList.remove('fade-in-up');
        }
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: `Hapus ${checkedBoxes.length} Data?`,
            html: `Aksi ini akan <b style="color:#f43f5e">menghapus masal</b> profil ibu hamil beserta seluruh log pemeriksaannya. Tindakan mutlak.`,
            icon: 'error', iconColor: '#f43f5e', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-skull-crossbones mr-2"></i> Ya, Hapus Masal', cancelButtonText: 'Batalkan',
            reverseButtons: true, buttonsStyling: false,
            customClass: { confirmButton: 'btn-nexus-danger', cancelButton: 'btn-nexus-cancel' }
        }).then((result) => {
            if (result.isConfirmed) {
                const inputContainer = document.getElementById('bulkDeleteInputs');
                inputContainer.innerHTML = ''; 
                
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                    inputContainer.appendChild(input);
                });
                
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/ibu-hamil/index.blade.php ENDPATH**/ ?>