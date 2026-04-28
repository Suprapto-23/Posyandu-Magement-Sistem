

<?php $__env->startSection('title', 'Edit Data Ibu Hamil'); ?>
<?php $__env->startSection('page-name', 'Koreksi Kehamilan'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* FORM INPUT CRM NEXUS */
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
    .form-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #f1f5f9; color: #1e293b;
        font-size: 0.875rem; border-radius: 16px; padding: 1rem 1.25rem; outline: none;
        transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.01);
    }
    .form-input:focus {
        background-color: #ffffff; border-color: #ec4899;
        box-shadow: 0 4px 20px -3px rgba(236, 72, 153, 0.15); transform: translateY(-2px);
    }
    .form-input::placeholder { color: #94a3b8; font-weight: 500; }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; box-shadow: 0 4px 15px -3px rgba(244, 63, 94, 0.15) !important; }
    
    /* KARTU KACA (GLASSMORPHISM) */
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }

    /* SWEETALERT CUSTOM KAPSUL NEXUS */
    div:where(.swal2-container) { z-index: 10000 !important; backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup.swal2-toast { border-radius: 16px !important; padding: 16px 24px !important; background: rgba(255, 255, 255, 0.98) !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.15) !important; }
    .swal2-toast .swal2-title { font-family: 'Poppins', sans-serif !important; font-size: 14px !important; color: #1e293b !important; }
    .swal2-toast .swal2-html-container { font-family: sans-serif !important; font-size: 12px !important; color: #64748b !important; margin-top: 4px !important; text-align: left !important; }
    .swal2-popup:not(.swal2-toast) { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; border: 1px solid rgba(255,255,255,0.5) !important; width: 28em !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-pink-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-pink-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-pen-nib text-pink-500 text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-pink-900 font-black tracking-widest text-[11px] animate-pulse uppercase">MENYIAPKAN DATA...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-12">
    
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-pink-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-rose-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    
    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-pink-50 hover:border-pink-300 hover:text-pink-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    
    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[24px] bg-gradient-to-br from-pink-100 to-rose-100 text-pink-600 mb-5 shadow-sm border border-pink-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib text-4xl"></i>
        </div>
        <div class="inline-flex items-center gap-2 bg-pink-50 border border-pink-200 text-pink-600 text-[9px] font-black px-3 py-1 rounded-full mb-3 uppercase tracking-widest mx-auto block w-max">
            <i class="fas fa-exclamation-circle text-pink-400"></i> Mode Koreksi Data Aktif
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Edit Profil Ibu Hamil</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Pembaruan data pada modul ini akan secara otomatis memperbarui rekam medis yang terintegrasi dengan portal Warga.</p>
    </div>

    
    <form action="<?php echo e(route('kader.data.ibu-hamil.update', $ibuHamil->id)); ?>" method="POST" id="formEditIbu" class="relative z-10">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 w-24 h-24 bg-pink-500/10 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-[14px] bg-pink-500 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Lengkap</h3>
                </div>
                
                <div class="space-y-6 flex-1">
                    <div>
                        <label class="form-label">NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="<?php echo e(old('nik', $ibuHamil->nik)); ?>" required placeholder="16 Digit NIK KTP" class="form-input <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-rose-500 text-xs font-bold mt-1.5"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="form-label">Nama Lengkap Ibu <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap', $ibuHamil->nama_lengkap)); ?>" required placeholder="Contoh: Siti Aisyah" class="form-input <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> form-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $ibuHamil->tempat_lahir)); ?>" required placeholder="Kota Kelahiran" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $ibuHamil->tanggal_lahir?->format('Y-m-d'))); ?>" required class="form-input cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Nama Suami <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_suami" value="<?php echo e(old('nama_suami', $ibuHamil->nama_suami)); ?>" required placeholder="Nama Lengkap Suami" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">No. HP Keluarga (Opsional)</label>
                            <input type="number" name="telepon_ortu" value="<?php echo e(old('telepon_ortu', $ibuHamil->telepon_ortu)); ?>" placeholder="Contoh: 0812xxxx" class="form-input">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="2" required placeholder="Alamat lengkap RT/RW..." class="form-input resize-none"><?php echo e(old('alamat', $ibuHamil->alamat)); ?></textarea>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-5 flex flex-col gap-8">
                
                
                <div class="bg-rose-50/80 rounded-[32px] border border-rose-200/80 shadow-[0_10px_40px_-10px_rgba(236,72,153,0.03)] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-rose-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                    
                    <div class="flex items-center justify-between mb-8 border-b border-rose-200 pb-5 relative z-10">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-10 rounded-[14px] bg-rose-500 text-white flex items-center justify-center font-black shadow-md">2</span>
                            <h3 class="text-xl font-black text-rose-900 font-poppins">Kandungan</h3>
                        </div>
                        
                        
                        <select name="status" class="bg-white border-2 border-rose-200 text-rose-600 font-bold text-xs rounded-full px-3 py-1.5 outline-none cursor-pointer focus:border-rose-400 shadow-sm">
                            <option value="aktif" <?php echo e(old('status', $ibuHamil->status) == 'aktif' ? 'selected' : ''); ?>>Hamil (Aktif)</option>
                            <option value="selesai" <?php echo e(old('status', $ibuHamil->status) == 'selesai' ? 'selected' : ''); ?>>Selesai (Lahir)</option>
                        </select>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div class="bg-white p-5 rounded-[20px] border border-rose-100 shadow-sm">
                            <label class="form-label text-rose-600"><i class="fas fa-calendar-minus mr-1"></i> HPHT (Haid Terakhir) <span class="text-rose-500">*</span></label>
                            <input type="date" name="hpht" id="hpht" value="<?php echo e(old('hpht', $ibuHamil->hpht?->format('Y-m-d'))); ?>" required class="form-input bg-slate-50 border-slate-200 focus:bg-white focus:border-rose-400 mb-4 cursor-pointer">
                            
                            <label class="form-label text-rose-600"><i class="fas fa-baby mr-1"></i> HPL (Perkiraan Lahir) <span class="text-rose-500">*</span></label>
                            <input type="date" name="hpl" id="hpl" value="<?php echo e(old('hpl', $ibuHamil->hpl?->format('Y-m-d'))); ?>" required class="form-input bg-rose-50 border-rose-200 text-rose-800 font-black focus:bg-white focus:border-rose-400" readonly title="Sistem menghitung otomatis dari HPHT">
                            <p class="text-[10px] font-black text-rose-400 mt-2 uppercase tracking-widest">*Dihitung presisi +280 Hari</p>
                        </div>

                        <div>
                            <label class="form-label text-rose-800">Golongan Darah</label>
                            <select name="golongan_darah" class="form-input bg-white border-rose-100 focus:ring-4 focus:ring-rose-50 cursor-pointer">
                                <option value="">-- Belum Tahu --</option>
                                <?php $__currentLoopData = ['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($gol); ?>" <?php echo e(old('golongan_darah', $ibuHamil->golongan_darah) == $gol ? 'selected' : ''); ?>><?php echo e($gol); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="bg-slate-900 rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] p-8 md:p-10 relative overflow-hidden flex-1 flex flex-col justify-center">
                    <div class="absolute right-0 bottom-0 w-32 h-32 bg-indigo-500/20 rounded-tl-full pointer-events-none blur-2xl"></div>

                    <div class="flex items-center gap-4 mb-6 border-b border-slate-700/80 pb-5 relative z-10">
                        <span class="w-10 h-10 rounded-[14px] bg-slate-700 text-white flex items-center justify-center font-black shadow-md border border-slate-600">3</span>
                        <h3 class="text-xl font-black text-white font-poppins">Fisik Dasar</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-5 relative z-10">
                        <div>
                            <label class="form-label text-slate-400">Berat (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="<?php echo e(old('berat_badan', $ibuHamil->berat_badan)); ?>" placeholder="0.0" class="form-input bg-slate-800 border-slate-700 text-white placeholder:text-slate-600 focus:bg-slate-700 focus:border-pink-500">
                        </div>
                        <div>
                            <label class="form-label text-slate-400">Tinggi (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="<?php echo e(old('tinggi_badan', $ibuHamil->tinggi_badan)); ?>" placeholder="0.0" class="form-input bg-slate-800 border-slate-700 text-white placeholder:text-slate-600 focus:bg-slate-700 focus:border-pink-500">
                        </div>
                    </div>

                    
                    <div id="imt-result" class="hidden animate-slide-up bg-slate-800 border border-slate-700 p-5 rounded-[20px] flex items-center justify-between relative z-10 shadow-inner">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Skor IMT Ibu</p>
                            <p class="text-2xl font-black text-white font-poppins tracking-tight" id="imt-val">0.00</p>
                        </div>
                        <div id="imt-kat" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white shadow-sm">
                            -
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
        
        
        <div class="mt-8 bg-white border border-slate-200 p-6 md:p-8 rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row items-center justify-between gap-6 relative z-30">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-[16px] bg-pink-50 text-pink-500 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-edit"></i></div>
                <div class="hidden sm:block">
                    <h4 class="text-[14px] font-black text-slate-800 font-poppins mb-0.5">Simpan Perubahan</h4>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">Pastikan data yang dikoreksi sudah sesuai dengan buku KIA terbaru.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="<?php echo e(route('kader.data.ibu-hamil.index')); ?>" onclick="showLoader()" class="flex-1 sm:flex-none px-8 py-4 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[12px] rounded-full hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batalkan
                </a>
                <button type="submit" id="btnSubmit" class="flex-1 sm:flex-none px-10 py-4 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-black text-[12px] rounded-full hover:from-pink-600 hover:to-rose-700 shadow-[0_8px_20px_rgba(236,72,153,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save text-lg"></i> Simpan Koreksi
                </button>
            </div>
        </div>
        
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. LIMITASI TANGGAL & INPUT
    const today = new Date(); 
    document.getElementById('tanggal_lahir').max = today.toISOString().split('T')[0];
    document.getElementById('hpht').max = today.toISOString().split('T')[0];
    
    document.querySelectorAll('input[type="number"]').forEach(input => { 
        input.addEventListener('input', function() { 
            if (this.name === 'nik' || this.name === 'nik_ayah') {
                if (this.value.length > 16) this.value = this.value.slice(0, 16); 
            }
        }); 
    });

    // 2. AUTO-CALC HPL
    document.getElementById('hpht').addEventListener('change', function() {
        const hpht = new Date(this.value); 
        if (isNaN(hpht)) return;
        const hpl = new Date(hpht); 
        hpl.setDate(hpl.getDate() + 280); 
        document.getElementById('hpl').value = hpl.toISOString().split('T')[0];
    });

    // 3. AUTO-CALC IMT WIDGET NEON
    const hitungIMT = () => {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        const res = document.getElementById('imt-result');
        
        if (!bb || !tb || tb < 50) { res.classList.add('hidden'); return; }
        
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : 'Overweight');
        
        let clr = imt < 18.5 ? 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)]' : 
                 (imt < 25 ? 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 
                             'bg-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.5)]');
        
        document.getElementById('imt-val').textContent = imt;
        document.getElementById('imt-kat').textContent = kat;
        document.getElementById('imt-kat').className = `px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-white border border-white/20 ${clr}`;
        res.classList.remove('hidden');
    };
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);

    // Load trigger untuk Edit (menampilkan IMT dan HPL awal)
    if(document.getElementById('hpht').value) document.getElementById('hpht').dispatchEvent(new Event('change'));
    if(document.getElementById('berat_badan').value && document.getElementById('tinggi_badan').value) hitungIMT();

    // 4. LOADER & SUBMIT INTERCEPTOR
    const hideLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 300); } 
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Koreksi'; btn.classList.remove('opacity-75', 'cursor-wait'); btn.disabled = false;}
    };
    
    const showLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0','pointer-events-none'); l.classList.add('opacity-100','pointer-events-auto'); } 
    };
    
    window.onload = hideLoader;
    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);

    // CEGAT FORM MUTLAK (HARD-BLOCK BYPASS)
    document.getElementById('formEditIbu').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.disabled = true;
        showLoader();
    });

    // TANGKAP ERROR DARI BACKEND
    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error', 
            title: 'Sistem Menolak',
            html: `<p class="mt-2 text-sm text-slate-600"><?php echo addslashes(session('error')); ?></p>`,
            confirmButtonText: 'Saya Mengerti', 
            buttonsStyling: false,
            customClass: { 
                popup: 'rounded-[32px] p-8 bg-white/95 backdrop-blur-xl border border-slate-100 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]', 
                confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-full font-black text-[11px] uppercase tracking-widest transition-all shadow-md mt-4' 
            }
        });
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/data/ibu-hamil/edit.blade.php ENDPATH**/ ?>