@extends('layouts.kader')

@section('title', 'Tambah Data Balita')
@section('page-name', 'Pendaftaran Balita')

@push('styles')
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
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
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
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Silakan lengkapi data profil anak dan identitas orang tua. NIK Ibu berfungsi sebagai kunci integrasi otomatis ke akun Web Warga.</p>
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
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-indigo-50" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[20px] border border-slate-100">
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
                    <div class="p-4 bg-white border border-rose-100 rounded-2xl shadow-sm">
                        <label class="form-label text-rose-500"><i class="fas fa-female mr-1"></i> NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu') }}" required placeholder="16 Digit NIK Ibu" class="form-input bg-slate-50 focus:bg-white @error('nik_ibu') form-error @enderror">
                        @error('nik_ibu') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                        
                        <label class="form-label text-rose-500 mt-4"><i class="fas fa-id-card-alt mr-1"></i> Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required placeholder="Nama Lengkap Ibu" class="form-input bg-slate-50 focus:bg-white">
                    </div>
                    
                    <div class="p-4 bg-white border border-sky-100 rounded-2xl shadow-sm">
                        <label class="form-label text-sky-600"><i class="fas fa-male mr-1"></i> NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah') }}" placeholder="16 Digit NIK Ayah" class="form-input bg-slate-50 focus:bg-white">
                        
                        <label class="form-label text-sky-600 mt-4"><i class="fas fa-id-card-alt mr-1"></i> Nama Ayah (Opsional)</label>
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
        <div class="p-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg border border-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[11px] font-bold text-slate-500 px-4 hidden sm:block"><i class="fas fa-shield-alt text-emerald-500 mr-1 text-lg align-middle"></i> Data dienkripsi & terhubung otomatis ke akun warga.</p>
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-black text-[13px] rounded-xl hover:from-indigo-500 hover:to-violet-500 shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                    <i class="fas fa-save text-lg"></i> Simpan Data Anak
                </button>
            </div>
        </div>
        
    </form>
</div>

<script>
    // Set max date to today
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    
    // Limit NIK input to 16 digits
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });

    // Auto-Calculate Age Logic (JS)
    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        const today = new Date();
        
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) {
            months--;
        }

        if(months < 0) { helper.innerHTML = '<div class="mt-2 text-rose-500 text-xs font-bold"><i class="fas fa-exclamation-triangle"></i> Tanggal tidak valid</div>'; return; }

        let text = '';
        let badge = '';
        if(months < 12) {
            text = months === 0 ? '0 Bulan (Baru Lahir)' : `${months} Bulan`;
            badge = '<span class="bg-sky-500 text-white px-2 py-0.5 rounded text-[10px] font-black uppercase ml-2 shadow-sm">Kategori Bayi</span>';
        } else {
            const y = Math.floor(months / 12);
            const m = months % 12;
            text = m > 0 ? `${y} Tahun ${m} Bulan` : `${y} Tahun`;
            badge = '<span class="bg-rose-500 text-white px-2 py-0.5 rounded text-[10px] font-black uppercase ml-2 shadow-sm">Kategori Balita</span>';
        }

        helper.innerHTML = `<div class="mt-3 inline-flex items-center bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-xl text-xs font-bold text-indigo-700 shadow-sm animate-slide-up"><i class="fas fa-info-circle text-indigo-500 mr-2 text-lg"></i> Usia tercatat: ${text} ${badge}</div>`;
    }

    // Call on load in case of validation back
    if(document.getElementById('tanggal_lahir').value) {
        calculateAge();
    }

    const hideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Data Anak'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    
    const showLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);
    document.getElementById('formBalita').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        showLoader();
    });
</script>
@endsection