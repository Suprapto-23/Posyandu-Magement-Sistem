@extends('layouts.kader')

@section('title', 'Edit Data Balita')
@section('page-name', 'Koreksi Profil Anak')

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
        background-color: #ffffff; border-color: #f59e0b;
        box-shadow: 0 4px 20px -3px rgba(245, 158, 11, 0.15); transform: translateY(-2px);
    }
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
        <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600 transition-all shadow-sm group">
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
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Edit Profil Anak</h1>
            <p class="text-amber-50 font-medium text-[13px] md:text-[14px] max-w-2xl leading-relaxed">Jika Anda mengubah NIK Ibu, sistem akan secara otomatis memutus koneksi akun Warga saat ini dan mencari akun Warga baru yang cocok di database.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.balita.update', $balita->id) }}" method="POST" id="formBalita" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Balita</h3>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="form-label">Nama Lengkap Anak <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $balita->nama_lengkap) }}" required class="form-input focus:ring-4 focus:ring-amber-50 @error('nama_lengkap') form-error @enderror">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">NIK Balita (Opsional)</label>
                            <input type="number" name="nik" value="{{ old('nik', $balita->nik) }}" class="form-input focus:ring-4 focus:ring-amber-50">
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input cursor-pointer focus:ring-4 focus:ring-amber-50">
                                <option value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $balita->tempat_lahir) }}" required class="form-input focus:ring-4 focus:ring-amber-50">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-amber-50" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 bg-slate-50/50 p-6 rounded-[20px] border border-slate-100">
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

            <div class="lg:col-span-5 bg-slate-50/90 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                    <span class="w-10 h-10 rounded-xl bg-slate-400 text-white flex items-center justify-center font-black shadow-md">2</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Data Keluarga</h3>
                </div>

                <div class="space-y-6 relative z-10 flex-1">
                    <div class="p-4 bg-white border border-rose-100 rounded-2xl shadow-sm">
                        <label class="form-label text-rose-500"><i class="fas fa-female mr-1"></i> NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu', $balita->nik_ibu) }}" required class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-amber-50">
                        
                        <label class="form-label text-rose-500 mt-4"><i class="fas fa-id-card-alt mr-1"></i> Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $balita->nama_ibu) }}" required class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-amber-50">
                    </div>
                    
                    <div class="p-4 bg-white border border-sky-100 rounded-2xl shadow-sm">
                        <label class="form-label text-sky-600"><i class="fas fa-male mr-1"></i> NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah', $balita->nik_ayah) }}" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-amber-50">
                        
                        <label class="form-label text-sky-600 mt-4"><i class="fas fa-id-card-alt mr-1"></i> Nama Ayah (Opsional)</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $balita->nama_ayah) }}" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-amber-50">
                    </div>

                    <div>
                        <label class="form-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required class="form-input bg-white resize-none focus:ring-4 focus:ring-amber-50">{{ old('alamat', $balita->alamat) }}</textarea>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="p-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg border border-white flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                Batalkan
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[13px] rounded-xl hover:from-amber-600 hover:to-orange-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                <i class="fas fa-save text-lg"></i> Simpan Perubahan
            </button>
        </div>
        
    </form>
</div>

<script>
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 16) this.value = this.value.slice(0, 16);
        });
    });

    // Auto-Calculate Age Logic (JS) - Sama dengan Create
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

        helper.innerHTML = `<div class="mt-3 inline-flex items-center bg-amber-50 border border-amber-100 px-4 py-2 rounded-xl text-xs font-bold text-amber-700 shadow-sm animate-slide-up"><i class="fas fa-info-circle text-amber-500 mr-2 text-lg"></i> Usia tercatat: ${text} ${badge}</div>`;
    }

    // Call on load
    if(document.getElementById('tanggal_lahir').value) {
        calculateAge();
    }

    const hideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Perubahan'; btn.classList.remove('opacity-75', 'cursor-wait'); }
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