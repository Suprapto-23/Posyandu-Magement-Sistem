@extends('layouts.kader')

@section('title', 'Edit Data Remaja')
@section('page-name', 'Koreksi Profil Remaja')

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
    .form-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 4px 20px -3px rgba(245, 158, 11, 0.15); transform: translateY(-2px); }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/80 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-amber-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-edit text-amber-500 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-amber-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MENYIAPKAN DATA...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.remaja.index') }}" onclick="window.showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- WARNING HEADER --}}
    <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(245,158,11,0.4)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-3xl md:text-4xl shrink-0 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib"></i>
        </div>
        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white text-[10px] font-black px-4 py-1.5 rounded-full mb-3 uppercase tracking-widest backdrop-blur-sm">
                <i class="fas fa-exclamation-triangle text-amber-200"></i> Mode Koreksi Data Aktif
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Edit Profil Remaja</h1>
            <p class="text-amber-50 font-medium text-[13px] md:text-[14px] max-w-2xl leading-relaxed">Perubahan pada NIK akan berdampak pada akses login Web Warga milik remaja ini.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.remaja.update', $remaja->id) }}" method="POST" id="formRemaja" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-8">
            
            {{-- KOLOM KIRI: Identitas Diri --}}
            <div class="xl:col-span-7 flex flex-col gap-8">
                <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-bl-full pointer-events-none"></div>
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black shadow-md">1</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Profil Remaja</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">NIK Remaja (Akses Warga) <span class="text-rose-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik', $remaja->nik) }}" required class="form-input focus:ring-4 focus:ring-amber-50 @error('nik') form-error @enderror">
                            @error('nik') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Lengkap Remaja <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $remaja->nama_lengkap) }}" required class="form-input focus:ring-4 focus:ring-amber-50">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $remaja->tempat_lahir) }}" required class="form-input focus:ring-4 focus:ring-amber-50">
                            </div>
                            <div>
                                <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $remaja->tanggal_lahir?->format('Y-m-d')) }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-amber-50" onchange="calculateAge()">
                                <div id="age-helper"></div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input cursor-pointer focus:ring-4 focus:ring-amber-50">
                                <option value="L" {{ old('jenis_kelamin', $remaja->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $remaja->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Data Akademik & Wali --}}
            <div class="xl:col-span-5 flex flex-col gap-8">
                <div class="bg-slate-50/90 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col h-full">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-slate-400/10 rounded-bl-full pointer-events-none blur-xl"></div>
                    
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                        <span class="w-10 h-10 rounded-xl bg-slate-400 text-white flex items-center justify-center font-black shadow-md">2</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Akademik & Wali</h3>
                    </div>

                    <div class="space-y-6 relative z-10 flex-1">
                        
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <label class="form-label text-slate-600"><i class="fas fa-school mr-1"></i> Nama Sekolah (Opsional)</label>
                            <input type="text" name="sekolah" value="{{ old('sekolah', $remaja->sekolah) }}" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100">
                            
                            <label class="form-label text-slate-600 mt-4"><i class="fas fa-chalkboard-teacher mr-1"></i> Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $remaja->kelas) }}" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100">
                        </div>
                        
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <label class="form-label text-slate-600"><i class="fas fa-user-friends mr-1"></i> Nama Orang Tua / Wali <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $remaja->nama_ortu) }}" required class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100">
                            
                            <label class="form-label text-slate-600 mt-4"><i class="fas fa-phone-alt mr-1"></i> No HP Orang Tua</label>
                            <input type="number" name="telepon_ortu" value="{{ old('telepon_ortu', $remaja->telepon_ortu) }}" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100">
                        </div>

                        <div>
                            <label class="form-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili <span class="text-rose-500">*</span></label>
                            <textarea name="alamat" rows="2" required class="form-input bg-white resize-none focus:ring-4 focus:ring-amber-50">{{ old('alamat', $remaja->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="p-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg border border-white flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('kader.data.remaja.index') }}" onclick="window.showLoader()" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                Batalkan
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[13px] rounded-xl hover:from-amber-600 hover:to-orange-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                <i class="fas fa-save text-lg"></i> Simpan Perubahan
            </button>
        </div>
        
    </form>
</div>

<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Perubahan'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    setTimeout(window.hideLoader, 2000);

    document.getElementById('formRemaja').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        window.showLoader();
    });

    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if(this.name === 'nik' && this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });

    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    const minDate = new Date(today.getFullYear() - 25, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    document.getElementById('tanggal_lahir').max = maxDate;
    document.getElementById('tanggal_lahir').min = minDate;

    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) months--;

        if(months < 0) { helper.innerHTML = '<div class="mt-2 text-rose-500 text-xs font-bold"><i class="fas fa-exclamation-triangle"></i> Tanggal di masa depan</div>'; return; }

        const y = Math.floor(months / 12);
        const m = months % 12;
        const text = m > 0 ? `${y} Tahun ${m} Bulan` : `${y} Tahun`;

        let alertClass = 'bg-amber-50 border-amber-100 text-amber-700';
        let alertIcon = 'fa-info-circle text-amber-500';
        let alertMsg = '';
        
        if(y < 10 || y > 19) {
            alertClass = 'bg-rose-50 border-rose-200 text-rose-700';
            alertIcon = 'fa-exclamation-triangle text-rose-500';
            alertMsg = '<span class="text-[9px] bg-rose-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Di Luar Usia Remaja</span>';
        }

        helper.innerHTML = `<div class="mt-3 inline-flex items-center ${alertClass} border px-4 py-2 rounded-xl text-xs font-bold shadow-sm animate-slide-up"><i class="fas ${alertIcon} mr-2 text-lg"></i> Usia tercatat: ${text} ${alertMsg}</div>`;
    }

    if(document.getElementById('tanggal_lahir').value) calculateAge();
</script>
@endsection