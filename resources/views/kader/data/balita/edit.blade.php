@extends('layouts.kader')

@section('title', 'Edit Data Balita')
@section('page-name', 'Koreksi Profil Anak')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* FORM INPUT CRM NEXUS */
    .form-label { display: block; font-size: 0.70rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
    .form-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #f1f5f9; color: #1e293b;
        font-size: 0.875rem; border-radius: 16px; padding: 1rem 1.25rem; outline: none;
        transition: all 0.3s ease; font-weight: 600; box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.01);
    }
    .form-input:focus {
        background-color: #ffffff; border-color: #f59e0b;
        box-shadow: 0 4px 20px -3px rgba(245, 158, 11, 0.15); transform: translateY(-2px);
    }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
    
    /* KARTU KACA (GLASSMORPHISM) */
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); }

    /* SWEETALERT CUSTOM */
    div:where(.swal2-container) { z-index: 10000 !important; }
    div:where(.swal2-container).swal2-backdrop-show { backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(16px) !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; border: 1px solid rgba(255,255,255,0.5) !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
    .btn-nexus-warning { background: #f59e0b !important; color: #ffffff !important; border-radius: 100px !important; padding: 12px 28px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; box-shadow: 0 8px 20px rgba(245,158,11,0.3) !important; transition: all 0.3s ease !important; }
    .btn-nexus-warning:hover { background: #d97706 !important; transform: translateY(-2px) !important; }
</style>
@endpush

@section('content')
{{-- PRELOADER --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-amber-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-edit text-amber-500 text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-amber-900 font-black tracking-widest text-[11px] animate-pulse uppercase">MENYIAPKAN DATA...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-12">
    
    {{-- AURA BACKGROUND --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- WARNING HEADER (EDIT MODE) --}}
    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(245,158,11,0.3)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.1] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-3xl md:text-4xl shrink-0 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib"></i>
        </div>
        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white text-[10px] font-black px-4 py-1.5 rounded-full mb-3 uppercase tracking-widest backdrop-blur-sm">
                <i class="fas fa-exclamation-triangle text-amber-200"></i> Mode Koreksi Data Aktif
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Edit Profil Anak</h1>
            <p class="text-amber-50 font-medium text-[13px] md:text-[14px] max-w-2xl leading-relaxed">Pembaruan data pada modul ini akan secara otomatis memperbarui rekam medis yang terintegrasi dengan akun orang tua.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.balita.update', $balita->id) }}" method="POST" id="formBalita" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- KOLOM 1: IDENTITAS ANAK --}}
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-bl-full pointer-events-none"></div>
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-[14px] bg-amber-500 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Balita</h3>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="form-label">Nama Lengkap Anak <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $balita->nama_lengkap) }}" required class="form-input @error('nama_lengkap') form-error @enderror">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">NIK Balita (Opsional)</label>
                            <input type="number" name="nik" value="{{ old('nik', $balita->nik) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input cursor-pointer">
                                <option value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $balita->tempat_lahir) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label text-amber-600">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}" required class="form-input cursor-pointer" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 bg-slate-50 p-6 rounded-[24px] border border-slate-100 mt-4">
                        <div>
                            <label class="form-label text-amber-600">Berat Lahir (kg)</label>
                            <input type="number" step="0.01" name="berat_lahir" value="{{ old('berat_lahir', $balita->berat_lahir) }}" class="form-input bg-white">
                        </div>
                        <div>
                            <label class="form-label text-amber-600">Panjang Lahir (cm)</label>
                            <input type="number" step="0.01" name="panjang_lahir" value="{{ old('panjang_lahir', $balita->panjang_lahir) }}" class="form-input bg-white">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: DATA KELUARGA --}}
            <div class="lg:col-span-5 bg-white/90 rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5 relative z-10">
                    <span class="w-10 h-10 rounded-[14px] bg-slate-400 text-white flex items-center justify-center font-black shadow-md">2</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Data Keluarga</h3>
                </div>

                <div class="space-y-6 relative z-10 flex-1">
                    <div class="p-6 bg-rose-50/50 border border-rose-100 rounded-[24px] shadow-sm">
                        <label class="form-label text-rose-500"><i class="fas fa-female mr-1"></i> NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu', $balita->nik_ibu) }}" required class="form-input bg-white focus:bg-white mb-4">
                        
                        <label class="form-label text-rose-500"><i class="fas fa-id-card-alt mr-1"></i> Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $balita->nama_ibu) }}" required class="form-input bg-white focus:bg-white">
                    </div>
                    
                    <div class="p-6 bg-sky-50/50 border border-sky-100 rounded-[24px] shadow-sm">
                        <label class="form-label text-sky-600"><i class="fas fa-male mr-1"></i> NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah', $balita->nik_ayah) }}" class="form-input bg-white focus:bg-white mb-4">
                        
                        <label class="form-label text-sky-600"><i class="fas fa-id-card-alt mr-1"></i> Nama Ayah (Opsional)</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $balita->nama_ayah) }}" class="form-input bg-white focus:bg-white">
                    </div>

                    <div>
                        <label class="form-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required class="form-input bg-white resize-none">{{ old('alamat', $balita->alamat) }}</textarea>
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- ACTION BUTTONS --}}
        <div class="mt-8 bg-white border border-slate-200 p-6 md:p-8 rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row items-center justify-between gap-6 relative z-30">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-[16px] bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-pen-nib"></i></div>
                <div class="hidden sm:block">
                    <h4 class="text-[14px] font-black text-slate-800 font-poppins mb-0.5">Koreksi Data Balita</h4>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">Pastikan perubahan data sudah sesuai dengan buku KIA asli.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="flex-1 sm:flex-none px-8 py-4 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[12px] rounded-full hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batalkan
                </a>
                <button type="submit" id="btnSubmit" class="flex-1 sm:flex-none px-10 py-4 bg-amber-500 text-white font-black text-[12px] rounded-full hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save text-lg"></i> Simpan Perubahan
                </button>
            </div>
        </div>
        
    </form>
