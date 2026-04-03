

<?php $__env->startSection('title', 'Data Bayi & Balita'); ?>
<?php $__env->startSection('page-name', 'Database Bayi & Balita'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); }
    
    /* Custom Checkbox */
    .checkbox-modern {
        appearance: none; width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #f8fafc; cursor: pointer; transition: all 0.2s; position: relative; display: inline-block;
    }
    .checkbox-modern:checked { background: #6366f1; border-color: #6366f1; }
    .checkbox-modern:checked::after {
        content: '✔'; position: absolute; color: white; font-size: 12px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    /* Tab Switcher */
    .tab-pill { padding: 0.75rem 2rem; border-radius: 9999px; font-weight: 800; font-size: 0.875rem; cursor: pointer; transition: all 0.3s ease; text-align: center; }
    .tab-inactive { color: #64748b; background: transparent; }
    .tab-inactive:hover { color: #334155; background: #f1f5f9; }
    .tab-bayi-active { background: #e0e7ff; color: #4338ca; box-shadow: 0 4px 14px 0 rgba(67, 56, 202, 0.2); }
    .tab-balita-active { background: #fce7f3; color: #be185d; box-shadow: 0 4px 14px 0 rgba(190, 24, 93, 0.2); }
    
    .tab-content { display: none; opacity: 0; animation: fadeIn 0.4s ease forwards; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .badge-jk-L { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-jk-P { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-all duration-200 opacity-100 pointer-events-auto">
    <div class="relative w-16 h-16 mb-4">
        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-baby text-lg animate-pulse"></i></div>
    </div>
</div>

<div class="max-w-[1400px] mx-auto animate-slide-up">
    
    
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0">
                <i class="fas fa-baby-carriage"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-1">Database Anak Lengkap</h1>
                <p class="text-sm font-bold text-slate-500">Kelola profil, usia *real-time*, fisik lahir, dan info keluarga.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('kader.import.index')); ?>" class="px-5 py-2.5 bg-emerald-50 text-emerald-600 font-extrabold text-sm rounded-xl hover:bg-emerald-100 transition-colors border border-emerald-200">
                <i class="fas fa-file-excel mr-1.5"></i> Import Excel
            </a>
            <a href="<?php echo e(route('kader.data.balita.create')); ?>" class="px-5 py-2.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus mr-1.5"></i> Tambah Data
            </a>
        </div>
    </div>

    
    <div class="glass-card rounded-3xl p-4 mb-6 flex flex-col xl:flex-row items-center gap-4 justify-between">
        
        <div class="flex items-center gap-3 w-full xl:w-auto">
            <div class="bg-slate-100 p-1.5 rounded-full flex w-full sm:w-max border border-slate-200 shadow-inner">
                <button id="tab-btn-bayi" onclick="switchTab('bayi')" class="tab-pill tab-bayi-active flex-1 sm:flex-none">
                    Bayi (<?php echo e($bayis->count()); ?>)
                </button>
                <button id="tab-btn-balita" onclick="switchTab('balita')" class="tab-pill tab-inactive flex-1 sm:flex-none">
                    Balita (<?php echo e($balitas->count()); ?>)
                </button>
            </div>
            
            
            <form action="<?php echo e(route('kader.data.balita.bulk-delete')); ?>" method="POST" id="bulkDeleteForm" class="hidden">
                <?php echo csrf_field(); ?>
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="px-5 py-2.5 bg-rose-500 text-white font-black text-sm rounded-full hover:bg-rose-600 shadow-[0_4px_12px_rgba(225,29,72,0.3)] transition-all flex items-center gap-2 animate-pulse">
                    <i class="fas fa-trash-alt"></i> Hapus <span id="bulkCount">0</span> Terpilih
                </button>
            </form>
        </div>
        
        <form action="<?php echo e(route('kader.data.balita.index')); ?>" method="GET" class="w-full xl:w-1/3 relative group">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Ketik Nama atau NIK untuk mencari..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-full py-2.5 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 shadow-sm placeholder:text-slate-400">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
        </form>
    </div>

    
    
    
    <div id="panel-bayi" class="tab-content active">
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                            <th class="px-4 py-4 text-center w-10"><input type="checkbox" class="checkbox-modern select-all-bayi" onclick="toggleSelectAll(this, 'bayi')"></th>
                            <th class="px-4 py-4">Identitas Bayi</th>
                            <th class="px-4 py-4">Lahir & Fisik Awal</th>
                            <th class="px-4 py-4">Data Orang Tua</th>
                            <th class="px-4 py-4 text-center">Usia & Akun</th>
                            <th class="px-4 py-4 text-right pr-6">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $bayis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php 
                            // LOGIKA UMUR SUPER CERDAS UNTUK BAYI
                            $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                            $totalBln = ($diff->y * 12) + $diff->m;
                            $strUmurBayi = $totalBln == 0 ? $diff->d . ' Hari' : $totalBln . ' Bulan';
                            $fullUmurTitle = $diff->y . ' Tahun, ' . $diff->m . ' Bulan, ' . $diff->d . ' Hari';
                        ?>
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-4 py-4 text-center"><input type="checkbox" name="ids[]" value="<?php echo e($item->id); ?>" class="checkbox-modern row-checkbox bayi-checkbox" onchange="checkBulkStatus()"></td>
                            
                            
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg shrink-0 <?php echo e($item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600'); ?>">
                                        <?php echo e(substr($item->nama_lengkap, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800"><?php echo e($item->nama_lengkap); ?></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-black tracking-wider px-2 py-0.5 rounded-md <?php echo e($item->jenis_kelamin == 'L' ? 'badge-jk-L' : 'badge-jk-P'); ?>">
                                                <?php echo e($item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'); ?>

                                            </span>
                                            <span class="text-[11px] font-bold text-slate-500"><i class="far fa-id-card"></i> <?php echo e($item->nik ?? 'Belum Ada NIK'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-700 mb-1"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> <?php echo e($item->tempat_lahir); ?>, <?php echo e(\Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y')); ?></p>
                                <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">BB: <span class="text-indigo-600"><?php echo e($item->berat_lahir); ?> kg</span></span>
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">PB: <span class="text-emerald-600"><?php echo e($item->panjang_lahir); ?> cm</span></span>
                                </div>
                            </td>

                            
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-800 mb-0.5"><i class="fas fa-female text-rose-400 w-4"></i> <?php echo e($item->nama_ibu ?? '-'); ?></p>
                                <p class="text-[11px] font-semibold text-slate-500"><i class="fas fa-male text-blue-400 w-4"></i> <?php echo e($item->nama_ayah ?? '-'); ?></p>
                            </td>

                            
                            <td class="px-4 py-4 text-center">
                                <span title="<?php echo e($fullUmurTitle); ?>" class="block cursor-help mb-2 w-max mx-auto px-3 py-1 rounded-lg text-[11px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm transition-transform hover:scale-105">
                                    <i class="fas fa-clock mr-1 opacity-70"></i> <?php echo e(strtoupper($strUmurBayi)); ?>

                                </span>
                                <?php if($item->user_id): ?>
                                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100"><i class="fas fa-check-circle"></i> Akun Tertaut</span>
                                <?php else: ?>
                                    <a href="<?php echo e(route('kader.data.balita.sync', $item->id)); ?>" class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-200 hover:bg-amber-100 transition-colors"><i class="fas fa-sync"></i> Tarik Akun</a>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-4 pr-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('kader.data.balita.show', $item->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:border-indigo-300 flex items-center justify-center transition-all shadow-sm" title="Lihat Rekam Medis"><i class="fas fa-file-medical"></i></a>
                                    <a href="<?php echo e(route('kader.data.balita.edit', $item->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-all shadow-sm" title="Edit Data"><i class="fas fa-pen"></i></a>
                                    
                                    <form action="<?php echo e(route('kader.data.balita.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmSingleDelete('<?php echo e($item->id); ?>', '<?php echo e($item->nama_lengkap); ?>')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 flex items-center justify-center transition-all shadow-sm" title="Hapus Data"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100"><i class="fas fa-box-open"></i></div>
                                <h3 class="font-black text-slate-800 text-xl">Database Bayi Kosong</h3>
                                <p class="text-sm text-slate-500 mt-2">Belum ada bayi usia 0-11 bulan yang didaftarkan.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    
    
    <div id="panel-balita" class="tab-content">
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                            <th class="px-4 py-4 text-center w-10"><input type="checkbox" class="checkbox-modern select-all-balita" onclick="toggleSelectAll(this, 'balita')"></th>
                            <th class="px-4 py-4">Identitas Balita</th>
                            <th class="px-4 py-4">Lahir & Fisik Awal</th>
                            <th class="px-4 py-4">Data Orang Tua</th>
                            <th class="px-4 py-4 text-center">Usia & Akun</th>
                            <th class="px-4 py-4 text-right pr-6">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $balitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // LOGIKA UMUR SUPER CERDAS UNTUK BALITA
                            $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                            if ($diff->y > 0 && $diff->m > 0) {
                                $strUmurBalita = $diff->y . ' Thn ' . $diff->m . ' Bln';
                            } elseif ($diff->y > 0 && $diff->m == 0) {
                                $strUmurBalita = $diff->y . ' Tahun'; // Hilangkan 0 Bulan
                            } elseif ($diff->y == 0 && $diff->m > 0) {
                                $strUmurBalita = $diff->m . ' Bulan';
                            } else {
                                $strUmurBalita = $diff->d . ' Hari';
                            }
                            $fullUmurTitle = $diff->y . ' Tahun, ' . $diff->m . ' Bulan, ' . $diff->d . ' Hari';
                        ?>
                        <tr class="hover:bg-rose-50/30 transition-colors">
                            <td class="px-4 py-4 text-center"><input type="checkbox" name="ids[]" value="<?php echo e($item->id); ?>" class="checkbox-modern row-checkbox balita-checkbox" onchange="checkBulkStatus()"></td>
                            
                            
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg shrink-0 <?php echo e($item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600'); ?>">
                                        <?php echo e(substr($item->nama_lengkap, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800"><?php echo e($item->nama_lengkap); ?></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-black tracking-wider px-2 py-0.5 rounded-md <?php echo e($item->jenis_kelamin == 'L' ? 'badge-jk-L' : 'badge-jk-P'); ?>">
                                                <?php echo e($item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'); ?>

                                            </span>
                                            <span class="text-[11px] font-bold text-slate-500"><i class="far fa-id-card"></i> <?php echo e($item->nik ?? 'Belum Ada NIK'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-700 mb-1"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> <?php echo e($item->tempat_lahir); ?>, <?php echo e(\Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y')); ?></p>
                                <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">BB: <span class="text-indigo-600"><?php echo e($item->berat_lahir); ?> kg</span></span>
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">PB: <span class="text-emerald-600"><?php echo e($item->panjang_lahir); ?> cm</span></span>
                                </div>
                            </td>

                            
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-800 mb-0.5"><i class="fas fa-female text-rose-400 w-4"></i> <?php echo e($item->nama_ibu ?? '-'); ?></p>
                                <p class="text-[11px] font-semibold text-slate-500"><i class="fas fa-male text-blue-400 w-4"></i> <?php echo e($item->nama_ayah ?? '-'); ?></p>
                            </td>

                            
                            <td class="px-4 py-4 text-center">
                                <span title="<?php echo e($fullUmurTitle); ?>" class="block cursor-help mb-2 w-max mx-auto px-3 py-1 rounded-lg text-[11px] font-black bg-rose-100 text-rose-700 border border-rose-200 shadow-sm transition-transform hover:scale-105">
                                    <i class="fas fa-clock mr-1 opacity-70"></i> <?php echo e(strtoupper($strUmurBalita)); ?>

                                </span>
                                <?php if($item->user_id): ?>
                                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100"><i class="fas fa-check-circle"></i> Akun Tertaut</span>
                                <?php else: ?>
                                    <a href="<?php echo e(route('kader.data.balita.sync', $item->id)); ?>" class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-200 hover:bg-amber-100 transition-colors"><i class="fas fa-sync"></i> Tarik Akun</a>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-4 pr-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo e(route('kader.data.balita.show', $item->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:border-indigo-300 flex items-center justify-center transition-all shadow-sm" title="Lihat Rekam Medis"><i class="fas fa-file-medical"></i></a>
                                    <a href="<?php echo e(route('kader.data.balita.edit', $item->id)); ?>" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-all shadow-sm" title="Edit Data"><i class="fas fa-pen"></i></a>
                                    
                                    <form action="<?php echo e(route('kader.data.balita.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmSingleDelete('<?php echo e($item->id); ?>', '<?php echo e($item->nama_lengkap); ?>')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 flex items-center justify-center transition-all shadow-sm" title="Hapus Data"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100"><i class="fas fa-box-open"></i></div>
                                <h3 class="font-black text-slate-800 text-xl">Database Balita Kosong</h3>
                                <p class="text-sm text-slate-500 mt-2">Belum ada anak usia 12-59 bulan yang didaftarkan.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Matikan Loader Cepat
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 200); }
    };

    // TAB SWITCHER
    function switchTab(tab) {
        const panels = ['bayi', 'balita'];
        panels.forEach(p => document.getElementById('panel-' + p)?.classList.remove('active'));
        
        document.getElementById('tab-btn-bayi').className = 'tab-pill tab-inactive flex-1 sm:flex-none';
        document.getElementById('tab-btn-balita').className = 'tab-pill tab-inactive flex-1 sm:flex-none';

        document.getElementById('panel-' + tab).classList.add('active');
        document.getElementById('tab-btn-' + tab).className = `tab-pill tab-${tab}-active flex-1 sm:flex-none`;
        
        sessionStorage.setItem('activeTabBalita', tab);
        
        // Reset checkbox when switching tabs
        document.querySelectorAll('.checkbox-modern').forEach(cb => cb.checked = false);
        checkBulkStatus();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const saved = sessionStorage.getItem('activeTabBalita');
        if (saved && (saved === 'bayi' || saved === 'balita')) switchTab(saved);
    });

    // ==========================================
    // SISTEM NOTIFIKASI MODERN & HAPUS DATA
    // ==========================================

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
        timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    <?php if(session('success')): ?>
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: "<?php echo e(session('success')); ?>" });
    <?php endif; ?>
    <?php if(session('error')): ?>
        Toast.fire({ icon: 'error', title: 'Oops...', text: "<?php echo e(session('error')); ?>" });
    <?php endif; ?>

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus ' + name + '?',
            html: "Data rekam medis dan absensi anak ini akan <b>hilang permanen!</b>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // ==========================================
    // SISTEM CHECKBOX & HAPUS BANYAK (BULK DELETE)
    // ==========================================
    function toggleSelectAll(source, type) {
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
        checkboxes.forEach(cb => cb.checked = source.checked);
        checkBulkStatus();
    }

    function checkBulkStatus() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkDeleteForm');
        const bulkCountSpan = document.getElementById('bulkCount');
        
        if (checkedBoxes.length > 0) {
            bulkForm.classList.remove('hidden');
            bulkCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkForm.classList.add('hidden');
        }
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checkedBoxes.length + ' Data Terpilih?',
            html: "Anda akan menghapus data dalam jumlah banyak sekaligus. Ini tidak bisa dibatalkan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-skull-crossbones"></i> Eksekusi Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl border-4 border-rose-100' }
        }).then((result) => {
            if (result.isConfirmed) {
                const inputContainer = document.getElementById('bulkDeleteInputs');
                inputContainer.innerHTML = ''; 
                
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    inputContainer.appendChild(input);
                });
                
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/balita/index.blade.php ENDPATH**/ ?>