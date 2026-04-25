@extends('layouts.kader')
@section('title', 'Input Pemeriksaan Fisik')
@section('page-name', 'Rekam Antropometri & Klinis')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* =========================================================================
       ANIMASI & UI SYSTEM (ENTERPRISE GRADE)
       ========================================================================= */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.2s; } .delay-300 { animation-delay: 0.3s; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; text-align: left;}
    
    .form-input { 
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; 
        font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none; 
        transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); 
    }
    .form-input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-1px); }
    .form-input::placeholder { color: #94a3b8; font-weight: 600; }
    
    /* ✨ SOLUSI ANTI-TABRAKAN IKON ✨ */
    .has-left-icon { padding-left: 3.25rem !important; }
    .has-right-icon { padding-right: 2.75rem !important; }
    
    /* Smooth Toggle Transition untuk Form Spesifik */
    .dynamic-section { transition: all 0.4s ease-in-out; overflow: hidden; opacity: 1; max-height: 1000px; }
    .dynamic-section.hidden-section { opacity: 0; max-height: 0; padding: 0; margin: 0; border: none; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-slide-up">

    {{-- HERO BANNER --}}
    <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 rounded-[2.5rem] p-8 md:p-12 mb-8 relative overflow-hidden shadow-2xl shadow-indigo-500/20 text-center md:text-left flex flex-col md:flex-row items-center gap-8 border border-white/10">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 2px, transparent 2px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>
        
        <div class="w-20 h-20 rounded-[1.5rem] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-inner z-10 rotate-3">
            <i class="fas fa-stethoscope"></i>
        </div>

        <div class="relative z-10 flex-1">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-black px-4 py-1.5 rounded-full mb-3 uppercase tracking-widest shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Form Perekaman Medis
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-white font-poppins tracking-tight text-shadow-sm mb-2">Input Pemeriksaan Fisik</h2>
            <p class="text-indigo-100 text-sm font-medium leading-relaxed max-w-xl">Catat data antropometri dan klinis pasien berdasarkan kategori. Data akan diteruskan ke Bidan untuk verifikasi akhir KMS.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 mb-6 text-sm font-bold text-rose-600 flex items-start gap-4 shadow-sm animate-slide-up delay-100">
        <i class="fas fa-exclamation-triangle text-xl mt-0.5"></i>
        <div>
            <p class="text-[11px] uppercase tracking-widest font-black mb-1">Gagal Menyimpan</p>
            <p class="text-xs">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="formPemeriksaan">
        @csrf
        
        {{-- =========================================================================
             STEP 1: IDENTIFIKASI PASIEN (DISEMPURNAKAN - ANTI OVERLAP)
             ========================================================================= --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8 animate-slide-up delay-100">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner border border-indigo-100"><i class="fas fa-id-card-alt"></i></div>
                <div>
                    <h5 class="font-black text-slate-800 text-[15px] uppercase tracking-widest font-poppins mb-0.5">Identifikasi Kunjungan</h5>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tentukan Kategori & Nama Warga</p>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Kategori Sasaran dengan Ikon Sempurna --}}
                <div class="space-y-1">
                    <label class="form-label">1. Kategori Sasaran <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none">
                            <i class="fas fa-users text-indigo-400 group-focus-within:text-indigo-600 transition-colors text-lg"></i>
                        </div>
                        
                        <select name="kategori_pasien" id="kategori_pasien" required class="form-input has-left-icon has-right-icon cursor-pointer appearance-none">
                            <option value="balita" {{ old('kategori_pasien', $kategori_awal) == 'balita' ? 'selected' : '' }}>Anak & Balita</option>
                            <option value="ibu_hamil" {{ old('kategori_pasien', $kategori_awal) == 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                            <option value="remaja" {{ old('kategori_pasien', $kategori_awal) == 'remaja' ? 'selected' : '' }}>Remaja</option>
                            <option value="lansia" {{ old('kategori_pasien', $kategori_awal) == 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                        
                        <div class="absolute inset-y-0 right-0 w-10 flex items-center justify-center pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                </div>

                {{-- Nama Pasien dengan Ikon Sempurna --}}
                <div class="space-y-1">
                    <label class="form-label">2. Nama Pasien / Warga <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none">
                            <i class="fas fa-search text-indigo-400 group-focus-within:text-indigo-600 transition-colors text-lg"></i>
                        </div>
                        
                        <select name="pasien_id" id="pasien_id" required class="form-input has-left-icon has-right-icon cursor-pointer appearance-none border-indigo-200 bg-indigo-50/30">
                            <option value="">-- Sedang memuat database... --</option>
                        </select>
                        
                        <div class="absolute inset-y-0 right-0 w-10 flex items-center justify-center pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Kedatangan --}}
                <div class="md:col-span-2 space-y-1 mt-2 border-t border-slate-100 pt-6">
                    <label class="form-label">Tanggal Pemeriksaan & Kunjungan <span class="text-rose-500">*</span></label>
                    <div class="relative group w-full md:w-1/2">
                        <div class="absolute inset-y-0 left-0 w-12 flex items-center justify-center pointer-events-none">
                            <i class="far fa-calendar-alt text-indigo-400 group-focus-within:text-indigo-600 transition-colors text-lg"></i>
                        </div>
                        <input type="date" name="tanggal_periksa" value="{{ old('tanggal_periksa', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}" class="form-input has-left-icon text-slate-600">
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 mt-2"><i class="fas fa-info-circle text-indigo-400"></i> Secara default, tanggal disetel ke hari ini. Jangan diubah kecuali untuk input data susulan.</p>
                </div>
            </div>
        </div>

        {{-- =========================================================================
             STEP 2: ANTROPOMETRI UMUM (SEMUA KATEGORI)
             ========================================================================= --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8 animate-slide-up delay-200">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner border border-emerald-100"><i class="fas fa-weight"></i></div>
                    <div>
                        <h5 class="font-black text-slate-800 text-[15px] uppercase tracking-widest font-poppins mb-0.5">Ukur Dasar</h5>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Berat, Tinggi, Suhu & IMT</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <label class="form-label">Berat Badan <span class="text-slate-400">(Kg)</span></label>
                    <input type="number" step="0.01" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}" class="form-input text-center text-xl font-black text-indigo-700" placeholder="0.00">
                </div>
                <div class="space-y-1">
                    <label class="form-label">Tinggi/Panjang <span class="text-slate-400">(Cm)</span></label>
                    <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan') }}" class="form-input text-center text-xl font-black text-indigo-700" placeholder="0.0">
                </div>
                <div class="space-y-1">
                    <label class="form-label">Suhu Tubuh <span class="text-slate-400">(°C)</span></label>
                    <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh') }}" class="form-input text-center text-xl font-black text-rose-600" placeholder="36.5">
                </div>
                
                {{-- Kalkulator IMT Cerdas --}}
                <div class="space-y-1 bg-slate-900 rounded-2xl border border-slate-800 p-3 text-center flex flex-col items-center justify-center shadow-inner relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-white/5 text-5xl"><i class="fas fa-calculator"></i></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Kalkulasi IMT</p>
                    <p id="imt-val" class="text-3xl font-black text-white font-mono relative z-10">0.0</p>
                    <span id="imt-kat" class="mt-1 px-3 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700 text-[9px] font-bold uppercase tracking-widest relative z-10">-</span>
                </div>
            </div>
        </div>

        {{-- =========================================================================
             STEP 3: FORM SPESIFIK KATEGORI
             ========================================================================= --}}
        
        {{-- A. FORM BALITA --}}
        <div id="form_balita" class="dynamic-section bg-white rounded-[2.5rem] border border-sky-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-sky-50/50 px-8 py-5 border-b border-sky-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg"><i class="fas fa-baby"></i></div>
                <h5 class="font-black text-sky-800 text-[14px] uppercase tracking-widest font-poppins">Pengukuran Khusus Balita</h5>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="form-label">Lingkar Kepala <span class="text-slate-400">(Cm)</span></label>
                    <input type="number" step="0.1" name="lingkar_kepala" value="{{ old('lingkar_kepala') }}" class="form-input text-center" placeholder="0.0">
                    <p class="text-[10px] font-bold text-slate-400 mt-1"><i class="fas fa-info-circle"></i> Penting untuk deteksi Mikrosefali.</p>
                </div>
                <div class="space-y-1">
                    <label class="form-label">Lingkar Lengan Atas (LiLA) <span class="text-slate-400">(Cm)</span></label>
                    <input type="number" step="0.1" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="form-input text-center" placeholder="0.0">
                </div>
            </div>
        </div>

        {{-- B. FORM IBU HAMIL --}}
        <div id="form_ibu_hamil" class="dynamic-section hidden-section bg-white rounded-[2.5rem] border border-pink-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-pink-50/50 px-8 py-5 border-b border-pink-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-lg"><i class="fas fa-female"></i></div>
                <h5 class="font-black text-pink-800 text-[14px] uppercase tracking-widest font-poppins">Pemeriksaan Ibu Hamil</h5>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <label class="form-label">Usia Kehamilan <span class="text-slate-400">(Minggu)</span></label>
                    <input type="number" name="usia_kehamilan" value="{{ old('usia_kehamilan') }}" class="form-input text-center" placeholder="Misal: 12">
                </div>
                <div class="space-y-1">
                    <label class="form-label">Tensi Darah <span class="text-slate-400">(mmHg)</span></label>
                    <input type="text" name="tensi_darah" value="{{ old('tensi_darah') }}" class="form-input text-center" placeholder="Contoh: 120/80">
                </div>
                <div class="space-y-1">
                    <label class="form-label">Lingkar Lengan (LiLA) <span class="text-slate-400">(Cm)</span></label>
                    <input type="number" step="0.1" id="lila_bumil" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="form-input text-center" placeholder="0.0">
                </div>
            </div>
        </div>

        {{-- C. FORM REMAJA --}}
        <div id="form_remaja" class="dynamic-section hidden-section bg-white rounded-[2.5rem] border border-blue-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-blue-50/50 px-8 py-5 border-b border-blue-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg"><i class="fas fa-user-graduate"></i></div>
                <h5 class="font-black text-blue-800 text-[14px] uppercase tracking-widest font-poppins">Pemeriksaan Remaja</h5>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <label class="form-label">Tensi Darah <span class="text-slate-400">(mmHg)</span></label>
                    <input type="text" name="tensi_darah" value="{{ old('tensi_darah') }}" class="form-input text-center" placeholder="Contoh: 110/80">
                </div>
                <div class="space-y-1">
                    <label class="form-label">Gula Darah <span class="text-slate-400">(mg/dL)</span></label>
                    <input type="number" step="0.1" name="gula_darah" value="{{ old('gula_darah') }}" class="form-input text-center" placeholder="0.0">
                </div>
                <div class="space-y-1">
                    <label class="form-label">LILA <span class="text-slate-400">(Khusus Putri - Cm)</span></label>
                    <input type="number" step="0.1" id="lila_remaja" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="form-input text-center" placeholder="0.0">
                </div>
            </div>
        </div>

        {{-- D. FORM LANSIA --}}
        <div id="form_lansia" class="dynamic-section hidden-section bg-white rounded-[2.5rem] border border-emerald-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-emerald-50/50 px-8 py-5 border-b border-emerald-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg"><i class="fas fa-wheelchair"></i></div>
                <h5 class="font-black text-emerald-800 text-[14px] uppercase tracking-widest font-poppins">Cek Medis Lansia</h5>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-1"><label class="form-label">Tensi <span class="text-slate-400">(mmHg)</span></label><input type="text" name="tensi_darah" value="{{ old('tensi_darah') }}" class="form-input text-center" placeholder="130/90"></div>
                <div class="space-y-1"><label class="form-label">Gula Darah <span class="text-slate-400">(mg/dL)</span></label><input type="number" step="0.1" name="gula_darah" value="{{ old('gula_darah') }}" class="form-input text-center" placeholder="0.0"></div>
                <div class="space-y-1"><label class="form-label">Kolesterol <span class="text-slate-400">(mg/dL)</span></label><input type="number" name="kolesterol" value="{{ old('kolesterol') }}" class="form-input text-center" placeholder="0"></div>
                <div class="space-y-1"><label class="form-label">Asam Urat <span class="text-slate-400">(mg/dL)</span></label><input type="number" step="0.1" name="asam_urat" value="{{ old('asam_urat') }}" class="form-input text-center" placeholder="0.0"></div>
            </div>
        </div>

        {{-- KOTAK PERINGATAN KEK (Otomatis Muncul) --}}
        <div id="warn_kek" class="mb-8 bg-rose-50 border-2 border-rose-400 rounded-2xl p-6 hidden items-center gap-5 shadow-lg shadow-rose-500/20 transform transition-all duration-500">
            <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 text-3xl shrink-0 animate-pulse"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <p class="text-[13px] font-black text-rose-800 uppercase tracking-widest mb-1">Peringatan Medis: Risiko KEK</p>
                <p class="text-sm font-bold text-rose-600">Nilai Lingkar Lengan Atas (LiLA) pasien kurang dari 23.5 cm. Pasien berisiko mengalami Kurang Energi Kronis. Harap beri catatan khusus untuk Bidan!</p>
            </div>
        </div>

        {{-- =========================================================================
             STEP 4: CATATAN & KELUHAN
             ========================================================================= --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-8 animate-slide-up delay-300">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner border border-amber-100"><i class="fas fa-comment-medical"></i></div>
                <div>
                    <h5 class="font-black text-slate-800 text-[15px] uppercase tracking-widest font-poppins mb-0.5">Keluhan & Catatan</h5>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Informasi Tambahan Lapangan</p>
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <label class="form-label">Keluhan Utama Pasien <span class="text-slate-400">(Opsional)</span></label>
                    <textarea name="keluhan" rows="3" class="form-input resize-none" placeholder="Tanyakan apa yang dirasakan pasien hari ini...">{{ old('keluhan') }}</textarea>
                </div>
                <div class="space-y-1">
                    <label class="form-label">Catatan Kader <span class="text-slate-400">(Opsional)</span></label>
                    <textarea name="catatan_kader" rows="3" class="form-input resize-none" placeholder="Catatan Anda untuk Bidan Desa...">{{ old('catatan_kader') }}</textarea>
                </div>
            </div>
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4 pb-10">
            <a href="{{ route('kader.pemeriksaan.index') }}" class="spa-route w-full sm:w-auto px-10 py-4 rounded-[1.25rem] font-black text-slate-500 bg-white border-2 border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all uppercase text-[12px] tracking-widest text-center flex items-center justify-center gap-2">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 rounded-[1.25rem] font-black text-white bg-indigo-600 hover:bg-indigo-700 hover:-translate-y-1 transition-all shadow-[0_15px_30px_rgba(79,70,229,0.3)] uppercase text-[12px] tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Simpan & Ajukan ke Bidan
            </button>
        </div>
    </form>
</div>

<script>
    // 1. STATE MEMORY
    const oldPasienId = "{{ old('pasien_id', $pasien_id_awal) }}";

    // 2. FETCH API NAMA PASIEN
    async function loadPasien(kategori) {
        const select = document.getElementById('pasien_id');
        select.innerHTML = '<option value="">-- Menghubungkan ke Database... --</option>';
        
        try {
            const url = `{{ route('kader.pemeriksaan.api') }}?kategori=${kategori}`;
            const response = await fetch(url);
            const res = await response.json();
            
            select.innerHTML = '<option value="">-- Pilih Warga / Pasien --</option>';
            if(res.status === 'success' && res.data.length > 0) {
                res.data.forEach(p => {
                    const isSelected = (oldPasienId == p.id) ? 'selected' : '';
                    const jk = p.jenis_kelamin || '';
                    select.innerHTML += `<option value="${p.id}" data-jk="${jk}" ${isSelected}>${p.nama} - NIK: ${p.nik || 'Belum Ada'}</option>`;
                });
            } else {
                select.innerHTML = '<option value="">-- Database Kosong pada Kategori Ini --</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">-- Koneksi API Gagal --</option>';
        }
    }

    // 3. LOGIKA TOGGLE FORM HTML (Aman & Detail)
    function toggleForms(kategori) {
        ['form_balita', 'form_ibu_hamil', 'form_remaja', 'form_lansia'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.classList.add('hidden-section');
                const inputs = el.querySelectorAll('input');
                inputs.forEach(inp => { if(inp.dataset.name) { inp.name = ''; } });
            }
        });

        const activeFormId = 'form_' + kategori;
        const activeEl = document.getElementById(activeFormId);
        if (activeEl) {
            activeEl.classList.remove('hidden-section');
            const inputs = activeEl.querySelectorAll('input');
            inputs.forEach(inp => { 
                if(!inp.dataset.name) inp.dataset.name = inp.name; 
                inp.name = inp.dataset.name; 
            });
        }
    }

    // 4. TRIGGER PERUBAHAN
    document.getElementById('kategori_pasien').addEventListener('change', function() {
        loadPasien(this.value);
        toggleForms(this.value);
        document.getElementById('warn_kek').classList.add('hidden');
    });

    const initKat = document.getElementById('kategori_pasien').value;
    loadPasien(initKat);
    toggleForms(initKat);

    // 5. KALKULATOR IMT
    const bbInput = document.getElementById('berat_badan'), tbInput = document.getElementById('tinggi_badan');
    function hitungIMT() {
        const bb = parseFloat(bbInput.value), tb = parseFloat(tbInput.value) / 100;
        if(bb > 0 && tb > 0) {
            const imt = (bb / (tb * tb)).toFixed(1);
            let label = 'NORMAL', color = 'bg-emerald-500 text-white';
            if(imt < 18.5) { label = 'KURUS'; color = 'bg-amber-500 text-white border-none'; }
            else if(imt >= 25) { label = 'OBESITAS'; color = 'bg-rose-500 text-white border-none'; }
            
            document.getElementById('imt-val').textContent = imt;
            const imtKatEl = document.getElementById('imt-kat');
            imtKatEl.textContent = label;
            imtKatEl.className = `mt-2 px-4 py-1.5 rounded-full border border-slate-700 text-[10px] font-black uppercase tracking-widest relative z-10 transition-colors ${color}`;
        }
    }
    bbInput.addEventListener('input', hitungIMT); tbInput.addEventListener('input', hitungIMT); 
    if(bbInput.value && tbInput.value) hitungIMT();

    // 6. DETEKSI DINI RISIKO KEK
    function cekLila(e) {
        const val = parseFloat(e.target.value);
        const kat = document.getElementById('kategori_pasien').value;
        const select = document.getElementById('pasien_id');
        const jk = select.options[select.selectedIndex]?.dataset.jk;
        const warn = document.getElementById('warn_kek');

        if (val > 0 && val < 23.5 && (kat === 'ibu_hamil' || (kat === 'remaja' && jk === 'P'))) {
            warn.classList.remove('hidden');
            warn.classList.add('flex');
        } else {
            warn.classList.add('hidden');
            warn.classList.remove('flex');
        }
    }
    document.getElementById('lila_bumil')?.addEventListener('input', cekLila);
    document.getElementById('lila_remaja')?.addEventListener('input', cekLila);

    // 7. UX SUBMIT FORM
    document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
        const pasienId = document.getElementById('pasien_id').value;
        if(!pasienId) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Aksi Ditolak', text: 'Nama Pasien wajib dipilih sebelum menyimpan.', confirmButtonColor: '#4f46e5', customClass: { popup: 'rounded-[28px]' } });
            return;
        }
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Mengamankan Data...';
        btn.classList.add('opacity-75', 'cursor-wait');
    });
</script>
@endsection