</div>

<script>
    // === 1. PEMBATASAN KALENDER ===
    const todayDate = new Date();
    document.getElementById('tanggal_lahir').max = todayDate.toISOString().split('T')[0];
    
    // Mencegah klik tanggal yang lebih tua dari 59 bulan di UI
    const minDate = new Date();
    minDate.setMonth(minDate.getMonth() - 59);
    document.getElementById('tanggal_lahir').min = minDate.toISOString().split('T')[0];

    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });

    const btnSubmit = document.getElementById('btnSubmit');

    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        const today = new Date();
        
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) { months--; }

        if(months < 0) { 
            helper.innerHTML = '<div class="mt-2 text-rose-500 text-xs font-bold"><i class="fas fa-exclamation-triangle"></i> Tanggal tidak valid</div>'; 
            btnSubmit.disabled = true;
            return; 
        } else if (months >= 60) {
            helper.innerHTML = '<div class="mt-2 text-rose-500 text-xs font-bold"><i class="fas fa-ban"></i> Usia Melebihi Batas (Max 59 Bulan)</div>'; 
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
            return;
        }

        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');

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

        helper.innerHTML = `<div class="mt-3 inline-flex items-center bg-amber-50 border border-amber-100 px-4 py-2 rounded-[12px] text-xs font-bold text-amber-700 shadow-sm"><i class="fas fa-info-circle text-amber-500 mr-2 text-lg"></i> Usia tercatat: ${text} ${badge}</div>`;
    }

    if(document.getElementById('tanggal_lahir').value) { calculateAge(); }

    const hideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        if(btnSubmit && !btnSubmit.disabled) { btnSubmit.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Perubahan'; btnSubmit.classList.remove('opacity-75', 'cursor-wait'); }
    };
    const showLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);
    
    // === 2. PENCEGATAN FORM (HARD-BLOCK) ===
    document.getElementById('formBalita').addEventListener('submit', function(e) {
        e.preventDefault(); 
        
        const dobInput = document.getElementById('tanggal_lahir').value;
        if(!dobInput) return;

        const dob = new Date(dobInput);
        const today = new Date();
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) { months--; }

        if (months >= 60 || months < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Koreksi Ditolak',
                html: `<p class="mt-2 text-sm text-slate-600">Usia anak melewati batas maksimal sistem (<b>59 Bulan</b>). Data tidak akan disimpan.</p>`,
                confirmButtonText: 'Baik, Saya Mengerti',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn-nexus-warning' }
            });
            return false; // Mematikan paksa pengiriman form
        }

        btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btnSubmit.classList.add('opacity-75', 'cursor-wait');
        btnSubmit.disabled = true;
        showLoader();
        
        this.submit(); // Lolos validasi, lanjutkan
    });
</script>
@endsection