

<?php $__env->startSection('title', 'Presensi Kehadiran Warga'); ?>
<?php $__env->startSection('page-name', 'Buku Kehadiran (Meja 1)'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK HALUS */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* INPUT PENCARIAN NEXUS */
    .crm-search {
        width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b;
        font-size: 0.85rem; font-weight: 600; border-radius: 9999px; padding: 0.7rem 1.5rem 0.7rem 2.75rem;
        outline: none; transition: all 0.3s ease;
    }
    .crm-search:focus { background-color: #ffffff; border-color: #6366f1; box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.15); }

    /* KARTU WARGA */
    .warga-card { 
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .warga-card:hover { border-color: #e0e7ff; box-shadow: 0 8px 25px -8px rgba(79, 70, 229, 0.12); transform: translateY(-2px); z-index: 10; position: relative; }
    
    /* RADIO BUTTON (Gaya Toggle Modern) */
    .radio-hidden { display: none; }
    .status-btn { 
        transition: all 0.2s ease; cursor: pointer; 
        background-color: transparent; color: #94a3b8;
    }
    
    /* State: Hadir (Hijau Zamrud Elegan) */
    .radio-hadir:checked + .status-btn { 
        background-color: #10b981; color: white;
        box-shadow: 0 4px 12px -3px rgba(16, 185, 129, 0.4); transform: translateY(-1px);
    }
    /* State: Absen (Merah Rose Elegan) */
    .radio-absen:checked + .status-btn { 
        background-color: #f43f5e; color: white;
        box-shadow: 0 4px 12px -3px rgba(244, 63, 94, 0.4); transform: translateY(-1px);
    }

    /* KOTAK KETERANGAN */
    .ket-box { max-height: 0; opacity: 0; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
    .ket-box.open { max-height: 80px; opacity: 1; margin-top: 0.5rem; overflow: visible;}

    /* CUSTOM SCROLLBAR HALUS */
    .custom-scrollbar::-webkit-scrollbar { height: 5px; width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.5); }

    /* ==========================================================
       SWEETALERT 2 - NEXUS MASTER UI
       ========================================================== */
    /* 1. Backdrop Gelap & Blur (Hanya untuk Popup Tengah) */
    div:where(.swal2-container).swal2-backdrop-show { 
        z-index: 10000 !important; backdrop-filter: blur(6px) !important; background: rgba(15, 23, 42, 0.5) !important; 
    }
    /* 2. Amankan Toast (Notifikasi Pojok Kanan Atas) agar tidak hitam */
    div:where(.swal2-container).swal2-top-end { 
        background: transparent !important; backdrop-filter: none !important; 
    }
    
    /* 3. Panel Utama Popup */
    .swal2-popup:not(.swal2-toast) { 
        border-radius: 32px !important; padding: 2.5rem 2rem 2rem !important; 
        background: #ffffff !important; border: none !important; width: 28em !important; 
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; 
    }
    
    /* 4. Tipografi Popup */
    .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; padding-top: 0 !important; }
    .swal2-html-container { font-family: 'Inter', sans-serif !important; color: #64748b !important; font-size: 0.875rem !important; line-height: 1.6 !important; margin: 1em 1.6em 0.3em !important; }
    
    /* 5. Tata Letak Tombol */
    .swal2-actions { gap: 12px !important; margin-top: 2rem !important; width: 100% !important; justify-content: center !important; }
    
    /* 6. Desain Tombol Nexus */
    .swal-btn-confirm-emerald { 
        background: #10b981 !important; color: #ffffff !important; border-radius: 100px !important; 
        padding: 14px 28px !important; font-size: 11px !important; font-weight: 900 !important; 
        text-transform: uppercase !important; letter-spacing: 0.05em !important; 
        box-shadow: 0 8px 20px -5px rgba(16,185,129,0.4) !important; border: none !important; margin: 0 !important; transition: all 0.2s ease !important;
    }
    .swal-btn-confirm-emerald:hover { background: #059669 !important; transform: translateY(-2px) !important; }

    .swal-btn-confirm-indigo { 
        background: #4f46e5 !important; color: #ffffff !important; border-radius: 100px !important; 
        padding: 14px 28px !important; font-size: 11px !important; font-weight: 900 !important; 
        text-transform: uppercase !important; letter-spacing: 0.05em !important; 
        box-shadow: 0 8px 20px -5px rgba(79,70,229,0.4) !important; border: none !important; margin: 0 !important; transition: all 0.2s ease !important;
    }
    .swal-btn-confirm-indigo:hover { background: #4338ca !important; transform: translateY(-2px) !important; }

    .swal-btn-cancel { 
        background: #f1f5f9 !important; color: #64748b !important; border-radius: 100px !important; 
        padding: 14px 28px !important; font-size: 11px !important; font-weight: 900 !important; 
        text-transform: uppercase !important; letter-spacing: 0.05em !important; 
        border: none !important; margin: 0 !important; transition: all 0.2s ease !important;
    }
    .swal-btn-cancel:hover { background: #e2e8f0 !important; color: #334155 !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/95 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="w-14 h-14 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
    <p class="text-indigo-900 font-bold tracking-widest text-[10px] uppercase">MEMUAT DAFTAR PANGGILAN...</p>
</div>

<div class="max-w-[1100px] mx-auto animate-slide-up relative z-10 pb-16 mt-2">

    
    <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[300px] h-[300px] bg-violet-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    
    <div class="bg-white/80 backdrop-blur-xl rounded-[28px] border border-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] p-6 md:p-8 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50 rounded-bl-full blur-2xl pointer-events-none z-0"></div>
        
        <div class="flex items-center gap-5 relative z-10 w-full md:w-auto">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200 shrink-0 transform -rotate-3">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest">Sesi Posyandu Hari Ini</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight font-poppins mb-0.5">Registrasi Kehadiran</h1>
                <p class="text-slate-500 font-medium text-[12px]"><i class="far fa-calendar-alt mr-1"></i> <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></p>
            </div>
        </div>

        <div class="relative z-10 shrink-0 bg-white p-3.5 rounded-2xl border border-slate-100 flex items-center gap-4 w-full md:w-auto justify-between md:justify-center shadow-sm">
            <div class="text-left md:text-right">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pertemuan Ke</p>
                <p class="text-xl font-bold text-indigo-600 font-poppins leading-none">#<?php echo e($pertemuanBerikutnya ?? 1); ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg"><i class="fas fa-hashtag"></i></div>
        </div>
    </div>

    
    <div class="flex flex-wrap gap-3 mb-6 overflow-x-auto pb-2 custom-scrollbar">
        <?php
            $tabs = [
                'bayi'      => ['label' => 'Bayi', 'icon' => 'fa-baby-carriage', 'color' => 'sky'],
                'balita'    => ['label' => 'Balita', 'icon' => 'fa-child', 'color' => 'indigo'],
                'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female', 'color' => 'pink'],
                'remaja'    => ['label' => 'Remaja', 'icon' => 'fa-user-graduate', 'color' => 'blue'],
                'lansia'    => ['label' => 'Lansia', 'icon' => 'fa-wheelchair', 'color' => 'emerald'],
            ];
        ?>

        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isActive = $kategori === $key; ?>
            <a href="<?php echo e(route('kader.absensi.index', ['kategori' => $key])); ?>" onclick="window.showLoader()"
               class="relative flex items-center justify-center gap-2 px-6 py-3 rounded-full text-[12px] font-bold tracking-wide uppercase transition-all shrink-0
               <?php echo e($isActive ? "bg-white border-2 border-{$tab['color']}-400 text-{$tab['color']}-600 shadow-sm" : "bg-white/60 border-2 border-transparent text-slate-400 hover:bg-white hover:text-slate-600 hover:border-slate-200"); ?>">
                <i class="fas <?php echo e($tab['icon']); ?> <?php echo e($isActive ? "text-{$tab['color']}-500" : "opacity-50"); ?> text-[14px]"></i> 
                <?php echo e($tab['label']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <form action="<?php echo e(route('kader.absensi.store')); ?>" method="POST" id="formAbsensi">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="kategori" value="<?php echo e($kategori); ?>">

        <div class="bg-white/80 backdrop-blur-xl rounded-[28px] border border-slate-200 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.05)] p-5 md:p-8 mb-8">
            
            
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl text-indigo-500 flex items-center justify-center text-lg shrink-0"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-[13px] uppercase tracking-widest">Daftar Panggilan</h3>
                        <p class="text-[11px] font-medium text-slate-500">Menampilkan <?php echo e(count($pasiens)); ?> Sasaran</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <?php if(count($pasiens) > 0): ?>
                        <button type="button" id="btnHadirSemua" class="w-full sm:w-auto px-5 py-2.5 bg-white text-emerald-600 border border-emerald-200 hover:bg-emerald-50 font-bold text-[11px] uppercase tracking-widest rounded-full transition-all shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-check-double text-[12px]"></i> Tandai Hadir Semua
                        </button>
                    <?php endif; ?>

                    <div class="relative w-full sm:w-[280px] group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[12px] group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama atau NIK..." class="crm-search">
                    </div>
                </div>
            </div>

            
            <div class="space-y-3" id="wargaList">
                <?php $__empty_1 = true; $__currentLoopData = $pasiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $statusHadir = $absensiData[$p->id]['hadir'] ?? null;
                        $keterangan  = $absensiData[$p->id]['keterangan'] ?? '';
                    ?>
                    
                    <div class="warga-card p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 md:gap-6">
                        
                        
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="w-11 h-11 rounded-[14px] bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center font-bold text-[13px] shrink-0 font-poppins shadow-sm">
                                <?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?>

                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="warga-nama text-[15px] font-semibold text-slate-800 font-poppins truncate tracking-tight leading-tight mb-1" title="<?php echo e($p->nama_lengkap); ?>"><?php echo e($p->nama_lengkap); ?></h4>
                                <div class="flex items-center gap-1.5">
                                    <i class="far fa-address-card text-slate-300 text-[12px]"></i> 
                                    <span class="text-[11px] font-medium text-slate-500 font-mono tracking-wide warga-nik"><?php echo e($p->nik ?? '-'); ?></span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="shrink-0 flex flex-col lg:items-end w-full lg:w-auto mt-1 lg:mt-0">
                            <div class="flex gap-2 w-full sm:w-[240px] bg-slate-50 p-1.5 rounded-[16px] border border-slate-100">
                                <input type="radio" name="kehadiran[<?php echo e($p->id); ?>]" id="hadir_<?php echo e($p->id); ?>" value="1" class="radio-hidden radio-hadir logic-radio" data-id="<?php echo e($p->id); ?>" <?php echo e($statusHadir === true ? 'checked' : ''); ?> required>
                                <label for="hadir_<?php echo e($p->id); ?>" class="status-btn flex-1 flex justify-center items-center gap-1.5 py-2 rounded-[12px] text-[10px] sm:text-[11px] font-bold tracking-widest uppercase">
                                    <i class="fas fa-check"></i> Hadir
                                </label>
                                
                                <input type="radio" name="kehadiran[<?php echo e($p->id); ?>]" id="absen_<?php echo e($p->id); ?>" value="0" class="radio-hidden radio-absen logic-radio" data-id="<?php echo e($p->id); ?>" <?php echo e($statusHadir === false ? 'checked' : ''); ?> required>
                                <label for="absen_<?php echo e($p->id); ?>" class="status-btn flex-1 flex justify-center items-center gap-1.5 py-2 rounded-[12px] text-[10px] sm:text-[11px] font-bold tracking-widest uppercase">
                                    <i class="fas fa-times"></i> Absen
                                </label>
                            </div>

                            <div id="ketBox_<?php echo e($p->id); ?>" class="ket-box <?php echo e($statusHadir === false ? 'open' : ''); ?> w-full sm:w-[240px]">
                                <input type="text" name="keterangan[<?php echo e($p->id); ?>]" value="<?php echo e($keterangan); ?>" placeholder="Tulis keterangan absen..." class="w-full bg-white border border-rose-100 text-rose-600 text-[11px] font-medium px-4 py-2.5 rounded-[12px] outline-none focus:border-rose-300 focus:ring-2 focus:ring-rose-50 placeholder:text-rose-300 transition-all shadow-sm">
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-20 px-4 border-2 border-dashed border-slate-100 rounded-[24px] bg-slate-50/50">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4 shadow-sm border border-slate-100"><i class="fas fa-box-open"></i></div>
                        <h4 class="text-[14px] font-bold text-slate-700 uppercase tracking-widest mb-1">Daftar Kosong</h4>
                        <p class="text-[12px] text-slate-500 max-w-sm mx-auto">Belum ada warga yang terdaftar pada kategori ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if(count($pasiens) > 0): ?>
            <div class="border-t border-slate-100 pt-6 mt-6 flex flex-col md:flex-row items-center justify-between gap-6 relative z-20">
                
                
                <div class="flex items-center justify-center md:justify-start gap-6 w-full md:w-auto">
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Tercentang</p>
                        <p class="text-2xl font-bold text-indigo-500 font-poppins leading-none" id="countSudah">0</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Sisa Warga</p>
                        <p class="text-2xl font-bold text-rose-400 font-poppins leading-none transition-colors duration-300" id="countSisa"><?php echo e(count($pasiens)); ?></p>
                    </div>
                </div>

                
                <button type="submit" id="btnSubmit" class="w-full md:w-auto px-8 py-3.5 bg-white/80 backdrop-blur-md text-indigo-600 font-bold text-[12px] uppercase tracking-widest rounded-full border-2 border-indigo-100 shadow-[0_4px_15px_rgba(99,102,241,0.08)] hover:bg-indigo-50/90 hover:border-indigo-300 hover:text-indigo-700 hover:shadow-[0_8px_25px_rgba(99,102,241,0.15)] hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-cloud-upload-alt text-lg"></i> Simpan Data Presensi
                </button>
            </div>
            <?php endif; ?>

        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalPasiens = <?php echo e(count($pasiens)); ?>;
    const countSudahEl = document.getElementById('countSudah');
    const countSisaEl  = document.getElementById('countSisa');
    const radios       = document.querySelectorAll('.logic-radio');

    window.hideLoader = () => { const l = document.getElementById('smoothLoader'); if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 300); } };
    window.showLoader = () => { const l = document.getElementById('smoothLoader'); if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0','pointer-events-none'); l.classList.add('opacity-100','pointer-events-auto'); } };

    // 1. ENGINE PENGHITUNG REAL-TIME
    function updateCounters() {
        const answered = document.querySelectorAll('.logic-radio:checked').length;
        const sisa = totalPasiens - answered;
        
        if(countSudahEl) countSudahEl.textContent = answered;
        if(countSisaEl) {
            countSisaEl.textContent = sisa;
            if (sisa === 0) {
                countSisaEl.classList.remove('text-rose-400'); countSisaEl.classList.add('text-emerald-400');
            } else {
                countSisaEl.classList.add('text-rose-400'); countSisaEl.classList.remove('text-emerald-400');
            }
        }
    }

    // 2. ENGINE TOGGLE KETERANGAN ABSEN
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const id = this.dataset.id;
            const ketBox = document.getElementById('ketBox_' + id);
            
            if (this.value == '0') {
                ketBox.classList.add('open');
                setTimeout(() => ketBox.querySelector('input').focus(), 100);
            } else {
                ketBox.classList.remove('open');
                ketBox.querySelector('input').value = '';
            }
            updateCounters();
        });
    });

    updateCounters();

    // 3. HADIR SEMUA ENGINE DENGAN SWEETALERT KUSTOM (SESUAI GAMBAR)
    document.getElementById('btnHadirSemua')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Tandai Hadir Semua?',
            html: 'Seluruh warga di halaman ini akan otomatis ditandai sebagai <b>Hadir</b>. Sistem <b class="text-rose-500">tidak akan menyimpannya</b> sebelum Anda menekan tombol Simpan di bagian bawah.',
            icon: 'question',
            showCancelButton: true,
            buttonsStyling: false,
            reverseButtons: true, // Membuat tombol Batal ada di kiri
            confirmButtonText: '<i class="fas fa-check-double mr-1"></i> Ya, Hadir Semua',
            cancelButtonText: 'Batalkan',
            customClass: { 
                popup: 'swal2-popup',
                confirmButton: 'swal-btn-confirm-emerald',
                cancelButton: 'swal-btn-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.radio-hadir').forEach(radio => {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                });
            }
        });
    });

    // 4. LIVE SEARCH INSTAN
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.warga-card');
            
            rows.forEach(row => {
                const nama = row.querySelector('.warga-nama').textContent.toLowerCase();
                const nik = row.querySelector('.warga-nik').textContent.toLowerCase();
                if (nama.includes(filter) || nik.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // 5. VALIDASI SEBELUM SIMPAN & LOADER
    document.getElementById('formAbsensi')?.addEventListener('submit', function(e) {
        const answered = document.querySelectorAll('.logic-radio:checked').length;
        
        if (answered < totalPasiens) {
            e.preventDefault();
            Swal.fire({
                title: 'Data Belum Tuntas',
                html: `Masih ada <b class="text-rose-500">${totalPasiens - answered} warga</b> yang belum Anda pilih statusnya. Mohon lengkapi terlebih dahulu.`,
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: '<i class="fas fa-pencil-alt mr-1"></i> Lanjutkan Mengisi',
                customClass: { 
                    popup: 'swal2-popup',
                    confirmButton: 'swal-btn-confirm-indigo'
                }
            });
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg text-indigo-400"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-wait', 'scale-95');
        showLoader();
    });

    window.onload = hideLoader;
    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/absensi/index.blade.php ENDPATH**/ ?>