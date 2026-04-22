

<?php $__env->startSection('title', 'Validasi & Pemeriksaan Lanjutan'); ?>
<?php $__env->startSection('page-name', 'Workspace Bidan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Form Input Medis */
    .med-input { 
        width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px; 
        padding: 12px 16px; color: #0f172a; font-weight: 600; font-size: 13px; 
        transition: all 0.3s ease; outline: none;
    }
    .med-input:focus { background: #fff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { 
        display: block; font-size: 10px; font-weight: 900; color: #64748b; 
        text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; 
    }
    .input-unit { position: relative; }
    .input-unit input { padding-right: 48px; }
    .input-unit .unit { 
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%); 
        font-size: 11px; font-weight: 900; color: #94a3b8; pointer-events: none; 
    }
    
    /* Kartu data Kader (read-only) */
    .kader-card { 
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 16px; 
    }
    .kader-card .kd-label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
    .kader-card .kd-val { font-size: 22px; font-weight: 900; color: #0f172a; line-height: 1.1; margin-top: 2px; }
    .kader-card .kd-unit { font-size: 11px; font-weight: 700; color: #94a3b8; margin-left: 2px; }
    .kader-card.highlight { border-color: #e0f2fe; background: #f0f9ff; }
    .kader-card.warn { border-color: #fee2e2; background: #fff1f2; }
    
    /* Radio keputusan */
    .keputusan-radio:checked + div { 
        border-color: #06b6d4; background: #ecfeff; transform: translateY(-2px); 
        box-shadow: 0 8px 20px -6px rgba(6,182,212,0.2); 
    }
    .keputusan-radio:checked + div .icon-box { background: #06b6d4; color: white; }
    .keputusan-radio-tolak:checked + div { 
        border-color: #f43f5e; background: #fff1f2; transform: translateY(-2px); 
        box-shadow: 0 8px 20px -6px rgba(244,63,94,0.2); 
    }
    .keputusan-radio-tolak:checked + div .icon-box { background: #f43f5e; color: white; }
    
    /* Section header dalam form */
    .form-section-header {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 900; color: #1e293b;
        padding-bottom: 12px; border-bottom: 2px solid #f1f5f9; margin-bottom: 18px;
    }
    .form-section-header i { color: #06b6d4; }
    
    /* Badge kategori pasien */
    .badge-balita { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-remaja { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .badge-lansia { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-ibuhamil { background: #fce7f3; color: #9d174d; border: 1px solid #fbcfe8; }
    .badge-bayi { background: #cffafe; color: #155e75; border: 1px solid #a5f3fc; }
    
    /* Alert KEK */
    @keyframes pulse-border { 0%,100%{box-shadow:0 0 0 0 rgba(244,63,94,0)} 50%{box-shadow:0 0 0 6px rgba(244,63,94,0.15)} }
    .kek-alert { animation: pulse-border 2s infinite; }
    
    /* Blok pembagi antar seksi */
    .divider { height: 1px; background: linear-gradient(to right, transparent, #e2e8f0, transparent); margin: 24px 0; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    // ── Deteksi Kategori Pasien ─────────────────────────────────────────
    $namaPasien  = $pemeriksaan->balita->nama_lengkap 
                ?? $pemeriksaan->remaja->nama_lengkap 
                ?? $pemeriksaan->lansia->nama_lengkap 
                ?? $pemeriksaan->ibuHamil->nama_lengkap 
                ?? 'Pasien Anonim';
                
    $kategoriRaw = strtolower(class_basename($pemeriksaan->kategori_pasien ?? $pemeriksaan->pasien_type));
    
    // Normalisasi nama kategori
    $isBalita   = $kategoriRaw === 'balita';
    $isBayi     = $kategoriRaw === 'bayi';           // bayi = balita < 12 bulan (gunakan tabel balitas)
    $isRemaja   = $kategoriRaw === 'remaja';
    $isLansia   = $kategoriRaw === 'lansia';
    $isBumil    = in_array($kategoriRaw, ['ibu_hamil','ibuhamil','ibuhamil','bumil']);
    
    // Nama & warna tampilan per kategori
    if ($isBalita || $isBayi) { 
        $kategoriLabel = $isBayi ? 'Bayi' : 'Balita'; 
        $nCol = $isBayi ? 'cyan'   : 'sky'; 
        $nIco = $isBayi ? 'baby'   : 'child'; 
        $badgeClass = $isBayi ? 'badge-bayi' : 'badge-balita';
    } elseif ($isRemaja) { 
        $kategoriLabel = 'Remaja';   $nCol = 'violet'; $nIco = 'user-graduate'; $badgeClass = 'badge-remaja';
    } elseif ($isLansia) { 
        $kategoriLabel = 'Lansia';   $nCol = 'emerald'; $nIco = 'user-clock';   $badgeClass = 'badge-lansia';
    } elseif ($isBumil) { 
        $kategoriLabel = 'Ibu Hamil'; $nCol = 'pink';   $nIco = 'person-pregnant'; $badgeClass = 'badge-ibuhamil';
    } else { 
        $kategoriLabel = 'Pasien';   $nCol = 'slate';  $nIco = 'user';         $badgeClass = '';
    }
    
    // Status verifikasi
    $isVerified = $pemeriksaan->status_verifikasi === 'verified';
    
    // Peringatan KEK (Kekurangan Energi Kronik) otomatis dari data Kader
    // LiLA < 23.5 cm = risiko KEK (ini hanya INDIKATOR, bukan diagnosis — bidan yang memutuskan)
    $lilaKader = floatval($pemeriksaan->lingkar_lengan ?? $pemeriksaan->lila ?? 0);
    $isRisikoKEK = ($lilaKader > 0 && $lilaKader < 23.5) && ($isBumil || $isRemaja);
?>

<div class="max-w-5xl mx-auto space-y-6 animate-slide-up pb-12">

    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>" 
           class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-400 hover:text-cyan-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrian
        </a>
        <span class="text-[11px] font-bold text-slate-400">
            Kode: <?php echo e($pemeriksaan->kunjungan->kode_kunjungan ?? '#'.$pemeriksaan->id); ?>

        </span>
    </div>

    
    <div class="bg-white rounded-[28px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        
        <div class="p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 relative overflow-hidden">
            
            <div class="absolute -right-16 -bottom-16 w-56 h-56 rounded-full opacity-10 pointer-events-none"
                 style="background: radial-gradient(circle, var(--color-<?php echo e($nCol); ?>-400, #0ea5e9), transparent)"></div>

            
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-[70px] h-[70px] rounded-[18px] flex items-center justify-center text-3xl shrink-0 shadow-md
                            <?php echo e($nCol == 'sky' ? 'bg-sky-100 text-sky-500' : ''); ?>

                            <?php echo e($nCol == 'cyan' ? 'bg-cyan-100 text-cyan-500' : ''); ?>

                            <?php echo e($nCol == 'violet' ? 'bg-violet-100 text-violet-500' : ''); ?>

                            <?php echo e($nCol == 'emerald' ? 'bg-emerald-100 text-emerald-500' : ''); ?>

                            <?php echo e($nCol == 'pink' ? 'bg-pink-100 text-pink-500' : ''); ?>">
                    <i class="fas fa-<?php echo e($nIco); ?>"></i>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2 <?php echo e($badgeClass); ?>">
                        <?php echo e($kategoriLabel); ?>

                    </span>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none mb-1">
                        <?php echo e($namaPasien); ?>

                    </h2>
                    <div class="flex items-center gap-3 text-[11px] font-medium text-slate-500">
                        <span><i class="far fa-calendar-alt mr-1 text-slate-400"></i>
                            <?php echo e(\Carbon\Carbon::parse($pemeriksaan->tanggal_periksa ?? $pemeriksaan->created_at)->translatedFormat('d F Y')); ?>

                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span>Diukur Kader: <?php echo e(Str::words($pemeriksaan->pemeriksa->name ?? 'Sistem', 2, '')); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="relative z-10 shrink-0">
                <?php if($isVerified): ?>
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 px-4 py-3 rounded-2xl">
                        <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Status</p>
                            <p class="text-[13px] font-black text-slate-800">Tervalidasi</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 px-4 py-3 rounded-2xl">
                        <div class="w-9 h-9 rounded-full bg-amber-400 text-white flex items-center justify-center animate-pulse">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Status</p>
                            <p class="text-[13px] font-black text-slate-800">Perlu Validasi</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($isRisikoKEK): ?>
        <div class="mx-6 mb-4 flex items-center gap-3 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl kek-alert">
            <i class="fas fa-exclamation-triangle text-rose-500 text-lg"></i>
            <div>
                <p class="text-[11px] font-black text-rose-700">
                    Indikator Risiko KEK — LiLA: <?php echo e($lilaKader); ?> cm (di bawah ambang 23,5 cm)
                </p>
                <p class="text-[10px] font-medium text-rose-500 mt-0.5">
                    Ini adalah indikator otomatis dari data kader. Keputusan final ada pada bidan.
                </p>
            </div>
        </div>
        <?php endif; ?>

    </div>

    
    <div class="bg-white rounded-[28px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8">
        
        <div class="form-section-header">
            <i class="fas fa-clipboard-list"></i>
            Hasil Pengukuran Fisik Kader (Meja 2 — Baca Saja)
        </div>

        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-4">
            <div class="kader-card highlight">
                <p class="kd-label">Berat Badan</p>
                <p class="kd-val"><?php echo e($pemeriksaan->berat_badan ?? '-'); ?><span class="kd-unit">kg</span></p>
            </div>
            <div class="kader-card highlight">
                <p class="kd-label"><?php echo e(($isBalita||$isBayi) ? 'Panjang Badan' : 'Tinggi Badan'); ?></p>
                <p class="kd-val"><?php echo e($pemeriksaan->tinggi_badan ?? '-'); ?><span class="kd-unit">cm</span></p>
            </div>
            <div class="kader-card">
                <p class="kd-label">LiLA (Lingkar Lengan)</p>
                <p class="kd-val <?php echo e(($lilaKader > 0 && $lilaKader < 23.5) ? 'text-rose-500' : ''); ?>">
                    <?php echo e($pemeriksaan->lingkar_lengan ?? $pemeriksaan->lila ?? '-'); ?><span class="kd-unit">cm</span>
                </p>
            </div>
            <?php if($pemeriksaan->suhu_tubuh): ?>
            <div class="kader-card <?php echo e(floatval($pemeriksaan->suhu_tubuh) > 37.5 ? 'warn' : ''); ?>">
                <p class="kd-label">Suhu Tubuh</p>
                <p class="kd-val <?php echo e(floatval($pemeriksaan->suhu_tubuh) > 37.5 ? 'text-rose-500' : ''); ?>">
                    <?php echo e($pemeriksaan->suhu_tubuh); ?><span class="kd-unit">°C</span>
                    <?php if(floatval($pemeriksaan->suhu_tubuh) > 37.5): ?>
                        <span class="text-[9px] block text-rose-500 font-black">DEMAM</span>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($isBalita || $isBayi): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 bg-sky-50/50 rounded-2xl border border-sky-100">
            <p class="col-span-full text-[9px] font-black text-sky-500 uppercase tracking-widest mb-1">Data Khusus Balita</p>
            <div class="kader-card">
                <p class="kd-label text-sky-500">Lingkar Kepala</p>
                <p class="kd-val"><?php echo e($pemeriksaan->lingkar_kepala ?? '-'); ?><span class="kd-unit">cm</span></p>
            </div>
            <div class="kader-card">
                <p class="kd-label text-sky-500">Lingkar Perut</p>
                <p class="kd-val"><?php echo e($pemeriksaan->lingkar_perut ?? '-'); ?><span class="kd-unit">cm</span></p>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($isRemaja || $isLansia || $isBumil): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 p-4 
                    <?php echo e($isRemaja ? 'bg-violet-50/50 border-violet-100' : ''); ?>

                    <?php echo e($isLansia ? 'bg-emerald-50/50 border-emerald-100' : ''); ?>

                    <?php echo e($isBumil ? 'bg-pink-50/50 border-pink-100' : ''); ?>

                    rounded-2xl border">
            <p class="col-span-full text-[9px] font-black uppercase tracking-widest mb-1
                       <?php echo e($isRemaja ? 'text-violet-500' : ''); ?>

                       <?php echo e($isLansia ? 'text-emerald-600' : ''); ?>

                       <?php echo e($isBumil ? 'text-pink-500' : ''); ?>">
                Data Khusus <?php echo e($kategoriLabel); ?>

            </p>
            <div class="kader-card <?php echo e(floatval($pemeriksaan->tekanan_darah) > 0 && intval($pemeriksaan->tekanan_darah) >= 140 ? 'warn' : ''); ?>">
                <p class="kd-label">Tekanan Darah</p>
                <p class="kd-val text-sm"><?php echo e($pemeriksaan->tekanan_darah ?? '-'); ?><span class="kd-unit">mmHg</span></p>
                <?php if(intval($pemeriksaan->tekanan_darah) >= 140): ?>
                    <p class="text-[9px] font-black text-rose-500 mt-1">HIPERTENSI</p>
                <?php endif; ?>
            </div>
            <?php if($isLansia): ?>
            <div class="kader-card">
                <p class="kd-label">Lingkar Perut (LP)</p>
                <p class="kd-val"><?php echo e($pemeriksaan->lingkar_perut ?? '-'); ?><span class="kd-unit">cm</span></p>
            </div>
            <?php endif; ?>
            <?php if($isBumil): ?>
            <div class="kader-card">
                <p class="kd-label">TFU (Tinggi Fundus)</p>
                <p class="kd-val"><?php echo e($pemeriksaan->tfu ?? '-'); ?><span class="kd-unit">cm</span></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>

    
    <div class="bg-white rounded-[28px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        
        
        <div class="px-8 py-5 bg-gradient-to-r from-cyan-600 to-blue-600 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-cyan-200 uppercase tracking-widest mb-0.5">Meja 5 — Bidan</p>
                <h3 class="text-[16px] font-black text-white flex items-center gap-2">
                    <i class="fas fa-user-md"></i> Pemeriksaan Lanjutan & Diagnosa
                </h3>
            </div>
            <span class="px-3 py-1.5 bg-white/20 text-white text-[10px] font-black rounded-lg uppercase tracking-widest backdrop-blur-sm">
                <?php echo e($kategoriLabel); ?>

            </span>
        </div>
        
        <div class="p-6 md:p-8">
            <form id="formPemeriksaan" action="<?php echo e(route('bidan.pemeriksaan.update', $pemeriksaan->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-8">
                    <div class="form-section-header">
                        <i class="fas fa-gavel"></i>
                        A. Keputusan Validasi
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        
                        <label class="cursor-pointer">
                            
                            <input type="radio" name="status_verifikasi" value="verified" 
                                   class="sr-only keputusan-radio"
                                   <?php echo e($isVerified ? 'checked' : ''); ?>>
                            <div class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-[18px] transition-all bg-white hover:border-cyan-200">
                                <div class="icon-box w-11 h-11 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-lg transition-colors">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-black text-slate-800">Validasi & Selesai</p>
                                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Data akurat, simpan ke rekam medis warga.</p>
                                </div>
                            </div>
                        </label>

                        
                        <label class="cursor-pointer">
                            <input type="radio" name="status_verifikasi" value="ditolak"
                                   class="sr-only keputusan-radio-tolak"
                                   <?php echo e(($pemeriksaan->status_verifikasi === 'ditolak') ? 'checked' : ''); ?>>
                            <div class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-[18px] transition-all bg-white hover:border-rose-200">
                                <div class="icon-box w-11 h-11 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-lg transition-colors">
                                    <i class="fas fa-undo-alt"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-black text-slate-800">Kembalikan ke Kader</p>
                                    <p class="text-[10px] font-medium text-slate-500 mt-0.5">Data fisik tidak valid, kader perlu ukur ulang.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="divider"></div>

                
                
                <div class="mb-8">
                    <div class="form-section-header">
                        <i class="fas fa-thermometer-half"></i>
                        B. Pengukuran Tambahan oleh Bidan
                    </div>

                    
                    <?php if($isBalita || $isBayi): ?>
                    <div class="space-y-4 p-5 bg-sky-50/50 border border-sky-100 rounded-[20px]">
                        <p class="text-[10px] font-black text-sky-600 uppercase tracking-widest">Pengukuran Fisik Balita / Bayi</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="med-label">Suhu Tubuh</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="suhu_tubuh" 
                                           class="med-input" placeholder="36.5"
                                           value="<?php echo e($pemeriksaan->suhu_tubuh); ?>">
                                    <span class="unit">°C</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Lingkar Kepala</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="lingkar_kepala" 
                                           class="med-input" placeholder="0.0"
                                           value="<?php echo e($pemeriksaan->lingkar_kepala); ?>">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Lingkar Perut</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="lingkar_perut" 
                                           class="med-input" placeholder="0.0"
                                           value="<?php echo e($pemeriksaan->lingkar_perut); ?>">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isRemaja): ?>
                    <div class="space-y-4 p-5 bg-violet-50/50 border border-violet-100 rounded-[20px]">
                        <p class="text-[10px] font-black text-violet-600 uppercase tracking-widest">Pengukuran Tambahan Remaja</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="med-label">Suhu Tubuh</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="suhu_tubuh" 
                                           class="med-input" placeholder="36.5"
                                           value="<?php echo e($pemeriksaan->suhu_tubuh); ?>">
                                    <span class="unit">°C</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Hemoglobin (HB)
                                    <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal ml-1">jika tersedia alat</span>
                                </label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="hb" 
                                           class="med-input" placeholder="12.0"
                                           value="<?php echo e($pemeriksaan->hb); ?>">
                                    <span class="unit">g/dL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isLansia): ?>
                    <div class="space-y-4 p-5 bg-emerald-50/50 border border-emerald-100 rounded-[20px]">
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Pemeriksaan Biomedis Lansia</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="med-label">Suhu Tubuh</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="suhu_tubuh" 
                                           class="med-input" placeholder="36.5"
                                           value="<?php echo e($pemeriksaan->suhu_tubuh); ?>">
                                    <span class="unit">°C</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Gula Darah Sewaktu (GDS)
                                    <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal ml-1">jika tersedia</span>
                                </label>
                                <div class="input-unit">
                                    <input type="number" step="1" name="gula_darah" 
                                           class="med-input" placeholder="120"
                                           value="<?php echo e($pemeriksaan->gula_darah); ?>">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Kolesterol
                                    <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal ml-1">jika tersedia</span>
                                </label>
                                <div class="input-unit">
                                    <input type="number" step="1" name="kolesterol" 
                                           class="med-input" placeholder="200"
                                           value="<?php echo e($pemeriksaan->kolesterol); ?>">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Asam Urat
                                    <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal ml-1">jika tersedia</span>
                                </label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="asam_urat" 
                                           class="med-input" placeholder="5.5"
                                           value="<?php echo e($pemeriksaan->asam_urat); ?>">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isBumil): ?>
                    <div class="space-y-4 p-5 bg-pink-50/50 border border-pink-100 rounded-[20px]">
                        <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest">Pemeriksaan Kebidanan</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="med-label text-pink-600">Suhu Tubuh</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="suhu_tubuh" 
                                           class="med-input" placeholder="36.5"
                                           value="<?php echo e($pemeriksaan->suhu_tubuh); ?>">
                                    <span class="unit">°C</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label text-pink-600">Hemoglobin (HB)</label>
                                <div class="input-unit">
                                    <input type="number" step="0.1" name="hb" 
                                           class="med-input" placeholder="11.0"
                                           value="<?php echo e($pemeriksaan->hb); ?>">
                                    <span class="unit">g/dL</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label text-pink-600">Usia Kehamilan</label>
                                <div class="input-unit">
                                    <input type="number" step="1" name="usia_kehamilan" 
                                           class="med-input" placeholder="20"
                                           value="<?php echo e($pemeriksaan->usia_kehamilan); ?>">
                                    <span class="unit">mgg</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label text-pink-600">TFU (Tinggi Fundus Uteri)</label>
                                <div class="input-unit">
                                    <input type="text" name="tfu" 
                                           class="med-input" placeholder="Contoh: 28"
                                           value="<?php echo e($pemeriksaan->tfu); ?>">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label text-pink-600">Denyut Jantung Janin (DJJ)</label>
                                <div class="input-unit">
                                    <input type="text" name="djj" 
                                           class="med-input" placeholder="140"
                                           value="<?php echo e($pemeriksaan->djj); ?>">
                                    <span class="unit">bpm</span>
                                </div>
                            </div>
                            <div>
                                <label class="med-label text-pink-600">Presentasi / Posisi Janin</label>
                                <select name="posisi_janin" class="med-input cursor-pointer">
                                    <option value="">-- Pilih Presentasi --</option>
                                    <option value="Kepala" <?php echo e($pemeriksaan->posisi_janin == 'Kepala' ? 'selected' : ''); ?>>Kepala (Presentasi Normal)</option>
                                    <option value="Sungsang" <?php echo e($pemeriksaan->posisi_janin == 'Sungsang' ? 'selected' : ''); ?>>Sungsang (Bokong/Kaki)</option>
                                    <option value="Lintang" <?php echo e($pemeriksaan->posisi_janin == 'Lintang' ? 'selected' : ''); ?>>Lintang (Transversal)</option>
                                    <option value="Miring" <?php echo e($pemeriksaan->posisi_janin == 'Miring' ? 'selected' : ''); ?>>Miring (Oblique)</option>
                                    <option value="Belum Bisa Ditentukan" <?php echo e($pemeriksaan->posisi_janin == 'Belum Bisa Ditentukan' ? 'selected' : ''); ?>>Belum Bisa Ditentukan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="divider"></div>

                
                
                
                <div class="mb-8">
                    <div class="form-section-header">
                        <i class="fas fa-stethoscope"></i>
                        C. Penilaian Klinis (Diisi Bidan)
                    </div>

                    
                    <?php if($isBalita || $isBayi): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-5 bg-sky-50/50 border border-sky-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-sky-600 uppercase tracking-widest mb-1">Status Gizi Balita (Standar WHO/Kemenkes)</p>
                        
                        <div>
                            <label class="med-label">Status BB/U <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal">(Berat Badan/Umur)</span></label>
                            <select name="status_gizi_bb_u" class="med-input cursor-pointer border-sky-200">
                                <option value="">-- Pilih Status --</option>
                                <option value="BB Sangat Kurang" <?php echo e(($pemeriksaan->status_gizi_bb_u ?? $pemeriksaan->status_gizi) == 'BB Sangat Kurang' ? 'selected' : ''); ?>>
                                    BB Sangat Kurang (&lt; -3 SD)
                                </option>
                                <option value="BB Kurang" <?php echo e(($pemeriksaan->status_gizi_bb_u ?? $pemeriksaan->status_gizi) == 'BB Kurang' ? 'selected' : ''); ?>>
                                    BB Kurang (-3 s/d &lt;-2 SD)
                                </option>
                                <option value="BB Normal" <?php echo e(($pemeriksaan->status_gizi_bb_u ?? $pemeriksaan->status_gizi) == 'BB Normal' ? 'selected' : ''); ?>>
                                    BB Normal (-2 s/d +1 SD)
                                </option>
                                <option value="Risiko BB Lebih" <?php echo e(($pemeriksaan->status_gizi_bb_u ?? $pemeriksaan->status_gizi) == 'Risiko BB Lebih' ? 'selected' : ''); ?>>
                                    Risiko BB Lebih (&gt; +1 SD)
                                </option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="med-label">Status TB/U <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal">(Tinggi Badan/Umur)</span></label>
                            <select name="indikasi_stunting" class="med-input cursor-pointer border-rose-200 focus:border-rose-400 focus:ring-rose-50 bg-rose-50/30">
                                <option value="">-- Pilih Status --</option>
                                <option value="Sangat Pendek" <?php echo e($pemeriksaan->indikasi_stunting == 'Sangat Pendek' ? 'selected' : ''); ?>>
                                    Sangat Pendek / Severely Stunted (&lt; -3 SD)
                                </option>
                                <option value="Pendek" <?php echo e($pemeriksaan->indikasi_stunting == 'Pendek' ? 'selected' : ''); ?>>
                                    Pendek / Stunted (-3 s/d &lt;-2 SD)
                                </option>
                                <option value="Normal" <?php echo e(($pemeriksaan->indikasi_stunting == 'Normal' || $pemeriksaan->indikasi_stunting == 'Tidak Stunting') ? 'selected' : ''); ?>>
                                    Normal (-2 s/d +3 SD)
                                </option>
                                <option value="Tinggi" <?php echo e($pemeriksaan->indikasi_stunting == 'Tinggi' ? 'selected' : ''); ?>>
                                    Tinggi (&gt; +3 SD)
                                </option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="med-label">Status BB/TB <span class="text-[9px] font-medium text-slate-400 normal-case tracking-normal">(Gizi/Wasting)</span></label>
                            <select name="status_gizi" class="med-input cursor-pointer border-sky-200">
                                <option value="">-- Pilih Status --</option>
                                <option value="Gizi Buruk" <?php echo e($pemeriksaan->status_gizi == 'Gizi Buruk' ? 'selected' : ''); ?>>
                                    Gizi Buruk / Sangat Kurus (&lt; -3 SD)
                                </option>
                                <option value="Gizi Kurang" <?php echo e($pemeriksaan->status_gizi == 'Gizi Kurang' ? 'selected' : ''); ?>>
                                    Gizi Kurang / Kurus (-3 s/d &lt;-2 SD)
                                </option>
                                <option value="Gizi Baik" <?php echo e($pemeriksaan->status_gizi == 'Gizi Baik' ? 'selected' : ''); ?>>
                                    Gizi Baik / Normal (-2 s/d +1 SD)
                                </option>
                                <option value="Risiko Lebih" <?php echo e($pemeriksaan->status_gizi == 'Risiko Lebih' ? 'selected' : ''); ?>>
                                    Risiko Berat Lebih (+1 s/d +2 SD)
                                </option>
                                <option value="Gizi Lebih" <?php echo e($pemeriksaan->status_gizi == 'Gizi Lebih' ? 'selected' : ''); ?>>
                                    Gizi Lebih / Obesitas (&gt; +2 SD)
                                </option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isRemaja): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-5 bg-violet-50/50 border border-violet-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-violet-600 uppercase tracking-widest mb-1">Status Gizi & Anemia Remaja</p>
                        <div>
                            <label class="med-label">Status IMT Remaja</label>
                            <select name="status_imt" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status IMT --</option>
                                <option value="Sangat Kurus" <?php echo e(($pemeriksaan->status_imt ?? $pemeriksaan->status_gizi) == 'Sangat Kurus' ? 'selected' : ''); ?>>Sangat Kurus</option>
                                <option value="Kurus" <?php echo e(($pemeriksaan->status_imt ?? $pemeriksaan->status_gizi) == 'Kurus' ? 'selected' : ''); ?>>Kurus</option>
                                <option value="Normal" <?php echo e(($pemeriksaan->status_imt ?? $pemeriksaan->status_gizi) == 'Normal' ? 'selected' : ''); ?>>Normal</option>
                                <option value="Gemuk" <?php echo e(($pemeriksaan->status_imt ?? $pemeriksaan->status_gizi) == 'Gemuk' ? 'selected' : ''); ?>>Gemuk</option>
                                <option value="Obesitas" <?php echo e(($pemeriksaan->status_imt ?? $pemeriksaan->status_gizi) == 'Obesitas' ? 'selected' : ''); ?>>Obesitas</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label">Status Anemia (dari HB)</label>
                            <select name="status_anemia" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak Anemia" <?php echo e($pemeriksaan->status_anemia == 'Tidak Anemia' ? 'selected' : ''); ?>>Tidak Anemia (HB ≥ 12 g/dL)</option>
                                <option value="Anemia Ringan" <?php echo e($pemeriksaan->status_anemia == 'Anemia Ringan' ? 'selected' : ''); ?>>Anemia Ringan (HB 10–11.9)</option>
                                <option value="Anemia Sedang" <?php echo e($pemeriksaan->status_anemia == 'Anemia Sedang' ? 'selected' : ''); ?>>Anemia Sedang (HB 8–9.9)</option>
                                <option value="Anemia Berat" <?php echo e($pemeriksaan->status_anemia == 'Anemia Berat' ? 'selected' : ''); ?>>Anemia Berat (HB &lt; 8)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label">Status KEK (LiLA) <span class="text-[9px] text-slate-400 normal-case font-normal ml-1">Remaja Perempuan</span></label>
                            <select name="status_kek" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak KEK" <?php echo e(($pemeriksaan->status_kek ?? 'Tidak KEK') == 'Tidak KEK' ? 'selected' : ''); ?>>Tidak KEK (LiLA ≥ 23.5 cm)</option>
                                <option value="KEK" <?php echo e($pemeriksaan->status_kek == 'KEK' ? 'selected' : ''); ?>>KEK (LiLA &lt; 23.5 cm)</option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isLansia): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-5 bg-emerald-50/50 border border-emerald-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Penilaian Klinis Lansia</p>
                        <div>
                            <label class="med-label">Status Gizi / IMT Lansia</label>
                            <select name="status_gizi" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Kurus" <?php echo e($pemeriksaan->status_gizi == 'Kurus' ? 'selected' : ''); ?>>Kurus (IMT &lt; 18.5)</option>
                                <option value="Normal" <?php echo e($pemeriksaan->status_gizi == 'Normal' ? 'selected' : ''); ?>>Normal (IMT 18.5–24.9)</option>
                                <option value="Gemuk" <?php echo e($pemeriksaan->status_gizi == 'Gemuk' ? 'selected' : ''); ?>>Gemuk (IMT 25–26.9)</option>
                                <option value="Obesitas" <?php echo e($pemeriksaan->status_gizi == 'Obesitas' ? 'selected' : ''); ?>>Obesitas (IMT ≥ 27)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label">Skala Kemandirian (Barthel/ABC)</label>
                            <select name="tingkat_kemandirian" class="med-input cursor-pointer">
                                <option value="">-- Pilih Skala --</option>
                                <option value="A" <?php echo e($pemeriksaan->tingkat_kemandirian == 'A' ? 'selected' : ''); ?>>
                                    A — Mandiri Sepenuhnya
                                </option>
                                <option value="B" <?php echo e($pemeriksaan->tingkat_kemandirian == 'B' ? 'selected' : ''); ?>>
                                    B — Bantuan Sebagian
                                </option>
                                <option value="C" <?php echo e($pemeriksaan->tingkat_kemandirian == 'C' ? 'selected' : ''); ?>>
                                    C — Ketergantungan Total
                                </option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($isBumil): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-5 bg-pink-50/50 border border-pink-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-pink-600 uppercase tracking-widest mb-1">Penilaian Risiko Kehamilan</p>
                        <div>
                            <label class="med-label text-pink-600">Status Anemia (dari HB)</label>
                            <select name="status_anemia" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak Anemia" <?php echo e($pemeriksaan->status_anemia == 'Tidak Anemia' ? 'selected' : ''); ?>>Tidak Anemia (HB ≥ 11 g/dL)</option>
                                <option value="Anemia Ringan" <?php echo e($pemeriksaan->status_anemia == 'Anemia Ringan' ? 'selected' : ''); ?>>Anemia Ringan (HB 8–10.9)</option>
                                <option value="Anemia Sedang" <?php echo e($pemeriksaan->status_anemia == 'Anemia Sedang' ? 'selected' : ''); ?>>Anemia Sedang (HB 6–7.9)</option>
                                <option value="Anemia Berat" <?php echo e($pemeriksaan->status_anemia == 'Anemia Berat' ? 'selected' : ''); ?>>Anemia Berat (HB &lt; 6)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label text-pink-600">Status KEK (dari LiLA)</label>
                            <select name="status_kek" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak KEK" <?php echo e(($pemeriksaan->status_kek ?? 'Tidak KEK') == 'Tidak KEK' ? 'selected' : ''); ?>>Tidak KEK (LiLA ≥ 23.5 cm)</option>
                                <option value="KEK" <?php echo e($pemeriksaan->status_kek == 'KEK' ? 'selected' : ''); ?>>KEK (LiLA &lt; 23.5 cm)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label text-pink-600">Kategori Risiko Kehamilan</label>
                            <select name="status_risiko" class="med-input cursor-pointer">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Risiko Rendah" <?php echo e($pemeriksaan->status_risiko == 'Risiko Rendah' ? 'selected' : ''); ?>>Risiko Rendah</option>
                                <option value="Risiko Sedang" <?php echo e($pemeriksaan->status_risiko == 'Risiko Sedang' ? 'selected' : ''); ?>>Risiko Sedang</option>
                                <option value="Risiko Tinggi" <?php echo e($pemeriksaan->status_risiko == 'Risiko Tinggi' ? 'selected' : ''); ?>>Risiko Tinggi (Rujuk!)</option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="divider"></div>

                
                <div class="space-y-5 mb-8">
                    <div class="form-section-header">
                        <i class="fas fa-file-medical"></i>
                        D. Kesimpulan Diagnosa & Tindakan
                    </div>

                    <div>
                        <label class="med-label">
                            Kesimpulan / Diagnosa <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="diagnosa" rows="3" required
                                  class="med-input resize-none"
                                  placeholder="Tuliskan kesimpulan klinis dari seluruh hasil pemeriksaan. Contoh: Tumbuh kembang anak sesuai umur, tidak ada indikasi gizi buruk."><?php echo e($pemeriksaan->diagnosa); ?></textarea>
                    </div>

                    <div>
                        <label class="med-label">
                            Tindakan / Pelayanan yang Diberikan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="tindakan" rows="2"
                                  class="med-input resize-none"
                                  placeholder="Contoh: Pemberian Vitamin A, edukasi gizi, rujuk ke Puskesmas, imunisasi lanjutan..."><?php echo e($pemeriksaan->tindakan); ?></textarea>
                    </div>

                    <div class="bg-cyan-50 border border-cyan-100 rounded-[18px] p-5">
                        <label class="med-label text-cyan-700">
                            <i class="fas fa-comment-medical mr-1"></i>
                            Catatan untuk Warga / Orang Tua
                        </label>
                        <p class="text-[10px] font-medium text-cyan-600 mb-2">
                            Pesan ini akan ditampilkan di aplikasi warga sebagai catatan asuhan dari bidan.
                        </p>
                        <textarea name="catatan_bidan" rows="3"
                                  class="med-input border-cyan-200 focus:border-cyan-400 focus:ring-cyan-50 bg-white"
                                  placeholder="Contoh: Ibu, tolong perbanyak konsumsi protein hewani dan sayuran hijau ya. Jadwal kontrol berikutnya bulan depan..."><?php echo e($pemeriksaan->catatan_bidan); ?></textarea>
                    </div>
                </div>

                
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <?php if($isVerified): ?>
                    <button type="button" onclick="confirmReset()" 
                            class="px-6 py-3 bg-white border-2 border-rose-100 text-rose-500 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-rose-50 transition-colors w-full sm:w-auto">
                        <i class="fas fa-lock-open mr-1"></i> Buka Kunci Validasi
                    </button>
                    <?php else: ?>
                        <div class="text-[11px] font-medium text-slate-400 flex items-center gap-2">
                            <i class="fas fa-info-circle text-cyan-400"></i>
                            Semua field bertanda <span class="text-rose-500 font-black">*</span> wajib diisi.
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" id="btnSubmit"
                            class="px-10 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[12px] uppercase tracking-widest rounded-xl hover:shadow-[0_10px_25px_rgba(6,182,212,0.3)] transition-all hover:-translate-y-0.5 w-full sm:w-auto">
                        <i class="fas fa-save mr-2"></i> Simpan Hasil Pemeriksaan
                    </button>
                </div>

            </form>
        </div>

        
        <?php if($isVerified): ?>
        <form id="formReset" action="<?php echo e(route('bidan.pemeriksaan.update', $pemeriksaan->id)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="hidden" name="status_verifikasi" value="pending">
        </form>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
    e.preventDefault();

    const statusEl = document.querySelector('input[name="status_verifikasi"]:checked');
    if (!statusEl) {
        Swal.fire({ icon: 'warning', title: 'Belum Dipilih', text: 'Pilih keputusan validasi terlebih dahulu (Validasi atau Kembalikan ke Kader).', confirmButtonColor: '#06b6d4', customClass: { popup: 'rounded-[24px]' } });
        return;
    }

    const status = statusEl.value;
    const isValidasi = status === 'verified';
    
    Swal.fire({
        title: isValidasi ? 'Konfirmasi Validasi' : 'Kembalikan ke Kader?',
        text: isValidasi 
            ? 'Data akan disimpan ke rekam medis dan dapat dilihat warga.' 
            : 'Data fisik akan dikembalikan ke kader untuk diukur ulang.',
        icon: isValidasi ? 'success' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isValidasi ? '#0891b2' : '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: isValidasi ? 'Ya, Validasi' : 'Ya, Kembalikan',
        cancelButtonText: 'Cek Lagi',
        reverseButtons: true,
        customClass: { popup: 'rounded-[24px]' }
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            this.submit();
        }
    });
});

function confirmReset() {
    Swal.fire({
        title: 'Buka Kunci Validasi?',
        text: "Status akan dikembalikan ke 'Pending'. Warga tidak dapat melihat hasil diagnosa sampai divalidasi ulang.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Buka Kunci',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: { popup: 'rounded-[24px]' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formReset').submit();
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pemeriksaan/show.blade.php ENDPATH**/ ?>