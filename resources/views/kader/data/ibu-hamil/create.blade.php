@extends('layouts.kader')
@section('title', 'Tambah Data Ibu Hamil')
@section('page-name', 'Registrasi Ibu Hamil')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
    .crm-label { display:block; font-size:.75rem; font-weight:800; color:#4b5563; margin-bottom:.375rem; text-transform:uppercase; letter-spacing:0.05em; }
    .crm-input { width:100%; background:#f8fafc; border:2px solid #e2e8f0; color:#0f172a; font-size:.875rem;
        border-radius:.75rem; padding:.75rem 1rem; outline:none; transition:all .3s; font-weight:600; }
    .crm-input:focus { background:#fff; border-color:#ec4899; box-shadow:0 4px 12px -3px rgba(236,72,153,.15); }
    .crm-input::placeholder { color:#94a3b8; font-weight:500; }
    .crm-error { border-color:#ef4444!important; background:#fef2f2!important; }
    .crm-error-text { color:#ef4444; font-size:.75rem; margin-top:.375rem; font-weight:700; display:block; }
    #hpl-preview { transition: all .3s ease; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('kader.data.ibu-hamil.index') }}"
           class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-pink-50 hover:text-pink-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Registrasi Ibu Hamil</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Catat data fisik dasar ibu hamil. Pemeriksaan mendalam akan dilanjutkan oleh bidan.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.ibu-hamil.store') }}" method="POST" id="formCreateIbu">
        @csrf

        {{-- SEKSI 1: IDENTITAS --}}
        <div class="bg-white border border-slate-200/80 rounded-[24px] shadow-sm p-8 md:p-10 mb-6">
            <h3 class="text-lg font-black text-slate-800 border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm"><i class="fas fa-user"></i></span>
                Identitas Diri
            </h3>
            
            <div class="mb-6">
                <label class="crm-label">Nama Lengkap Ibu <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                       placeholder="Nama sesuai KTP" class="crm-input @error('nama_lengkap') crm-error @enderror">
                @error('nama_lengkap') <span class="crm-error-text">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="crm-label">NIK (Kunci Integrasi)</label>
                    <input type="number" name="nik" value="{{ old('nik') }}" placeholder="16 digit NIK" class="crm-input @error('nik') crm-error @enderror">
                    @error('nik') <span class="crm-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="crm-label">No. WhatsApp / Telepon</label>
                    <input type="text" name="telepon_ortu" value="{{ old('telepon_ortu') }}" placeholder="08xx-xxxx-xxxx" class="crm-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="crm-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran" class="crm-input">
                </div>
                <div>
                    <label class="crm-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="crm-input cursor-pointer" id="tgl_lahir">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="crm-label">Nama Suami</label>
                    <input type="text" name="nama_suami" value="{{ old('nama_suami') }}" placeholder="Nama suami" class="crm-input">
                </div>
                <div>
                    <label class="crm-label">Golongan Darah</label>
                    <select name="golongan_darah" class="crm-input cursor-pointer">
                        <option value="">-- Pilih --</option>
                        @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gd)
                        <option value="{{ $gd }}" {{ old('golongan_darah')==$gd?'selected':'' }}>{{ $gd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div>
                <label class="crm-label">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="alamat" rows="2" required placeholder="Alamat domisili saat ini..." class="crm-input resize-none">{{ old('alamat') }}</textarea>
            </div>
        </div>

        {{-- SEKSI 2: DATA KEHAMILAN --}}
        <div class="bg-white border border-slate-200/80 rounded-[24px] shadow-sm p-8 md:p-10 mb-6">
            <h3 class="text-lg font-black text-pink-600 border-b border-pink-100 pb-4 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-sm"><i class="fas fa-baby"></i></span>
                Data Kehamilan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="crm-label">HPHT (Hari Pertama Haid Terakhir)</label>
                    <input type="date" name="hpht" id="hpht" value="{{ old('hpht') }}" class="crm-input cursor-pointer">
                </div>
                <div>
                    <label class="crm-label">HPL (Hari Perkiraan Lahir)</label>
                    <input type="date" name="hpl" id="hpl" value="{{ old('hpl') }}" class="crm-input cursor-pointer bg-slate-50 border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 mt-1">*Sistem otomatis menghitung jika dikosongkan.</p>
                </div>
            </div>

            <div id="hpl-preview" class="hidden p-5 bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 rounded-2xl mb-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-white shadow-sm flex items-center justify-center text-pink-500 text-2xl shrink-0">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-pink-400 uppercase tracking-widest mb-1">Estimasi AI</p>
                        <p id="preview-text" class="font-black text-pink-700 text-lg leading-none"></p>
                        <p id="preview-hpl" class="text-xs font-bold text-pink-600 mt-1"></p>
                    </div>
                </div>
            </div>

            <div>
                <label class="crm-label">Riwayat Penyakit Penyerta (Opsional)</label>
                <input type="text" name="riwayat_penyakit" value="{{ old('riwayat_penyakit') }}" placeholder="Mis: Hipertensi, Asma..." class="crm-input bg-amber-50/30 border-amber-200 focus:border-amber-400">
            </div>
        </div>

        {{-- SEKSI 3: DATA FISIK KADER --}}
        <div class="bg-gradient-to-b from-emerald-50 to-white border border-emerald-200 rounded-[24px] shadow-sm p-8 md:p-10 mb-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-bl-full pointer-events-none"></div>
            
            <div class="flex items-center justify-between border-b border-emerald-100 pb-4 mb-6">
                <h3 class="text-lg font-black text-emerald-800 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-emerald-200/50 text-emerald-600 flex items-center justify-center text-sm"><i class="fas fa-weight"></i></span>
                    Data Fisik Awal
                </h3>
                <span class="text-[10px] font-black bg-emerald-500 text-white px-3 py-1 rounded-lg uppercase tracking-wider shadow-sm">Khusus Kader</span>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="crm-label text-emerald-800">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}" placeholder="0.0" class="crm-input border-emerald-200 focus:border-emerald-500">
                </div>
                <div>
                    <label class="crm-label text-emerald-800">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="0.0" class="crm-input border-emerald-200 focus:border-emerald-500">
                </div>
            </div>

            <div id="imt-result" class="hidden mt-6 p-4 bg-white border border-emerald-200 rounded-2xl flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Indeks Massa Tubuh</p>
                    <p id="imt-val" class="text-3xl font-black text-emerald-600 leading-none"></p>
                </div>
                <div class="text-right">
                    <span id="imt-kat" class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-white bg-emerald-500"></span>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="sticky bottom-6 z-30 bg-white/90 backdrop-blur-xl border border-slate-200 p-5 rounded-[24px] shadow-[0_20px_40px_rgba(0,0,0,0.1)] flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[11px] font-bold text-slate-400 hidden sm:block"><i class="fas fa-shield-alt mr-1"></i> Data dienkripsi aman.</p>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('kader.data.ibu-hamil.index') }}" class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 text-slate-600 font-black text-sm rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">Batal</a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3.5 bg-pink-600 text-white font-black text-sm rounded-xl hover:bg-pink-700 shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-paper-plane"></i> Simpan Pendaftaran
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('tgl_lahir').max = new Date().toISOString().split('T')[0];
    document.getElementById('hpht').max = new Date().toISOString().split('T')[0];

    document.querySelector('input[name="nik"]')?.addEventListener('input', function() {
        if (this.value.length > 16) this.value = this.value.slice(0, 16);
    });

    document.getElementById('hpht').addEventListener('change', function() {
        const hpht = new Date(this.value);
        if (isNaN(hpht)) return;
        const hpl = new Date(hpht);
        hpl.setDate(hpl.getDate() + 280);
        const hplInput = document.getElementById('hpl');
        if (!hplInput.value) hplInput.value = hpl.toISOString().split('T')[0];
        updatePreview(hpht, hpl);
    });

    document.getElementById('hpl').addEventListener('change', function() {
        const hpht = new Date(document.getElementById('hpht').value);
        const hpl  = new Date(this.value);
        if (!isNaN(hpht) && !isNaN(hpl)) updatePreview(hpht, hpl);
    });

    function updatePreview(hpht, hpl) {
        const today = new Date();
        const minggu = Math.floor((today - hpht) / (7 * 24 * 3600 * 1000));
        let trimLabel = minggu <= 12 ? 'Trimester I' : (minggu <= 27 ? 'Trimester II' : 'Trimester III');
        const sisaHari = Math.floor((hpl - today) / (24 * 3600 * 1000));
        const opt = {day:'numeric',month:'long',year:'numeric'};
        document.getElementById('preview-text').textContent = `${trimLabel} — ${minggu} Minggu`;
        document.getElementById('preview-hpl').textContent = `Diperkirakan lahir: ${hpl.toLocaleDateString('id-ID', opt)} (${sisaHari > 0 ? sisaHari+' hari lagi' : 'Sudah waktunya lahir'})`;
        document.getElementById('hpl-preview').classList.remove('hidden');
    }

    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        if (!bb || !tb || tb < 50) { document.getElementById('imt-result').classList.add('hidden'); return; }
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk Ringan' : 'Obesitas'));
        let color = imt < 18.5 ? 'bg-amber-500' : (imt < 25 ? 'bg-emerald-500' : 'bg-rose-500');
        
        document.getElementById('imt-val').textContent = imt;
        const imtKatEl = document.getElementById('imt-kat');
        imtKatEl.textContent = kat;
        imtKatEl.className = `inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-white ${color}`;
        document.getElementById('imt-result').classList.remove('hidden');
    }
    
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);

    // Animasi Submit (Real-time Feel)
    document.getElementById('formCreateIbu').addEventListener('submit', function(e) {
        Swal.fire({
            title: 'Menyimpan Data...',
            html: 'Sistem sedang memproses pendaftaran dan mengintegrasikan akun.',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });
    });
</script>
@endpush
@endsection