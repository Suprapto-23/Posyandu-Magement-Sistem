@extends('layouts.kader')

@section('title', 'Input Pemeriksaan')
@section('page-name', 'Input Pengukuran Fisik')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .form-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none;
        transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .form-input:focus {
        background-color: #ffffff; border-color: #4f46e5;
        box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-2px);
    }
    .form-input:disabled { background-color: #f1f5f9; color: #94a3b8; cursor: not-allowed; border-style: dashed; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
    .dynamic-field { display: none; opacity: 0; transition: opacity 0.4s ease-in-out; }
    .dynamic-field.show { display: block; opacity: 1; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-stethoscope text-indigo-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-indigo-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase" id="loaderText">MENYIAPKAN FORMULIR...</p>
</div>

<div class="max-w-[1000px] mx-auto animate-slide-up relative pb-12 z-10">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="smooth-route w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 via-violet-600 to-indigo-800 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(79,70,229,0.4)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="w-24 h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform -rotate-6 hover:rotate-0 transition-transform"><i class="fas fa-notes-medical"></i></div>
        <div class="text-center md:text-left">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Form Cerdas Auto-Fill
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Input Pemeriksaan</h1>
            <p class="text-indigo-100 font-medium text-[14px] max-w-xl">Pilih kategori, lalu pilih nama pasien. Kolom ukuran fisik akan beradaptasi secara instan tanpa perlu loading halaman.</p>
        </div>
    </div>

    <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="formPemeriksaan" class="relative z-10">
        @csrf
        
        <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col mb-8">
            
            <div class="p-8 md:p-12 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg border border-indigo-200">1</div>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Target</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-6">
                    <div>
                        <label class="form-label">Kategori Pasien <span class="text-rose-500">*</span></label>
                        <select name="kategori_pasien" id="kategoriSelect" required class="form-input cursor-pointer" onchange="updateFormRealtime()">
                            <option value="">-- Tentukan Kategori --</option>
                            <option value="balita">👶 Anak & Balita</option>
                            <option value="remaja">🎓 Usia Remaja</option>
                            <option value="lansia">👴 Lanjut Usia (Lansia)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_periksa" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}" class="form-input cursor-pointer bg-white">
                    </div>
                </div>

                <div>
                    <label class="form-label">Cari & Pilih Nama Pasien <span class="text-rose-500">*</span></label>
                    <select name="pasien_id" id="pasienSelect" required class="form-input transition-all" disabled>
                        <option value="">-- Pilih kategori di atas terlebih dahulu --</option>
                    </select>
                </div>
            </div>

            <div class="p-8 md:p-12 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-lg border border-emerald-200">2</div>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Parameter Pengukuran</h3>
                    </div>
                    <span id="labelKategori" class="px-4 py-2 bg-slate-100 text-slate-500 text-[11px] font-black uppercase rounded-xl border border-slate-200 tracking-widest shadow-sm">Menunggu Pilihan</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="col-span-1">
                        <label class="form-label">Berat B. (kg) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="berat_badan" required placeholder="0.0" class="form-input text-center text-lg bg-white">
                    </div>
                    <div class="col-span-1">
                        <label class="form-label">Tinggi B. (cm) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="tinggi_badan" required placeholder="0.0" class="form-input text-center text-lg bg-white">
                    </div>
                    
                    <div class="col-span-1 dynamic-field" data-kategori="balita">
                        <label class="form-label text-rose-600">L. Kepala (cm)</label>
                        <input type="number" step="0.1" name="lingkar_kepala" placeholder="0.0" class="form-input text-center border-rose-200 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30">
                    </div>

                    <div class="col-span-1 dynamic-field" data-kategori="balita,remaja">
                        <label class="form-label text-indigo-600">L. Lengan (cm)</label>
                        <input type="number" step="0.1" name="lingkar_lengan" placeholder="0.0" class="form-input text-center border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500/20 bg-indigo-50/30">
                    </div>

                    <div class="col-span-1 dynamic-field" data-kategori="remaja,lansia">
                        <label class="form-label">L. Perut (cm)</label>
                        <input type="number" step="0.1" name="lingkar_perut" placeholder="0.0" class="form-input text-center bg-white">
                    </div>
                    
                    <div class="col-span-1 dynamic-field" data-kategori="remaja,lansia">
                        <label class="form-label text-rose-600">Tensi Darah</label>
                        <input type="text" name="tekanan_darah" placeholder="120/80" class="form-input text-center border-rose-200 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30">
                    </div>
                    
                    <div class="col-span-2 dynamic-field" data-kategori="remaja">
                        <label class="form-label text-sky-600">Hemoglobin (Hb) - Cek Anemia</label>
                        <input type="number" step="0.1" name="hemoglobin" placeholder="Kadar g/dL" class="form-input border-sky-200 focus:border-sky-500 bg-sky-50/30 focus:ring-sky-500/20">
                    </div>
                    
                    <div class="col-span-2 sm:col-span-4 dynamic-field" data-kategori="lansia">
                        <div class="p-6 rounded-[24px] bg-emerald-50/50 border border-emerald-100">
                            <h4 class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fas fa-vial"></i> Pengecekan Lab Lansia</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label class="form-label text-emerald-700">Gula Darah (mg/dL)</label>
                                    <input type="text" name="gula_darah" placeholder="0.0" class="form-input text-center border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500/20 bg-white">
                                </div>
                                <div>
                                    <label class="form-label text-emerald-700">Asam Urat (mg/dL)</label>
                                    <input type="number" step="0.1" name="asam_urat" placeholder="0.0" class="form-input text-center border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500/20 bg-white">
                                </div>
                                <div>
                                    <label class="form-label text-emerald-700">Kolesterol (mg/dL)</label>
                                    <input type="number" name="kolesterol" placeholder="0.0" class="form-input text-center border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500/20 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-8">
                    <label class="form-label flex items-center gap-2"><i class="fas fa-comment-medical text-amber-500"></i> Keluhan Utama Pasien (Opsional)</label>
                    <textarea name="keluhan" rows="3" placeholder="Contoh: Pasien mengeluh sering pusing dan demam..." class="form-input resize-none bg-white"></textarea>
                </div>
            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-end gap-4">
                <a href="{{ route('kader.pemeriksaan.index') }}" onclick="showLoader()" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-600 font-black text-[13px] rounded-xl hover:bg-slate-100 transition-colors text-center uppercase tracking-widest shadow-sm">
                    Batalkan
                </a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white font-black text-[13px] rounded-xl hover:bg-indigo-700 shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-paper-plane"></i> Kirim ke Bidan
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    // 1. Terima JSON Data dari Controller ($semuaPasien)
    const masterDataPasien = @json($semuaPasien ?? []); 

    // 2. Fungsi Smart Auto-Fill Javascript
    function updateFormRealtime() {
        const kategori = document.getElementById('kategoriSelect').value;
        const pasienSelect = document.getElementById('pasienSelect');
        const labelKategori = document.getElementById('labelKategori');
        const dynamicFields = document.querySelectorAll('.dynamic-field');

        // Update Label
        if (kategori === 'balita') {
            labelKategori.textContent = 'Parameter Bayi/Balita';
            labelKategori.className = 'px-4 py-2 bg-rose-100 text-rose-600 text-[11px] font-black uppercase rounded-xl border border-rose-200 tracking-widest transition-all';
        } else if (kategori === 'remaja') {
            labelKategori.textContent = 'Parameter Remaja';
            labelKategori.className = 'px-4 py-2 bg-sky-100 text-sky-600 text-[11px] font-black uppercase rounded-xl border border-sky-200 tracking-widest transition-all';
        } else if (kategori === 'lansia') {
            labelKategori.textContent = 'Parameter Lansia / Manula';
            labelKategori.className = 'px-4 py-2 bg-emerald-100 text-emerald-600 text-[11px] font-black uppercase rounded-xl border border-emerald-200 tracking-widest transition-all';
        } else {
            labelKategori.textContent = 'Pilih Kategori Dulu';
            labelKategori.className = 'px-4 py-2 bg-slate-100 text-slate-500 text-[11px] font-black uppercase rounded-xl border border-slate-200 tracking-widest transition-all';
        }

        // Filter Dropdown Nama Pasien (Murni JS, Tanpa Loading Layar!)
        pasienSelect.innerHTML = ''; 
        if (kategori === '') {
            pasienSelect.innerHTML = '<option value="">-- Pilih kategori di atas terlebih dahulu --</option>';
            pasienSelect.disabled = true;
            pasienSelect.classList.remove('bg-white');
        } else {
            pasienSelect.disabled = false;
            pasienSelect.classList.add('bg-white');
            let optionsHtml = '<option value="">-- Silakan Pilih Nama Pasien --</option>';
            
            const pasienDifilter = masterDataPasien.filter(p => p.kategori === kategori);
            if (pasienDifilter.length > 0) {
                pasienDifilter.forEach(p => {
                    optionsHtml += `<option value="${p.id}">${p.nama} (NIK/ID: ${p.nik || '-'})</option>`;
                });
            } else {
                optionsHtml = '<option value="">(Tidak ada pasien terdaftar di sistem untuk kategori ini)</option>';
            }
            pasienSelect.innerHTML = optionsHtml;
        }

        // Tampilkan/Sembunyikan Kolom Parameter Fisik
        dynamicFields.forEach(field => {
            const targetCategories = field.getAttribute('data-kategori').split(',');
            if (kategori && targetCategories.includes(kategori)) {
                field.classList.add('show');
            } else {
                field.classList.remove('show');
                const inputs = field.querySelectorAll('input');
                inputs.forEach(input => input.value = ''); 
            }
        });
    }

    // 3. Matikan Loader Awal Layar Putih (Fix Freeze Bug)
    const forceHideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) {
            l.classList.remove('opacity-100', 'pointer-events-auto');
            l.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => l.style.display = 'none', 300);
        }
    };

    // Panggil saat layar pertama kali dibuka
    document.addEventListener('DOMContentLoaded', () => {
        forceHideLoader();
        updateFormRealtime(); // Render awal form (kategori kosong)
    });
    window.addEventListener('pageshow', forceHideLoader);

    // Animasi Submit Form
    document.getElementById('formPemeriksaan').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    });
</script>
@endpush
@endsection