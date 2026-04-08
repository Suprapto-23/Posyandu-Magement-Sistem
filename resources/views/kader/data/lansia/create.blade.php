@extends('layouts.kader')

@section('title', 'Tambah Data Lansia')
@section('page-name', 'Registrasi Lansia')

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
    .form-input:focus { background-color: #ffffff; border-color: #10b981; box-shadow: 0 4px 20px -3px rgba(16, 185, 129, 0.15); transform: translateY(-2px); }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/80 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-user-clock text-emerald-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-emerald-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MENYIAPKAN FORMULIR...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.lansia.index') }}" onclick="window.showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[24px] bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-600 mb-5 shadow-sm border border-emerald-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-user-clock text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Pendaftaran Lansia</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Isi data profil dan kondisi kesehatan dasar. NIK Lansia (atau NIK Wali) akan digunakan sebagai akses login Web Warga.</p>
    </div>

    <form action="{{ route('kader.data.lansia.store') }}" method="POST" id="formLansia" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-8">
            
            {{-- KOLOM KIRI: Identitas Diri --}}
            <div class="xl:col-span-7 flex flex-col gap-8">
                <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full pointer-events-none"></div>
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black shadow-md">1</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Profil Identitas</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">NIK KTP Lansia (Akses Warga) <span class="text-rose-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik') }}" required placeholder="16 Digit NIK" class="form-input focus:ring-4 focus:ring-emerald-50 @error('nik') form-error @enderror">
                            @error('nik') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Lengkap Lansia <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai KTP" class="form-input focus:ring-4 focus:ring-emerald-50 @error('nama_lengkap') form-error @enderror">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="form-input focus:ring-4 focus:ring-emerald-50">
                            </div>
                            <div>
                                <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-emerald-50" onchange="calculateAge()">
                                <div id="age-helper"></div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input cursor-pointer focus:ring-4 focus:ring-emerald-50">
                                <option value="">-- Pilih Gender --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Kondisi Fisik & Kontak --}}
            <div class="xl:col-span-5 flex flex-col gap-8">
                <div class="bg-slate-50/90 rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex flex-col h-full">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-teal-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                    
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                        <span class="w-10 h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center font-black shadow-md">2</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Kesehatan & Kontak</h3>
                    </div>

                    <div class="space-y-6 relative z-10 flex-1">
                        
                        <div class="p-5 bg-white border border-teal-100 rounded-[20px] shadow-sm">
                            <label class="form-label text-teal-600"><i class="fas fa-wheelchair mr-1"></i> Status Kemandirian <span class="text-rose-500">*</span></label>
                            <select name="kemandirian" required class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-teal-50 cursor-pointer">
                                <option value="Mandiri">Mandiri (Aktivitas Penuh)</option>
                                <option value="Bantuan Ringan">Bantuan Ringan</option>
                                <option value="Bantuan Sedang">Bantuan Sedang</option>
                                <option value="Bantuan Berat">Bantuan Berat</option>
                                <option value="Ketergantungan Total">Ketergantungan Total (Bedridden)</option>
                            </select>
                            
                            <label class="form-label text-teal-600 mt-4"><i class="fas fa-notes-medical mr-1"></i> Riwayat Penyakit Bawaan</label>
                            <input type="text" name="penyakit_bawaan" value="{{ old('penyakit_bawaan') }}" placeholder="Contoh: Hipertensi, Asam Urat, Diabetes..." class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-teal-50">
                        </div>

                        <div class="grid grid-cols-2 gap-4 bg-white p-5 rounded-[20px] border border-slate-200 shadow-sm">
                            <div>
                                <label class="form-label text-indigo-600">Berat Badan (kg)</label>
                                <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}" class="form-input bg-slate-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="form-label text-emerald-600">Tinggi (cm)</label>
                                <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan') }}" class="form-input bg-slate-50 focus:bg-white">
                            </div>
                            <div class="col-span-2">
                                <div id="imt-preview" class="hidden border rounded-xl p-4 flex items-center gap-4 mt-2 transition-all">
                                    <div class="flex-1">
                                        <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5" id="imt-label">Status</p>
                                        <p class="text-2xl font-black font-poppins"><span id="imt-angka">0</span></p>
                                        <p class="text-[9px] font-bold mt-1 opacity-70" id="imt-range">Range</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center text-xl shrink-0"><i class="fas fa-child"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <label class="form-label text-slate-600"><i class="fas fa-phone-alt mr-1"></i> No HP Keluarga / Pendamping <span class="text-rose-500">*</span></label>
                            <input type="number" name="telepon_keluarga" value="{{ old('telepon_keluarga') }}" required placeholder="08xxx" class="form-input bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100">
                        </div>

                        <div>
                            <label class="form-label"><i class="fas fa-map-marker-alt mr-1"></i> Alamat Domisili <span class="text-rose-500">*</span></label>
                            <textarea name="alamat" rows="2" required placeholder="Alamat lengkap RT/RW..." class="form-input bg-white resize-none focus:ring-4 focus:ring-emerald-50">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="p-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg border border-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[11px] font-bold text-slate-500 px-4 hidden sm:block"><i class="fas fa-shield-alt text-emerald-500 mr-1 text-lg align-middle"></i> Data dienkripsi & terhubung otomatis ke akun warga.</p>
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                <a href="{{ route('kader.data.lansia.index') }}" onclick="window.showLoader()" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-[13px] rounded-xl hover:from-emerald-600 hover:to-teal-700 shadow-[0_8px_20px_rgba(16,185,129,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                    <i class="fas fa-save text-lg"></i> Simpan Data Lansia
                </button>
            </div>
        </div>
        
    </form>
</div>

<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Data Lansia'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    setTimeout(window.hideLoader, 2000);

    document.getElementById('formLansia').addEventListener('submit', function() {
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

    // Validasi Umur Lansia (Biasanya > 45 / 60)
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 40, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    document.getElementById('tanggal_lahir').max = maxDate;

    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        let months = (today.getFullYear() - dob.getFullYear()) * 12;
        months -= dob.getMonth();
        months += today.getMonth();
        if (today.getDate() < dob.getDate()) months--;

        if(months < 0) return;

        const y = Math.floor(months / 12);
        let alertClass = 'bg-emerald-50 border-emerald-100 text-emerald-700';
        let alertIcon = 'fa-check-circle text-emerald-500';
        let alertMsg = '<span class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Kategori Lansia Valid</span>';
        
        if(y < 45) {
            alertClass = 'bg-amber-50 border-amber-200 text-amber-700';
            alertIcon = 'fa-exclamation-triangle text-amber-500';
            alertMsg = '<span class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Usia di Bawah 45 Tahun</span>';
        }

        helper.innerHTML = `<div class="mt-3 inline-flex items-center ${alertClass} border px-4 py-2 rounded-xl text-xs font-bold shadow-sm animate-slide-up"><i class="fas ${alertIcon} mr-2 text-lg"></i> Usia tercatat: ${y} Tahun ${alertMsg}</div>`;
    }
    if(document.getElementById('tanggal_lahir').value) calculateAge();

    // AUTO IMT
    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        const preview = document.getElementById('imt-preview');
        
        if (!bb || !tb || tb < 50) { preview.classList.add('hidden'); return; }
        
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk Ringan' : 'Obesitas'));
        let color = imt < 18.5 ? 'bg-amber-500 text-white border-amber-600' : (imt < 25 ? 'bg-emerald-500 text-white border-emerald-600' : 'bg-rose-500 text-white border-rose-600');
        
        document.getElementById('imt-angka').textContent = imt;
        document.getElementById('imt-label').textContent = kat;
        document.getElementById('imt-range').textContent = 'Kalkulasi Otomatis';
        preview.className = `border rounded-[16px] p-4 flex items-center gap-4 mt-3 transition-all shadow-sm ${color}`;
        preview.classList.remove('hidden');
    }
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);
    if(document.getElementById('berat_badan').value && document.getElementById('tinggi_badan').value) hitungIMT();
</script>
@endsection