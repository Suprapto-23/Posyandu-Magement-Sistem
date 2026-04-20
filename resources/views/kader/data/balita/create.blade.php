@extends('layouts.kader')

@section('title', 'Tambah Data Balita')
@section('page-name', 'Pendaftaran Balita')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
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
    .form-input::placeholder { color: #94a3b8; font-weight: 500; }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; box-shadow: 0 4px 15px -3px rgba(244, 63, 94, 0.15) !important; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }

    /* Custom SweetAlert Styles */
    .swal-custom-popup { border-radius: 24px !important; padding: 2rem !important; }
    .swal-custom-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; color: #0f172a !important; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/80 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-baby-carriage text-indigo-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-indigo-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MENYIAPKAN FORMULIR...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-rose-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[24px] bg-gradient-to-br from-indigo-100 to-violet-100 text-indigo-600 mb-5 shadow-sm border border-indigo-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-baby-carriage text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Registrasi Anak Baru</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Silakan lengkapi data profil anak. Batas maksimal usia pendaftaran di modul ini adalah <strong>59 Bulan (4 Tahun 11 Bulan)</strong>.</p>
    </div>

    <form action="{{ route('kader.data.balita.store') }}" method="POST" id="formBalita" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            
            {{-- KOLOM 1: IDENTITAS ANAK --}}
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-bl-full pointer-events-none"></div>
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Profil Anak</h3>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="form-label">Nama Lengkap Anak <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Muhammad Al-Fatih" class="form-input focus:ring-4 focus:ring-indigo-50 @error('nama_lengkap') form-error @enderror">
                        @error('nama_lengkap') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">NIK Anak (Opsional)</label>
                            <input type="number" name="nik" value="{{ old('nik') }}" placeholder="16 Digit Angka" class="form-input focus:ring-4 focus:ring-indigo-50 @error('nik') form-error @enderror">
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input cursor-pointer focus:ring-4 focus:ring-indigo-50 @error('jenis_kelamin') form-error @enderror">
                                <option value="">-- Pilih Gender --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota Kelahiran" class="form-input focus:ring-4 focus:ring-indigo-50">
                        </div>
                        <div>
                            <label class="form-label text-indigo-600">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-indigo-50" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    {{-- ENGINE: VISUALISASI UMUR & BLOKIR OTOMATIS --}}
                    <div id="ageValidationBox" class="hidden p-5 rounded-[20px] border transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div id="ageIcon" class="w-12 h-12 rounded-[14px] flex items-center justify-center text-2xl shadow-inner shrink-0"></div>
                            <div>
                                <p id="ageText" class="text-[14px] font-black leading-tight mb-1"></p>
                                <p id="categoryLabel" class="text-[11px] font-bold uppercase tracking-widest"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[20px] border border-slate-100 mt-4">
                        <div>
                            <label class="form-label text-indigo-600">Berat Lahir (kg)</label>
                            <input type="number" step="0.01" name="berat_lahir" value="{{ old('berat_lahir') }}" placeholder="Misal: 3.2" class="form-input bg-white">
                        </div>
                        <div>
                            <label class="form-label text-emerald-600">Panjang Lahir (cm)</label>
                            <input type="number" step="0.01" name="panjang_lahir" value="{{ old('panjang_lahir') }}" placeholder="Misal: 50" class="form-input bg-white">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: DATA ORANG TUA --}}
            <div class="lg:col-span-5 bg-slate-50/90 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute right-0 top-0 w-32 h-32 bg-rose-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                
                <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                    <span class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center font-black shadow-md">2</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Data Keluarga</h3>
                </div>

                <div class="space-y-6 relative z-10 flex-1">
                    <div class="p-5 bg-white border border-rose-100 rounded-[20px] shadow-sm">
                        <label class="form-label text-rose-500"><i class="fas fa-female mr-1"></i> NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu') }}" required placeholder="16 Digit NIK Ibu" class="form-input bg-slate-50 focus:bg-white @error('nik_ibu') form-error @enderror mb-4">
                        @error('nik_ibu') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                        
                        <label class="form-label text-rose-500"><i class="fas fa-id-card-alt mr-1"></i> Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required placeholder="Nama Lengkap Ibu" class="form-input bg-slate-50 focus:bg-white">
                    </div>
                    
                    <div class="p-5 bg-white border border-sky-100 rounded-[20px] shadow-sm">
                        <label class="form-label text-sky-600"><i class="fas fa-male mr-1"></i> NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah') }}" placeholder="16 Digit NIK Ayah" class="form-input bg-slate-50 focus:bg-white mb-4">
                        
                        <label class="form-label text-sky-600"><i class="fas fa-id-card-alt mr-1"></i> Nama Ayah (Opsional)</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" placeholder="Nama Lengkap Ayah" class="form-input bg-slate-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="form-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required placeholder="Alamat lengkap RT/RW..." class="form-input bg-white resize-none focus:ring-4 focus:ring-indigo-50">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- ACTION BUTTONS --}}
        <div class="mt-8 bg-white/80 backdrop-blur-xl border border-slate-200 p-6 rounded-[28px] shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6 sticky bottom-6 z-30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-baby-carriage"></i></div>
                <p class="text-xs font-bold text-slate-500 leading-relaxed uppercase tracking-wider hidden sm:block">Pastikan data balita akurat. Data fisik akan divalidasi oleh Bidan.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-full sm:w-auto px-8 py-4 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="btn-press w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-black text-[13px] rounded-xl hover:shadow-[0_8px_20px_rgba(79,70,229,0.3)] transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save text-lg"></i> Simpan Registrasi
                </button>
            </div>
        </div>
        
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Batasi input tanggal maksimal hari ini
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    
    // Limit NIK input
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });

    const birthInput = document.getElementById('tanggal_lahir');
    const ageBox = document.getElementById('ageValidationBox');
    const ageText = document.getElementById('ageText');
    const catLabel = document.getElementById('categoryLabel');
    const ageIcon = document.getElementById('ageIcon');
    const btnSubmit = document.getElementById('btnSubmit');

    function calculateAge() {
        const dobInput = birthInput.value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { ageBox.classList.add('hidden'); return; }

        const dob = new Date(dobInput);
        const today = new Date();
        
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) { months--; }

        ageBox.classList.remove('hidden');
        
        // LOGIKA KUNCI: USIA MAKSIMAL 59 BULAN
        if (months < 0) {
            setError("Tanggal Tidak Valid!", "Masukan dari masa depan", "fa-exclamation-triangle");
        } 
        else if (months >= 60) {
            const years = Math.floor(months / 12);
            const remainingMonths = months % 12;
            
            setError(`Usia: ${years} Thn ${remainingMonths} Bln`, "DITOLAK: MELEBIHI BATAS BALITA (MAX 59 BLN)", "fa-ban");
            
            // Pop-up Smooth & Elegan
            Swal.fire({
                icon: 'error',
                title: 'Usia Tidak Memenuhi Syarat',
                html: `<p class="text-sm font-medium text-slate-600 mt-2">Sistem mendeteksi anak ini sudah berusia <b>${years} tahun lebih</b>.</p><p class="text-sm font-medium text-slate-600 mt-2">Sesuai standar Kemenkes, modul ini khusus untuk anak usia <b>0 - 59 Bulan</b>. Silakan daftarkan warga ini di Modul Remaja/Umum.</p>`,
                confirmButtonText: 'Baik, Mengerti',
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'swal-custom-popup', title: 'swal-custom-title' }
            });
        } 
        else {
            const years = Math.floor(months / 12);
            const remainingMonths = months % 12;
            const category = months < 12 ? 'BAYI (0-11 Bulan)' : 'BALITA (12-59 Bulan)';
            let ageStr = months === 0 ? '0 Bulan (Baru Lahir)' : `${months} Bulan (${years} Thn ${remainingMonths} Bln)`;
            
            setSuccess(`Tercatat: ${ageStr}`, `KATEGORI ${category} TERVERIFIKASI`, "fa-check-circle");
        }
    }

    function setError(text, sub, icon) {
        ageBox.className = "p-5 rounded-[20px] border-2 border-rose-300 bg-rose-50 text-rose-700 mt-4 animate-pulse";
        ageText.textContent = text;
        catLabel.textContent = sub;
        ageIcon.className = "w-12 h-12 rounded-[14px] flex items-center justify-center text-2xl shadow-sm bg-rose-600 text-white shrink-0";
        ageIcon.innerHTML = `<i class="fas ${icon}"></i>`;
        birthInput.classList.add('form-error');
        
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
        btnSubmit.innerHTML = '<i class="fas fa-lock text-lg"></i> Usia Ditolak';
    }

    function setSuccess(text, sub, icon) {
        ageBox.className = "p-5 rounded-[20px] border-2 border-emerald-300 bg-emerald-50 text-emerald-800 mt-4 transition-all duration-300";
        ageText.textContent = text;
        catLabel.textContent = sub;
        ageIcon.className = "w-12 h-12 rounded-[14px] flex items-center justify-center text-2xl shadow-sm bg-emerald-500 text-white shrink-0";
        ageIcon.innerHTML = `<i class="fas ${icon}"></i>`;
        birthInput.classList.remove('form-error');
        
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');
        btnSubmit.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Registrasi';
    }

    if(document.getElementById('tanggal_lahir').value) { calculateAge(); }

    const hideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(!btn.disabled) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Registrasi'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    
    const showLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);
    
    document.getElementById('formBalita').addEventListener('submit', function(e) {
        if(btnSubmit.disabled) { e.preventDefault(); return; }
        btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses...';
        btnSubmit.classList.add('opacity-75', 'cursor-wait');
        showLoader();
    });
</script>
@endpush
@endsection