
<?php $__env->startSection('title', 'Input Pemeriksaan Fisik'); ?>
<?php $__env->startSection('page-name', 'Rekam Antropometri & Klinis'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .form-label { display: block; font-size: 0.65rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1.25rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); }
    .form-input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-2px); }
    .form-input:disabled { background-color: #f1f5f9; color: #94a3b8; cursor: not-allowed; border-color: #e2e8f0; }
    
    .input-group { position: relative; display: flex; align-items: center; }
    .input-group input { padding-right: 3.5rem; }
    .input-group .unit { position: absolute; right: 1.25rem; font-size: 0.75rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; pointer-events: none; }
    
    /* Logika Tampil Sembunyi yang Smooth */
    .med-block { display: none; opacity: 0; transition: opacity 0.5s ease-in-out; }
    .med-block.show { display: block; opacity: 1; animation: slideUpFade 0.4s ease forwards; }

    /* Custom Radio untuk Kategori Warga */
    .kat-radio:checked + label { background-color: #4f46e5; color: white; border-color: #4f46e5; box-shadow: 0 8px 20px -5px rgba(79,70,229,0.4); transform: scale(1.05); }
    .kat-radio:checked + label i { color: white !important; }

    /* Floating Action Bar */
    .floating-bar { transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease; transform: translateY(150%); opacity: 0; }
    .floating-bar.active { transform: translateY(0); opacity: 1; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto animate-slide-up relative z-10 pb-32">
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>

    
    <div class="mb-8 flex items-center justify-between gap-4 relative z-10">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>" class="w-14 h-14 rounded-2xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm group shrink-0">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins">Pemeriksaan Fisik</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 font-black text-[9px] uppercase tracking-widest rounded-md border border-indigo-100">Meja 2 & 3</span>
                    <p class="text-sm font-medium text-slate-500">Standar Integrasi Layanan Primer (ILP)</p>
                </div>
            </div>
        </div>
    </div>

    <form action="<?php echo e(route('kader.pemeriksaan.store')); ?>" method="POST" id="formPemeriksaan" class="relative z-10">
        <?php echo csrf_field(); ?>
        
        <input type="hidden" name="pasien_type" id="hidden_pasien_type" value="">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            
            
            
            
            <div class="lg:col-span-4 flex flex-col gap-6 h-fit sticky top-6 z-20">
                <div class="bg-white/95 backdrop-blur-2xl border border-white/80 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full pointer-events-none"></div>
                    
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-indigo-200">1</span>
                        <h3 class="text-lg font-black text-slate-800 font-poppins">Identitas Peserta</h3>
                    </div>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label class="form-label mb-3">Kategori Warga (Klaster Usia) <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <?php $reqKat = request('kategori', 'balita'); ?>
                                
                                <input type="radio" name="kategori_pasien" id="kat-balita" value="balita" class="kat-radio hidden" <?php echo e($reqKat == 'balita' ? 'checked' : ''); ?>>
                                <label for="kat-balita" class="border-2 border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer transition-all"><i class="fas fa-baby text-2xl mb-1.5 text-sky-500 transition-colors"></i> Balita</label>

                                <input type="radio" name="kategori_pasien" id="kat-bumil" value="ibu_hamil" class="kat-radio hidden" <?php echo e($reqKat == 'ibu_hamil' ? 'checked' : ''); ?>>
                                <label for="kat-bumil" class="border-2 border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer transition-all"><i class="fas fa-female text-2xl mb-1.5 text-pink-500 transition-colors"></i> Ibu Hamil</label>

                                <input type="radio" name="kategori_pasien" id="kat-remaja" value="remaja" class="kat-radio hidden" <?php echo e($reqKat == 'remaja' ? 'checked' : ''); ?>>
                                <label for="kat-remaja" class="border-2 border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer transition-all"><i class="fas fa-user-graduate text-2xl mb-1.5 text-indigo-500 transition-colors"></i> Remaja</label>

                                <input type="radio" name="kategori_pasien" id="kat-lansia" value="lansia" class="kat-radio hidden" <?php echo e($reqKat == 'lansia' ? 'checked' : ''); ?>>
                                <label for="kat-lansia" class="border-2 border-slate-200 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-xl p-3 flex flex-col items-center justify-center cursor-pointer transition-all"><i class="fas fa-user-clock text-2xl mb-1.5 text-emerald-500 transition-colors"></i> Lansia</label>
                            </div>
                        </div>

                        
                        <div class="relative bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <label class="form-label text-indigo-600">Pilih Nama Pasien <span class="text-rose-500">*</span></label>
                            <select name="pasien_id" id="pasien_id" required class="w-full bg-white border border-slate-200 text-slate-800 text-sm font-bold rounded-xl px-4 py-3 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 cursor-pointer shadow-sm appearance-none" style="background-image: url('data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3e%3cpath stroke=\'%2394a3b8\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M6 8l4 4 4-4\'/%3e%3c/svg%3e'); background-position: right 1rem center; background-repeat: no-repeat; background-size: 1.2em 1.2em;">
                                <option value="">-- Menunggu Kategori --</option>
                            </select>
                            <input type="hidden" id="old_pasien_id" value="<?php echo e(request('pasien_id')); ?>">
                            
                            
                            <div id="gender_badge" class="hidden mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_periksa" value="<?php echo e(date('Y-m-d')); ?>" required max="<?php echo e(date('Y-m-d')); ?>" class="form-input cursor-pointer bg-white">
                        </div>
                    </div>
                </div>
            </div>

            
            
            
            <div class="lg:col-span-8 flex flex-col gap-6 z-10">
                
                
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <span class="w-10 h-10 rounded-[14px] bg-emerald-50 text-emerald-600 flex items-center justify-center font-black border border-emerald-100"><i class="fas fa-weight"></i></span>
                        <h3 class="text-[15px] font-black text-slate-800 font-poppins uppercase tracking-wide">Antropometri Dasar</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                        <div>
                            <label class="form-label text-emerald-700">Berat Badan <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="berat_badan" id="berat_badan" required placeholder="0.0" class="form-input bg-emerald-50/30 focus:border-emerald-400 focus:bg-white text-lg">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-emerald-700">Tinggi / Panjang Badan <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" required placeholder="0.0" class="form-input bg-emerald-50/30 focus:border-emerald-400 focus:bg-white text-lg">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    
                    <div id="imt-widget" class="med-block mt-6">
                        <div class="flex items-center justify-between bg-slate-900 text-white p-5 rounded-[20px] shadow-[0_10px_20px_rgba(0,0,0,0.1)] relative overflow-hidden">
                            <i class="fas fa-heartbeat absolute -right-4 -bottom-4 text-6xl text-white/5"></i>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Kalkulator Cerdas</p>
                                <p class="text-sm font-bold text-slate-200">Indeks Massa Tubuh (IMT)</p>
                            </div>
                            <div class="text-right flex items-center gap-4">
                                <p class="text-4xl font-black font-poppins" id="imt-val">0.0</p>
                                <div id="imt-kat" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-700 text-slate-300">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <span class="w-10 h-10 rounded-[14px] bg-rose-50 text-rose-500 flex items-center justify-center font-black border border-rose-100"><i class="fas fa-stethoscope"></i></span>
                        <h3 class="text-[15px] font-black text-slate-800 font-poppins uppercase tracking-wide">Tanda Vital & Spesifik Klaster</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        
                        
                        <div class="med-block block-tensi">
                            <label class="form-label"><i class="fas fa-heartbeat text-rose-500 mr-1"></i> Tekanan Darah (Tensi)</label>
                            <input type="text" name="tekanan_darah" placeholder="Cth: 120/80" class="form-input font-mono text-center focus:border-rose-400">
                        </div>

                        
                        <div class="med-block block-suhu">
                            <label class="form-label"><i class="fas fa-thermometer-half text-rose-500 mr-1"></i> Suhu Tubuh</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="suhu_tubuh" placeholder="36.5" class="form-input focus:border-rose-400">
                                <span class="unit">°C</span>
                            </div>
                        </div>

                        
                        <div class="med-block block-lk">
                            <label class="form-label"><i class="fas fa-child text-sky-500 mr-1"></i> Lingkar Kepala (LK)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="lingkar_kepala" placeholder="0.0" class="form-input focus:border-sky-400">
                                <span class="unit">cm</span>
                            </div>
                        </div>

                        
                        <div class="med-block block-lp">
                            <label class="form-label"><i class="fas fa-ruler-horizontal text-amber-500 mr-1"></i> Lingkar Perut (LP)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="lingkar_perut" placeholder="0.0" class="form-input focus:border-amber-400">
                                <span class="unit">cm</span>
                            </div>
                        </div>

                        
                        <div class="med-block block-lila">
                            <label class="form-label" id="lbl_lila">
                                <i class="fas fa-tape text-pink-500 mr-1" id="ico_lila"></i> 
                                <span id="txt_lila">Lingkar Lengan (LiLA)</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="lila" id="inp_lila" placeholder="0.0" class="form-input">
                                <span class="unit">cm</span>
                            </div>
                            <div id="warn_kek" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-rose-50 border border-rose-200 rounded-lg">
                                <i class="fas fa-exclamation-circle text-rose-500 animate-pulse"></i>
                                <p class="text-[10px] font-bold text-rose-700">Waspada: Kurang Energi Kronis (KEK). Rujuk ke Bidan.</p>
                            </div>
                        </div>

                    </div>
                    
                    
                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <label class="form-label text-slate-500">Keluhan / Catatan (Opsional)</label>
                        <textarea name="keluhan" rows="2" placeholder="Tuliskan keluhan untuk dibaca oleh Bidan..." class="form-input resize-none bg-slate-50/50"></textarea>
                    </div>
                </div>

                
                <div class="med-block block-lab">
                    <div class="bg-indigo-50/50 rounded-[32px] border border-indigo-100 shadow-sm p-8">
                        <div class="flex items-center justify-between mb-6 border-b border-indigo-100 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-[14px] bg-indigo-100 text-indigo-600 flex items-center justify-center font-black"><i class="fas fa-microscope"></i></span>
                                <h3 class="text-[15px] font-black text-indigo-900 font-poppins uppercase tracking-wide">Lab Darah Sederhana</h3>
                            </div>
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest px-2 py-1 bg-white rounded-md border border-indigo-200">Opsional PTM</span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="med-block block-hb">
                                <label class="text-[9px] font-black text-rose-600 uppercase tracking-widest block mb-1.5">Hemoglobin (Hb)</label>
                                <input type="number" step="0.1" name="hemoglobin" placeholder="g/dL" class="w-full border border-rose-200 rounded-xl px-3 py-2.5 text-center font-bold text-sm bg-white focus:border-rose-400 focus:ring-2 focus:ring-rose-50 outline-none transition-all">
                            </div>
                            <div class="med-block block-gula">
                                <label class="text-[9px] font-black text-sky-600 uppercase tracking-widest block mb-1.5">Gula Darah</label>
                                <input type="number" step="1" name="gula_darah" placeholder="mg/dL" class="w-full border border-sky-200 rounded-xl px-3 py-2.5 text-center font-bold text-sm bg-white focus:border-sky-400 focus:ring-2 focus:ring-sky-50 outline-none transition-all">
                            </div>
                            <div class="med-block block-asam">
                                <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest block mb-1.5">Asam Urat</label>
                                <input type="number" step="0.1" name="asam_urat" placeholder="mg/dL" class="w-full border border-amber-200 rounded-xl px-3 py-2.5 text-center font-bold text-sm bg-white focus:border-amber-400 focus:ring-2 focus:ring-amber-50 outline-none transition-all">
                            </div>
                            <div class="med-block block-kolesterol">
                                <label class="text-[9px] font-black text-purple-600 uppercase tracking-widest block mb-1.5">Kolesterol</label>
                                <input type="number" step="1" name="kolesterol" placeholder="mg/dL" class="w-full border border-purple-200 rounded-xl px-3 py-2.5 text-center font-bold text-sm bg-white focus:border-purple-400 focus:ring-2 focus:ring-purple-50 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        
        
        
        <div id="actionDock" class="fixed bottom-6 left-0 right-0 z-50 flex justify-center px-4 pointer-events-none floating-bar">
            <div class="bg-white/90 backdrop-blur-xl border border-slate-200 p-4 sm:p-5 rounded-[2rem] shadow-[0_20px_50px_-10px_rgba(0,0,0,0.1)] flex flex-col sm:flex-row items-center justify-between gap-4 w-full max-w-4xl pointer-events-auto">
                
                
                <div class="hidden sm:flex items-center gap-4 pl-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-[14px] font-black text-slate-800 leading-tight">Transmisi Data Aman</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Enkripsi EMR ke Meja 5 Bidan</p>
                    </div>
                </div>
                
                
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white font-black text-[13px] rounded-full hover:bg-indigo-500 transition-all flex items-center justify-center gap-2 uppercase tracking-widest shadow-[0_8px_20px_rgba(79,70,229,0.25)] hover:-translate-y-1">
                    <i class="fas fa-paper-plane text-lg"></i> Simpan Rekam Medis
                </button>

            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Pastikan route ini sesuai dengan API yang ada di web.php Anda
    const API_PASIEN_URL = '<?php echo e(route('kader.pemeriksaan.api.pasien')); ?>'; 

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => document.getElementById('actionDock').classList.add('active'), 500);
    });

    // 1. ENGINE VISIBILITAS KLASTER ILP
    function updateFormVisibility() {
        const kategori = document.querySelector('input[name="kategori_pasien"]:checked')?.value;
        if(!kategori) return;

        // Siapkan string Model Namespace untuk dikirim ke Backend Controller Anda
        let modelNamespace = '';
        if(kategori === 'balita') modelNamespace = 'App\\Models\\Balita';
        else if(kategori === 'ibu_hamil') modelNamespace = 'App\\Models\\IbuHamil';
        else if(kategori === 'remaja') modelNamespace = 'App\\Models\\Remaja';
        else if(kategori === 'lansia') modelNamespace = 'App\\Models\\Lansia';
        
        // Isi hidden input agar Laravel tidak menolak form submit
        document.getElementById('hidden_pasien_type').value = modelNamespace;

        // Reset semua input form medis
        document.querySelectorAll('.med-block').forEach(el => {
            el.classList.remove('show');
            el.querySelectorAll('input').forEach(inp => { inp.disabled = true; inp.value = ''; }); 
        });

        // Hidupkan blok form sesuai kategori standar Kemenkes
        if (kategori === 'balita') {
            tampilkanBlok(['.block-suhu', '.block-lk', '.block-lila']);
            document.getElementById('imt-widget').classList.remove('show');
        } 
        else if (kategori === 'ibu_hamil') {
            tampilkanBlok(['.block-tensi', '.block-lila', '.block-lab', '.block-hb']);
            document.getElementById('imt-widget').classList.add('show');
        } 
        else if (kategori === 'remaja') {
            tampilkanBlok(['.block-tensi', '.block-lila', '.block-lp', '.block-lab', '.block-hb', '.block-gula']);
            document.getElementById('imt-widget').classList.add('show');
        } 
        else if (kategori === 'lansia') {
            tampilkanBlok(['.block-tensi', '.block-lp', '.block-lab', '.block-gula', '.block-asam', '.block-kolesterol']);
            document.getElementById('imt-widget').classList.add('show');
        }

        // Panggil data pasien dengan 2 parameter (Mendukung Controller Lama & Baru)
        fetchPasienData(kategori, modelNamespace);
    }

    function tampilkanBlok(selectors) {
        selectors.forEach(sel => {
            const el = document.querySelector(sel);
            if(el) {
                el.classList.add('show');
                el.querySelectorAll('input').forEach(inp => inp.disabled = false);
            }
        });
    }

    // 2. FETCH API DATA PASIEN (Anti-Error URL Encoding)
    async function fetchPasienData(kategori, modelNamespace) {
        const select = document.getElementById('pasien_id');
        const oldId = document.getElementById('old_pasien_id').value;
        
        select.innerHTML = '<option value="">⏳ Mengambil data database...</option>';
        document.getElementById('gender_badge').classList.add('hidden');
        
        try {
            // Gunakan URLSearchParams untuk memastikan API menerima request dengan benar
            const params = new URLSearchParams({
                kategori: kategori,     // Untuk Controller Anda yang menggunakan ?kategori=balita
                model: modelNamespace   // Untuk Controller Anda yang menggunakan ?model=App\Models\Balita
            });

            const response = await fetch(`${API_PASIEN_URL}?${params.toString()}`);
            if(!response.ok) throw new Error('API Error');
            const data = await response.json();
            
            // Jika Data Kosong di database
            if (data.length === 0) {
                select.innerHTML = `<option value="" disabled selected>-- Belum ada data warga di kategori ini --</option>`;
                return;
            }

            // Jika Data Ada
            select.innerHTML = '<option value="" disabled selected>-- Klik Untuk Memilih Pasien --</option>';
            data.forEach(p => {
                const isSelected = (oldId == p.id) ? 'selected' : '';
                // Fallback gender jika API tidak mengirimkan jenis_kelamin
                const jk = p.jenis_kelamin || (kategori === 'ibu_hamil' ? 'P' : 'L'); 
                select.innerHTML += `<option value="${p.id}" data-jk="${jk}" ${isSelected}>${p.nama_lengkap} ${p.nik ? ' (NIK: '+p.nik+')' : ''}</option>`;
            });

            if(oldId) select.dispatchEvent(new Event('change'));

        } catch(e) {
            select.innerHTML = '<option value="">⚠️ Koneksi ke server gagal. Refresh halaman.</option>';
            console.error(e);
        }
    }

    // Jalankan trigger saat tombol kategori diklik
    document.querySelectorAll('.kat-radio').forEach(radio => radio.addEventListener('change', updateFormVisibility));
    updateFormVisibility(); // Eksekusi saat halaman pertama dimuat

    // 3. ENGINE PERUBAHAN GENDER REAL-TIME & LABEL LILA
    document.getElementById('pasien_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if(!option || !option.dataset.jk) return;

        const jk = option.dataset.jk;
        const kategori = document.querySelector('input[name="kategori_pasien"]:checked')?.value;
        const badge = document.getElementById('gender_badge');
        
        badge.classList.remove('hidden', 'text-sky-600', 'bg-sky-50', 'border-sky-200', 'text-pink-600', 'bg-pink-50', 'border-pink-200');
        if (jk === 'L') {
            badge.innerHTML = '<i class="fas fa-mars"></i> Laki-laki (Putra)';
            badge.classList.add('text-sky-600', 'bg-sky-50', 'border-sky-200');
        } else {
            badge.innerHTML = '<i class="fas fa-venus"></i> Perempuan (Putri)';
            badge.classList.add('text-pink-600', 'bg-pink-50', 'border-pink-200');
        }

        const lilaTxt = document.getElementById('txt_lila');
        const lilaIco = document.getElementById('ico_lila');
        const inpLila = document.getElementById('inp_lila');

        // Netralkan dulu styling LILA
        inpLila.className = "form-input";

        if (kategori === 'balita') {
            lilaTxt.textContent = "Lingkar Lengan (Pita LiLA)";
            lilaIco.className = "fas fa-child text-emerald-500 mr-1";
            inpLila.classList.add("focus:border-emerald-400", "focus:ring-4", "focus:ring-emerald-50");
        } else if (kategori === 'remaja') {
            if (jk === 'L') {
                lilaTxt.textContent = "Lingkar Lengan (Putra)";
                lilaIco.className = "fas fa-male text-sky-500 mr-1";
                inpLila.classList.add("focus:border-sky-400", "focus:ring-4", "focus:ring-sky-50");
            } else {
                lilaTxt.textContent = "Lingkar Lengan (Putri)";
                lilaIco.className = "fas fa-female text-pink-500 mr-1";
                inpLila.classList.add("focus:border-pink-400", "focus:ring-4", "focus:ring-pink-50");
            }
        } else if (kategori === 'ibu_hamil') {
            lilaTxt.textContent = "Lingkar Lengan (Bumil / KEK)";
            lilaIco.className = "fas fa-tape text-rose-500 mr-1";
            inpLila.classList.add("focus:border-rose-400", "focus:ring-4", "focus:ring-rose-50");
        }
    });

    // 4. KALKULATOR IMT CERDAS & DETEKSI KEK
    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        
        if (bb > 0 && tb > 50) {
            const imt = (bb / Math.pow(tb/100, 2)).toFixed(1);
            document.getElementById('imt-val').textContent = imt;
            
            const imtKatEl = document.getElementById('imt-kat');
            let label = '-', color = 'bg-slate-700';

            if(imt < 18.5) { label = 'Kurus'; color = 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]'; }
            else if(imt < 25) { label = 'Normal'; color = 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]'; }
            else if(imt < 27) { label = 'Gemuk'; color = 'bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.5)]'; }
            else { label = 'Obesitas'; color = 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]'; }
            
            imtKatEl.textContent = label;
            imtKatEl.className = `px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-colors ${color}`;
        } else {
            document.getElementById('imt-val').textContent = "0.0";
            document.getElementById('imt-kat').className = "px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-700 text-slate-300";
            document.getElementById('imt-kat').textContent = "-";
        }
    }
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);

    document.getElementById('inp_lila').addEventListener('input', function() {
        const kategori = document.querySelector('input[name="kategori_pasien"]:checked')?.value;
        const jk = document.getElementById('pasien_id').options[document.getElementById('pasien_id').selectedIndex]?.dataset.jk;
        const warn = document.getElementById('warn_kek');
        
        // Munculkan Peringatan KEK khusus Wanita (Bumil atau Remaja Putri) jika LILA < 23.5
        if (this.value && parseFloat(this.value) < 23.5 && (kategori === 'ibu_hamil' || (kategori === 'remaja' && jk === 'P'))) {
            warn.classList.remove('hidden');
        } else {
            warn.classList.add('hidden');
        }
    });

    // 5. UX SUBMIT FORM
    document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
        const pasienId = document.getElementById('pasien_id').value;
        if(!pasienId) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', text: 'Anda belum memilih nama peserta/pasien.', confirmButtonColor: '#4f46e5', customClass: { popup: 'rounded-[28px]' } });
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Mengenkripsi Data...';
        btn.classList.add('opacity-75', 'cursor-wait');

        Swal.fire({
            title: 'Menyimpan EMR...',
            text: 'Data fisik akan ditransmisikan ke Meja 5 Bidan.',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/pemeriksaan/create.blade.php ENDPATH**/ ?>