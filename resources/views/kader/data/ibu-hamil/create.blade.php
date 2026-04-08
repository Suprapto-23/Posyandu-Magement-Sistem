@extends('layouts.kader')
@section('title', 'Tambah Data Ibu Hamil')
@section('page-name', 'Registrasi Kehamilan')

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
    .form-input:focus { background-color: #ffffff; border-color: #ec4899; box-shadow: 0 4px 20px -3px rgba(236, 72, 153, 0.15); transform: translateY(-2px); }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/80 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-pink-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-pink-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-female text-pink-500 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-pink-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MENYIAPKAN FORMULIR...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-pink-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.ibu-hamil.index') }}" onclick="window.showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-pink-50 hover:border-pink-300 hover:text-pink-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[24px] bg-gradient-to-br from-pink-100 to-rose-100 text-pink-600 mb-5 shadow-sm border border-pink-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-female text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Pendaftaran Ibu Hamil</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Isi data kehamilan dengan lengkap. Sistem akan otomatis menghitung <b>Hari Perkiraan Lahir (HPL)</b> dan <b>Indeks Massa Tubuh (IMT)</b>.</p>
    </div>

    <form action="{{ route('kader.data.ibu-hamil.store') }}" method="POST" id="formCreateIbu" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-8">
            
            {{-- KOLOM KIRI: Identitas & Medis Kehamilan --}}
            <div class="xl:col-span-7 flex flex-col gap-8">
                
                {{-- Card 1: Identitas --}}
                <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-xl bg-pink-500 text-white flex items-center justify-center font-black shadow-md">1</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Diri</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">NIK Ibu (Akses Warga) <span class="text-rose-500">*</span></label>
                            <input type="number" name="nik" value="{{ old('nik') }}" required placeholder="16 Digit NIK" class="form-input focus:ring-4 focus:ring-pink-50 @error('nik') form-error @enderror">
                            @error('nik') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Lengkap Ibu <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai KTP" class="form-input focus:ring-4 focus:ring-pink-50 @error('nama_lengkap') form-error @enderror">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="form-input focus:ring-4 focus:ring-pink-50">
                            </div>
                            <div>
                                <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-pink-50">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Nama Suami <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_suami" value="{{ old('nama_suami') }}" required class="form-input focus:ring-4 focus:ring-pink-50">
                            </div>
                            <div>
                                <label class="form-label">No. HP Keluarga (Opsional)</label>
                                <input type="number" name="telepon_ortu" value="{{ old('telepon_ortu') }}" placeholder="08xxx" class="form-input focus:ring-4 focus:ring-pink-50">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="alamat" rows="2" required class="form-input resize-none focus:ring-4 focus:ring-pink-50">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Data Klinis & Fisik --}}
            <div class="xl:col-span-5 flex flex-col gap-8">
                
                {{-- Card 2: Kehamilan --}}
                <div class="bg-rose-50/80 rounded-[32px] border border-rose-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8 border-b border-rose-200 pb-5">
                        <span class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center font-black shadow-md">2</span>
                        <h3 class="text-xl font-black text-rose-900 font-poppins">Kandungan</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm">
                            <label class="form-label text-rose-600"><i class="fas fa-calendar-minus mr-1"></i> HPHT (Haid Terakhir) <span class="text-rose-500">*</span></label>
                            <input type="date" name="hpht" id="hpht" value="{{ old('hpht') }}" required class="form-input bg-slate-50 focus:bg-white focus:border-rose-400">
                            
                            <label class="form-label text-rose-600 mt-4"><i class="fas fa-baby mr-1"></i> HPL (Perkiraan Lahir) <span class="text-rose-500">*</span></label>
                            <input type="date" name="hpl" id="hpl" value="{{ old('hpl') }}" required class="form-input bg-rose-50 border-rose-200 text-rose-800 focus:bg-white" readonly title="Dihitung Otomatis">
                            <p class="text-[10px] font-bold text-rose-400 mt-1 italic">*HPL otomatis dihitung +280 hari dari HPHT</p>
                        </div>

                        <div>
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-input focus:ring-4 focus:ring-rose-50">
                                <option value="">-- Belum Tahu --</option>
                                @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gol)
                                    <option value="{{ $gol }}" {{ old('golongan_darah') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Riwayat Penyakit (Opsional)</label>
                            <textarea name="riwayat_penyakit" rows="2" placeholder="Contoh: Asma, Hipertensi..." class="form-input resize-none focus:ring-4 focus:ring-rose-50">{{ old('riwayat_penyakit') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Fisik Awal (IMT Auto) --}}
                <div class="bg-slate-800 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.1)] p-8 md:p-10 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-700 pb-5">
                        <span class="w-10 h-10 rounded-full bg-slate-600 text-white flex items-center justify-center font-black shadow-md">3</span>
                        <h3 class="text-xl font-black text-white font-poppins">Fisik Dasar Ibu</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="form-label text-slate-400">Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}" class="form-input bg-slate-700 border-slate-600 text-white focus:bg-slate-600 focus:border-indigo-400 placeholder:text-slate-500">
                        </div>
                        <div>
                            <label class="form-label text-slate-400">Tinggi (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan') }}" class="form-input bg-slate-700 border-slate-600 text-white focus:bg-slate-600 focus:border-indigo-400 placeholder:text-slate-500">
                        </div>
                    </div>

                    {{-- Widget IMT Real-time --}}
                    <div id="imt-result" class="hidden animate-slide-up bg-slate-900 border border-slate-700 p-4 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Skor IMT Ibu</p>
                            <p class="text-2xl font-black text-white" id="imt-val">0.00</p>
                        </div>
                        <div id="imt-kat" class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-white bg-slate-500">
                            -
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        {{-- ACTION BUTTONS --}}
        <div class="p-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg border border-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[11px] font-bold text-slate-500 px-4 hidden sm:block"><i class="fas fa-shield-alt text-emerald-500 mr-1 text-lg align-middle"></i> Data dienkripsi. NIK Ibu menjadi akses login web Warga.</p>
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 w-full sm:w-auto">
                <a href="{{ route('kader.data.ibu-hamil.index') }}" onclick="window.showLoader()" class="w-full sm:w-auto px-8 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-black text-[13px] rounded-xl hover:from-pink-600 hover:to-rose-700 shadow-[0_8px_20px_rgba(236,72,153,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                    <i class="fas fa-save text-lg"></i> Simpan Data Ibu Hamil
                </button>
            </div>
        </div>
        
    </form>
</div>

@push('scripts')
<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Data Ibu Hamil'; btn.classList.remove('opacity-75', 'cursor-wait'); }
    };
    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    setTimeout(window.hideLoader, 2000);

    document.getElementById('formCreateIbu').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        window.showLoader();
    });

    // Batasi NIK max 16 karakter
    document.querySelector('input[name="nik"]').addEventListener('input', function() {
        if (this.value.length > 16) this.value = this.value.slice(0, 16);
    });
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];

    // AUTO-CALC: HPL dari HPHT (Rumus Naegele: HPHT + 280 hari)
    document.getElementById('hpht').addEventListener('change', function() {
        const hphtDate = new Date(this.value);
        if (isNaN(hphtDate)) return;
        const hplDate = new Date(hphtDate);
        hplDate.setDate(hplDate.getDate() + 280);
        document.getElementById('hpl').value = hplDate.toISOString().split('T')[0];
    });

    // AUTO-CALC: IMT
    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        const resultDiv = document.getElementById('imt-result');
        
        if (!bb || !tb || tb < 50) { resultDiv.classList.add('hidden'); return; }
        
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk Ringan' : 'Obesitas'));
        let color = imt < 18.5 ? 'bg-amber-500' : (imt < 25 ? 'bg-emerald-500' : 'bg-rose-500');
        
        document.getElementById('imt-val').textContent = imt;
        const imtKatEl = document.getElementById('imt-kat');
        imtKatEl.textContent = kat;
        imtKatEl.className = `inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-white ${color}`;
        resultDiv.classList.remove('hidden');
    }
    
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);

    // Trigger on load in case of validation back
    if(document.getElementById('hpht').value && !document.getElementById('hpl').value) {
        document.getElementById('hpht').dispatchEvent(new Event('change'));
    }
    if(document.getElementById('berat_badan').value && document.getElementById('tinggi_badan').value) {
        hitungIMT();
    }
</script>
@endpush
@endsection