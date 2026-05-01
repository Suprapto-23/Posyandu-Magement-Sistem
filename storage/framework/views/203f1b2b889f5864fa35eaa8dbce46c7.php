

<?php $__env->startSection('title', 'Edit Jadwal Posyandu'); ?>
<?php $__env->startSection('page-name', 'Perbarui Agenda'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ANIMASI MASUK HALUS */
    .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* INPUT & TIPOGRAFI PREMIUM (KONSISTEN NEXUS CYAN) */
    .med-input { 
        width: 100%; background: #ffffff; border: 2px solid #f1f5f9; border-radius: 16px; 
        padding: 16px 20px 16px 52px; color: #0f172a; font-weight: 600; font-size: 14px; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none; appearance: none;
        box-shadow: 0 2px 6px rgba(15,23,42,0.02);
    }
    .med-input:focus { 
        border-color: #0ea5e9; 
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15), 0 2px 6px rgba(15,23,42,0.02); 
    }
    .med-input::placeholder { color: #94a3b8; font-weight: 500; }
    
    .med-label { 
        display: block; font-size: 11px; font-weight: 800; color: #64748b; 
        text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 10px; margin-left: 4px; 
        font-family: 'Poppins', sans-serif;
    }

    .input-wrapper { position: relative; width: 100%; }
    .input-icon { 
        position: absolute; left: 20px; top: 50%; transform: translateY(-50%); 
        color: #94a3b8; font-size: 16px; transition: all 0.3s ease; z-index: 10;
    }
    .med-input:focus + .input-icon { color: #0ea5e9; }

    /* PANEL INFO EDIT (KONSISTEN BLUE/CYAN THEME) */
    .edit-panel {
        background: linear-gradient(160deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 28px; position: relative; overflow: hidden;
    }
    .edit-panel::before {
        content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
        background: rgba(255,255,255,0.1); border-radius: 50%;
    }

    /* CUSTOM SELECT ARROW */
    .select-custom { padding-right: 40px !important; cursor: pointer; }
    .select-arrow { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }

    /* SWEETALERT NEXUS POPUP */
    .swal2-popup.nexus-swal {
        border-radius: 32px !important; padding: 2.5rem 2rem !important;
        background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255,255,255,0.8) !important; box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.15) !important;
    }
    
    /* Anti-tumpang tindih notifikasi bawaan template */
    #toast-container, .toast, .alert-success, .alert-danger { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-save text-cyan-600 text-xl animate-pulse"></i>
        </div>
    </div>
    <p class="text-[10px] font-black text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MENYIMPAN PERUBAHAN...</p>
</div>

<div class="max-w-[1100px] mx-auto fade-in-up pb-20">

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 px-2">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-[20px] bg-white border border-slate-200 text-cyan-600 flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-pen-nib"></i>
            </div>
            <div>
                <h1 class="text-[26px] font-black text-slate-800 tracking-tight font-poppins leading-none">Edit Agenda Jadwal</h1>
                <p class="text-[13px] font-semibold text-slate-500 mt-1.5">Perbarui rincian agenda atau ubah status kegiatan posyandu.</p>
            </div>
        </div>
        <a href="<?php echo e(route('bidan.jadwal.index')); ?>" class="smooth-route inline-flex items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 text-slate-600 font-bold text-[11.5px] uppercase tracking-widest rounded-[16px] hover:bg-slate-50 hover:text-cyan-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-slate-400"></i> Batal Edit
        </a>
    </div>

    
    <div class="bg-white rounded-[36px] border border-slate-100 shadow-[0_25px_70px_-15px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col md:flex-row">
        
        
        <div class="md:w-[350px] edit-panel p-10 flex flex-col text-white">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[20px] border border-white/30 flex items-center justify-center text-3xl mb-8 shadow-xl">
                <i class="fas fa-shield-alt"></i>
            </div>
            
            <h2 class="text-2xl font-black font-poppins tracking-tight mb-4 leading-tight">Mode Koreksi Sistem</h2>
            <p class="text-cyan-50 text-[14px] leading-relaxed font-medium opacity-90">
                Pembaruan informasi pada formulir ini akan langsung merubah data yang tampil di Aplikasi Warga. 
            </p>

            <div class="mt-auto space-y-4 pt-10">
                <div class="p-4 bg-white/10 rounded-2xl border border-white/10 flex items-center gap-4">
                    <i class="fas fa-exclamation-triangle text-cyan-200 text-xl"></i>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-cyan-100">Status Jadwal</p>
                        <p class="text-[12px] font-bold">Warga Akan Ter-Update</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex-1 p-8 md:p-12">
            <form id="formJadwalEdit" action="<?php echo e(route('bidan.jadwal.update', $jadwal->id)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="space-y-8">
                    
                    
                    <div>
                        <label class="med-label">Judul Agenda Kegiatan <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-heading input-icon"></i>
                            <input type="text" name="judul" value="<?php echo e(old('judul', $jadwal->judul)); ?>" required class="med-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div>
                            <label class="med-label">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <input type="date" name="tanggal" value="<?php echo e(old('tanggal', $jadwal->tanggal)); ?>" required class="med-input cursor-pointer">
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="med-label">Jam Mulai <span class="text-rose-500">*</span></label>
                                <div class="input-wrapper">
                                    <i class="far fa-clock input-icon"></i>
                                    <input type="time" name="waktu_mulai" value="<?php echo e(old('waktu_mulai', date('H:i', strtotime($jadwal->waktu_mulai)))); ?>" required class="med-input cursor-pointer" style="padding-left: 48px;">
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Jam Selesai <span class="text-rose-500">*</span></label>
                                <div class="input-wrapper">
                                    <i class="far fa-clock input-icon"></i>
                                    <input type="time" name="waktu_selesai" value="<?php echo e(old('waktu_selesai', date('H:i', strtotime($jadwal->waktu_selesai)))); ?>" required class="med-input cursor-pointer" style="padding-left: 48px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <label class="med-label">Lokasi Kegiatan / Gedung <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <input type="text" name="lokasi" value="<?php echo e(old('lokasi', $jadwal->lokasi)); ?>" required class="med-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div>
                            <label class="med-label">Kategori Layanan <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-tags input-icon"></i>
                                <select name="kategori" required class="med-input select-custom">
                                    <option value="posyandu" <?php echo e((old('kategori', $jadwal->kategori) == 'posyandu') ? 'selected' : ''); ?>>Posyandu Rutin Bulanan</option>
                                    <option value="imunisasi" <?php echo e((old('kategori', $jadwal->kategori) == 'imunisasi') ? 'selected' : ''); ?>>Suntik Vaksin / Imunisasi</option>
                                    <option value="pemeriksaan" <?php echo e((old('kategori', $jadwal->kategori) == 'pemeriksaan') ? 'selected' : ''); ?>>Pemeriksaan Kandungan</option>
                                    <option value="konseling" <?php echo e((old('kategori', $jadwal->kategori) == 'konseling') ? 'selected' : ''); ?>>Penyuluhan & Edukasi</option>
                                    <option value="lainnya" <?php echo e((old('kategori', $jadwal->kategori) == 'lainnya') ? 'selected' : ''); ?>>Lain-Lain</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        
                        <div>
                            <label class="med-label">Target Sasaran <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-bullseye input-icon"></i>
                                <select name="target_peserta" required class="med-input select-custom">
                                    <option value="semua" <?php echo e((old('target_peserta', $jadwal->target_peserta) == 'semua') ? 'selected' : ''); ?>>Semua Elemen Warga</option>
                                    <option value="balita" <?php echo e((old('target_peserta', $jadwal->target_peserta) == 'balita') ? 'selected' : ''); ?>>Khusus Ibu & Balita</option>
                                    <option value="ibu_hamil" <?php echo e((old('target_peserta', $jadwal->target_peserta) == 'ibu_hamil') ? 'selected' : ''); ?>>Khusus Ibu Hamil (KIA)</option>
                                    <option value="remaja" <?php echo e((old('target_peserta', $jadwal->target_peserta) == 'remaja') ? 'selected' : ''); ?>>Khusus Remaja</option>
                                    <option value="lansia" <?php echo e((old('target_peserta', $jadwal->target_peserta) == 'lansia') ? 'selected' : ''); ?>>Khusus Lansia (Manula)</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-6 bg-cyan-50/50 border border-cyan-100 rounded-[20px]">
                        <label class="med-label text-cyan-700">Ubah Status Jadwal <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-toggle-on input-icon text-cyan-500"></i>
                            <select name="status" required class="med-input select-custom border-cyan-200 bg-white text-cyan-900 focus:border-cyan-400 focus:ring-cyan-50">
                                <option value="aktif" <?php echo e($jadwal->status == 'aktif' ? 'selected' : ''); ?>>Aktif Berjalan</option>
                                <option value="selesai" <?php echo e($jadwal->status == 'selesai' ? 'selected' : ''); ?>>Sudah Selesai</option>
                                <option value="dibatalkan" <?php echo e($jadwal->status == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan / Ditunda</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow text-cyan-400"></i>
                        </div>
                    </div>

                    
                    <div>
                        <label class="med-label">Pesan Tambahan (Deskripsi)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-comment-medical input-icon" style="top: 24px;"></i>
                            <textarea name="deskripsi" rows="3" class="med-input resize-none" style="padding-top: 16px;"><?php echo e(old('deskripsi', $jadwal->deskripsi)); ?></textarea>
                        </div>
                    </div>

                    
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" id="btnSubmit" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[13px] uppercase tracking-widest rounded-[18px] hover:shadow-[0_20px_40px_rgba(6,182,212,0.4)] hover:-translate-y-1 transition-all duration-300 shadow-xl active:scale-95">
                            <i class="fas fa-save text-lg"></i> Simpan Pembaruan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const showLoader = (text = 'MEMPROSES...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    // Melindungi tombol submit agar tidak di-klik dua kali
    document.getElementById('formJadwalEdit').addEventListener('submit', function(e) {
        e.preventDefault(); // Tahan pengiriman sesaat untuk animasi
        const form = this;
        const btn = document.getElementById('btnSubmit');
        
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg mr-2"></i> Mengirim Data...';
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.disabled = true;

        showLoader('MENYIMPAN KOREKSI DATA JADWAL...');

        // Jeda kecil agar loader terlihat mulus sebelum form di-submit
        setTimeout(() => {
            form.submit();
        }, 500);
    });

    // Efek kembali yang mulus
    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader('MEMBATALKAN...');
        });
    });

    // ====================================================================
    // SWEETALERT NEXUS CENTER POPUP: NOTIFIKASI ERROR (Jika Validasi Gagal)
    // ====================================================================
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
                title: 'text-[22px] font-black text-slate-800 font-poppins pt-2',
                htmlContainer: 'text-[13.5px] font-medium text-slate-500 mb-6',
                confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-[14px] font-bold text-[12px] uppercase tracking-widest transition-all shadow-[0_8px_20px_rgba(243,24,71,0.2)]'
            }
        });
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/jadwal/edit.blade.php ENDPATH**/ ?>