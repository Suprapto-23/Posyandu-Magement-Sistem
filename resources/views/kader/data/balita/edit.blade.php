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

<div class="max-w-5xl mx-auto animate-slide-up relative z-10 pb-10">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-amber-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(245,158,11,0.4)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="w-24 h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib"></i>
        </div>
        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest">
                <i class="fas fa-exclamation-triangle text-white"></i> Mode Koreksi Aktif
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Edit Profil Anak</h1>
            <p class="text-amber-50 font-medium text-[14px] max-w-xl">Jika Anda mengubah NIK Ibu, sistem akan secara otomatis memutus koneksi akun Warga lama dan mencari akun Warga baru yang cocok.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.balita.update', $balita->id) }}" method="POST" id="formBalita" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-black shadow-md">1</span>
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
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}" required class="form-input cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <div>
                            <label class="form-label">Berat Lahir (kg)</label>
                            <input type="number" step="0.01" name="berat_lahir" value="{{ old('berat_lahir', $balita->berat_lahir) }}" class="form-input bg-white">
                        </div>
                        <div>
                            <label class="form-label">Panjang (cm)</label>
                            <input type="number" step="0.01" name="panjang_lahir" value="{{ old('panjang_lahir', $balita->panjang_lahir) }}" class="form-input bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 bg-slate-50/80 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                    <span class="w-10 h-10 rounded-full bg-slate-400 text-white flex items-center justify-center font-black shadow-md">2</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Data Orang Tua</h3>
                </div>

                <div class="space-y-6 relative z-10 flex-1">
                    <div>
                        <label class="form-label">NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik_ibu" value="{{ old('nik_ibu', $balita->nik_ibu) }}" required class="form-input bg-white">
                    </div>
                    <div>
                        <label class="form-label">Nama Kandung Ibu <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $balita->nama_ibu) }}" required class="form-input bg-white">
                    </div>
                    
                    <hr class="border-slate-200 my-2">

                    <div>
                        <label class="form-label">NIK Ayah (Opsional)</label>
                        <input type="number" name="nik_ayah" value="{{ old('nik_ayah', $balita->nik_ayah) }}" class="form-input bg-white">
                    </div>
                    <div>
                        <label class="form-label">Nama Ayah (Opsional)</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $balita->nama_ayah) }}" class="form-input bg-white">
                    </div>

                    <div>
                        <label class="form-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required class="form-input bg-white resize-none">{{ old('alamat', $balita->alamat) }}</textarea>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="p-8 border-t border-slate-100 bg-white/60 backdrop-blur-md rounded-[32px] shadow-sm border flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-full sm:w-auto px-8 py-4 bg-slate-100 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                Batalkan
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                <i class="fas fa-save"></i> Simpan Perubahan
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

    const hideLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    const showLoader = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);
    document.getElementById('formBalita').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        showLoader();
    });
</script>
@endsection