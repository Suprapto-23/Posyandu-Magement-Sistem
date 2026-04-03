

<?php $__env->startSection('title', 'Absensi Posyandu'); ?>
<?php $__env->startSection('page-name', 'Absensi Warga'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

    /* Tab Kategori */
    .kat-tab { cursor:pointer; padding:0.6rem 1.25rem; border-radius:9999px; font-size:0.75rem; font-weight:900;
               letter-spacing:0.04em; text-transform:uppercase; border:2px solid #e2e8f0; background:white;
               color:#64748b; transition:all 0.25s; display:inline-flex; align-items:center; gap:0.4rem; }
    .kat-tab:hover { border-color:#cbd5e1; color:#334155; }
    .kat-tab.active-bayi   { background:#0ea5e9; color:white; border-color:#0ea5e9; box-shadow:0 4px 14px -3px rgba(14,165,233,0.5); }
    .kat-tab.active-balita { background:#8b5cf6; color:white; border-color:#8b5cf6; box-shadow:0 4px 14px -3px rgba(139,92,246,0.5); }
    .kat-tab.active-remaja { background:#f59e0b; color:white; border-color:#f59e0b; box-shadow:0 4px 14px -3px rgba(245,158,11,0.5); }
    .kat-tab.active-lansia { background:#10b981; color:white; border-color:#10b981; box-shadow:0 4px 14px -3px rgba(16,185,129,0.5); }

    /* Checkbox Hadir */
    .hadir-check { appearance:none; width:1.4rem; height:1.4rem; border:2px solid #cbd5e1; border-radius:6px;
                   cursor:pointer; position:relative; transition:all 0.2s; flex-shrink:0; }
    .hadir-check:checked { background:#10b981; border-color:#10b981; }
    .hadir-check:checked::after { content:'✓'; position:absolute; inset:0; display:flex; align-items:center;
                                   justify-content:center; color:white; font-size:0.8rem; font-weight:900; }

    /* Pasien row */
    .pasien-row { transition:all 0.2s; }
    .pasien-row.hadir-row { background:#f0fdf4; border-color:#bbf7d0 !important; }
    .pasien-row.absen-row { opacity:0.6; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto animate-slide-up">

    
    <?php if(session('success')): ?>
    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 font-bold text-sm">
        <i class="fas fa-check-circle text-emerald-500 text-lg"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 font-bold text-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i> <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Absensi Posyandu</h1>
            <p class="text-sm text-slate-500 mt-0.5">Catat kehadiran warga pada setiap pertemuan posyandu.</p>
        </div>
        <a href="<?php echo e(route('kader.absensi.riwayat')); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-50 shadow-sm transition-all">
            <i class="fas fa-history"></i> Riwayat Absensi
        </a>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <?php
            $katConfig = [
                'bayi'   => ['label'=>'Bayi',   'icon'=>'fa-baby',          'color'=>'sky'],
                'balita' => ['label'=>'Balita',  'icon'=>'fa-child',         'color'=>'violet'],
                'remaja' => ['label'=>'Remaja',  'icon'=>'fa-user-graduate', 'color'=>'amber'],
                'lansia' => ['label'=>'Lansia',  'icon'=>'fa-user-clock',    'color'=>'emerald'],
            ];
        ?>
        <?php $__currentLoopData = $katConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('kader.absensi.index')); ?>?kategori=<?php echo e($kat); ?>"
           class="bg-white border-2 rounded-2xl p-4 text-center transition-all hover:-translate-y-0.5 hover:shadow-md
                  <?php echo e($kategori === $kat ? 'border-'.$cfg['color'].'-400 shadow-sm' : 'border-slate-200'); ?>">
            <div class="w-9 h-9 rounded-xl bg-<?php echo e($cfg['color']); ?>-50 text-<?php echo e($cfg['color']); ?>-600 flex items-center justify-center mx-auto mb-2 text-base">
                <i class="fas <?php echo e($cfg['icon']); ?>"></i>
            </div>
            <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider"><?php echo e($cfg['label']); ?></p>
            <p class="text-lg font-black text-slate-800 mt-0.5">
                <?php echo e($statsPerKategori[$kat]['total_pertemuan']); ?>

                <span class="text-xs font-medium text-slate-400">pertemuan</span>
            </p>
            <p class="text-[11px] text-slate-400 font-medium"><?php echo e($statsPerKategori[$kat]['total_pasien']); ?> warga</p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="flex flex-wrap gap-2 mb-5">
        <?php $__currentLoopData = $katConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('kader.absensi.index')); ?>?kategori=<?php echo e($kat); ?>"
           class="kat-tab <?php echo e($kategori === $kat ? 'active-'.$kat : ''); ?>">
            <i class="fas <?php echo e($cfg['icon']); ?>"></i> <?php echo e($cfg['label']); ?>

        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($sesiHariIni): ?>
    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
        <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
        <div>
            <p class="font-extrabold text-amber-800 text-sm">Absensi <?php echo e($katConfig[$kategori]['label']); ?> hari ini sudah dicatat</p>
            <p class="text-xs text-amber-700 mt-0.5">
                Pertemuan ke-<?php echo e($sesiHariIni->nomor_pertemuan); ?> pada <?php echo e($sesiHariIni->tanggal_posyandu->translatedFormat('d F Y')); ?>.
                <a href="<?php echo e(route('kader.absensi.show', $sesiHariIni->id)); ?>" class="font-black underline ml-1">Lihat detail →</a>
            </p>
        </div>
    </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('kader.absensi.store')); ?>" method="POST" id="formAbsensi">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="kategori" value="<?php echo e($kategori); ?>">

        
        <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_posyandu" value="<?php echo e(date('Y-m-d')); ?>" required
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-400 transition-colors bg-white">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Nomor Pertemuan</label>
                    <div class="flex items-center gap-3 px-4 py-2.5 bg-emerald-50 border-2 border-emerald-200 rounded-xl">
                        <i class="fas fa-hashtag text-emerald-500"></i>
                        <span class="text-lg font-black text-emerald-700">Pertemuan ke-<?php echo e($pertemuanBerikutnya); ?></span>
                        <span class="text-xs text-emerald-500 font-medium">(otomatis)</span>
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Catatan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="Mis: Posyandu rutin bulanan..."
                           class="w-full border-2 border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-400 transition-colors bg-white">
                </div>
            </div>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-5">

            
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <div>
                    <h3 class="font-extrabold text-slate-800 text-sm">
                        Daftar Warga — <?php echo e($katConfig[$kategori]['label']); ?>

                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Centang yang hadir. Default: semua tidak hadir.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="centangSemua(true)"
                            class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black rounded-lg hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-check-double mr-1"></i> Semua Hadir
                    </button>
                    <button type="button" onclick="centangSemua(false)"
                            class="px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 text-[11px] font-black rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fas fa-times mr-1"></i> Reset
                    </button>
                </div>
            </div>

            
            <div class="px-5 py-2.5 bg-white border-b border-slate-100 flex items-center gap-4 text-xs font-bold">
                <span class="text-slate-500">Total: <span class="font-black text-slate-800"><?php echo e($pasiens->count()); ?></span> warga</span>
                <span class="text-emerald-600">Hadir: <span id="counter-hadir" class="font-black">0</span></span>
                <span class="text-rose-500">Absen: <span id="counter-absen" class="font-black"><?php echo e($pasiens->count()); ?></span></span>
            </div>

            <?php if($pasiens->isEmpty()): ?>
                <div class="text-center py-16">
                    <i class="fas fa-users-slash text-3xl text-slate-200 mb-3 block"></i>
                    <p class="font-black text-slate-600">Belum ada data <?php echo e($katConfig[$kategori]['label']); ?></p>
                    <p class="text-xs text-slate-400 mt-1">Tambah data pasien terlebih dahulu di menu Database.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-100 max-h-[500px] overflow-y-auto">
                    <?php $__currentLoopData = $pasiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pasien-row flex items-center gap-4 px-5 py-3.5 border border-transparent absen-row" id="row-<?php echo e($p->id); ?>">
                        <input type="checkbox" name="hadir[]" value="<?php echo e($p->id); ?>"
                               class="hadir-check" id="chk-<?php echo e($p->id); ?>"
                               onchange="updateRow(<?php echo e($p->id); ?>, this.checked)">

                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm shrink-0
                            <?php echo e($kategori === 'bayi' ? 'bg-sky-50 text-sky-600 border border-sky-100' :
                               ($kategori === 'balita' ? 'bg-violet-50 text-violet-600 border border-violet-100' :
                               ($kategori === 'remaja' ? 'bg-amber-50 text-amber-600 border border-amber-100' :
                               'bg-emerald-50 text-emerald-600 border border-emerald-100'))); ?>">
                            <?php echo e(strtoupper(substr($p->nama_lengkap, 0, 1))); ?>

                        </div>

                        <div class="flex-1 min-w-0">
                            <label for="chk-<?php echo e($p->id); ?>" class="font-extrabold text-slate-800 text-sm cursor-pointer block truncate">
                                <?php echo e($p->nama_lengkap); ?>

                            </label>
                            <p class="text-[11px] text-slate-400 font-medium">
                                <?php if($kategori === 'bayi' || $kategori === 'balita'): ?>
                                    Ibu: <?php echo e($p->nama_ibu ?? '-'); ?>

                                <?php elseif($kategori === 'remaja'): ?>
                                    <?php echo e($p->sekolah ?? 'NIK: '.$p->nik); ?>

                                <?php else: ?>
                                    NIK: <?php echo e($p->nik); ?>

                                <?php endif; ?>
                            </p>
                        </div>

                        
                        <div class="text-right shrink-0 hidden sm:block">
                            <?php
                                $diff = \Carbon\Carbon::parse($p->tanggal_lahir)->diff(now());
                                if ($kategori === 'bayi') {
                                    $usia = $diff->m . ' bln ' . $diff->d . ' hr';
                                } elseif ($kategori === 'balita') {
                                    $usia = $diff->y . ' thn ' . $diff->m . ' bln';
                                } else {
                                    $usia = $diff->y . ' tahun';
                                }
                            ?>
                            <span class="text-[11px] font-bold text-slate-500"><?php echo e($usia); ?></span>
                        </div>

                        
                        <div class="shrink-0 w-36 hidden sm:block" id="ket-wrap-<?php echo e($p->id); ?>">
                            <input type="text" name="keterangan[<?php echo e($p->id); ?>]"
                                   placeholder="Keterangan absen..."
                                   class="w-full text-[11px] font-medium border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-600 focus:outline-none focus:border-slate-400 bg-slate-50"
                                   id="ket-<?php echo e($p->id); ?>">
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if(!$pasiens->isEmpty()): ?>
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs text-slate-400 font-medium text-center sm:text-left">
                <i class="fas fa-info-circle mr-1"></i>
                Absensi akan tersimpan sebagai <strong class="text-slate-700">Pertemuan ke-<?php echo e($pertemuanBerikutnya); ?></strong> untuk kategori <?php echo e($katConfig[$kategori]['label']); ?>.
            </p>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="<?php echo e(route('kader.absensi.riwayat')); ?>"
                   class="flex-1 sm:flex-none px-6 py-3 bg-slate-100 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-200 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                        class="flex-1 sm:flex-none px-8 py-3 bg-emerald-500 text-white font-black text-sm rounded-xl hover:bg-emerald-600 shadow-[0_4px_14px_rgba(16,185,129,0.35)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
        </div>
        <?php endif; ?>

    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const total = <?php echo e($pasiens->count()); ?>;

    function hitungCounter() {
        const hadir = document.querySelectorAll('.hadir-check:checked').length;
        document.getElementById('counter-hadir').textContent = hadir;
        document.getElementById('counter-absen').textContent = total - hadir;
    }

    function updateRow(id, isHadir) {
        const row = document.getElementById('row-' + id);
        const ket  = document.getElementById('ket-' + id);
        row.classList.toggle('hadir-row', isHadir);
        row.classList.toggle('absen-row', !isHadir);
        if (ket) {
            ket.placeholder = isHadir ? 'Catatan kehadiran...' : 'Keterangan absen...';
        }
        hitungCounter();
    }

    function centangSemua(val) {
        document.querySelectorAll('.hadir-check').forEach(chk => {
            chk.checked = val;
            const id = chk.value;
            updateRow(id, val);
        });
    }

    // Submit loading state
    document.getElementById('formAbsensi').addEventListener('submit', function() {
        const btn = document.getElementById('btnSimpan');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/absensi/index.blade.php ENDPATH**/ ?>