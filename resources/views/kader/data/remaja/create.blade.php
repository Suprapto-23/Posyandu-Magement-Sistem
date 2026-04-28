@extends('layouts.kader')

@section('title', 'Tambah Data Remaja')
@section('page-name', 'Pendaftaran Remaja')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* FORM INPUT CRM NEXUS */
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
    .form-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #1e293b;
        font-size: 0.875rem; border-radius: 16px; padding: 1rem 1.25rem; outline: none;
        transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.01);
    }
    .form-input:focus {
        background-color: #ffffff; border-color: #4f46e5;
        box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-2px);
    }
    .form-input::placeholder { color: #94a3b8; font-weight: 500; }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; box-shadow: 0 4px 15px -3px rgba(244, 63, 94, 0.15) !important; }
    
    /* KARTU KACA (GLASSMORPHISM) */
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }

    /* SWEETALERT CUSTOM KAPSUL NEXUS */
    div:where(.swal2-container) { z-index: 10000 !important; backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(16px) !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; border: 1px solid rgba(255,255,255,0.5) !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
    .btn-nexus-primary { background: #4f46e5 !important; color: #ffffff !important; border-radius: 100px !important; padding: 12px 28px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; box-shadow: 0 8px 20px rgba(79,70,229,0.3) !important; transition: all 0.3s ease !important; }
</style>
@endpush

@section('content')
{{-- PRELOADER --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-user-graduate text-indigo-600 text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-indigo-900 font-black tracking-widest text-[11px] animate-pulse uppercase">MENYIAPKAN FORMULIR...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-12">
    
    {{-- AURA BACKGROUND --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.remaja.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- HEADER FORM --}}
    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[20px] bg-gradient-to-br from-indigo-100 to-blue-100 text-indigo-600 mb-5 shadow-sm border border-indigo-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-user-graduate text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Registrasi Remaja</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Input data master peserta Posyandu Remaja secara presisi. NIK akan digunakan sebagai kunci integrasi akun Warga.</p>
    </div>

    {{-- FORM UTAMA --}}
    <form action="{{ route('kader.data.remaja.store') }}" method="POST" id="formRemaja" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- KOLOM 1: IDENTITAS (7 KOLOM) --}}
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-[14px] bg-indigo-600 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Profil Remaja</h3>
                </div>
                
                <div class="space-y-6 flex-1">
                    <div>
                        <label class="form-label">NIK Remaja (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik') }}" required placeholder="16 Digit NIK KTP" class="form-input @error('nik') form-error @enderror">
                        @error('nik') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai Kartu Identitas" class="form-input @error('nama_lengkap') form-error @enderror">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota Kelahiran" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-input cursor-pointer" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="form-input cursor-pointer">
                            <option value="">-- Pilih Gender --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="2" required placeholder="Alamat lengkap RT/RW..." class="form-input resize-none">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: AKADEMIK & WALI (5 KOLOM) --}}
            <div class="lg:col-span-5 flex flex-col gap-8">
                
                {{-- KARTU AKADEMIK --}}
                <div class="bg-indigo-50/80 rounded-[32px] border border-indigo-100 shadow-[0_10px_40px_-10px_rgba(79,70,229,0.03)] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                    
                    <div class="flex items-center gap-4 mb-8 border-b border-indigo-200 pb-5 relative z-10">
                        <span class="w-10 h-10 rounded-[14px] bg-indigo-600 text-white flex items-center justify-center font-black shadow-md">2</span>
                        <h3 class="text-xl font-black text-indigo-900 font-poppins">Info Akademik</h3>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div class="p-4 bg-white border border-indigo-100 rounded-2xl shadow-sm">
                            <label class="form-label text-indigo-600"><i class="fas fa-school mr-1"></i> Nama Sekolah (Opsional)</label>
                            <input type="text" name="sekolah" value="{{ old('sekolah') }}" placeholder="SMP / SMA N 1..." class="form-input bg-slate-50 focus:bg-white focus:border-indigo-400 mb-4">
                            
                            <label class="form-label text-indigo-600"><i class="fas fa-chalkboard mr-1"></i> Tingkat Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Misal: VIII-A" class="form-input bg-slate-50 focus:bg-white focus:border-indigo-400">
                        </div>
                    </div>
                </div>

                {{-- KARTU ORANG TUA --}}
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex-1 flex flex-col">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-[14px] bg-slate-400 text-white flex items-center justify-center font-black shadow-md">3</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Data Orang Tua</h3>
                    </div>

                    <div class="space-y-6 flex-1">
                        <div>
                            <label class="form-label">Nama Lengkap Wali <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required placeholder="Nama Ibu atau Ayah" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">No. HP Keluarga (WhatsApp)</label>
                            <input type="number" name="telepon_ortu" value="{{ old('telepon_ortu') }}" placeholder="Contoh: 0812xxxx" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-input cursor-pointer">
                                <option value="">-- Belum Tahu --</option>
                                @foreach(['A','B','AB','O'] as $gol)
                                    <option value="{{ $gol }}" {{ old('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- ACTION BUTTONS --}}
        <div class="mt-8 bg-white border border-slate-200 p-6 md:p-8 rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row items-center justify-between gap-6 relative z-30">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-[16px] bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-check-circle"></i></div>
                <div class="hidden sm:block">
                    <h4 class="text-[14px] font-black text-slate-800 font-poppins mb-0.5">Konfirmasi Registrasi</h4>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">Pastikan NIK akurat agar sistem dapat menarik data akun warga secara otomatis.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('kader.data.remaja.index') }}" onclick="showLoader()" class="flex-1 sm:flex-none px-8 py-4 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[12px] rounded-full hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="flex-1 sm:flex-none px-10 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-black text-[12px] rounded-full shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save text-lg"></i> Simpan Data
                </button>
            </div>
        </div>
        
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. LIMITASI INPUT
    const today = new Date(); 
    document.getElementById('tanggal_lahir').max = today.toISOString().split('T')[0];
    
    document.querySelectorAll('input[type="number"]').forEach(input => { 
        input.addEventListener('input', function() { 
            if (this.name === 'nik') { if (this.value.length > 16) this.value = this.value.slice(0, 16); }
        }); 
    });

    // 2. AUTO-CALC USIA REMAJA (DENGAN NOTICE RANGE 10-19 TAHUN)
    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) months--;

        const y = Math.floor(months / 12);
        const m = months % 12;
        const text = m > 0 ? `${y} Tahun ${m} Bulan` : `${y} Tahun`;

        let alertClass = 'bg-indigo-50 border-indigo-100 text-indigo-700';
        let alertIcon = 'fa-info-circle text-indigo-500';
        let alertMsg = '';
        
        if(y < 10 || y > 19) {
            alertClass = 'bg-amber-50 border-amber-200 text-amber-700';
            alertIcon = 'fa-exclamation-triangle text-amber-500';
            alertMsg = '<span class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Di Luar Range Remaja</span>';
        }

        helper.innerHTML = `<div class="mt-3 inline-flex items-center ${alertClass} border px-4 py-2 rounded-xl text-xs font-bold shadow-sm animate-slide-up"><i class="fas ${alertIcon} mr-2 text-lg"></i> Usia tercatat: ${text} ${alertMsg}</div>`;
    }

    if(document.getElementById('tanggal_lahir').value) calculateAge();

    // 3. LOADER & PROTECTION
    const hideLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 300); } 
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Data'; btn.classList.remove('opacity-75', 'cursor-wait'); btn.disabled = false;}
    };
    const showLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0','pointer-events-none'); l.classList.add('opacity-100','pointer-events-auto'); } 
    };

    window.onload = hideLoader;
    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);

    document.getElementById('formRemaja').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.disabled = true;
        showLoader();
    });

    // 4. SERVER ERROR HANDLER (SWEETALERT)
    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Operasi Gagal',
            html: `<p class="mt-2 text-sm text-slate-600">{!! addslashes(session('error')) !!}</p>`,
            confirmButtonText: 'Saya Mengerti', buttonsStyling: false,
            customClass: { 
                popup: 'rounded-[32px] p-8 bg-white/95 backdrop-blur-xl border border-slate-100 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]', 
                confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-full font-black text-[11px] uppercase tracking-widest transition-all shadow-md mt-4' 
            }
        });
    @endif
</script>
@endpush
@endsection