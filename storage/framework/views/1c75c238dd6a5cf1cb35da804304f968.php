

<?php $__env->startSection('title', 'Data Remaja'); ?>
<?php $__env->startSection('page-name', 'Database Remaja'); ?>

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
    .crm-checkbox:hover { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .crm-checkbox:checked { background: #4f46e5; border-color: #4f46e5; }
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
    .crm-search:focus { border-color: #6366f1; box-shadow: 0 4px 20px -3px rgba(99, 102, 241, 0.15); }

    /* TABEL CRM KAPSUL */
    .crm-card { background: #ffffff; border: 1px solid #f1f5f9; border-radius: 28px; box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); overflow: hidden; padding: 4px; }
    .crm-table { width: 100%; border-collapse: collapse; text-align: left; }
    .crm-table th { background: #f8fafc; color: #4f46e5; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem 1rem; border-bottom: 1px solid #e0e7ff; }
    .crm-table th:first-child { border-top-left-radius: 24px; }
    .crm-table th:last-child { border-top-right-radius: 24px; }
    .crm-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; transition: all 0.2s ease; }
    .crm-table tr:hover td { background-color: #f8fafc; }
    .crm-table tr:last-child td { border-bottom: none; }

    /* CUSTOM SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(203, 213, 225, 0.4); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(203, 213, 225, 0.8); }

    /* MINI BADGES */
    .badge-mini { display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 9999px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }

    /* ACTION BUTTONS (GAYA NEXUS) */
    .action-btn { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; border: 1px solid transparent; background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .action-btn.sync:hover { background: #10b981; color: white; border-color: #059669; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .action-btn.view:hover { background: #4f46e5; color: white; border-color: #4338ca; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); transform: translateY(-2px); }
    .action-btn.edit:hover { background: #f59e0b; color: white; border-color: #d97706; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transform: translateY(-2px); }
    .action-btn.delete:hover { background: #f43f5e; color: white; border-color: #e11d48; box-shadow: 0 4px 15px rgba(244, 63, 94, 0.3); transform: translateY(-2px); }

    /* =========================================================
       NEXUS SWEETALERT BULLETPROOF OVERRIDES 
       ========================================================= */
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
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-user-graduate text-indigo-600 text-xl"></i>
    </div>
    <p class="text-[11px] font-black tracking-[0.2em] text-slate-500 uppercase">Memuat Pangkalan Data</p>
</div>

<div class="max-w-[1400px] mx-auto fade-in-up pb-12 relative z-10">

    
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-400/20 to-blue-400/20 rounded-full blur-[80px] pointer-events-none z-0"></div>

    
    <div class="bg-white/90 backdrop-blur-2xl rounded-[32px] border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 z-10">
        
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl"></div>

        <div class="flex items-center gap-6 relative z-10 w-full md:w-auto">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-600 to-blue-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0 transform -rotate-3 transition-transform hover:rotate-0">
                <i class="fas fa-user-graduate drop-shadow-sm"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest border border-emerald-200 bg-emerald-50 px-2.5 py-1 rounded-md">Automasi Sistem Aktif</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Database Remaja</h1>
                <p class="text-slate-500 font-medium text-[13px] max-w-md">Kelola master data peserta Posyandu Remaja, identitas, dan akademik.</p>
            </div>
        </div>
        
        <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <a href="<?php echo e(route('kader.import.index')); ?>?type=remaja" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 text-emerald-600 font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-emerald-50 hover:border-emerald-200 transition-all shadow-sm">
                <i class="fas fa-file-import text-sm"></i> Import Massal
            </a>
            <a href="<?php echo e(route('kader.data.remaja.create')); ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 text-white font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-indigo-700 transition-all shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-1 hover:shadow-[0_12px_25px_rgba(79,70,229,0.4)]">
                <i class="fas fa-plus text-sm text-indigo-200"></i> Registrasi Baru
            </a>
        </div>
    </div>

    
    <div class="flex flex-col xl:flex-row items-center justify-between gap-5 mb-6 relative z-20">
        
        
        <div class="flex items-center gap-4 w-full xl:w-auto">
            <div class="inline-flex items-center gap-2 bg-white border border-slate-200 px-6 py-3.5 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.02)]">
                <i class="fas fa-users text-indigo-500 text-sm"></i>
                <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Total: <?php echo e($stats['total'] ?? 0); ?> Remaja</span>
            </div>

            
            <form action="<?php echo e(route('kader.data.remaja.bulk-delete')); ?>" method="POST" id="bulkDeleteForm" class="hidden w-full sm:w-auto">
                <?php echo csrf_field(); ?>
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="w-full sm:w-auto px-6 py-3.5 bg-rose-50 border border-rose-200 text-rose-600 font-black text-[11px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white transition-all shadow-sm hover:shadow-[0_8px_20px_rgba(244,63,94,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Eksekusi Hapus (<span id="bulkCount">0</span>)
                </button>
            </form>
        </div>

        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
            <div class="relative w-full sm:w-[400px] group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-500 transition-colors"></i>
                <input type="text" id="liveSearchInput" placeholder="Ketik Nama, NIK, atau Sekolah..." 
                       class="w-full bg-white border border-e2e8f0 text-slate-800 font-semibold text-[13px] rounded-full py-3.5 pl-[3rem] pr-6 outline-none transition-all focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-[0_2px_8px_rgba(0,0,0,0.02)] placeholder:text-slate-400 placeholder:font-medium" autocomplete="off">
            </div>
        </div>
    </div>

    
    
    
    <div class="crm-card relative z-10">
        <div class="overflow-x-auto custom-scrollbar" style="min-h: 300px;">
            <table class="crm-table min-w-[1200px]">
                <thead>
                    <tr>
                        <th class="w-12 text-center pl-6"><input type="checkbox" class="crm-checkbox select-all-btn" onclick="toggleSelectAll(this)"></th>
                        <th class="w-56">Identitas Remaja</th>
                        <th class="w-48">Info Akademik</th>
                        <th class="w-48">Orang Tua / Wali</th>
                        <th class="w-32 text-center">Status Akun</th>
                        <th class="w-40 text-center pr-6">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $remajas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php 
                        $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                        $strUmur = $diff->y . ' Thn ' . $diff->m . ' Bln';
                        $avatarColor = $item->jenis_kelamin == 'L' ? 'bg-indigo-50 text-indigo-500 border-indigo-200' : 'bg-rose-50 text-rose-500 border-rose-200';
                        $jkBadge = $item->jenis_kelamin == 'L' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'bg-rose-50 text-rose-600 border-rose-200';
                    ?>
                    <tr class="pasien-row hover:bg-slate-50/50 transition-colors" data-search="<?php echo e(strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->sekolah)); ?>">
                        
                        <td class="text-center pl-6"><input type="checkbox" name="ids[]" value="<?php echo e($item->id); ?>" class="crm-checkbox row-checkbox" onchange="checkBulkStatus()"></td>
                        
                        
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[12px] flex items-center justify-center font-black text-sm shrink-0 border shadow-sm <?php echo e($avatarColor); ?>">
                                    <?php echo e(strtoupper(substr($item->nama_lengkap, 0, 1))); ?>

                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-slate-800 font-poppins truncate max-w-[150px]" title="<?php echo e($item->nama_lengkap); ?>"><?php echo e($item->nama_lengkap); ?></span>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-[10px] font-bold text-slate-400 font-mono"><?php echo e($item->nik ?? '-'); ?></span>
                                        <span class="text-[10px] font-bold text-slate-300">•</span>
                                        <span class="badge-mini border <?php echo e($jkBadge); ?>" style="padding: 2px 6px; font-size:8px;"><?php echo e($item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-slate-700 truncate max-w-[150px]" title="<?php echo e($item->sekolah); ?>"><i class="fas fa-school text-slate-400 w-3 text-center"></i> <?php echo e($item->sekolah ?? 'Tidak diisi'); ?> (Kls: <?php echo e($item->kelas ?? '-'); ?>)</span>
                                <span class="text-[10px] font-bold text-slate-500"><i class="fas fa-birthday-cake text-indigo-400"></i> Usia: <span class="text-indigo-600 font-black"><?php echo e($strUmur); ?></span></span>
                            </div>
                        </td>

                        
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-slate-700 truncate max-w-[150px]" title="<?php echo e($item->nama_ortu); ?>"><i class="fas fa-user-friends text-slate-400 w-3 text-center"></i> <?php echo e($item->nama_ortu ?? '—'); ?></span>
                                <span class="text-[10px] font-semibold text-slate-500 font-mono"><i class="fas fa-phone-alt text-slate-400 w-3 text-center"></i> <?php echo e($item->telepon_ortu ?? 'Tidak ada kontak'); ?></span>
                            </div>
                        </td>

                        
                        <td class="text-center">
                            <?php if($item->user_id): ?>
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500"><i class="fas fa-check-circle"></i> Terhubung Akun</span>
                            <?php else: ?>
                                <form action="<?php echo e(route('kader.data.remaja.sync', $item->id)); ?>" method="POST" class="m-0 p-0 inline-block">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-white border border-amber-300 px-2 py-1 rounded hover:bg-amber-50 transition-all shadow-sm">
                                        <i class="fas fa-satellite-dish animate-pulse"></i> Deteksi Akun
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        
                        <td class="text-center pr-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('kader.data.remaja.show', $item->id)); ?>" class="action-btn view text-indigo-500 border-slate-200" title="Buka Rekam Medis"><i class="fas fa-book-medical text-[12px]"></i></a>
                                <a href="<?php echo e(route('kader.data.remaja.edit', $item->id)); ?>" class="action-btn edit text-amber-500 border-slate-200" title="Edit Profil"><i class="fas fa-pen text-[12px]"></i></a>
                                <form action="<?php echo e(route('kader.data.remaja.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>" class="m-0 p-0">
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
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-sm border border-slate-100"><i class="fas fa-user-graduate"></i></div>
                                <h4 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-2 font-poppins">Pangkalan Data Kosong</h4>
                                <p class="text-[13px] text-slate-500 font-medium leading-relaxed">Belum ada data peserta Remaja yang tercatat. Gunakan tombol Registrasi Baru untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if($remajas->hasPages()): ?>
        <div class="p-4 border-t border-slate-100 bg-white">
            <?php echo e($remajas->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
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

    // =========================================================================
    // 3. NEXUS ALERT SYSTEM (Kustomisasi Premium SweetAlert)
    // =========================================================================
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true
    });

    <?php if(session('success')): ?> Toast.fire({ icon: 'success', title: 'Berhasil!', text: "<?php echo addslashes(session('success')); ?>" }); <?php endif; ?>
    <?php if(session('error')): ?> Toast.fire({ icon: 'error', title: 'Gagal', text: "<?php echo addslashes(session('error')); ?>" }); <?php endif; ?>

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `Data profil dan catatan rekam medis untuk <b>${name}</b> akan <b style="color:#f43f5e">dihapus permanen</b> dari sistem.`,
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
            html: `Aksi ini akan <b style="color:#f43f5e">menghapus masal</b> profil peserta Remaja beserta seluruh log pemeriksaannya. Tindakan mutlak.`,
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
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/remaja/index.blade.php ENDPATH**/ ?>