

<?php $__env->startSection('title', 'Buat Jadwal Baru'); ?>
<?php $__env->startSection('page-name', 'Tambah Agenda Medis'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .premium-input { width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px 16px 14px 46px; font-size: 14px; font-weight: 600; color: #1e293b; outline: none; transition: all 0.3s ease; }
    .premium-input:focus { background-color: #ffffff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .input-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 16px; transition: color 0.3s; }
    .group-focus-within .input-icon { color: #06b6d4; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-paper-plane text-cyan-600 text-2xl animate-pulse"></i>
    </div>
    <p class="text-cyan-800 font-poppins font-black tracking-widest text-[11px] animate-pulse" id="loaderText">MENYIMPAN & MEMBROADCAST NOTIFIKASI...</p>
</div>

<div class="max-w-[1000px] mx-auto animate-slide-up pb-10">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Form Publikasi Jadwal</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-[13px] sm:text-sm">Jadwal yang dibuat akan otomatis dikirimkan ke <strong class="text-cyan-600">Aplikasi Warga</strong> sebagai Notifikasi Push.</p>
        </div>
        <a href="<?php echo e(route('bidan.jadwal.index')); ?>" class="smooth-route inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 hover:text-cyan-700 transition-colors shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i> Batal
        </a>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-4/12 bg-gradient-to-br from-cyan-500 to-sky-600 p-8 lg:p-10 flex flex-col relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 border-[20px] border-white/10 rounded-full"></div>
            
            <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-[20px] border border-white/30 flex items-center justify-center text-4xl text-white mb-8 shadow-xl relative z-10 transform -rotate-3">
                <i class="fas fa-broadcast-tower"></i>
            </div>
            
            <h2 class="text-2xl font-black text-white font-poppins tracking-tight mb-3 relative z-10 leading-tight">Sistem Broadcast Pintar</h2>
            <p class="text-cyan-50 text-[13px] leading-relaxed relative z-10 font-medium">Sistem secara otomatis mendeteksi siapa sasaran jadwal ini. Jika Anda memilih "Khusus Ibu Hamil", maka HANYA akun Ibu Hamil yang akan menerima pesan peringatan (Notifikasi) di HP mereka.</p>
        </div>

        <div class="md:w-8/12 flex flex-col">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <i class="fas fa-edit text-cyan-600 text-xl"></i>
                <h3 class="font-black text-slate-800 text-[15px] font-poppins">Lengkapi Detail Agenda</h3>
            </div>

            <form id="formJadwal" action="<?php echo e(route('bidan.jadwal.store')); ?>" method="POST" class="flex flex-col flex-1">
                <?php echo csrf_field(); ?>
                
                <div class="p-8 space-y-6 flex-1">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Judul Agenda <span class="text-rose-500">*</span></label>
                        <div class="relative group-focus-within">
                            <i class="fas fa-heading input-icon"></i>
                            <input type="text" name="judul" value="<?php echo e(old('judul')); ?>" required class="premium-input" placeholder="Contoh: Imunisasi Polio Akbar Desa Mekar">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tanggal Eksekusi <span class="text-rose-500">*</span></label>
                            <div class="relative group-focus-within">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <input type="date" name="tanggal" value="<?php echo e(old('tanggal')); ?>" required class="premium-input cursor-pointer" style="padding-left: 46px; padding-right: 16px;">
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Mulai <span class="text-rose-500">*</span></label>
                                <div class="relative group-focus-within">
                                    <input type="time" name="waktu_mulai" value="<?php echo e(old('waktu_mulai')); ?>" required class="premium-input px-4 cursor-pointer text-center">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Selesai <span class="text-rose-500">*</span></label>
                                <div class="relative group-focus-within">
                                    <input type="time" name="waktu_selesai" value="<?php echo e(old('waktu_selesai')); ?>" required class="premium-input px-4 cursor-pointer text-center">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Lokasi Gedung / Posyandu <span class="text-rose-500">*</span></label>
                        <div class="relative group-focus-within">
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <input type="text" name="lokasi" value="<?php echo e(old('lokasi', 'Posyandu Induk Desa')); ?>" required class="premium-input" placeholder="Tuliskan nama tempat/alamat detail...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Kategori Layanan <span class="text-rose-500">*</span></label>
                            <div class="relative group-focus-within">
                                <i class="fas fa-tags input-icon"></i>
                                <select name="kategori" required class="premium-input cursor-pointer appearance-none" style="padding-right: 16px;">
                                    <option value="posyandu" <?php echo e(old('kategori') == 'posyandu' ? 'selected' : ''); ?>>Posyandu Rutin Bulanan</option>
                                    <option value="imunisasi" <?php echo e(old('kategori') == 'imunisasi' ? 'selected' : ''); ?>>Suntik Vaksin/Imunisasi</option>
                                    <option value="pemeriksaan" <?php echo e(old('kategori') == 'pemeriksaan' ? 'selected' : ''); ?>>Pemeriksaan Kandungan</option>
                                    <option value="konseling" <?php echo e(old('kategori') == 'konseling' ? 'selected' : ''); ?>>Penyuluhan & Edukasi</option>
                                    <option value="lainnya" <?php echo e(old('kategori') == 'lainnya' ? 'selected' : ''); ?>>Lain-Lain</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-rose-500 uppercase tracking-widest mb-2 pl-1">Target Peserta (Sasaran) <span class="text-rose-500">*</span></label>
                            <div class="relative group-focus-within">
                                <i class="fas fa-bullseye input-icon text-rose-400"></i>
                                <select name="target_peserta" required class="premium-input cursor-pointer appearance-none border-rose-200 focus:border-rose-500 focus:ring-rose-50" style="padding-right: 16px;">
                                    <option value="semua" <?php echo e(old('target_peserta') == 'semua' ? 'selected' : ''); ?>>Semua Elemen Warga</option>
                                    <option value="balita" <?php echo e(old('target_peserta') == 'balita' ? 'selected' : ''); ?>>Khusus Anak & Balita</option>
                                    <option value="ibu_hamil" <?php echo e(old('target_peserta') == 'ibu_hamil' ? 'selected' : ''); ?>>Khusus Ibu Hamil (KIA)</option>
                                    <option value="remaja" <?php echo e(old('target_peserta') == 'remaja' ? 'selected' : ''); ?>>Khusus Remaja</option>
                                    <option value="lansia" <?php echo e(old('target_peserta') == 'lansia' ? 'selected' : ''); ?>>Khusus Lansia (Manula)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Pesan / Syarat Tambahan (Opsional)</label>
                        <div class="relative group-focus-within">
                            <i class="fas fa-align-left input-icon" style="top: 24px;"></i>
                            <textarea name="deskripsi" rows="3" class="premium-input resize-none custom-scrollbar" placeholder="Ketik pesan tambahan yang akan muncul di notifikasi warga... (Misal: Wajib membawa buku KIA/KMS)"><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5 hidden sm:flex"><i class="fas fa-info-circle text-cyan-500 text-sm"></i> Validasi Ganda Aktif</p>
                    <button type="submit" id="btnSubmit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[13px] uppercase tracking-widest rounded-[14px] hover:from-cyan-600 hover:to-blue-700 shadow-[0_8px_20px_rgba(6,182,212,0.3)] hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-paper-plane text-lg"></i> Publikasi & Broadcast
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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

    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        const btn = document.getElementById('btnSubmit');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
        if(btn) {
            btn.innerHTML = '<i class="fas fa-paper-plane text-lg"></i> Publikasi & Broadcast';
            btn.classList.remove('opacity-75', 'cursor-wait');
        }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader('MEMBATALKAN...');
        });
    });

    document.getElementById('formJadwal').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Mengirim Notifikasi...';
        btn.classList.add('opacity-75', 'cursor-wait');
        showLoader('MENDISTRIBUSIKAN NOTIFIKASI KE WARGA...');
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/jadwal/create.blade.php ENDPATH**/ ?>