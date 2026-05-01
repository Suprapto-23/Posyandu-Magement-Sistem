@extends('layouts.kader')

@section('title', 'Edit Data Lansia')
@section('page-name', 'Koreksi Profil Lansia')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* FORM INPUT CRM NEXUS (TEMA EMERALD) */
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.6rem; }
    .form-input {
        width: 100%; background-color: #f8fafc; border: 2px solid #f1f5f9; color: #1e293b;
        font-size: 0.875rem; border-radius: 16px; padding: 1rem 1.25rem; outline: none;
        transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.01);
    }
    .form-input:focus {
        background-color: #ffffff; border-color: #10b981;
        box-shadow: 0 4px 20px -3px rgba(16, 185, 129, 0.15); transform: translateY(-2px);
    }
    .form-input::placeholder { color: #94a3b8; font-weight: 500; }
    .form-error { border-color: #f43f5e !important; background-color: #fff1f2 !important; box-shadow: 0 4px 15px -3px rgba(244, 63, 94, 0.15) !important; }
    
    /* KARTU KACA (GLASSMORPHISM) */
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }

    /* SWEETALERT CUSTOM KAPSUL NEXUS */
    div:where(.swal2-container) { z-index: 10000 !important; backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; backdrop-filter: blur(16px) !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; border: 1px solid rgba(255,255,255,0.5) !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
</style>
@endpush

@section('content')
{{-- PRELOADER --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-pen-nib text-emerald-600 text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-emerald-900 font-black tracking-widest text-[11px] animate-pulse uppercase">MENYIAPKAN DATA...</p>
</div>

<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-12">
    
    {{-- AURA BACKGROUND (Konsisten Emerald/Teal) --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-teal-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- TOMBOL KEMBALI --}}
    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.data.lansia.index') }}" onclick="showLoader()" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- HEADER KOREKSI (Layout Sama dengan Create) --}}
    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[20px] bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-600 mb-5 shadow-sm border border-emerald-200 transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib text-4xl"></i>
        </div>
        <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-600 text-[9px] font-black px-3 py-1 rounded-full mb-3 uppercase tracking-widest mx-auto block w-max">
            <i class="fas fa-exclamation-circle text-emerald-400"></i> Mode Koreksi Data Aktif
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Edit Profil Lansia</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Pembaruan NIK pada modul ini akan berdampak pada akses login portal Warga milik peserta lansia tersebut.</p>
    </div>

    {{-- FORM UTAMA --}}
    <form action="{{ route('kader.data.lansia.update', $lansia->id) }}" method="POST" id="formEditLansia" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- KOLOM 1: IDENTITAS (7 KOLOM) --}}
            <div class="lg:col-span-7 glass-panel rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full pointer-events-none"></div>
                
                <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                    <span class="w-10 h-10 rounded-[14px] bg-emerald-600 text-white flex items-center justify-center font-black shadow-md">1</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Profil Identitas</h3>
                </div>
                
                <div class="space-y-6 flex-1">
                    <div>
                        <label class="form-label">NIK Lansia (Akses Warga) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik', $lansia->nik) }}" required class="form-input focus:ring-4 focus:ring-emerald-50 @error('nik') form-error @enderror">
                        @error('nik') <p class="text-rose-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $lansia->nama_lengkap) }}" required class="form-input focus:ring-4 focus:ring-emerald-50">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $lansia->tempat_lahir) }}" required class="form-input focus:ring-4 focus:ring-emerald-50">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $lansia->tanggal_lahir?->format('Y-m-d')) }}" required class="form-input cursor-pointer focus:ring-4 focus:ring-emerald-50" onchange="calculateAge()">
                            <div id="age-helper"></div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="form-input cursor-pointer focus:ring-4 focus:ring-emerald-50">
                            <option value="L" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="2" required class="form-input resize-none focus:ring-4 focus:ring-emerald-50">{{ old('alamat', $lansia->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: KESEHATAN & KONTAK (5 KOLOM) --}}
            <div class="lg:col-span-5 flex flex-col gap-8">
                
                {{-- KARTU KESEHATAN --}}
                <div class="bg-emerald-50/80 rounded-[32px] border border-emerald-100 shadow-[0_10px_40px_-10px_rgba(16,185,129,0.03)] p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-bl-full pointer-events-none blur-xl"></div>
                    
                    <div class="flex items-center gap-4 mb-8 border-b border-emerald-200 pb-5 relative z-10">
                        <span class="w-10 h-10 rounded-[14px] bg-emerald-600 text-white flex items-center justify-center font-black shadow-md">2</span>
                        <h3 class="text-xl font-black text-emerald-900 font-poppins">Kesehatan Awal</h3>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div>
                            <label class="form-label text-emerald-800">Status Kemandirian <span class="text-rose-500">*</span></label>
                            <select name="kemandirian" required class="form-input bg-white border-emerald-100 cursor-pointer shadow-sm">
                                <option value="Mandiri" {{ old('kemandirian', $lansia->kemandirian) == 'Mandiri' ? 'selected' : '' }}>Mandiri (Aktivitas Penuh)</option>
                                <option value="Bantuan Ringan" {{ old('kemandirian', $lansia->kemandirian) == 'Bantuan Ringan' ? 'selected' : '' }}>Bantuan Ringan</option>
                                <option value="Bantuan Sedang" {{ old('kemandirian', $lansia->kemandirian) == 'Bantuan Sedang' ? 'selected' : '' }}>Bantuan Sedang</option>
                                <option value="Bantuan Berat" {{ old('kemandirian', $lansia->kemandirian) == 'Bantuan Berat' ? 'selected' : '' }}>Bantuan Berat</option>
                                <option value="Ketergantungan Total" {{ old('kemandirian', $lansia->kemandirian) == 'Ketergantungan Total' ? 'selected' : '' }}>Ketergantungan Total</option>
                            </select>
                        </div>

                        <div class="p-5 bg-white border border-emerald-100 rounded-[20px] shadow-sm">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="form-label text-emerald-600">Berat (kg)</label>
                                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan', $lansia->berat_badan) }}" placeholder="0.0" class="form-input bg-slate-50 focus:bg-white focus:border-emerald-400">
                                </div>
                                <div>
                                    <label class="form-label text-emerald-600">Tinggi (cm)</label>
                                    <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan', $lansia->tinggi_badan) }}" placeholder="0.0" class="form-input bg-slate-50 focus:bg-white focus:border-emerald-400">
                                </div>
                            </div>
                            
                            {{-- Widget IMT Real-time --}}
                            <div id="imt-preview" class="hidden border rounded-xl p-4 flex items-center gap-4 transition-all animate-slide-up">
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5" id="imt-label">Status</p>
                                    <p class="text-2xl font-black font-poppins"><span id="imt-angka">0</span></p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center text-lg shrink-0 shadow-sm"><i class="fas fa-heartbeat"></i></div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-emerald-800">Riwayat Penyakit Bawaan</label>
                            <input type="text" name="penyakit_bawaan" value="{{ old('penyakit_bawaan', $lansia->penyakit_bawaan) }}" placeholder="Misal: Hipertensi, Diabetes..." class="form-input bg-white border-emerald-100">
                        </div>
                    </div>
                </div>

                {{-- KARTU KONTAK WALI --}}
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] p-8 md:p-10 relative overflow-hidden flex-1 flex flex-col">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-[14px] bg-teal-500 text-white flex items-center justify-center font-black shadow-md">3</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Kontak Keluarga</h3>
                    </div>

                    <div class="space-y-6 flex-1">
                        <div>
                            <label class="form-label">No. HP Keluarga / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="number" name="telepon_keluarga" value="{{ old('telepon_keluarga', $lansia->telepon_keluarga) }}" required class="form-input focus:ring-4 focus:ring-teal-50">
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- ACTION BUTTONS --}}
        <div class="mt-8 bg-white border border-slate-200 p-6 md:p-8 rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row items-center justify-between gap-6 relative z-30">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-[16px] bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl shrink-0"><i class="fas fa-edit"></i></div>
                <div class="hidden sm:block">
                    <h4 class="text-[14px] font-black text-slate-800 font-poppins mb-0.5">Simpan Perubahan</h4>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">Data NIK dan profil kesehatan akan diperbarui ke jaringan database.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                <a href="{{ route('kader.data.lansia.index') }}" onclick="showLoader()" class="flex-1 sm:flex-none px-8 py-4 bg-slate-100 border border-slate-200 text-slate-600 font-extrabold text-[12px] rounded-full hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                    Batalkan
                </a>
                <button type="submit" id="btnSubmit" class="flex-1 sm:flex-none px-10 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-black text-[12px] rounded-full hover:from-emerald-700 hover:to-teal-700 shadow-[0_8px_20px_rgba(16,185,129,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save text-lg"></i> Simpan Koreksi
                </button>
            </div>
        </div>
        
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. LIMITASI TANGGAL & INPUT
    const today = new Date(); 
    document.getElementById('tanggal_lahir').max = new Date(today.getFullYear() - 40, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    
    document.querySelectorAll('input[type="number"]').forEach(input => { 
        input.addEventListener('input', function() { 
            if (this.name === 'nik') { if (this.value.length > 16) this.value = this.value.slice(0, 16); }
        }); 
    });

    // 2. AUTO-CALC USIA LANSIA
    function calculateAge() {
        const dobInput = document.getElementById('tanggal_lahir').value;
        const helper = document.getElementById('age-helper');
        if(!dobInput) { helper.innerHTML = ''; return; }

        const dob = new Date(dobInput);
        let y = today.getFullYear() - dob.getFullYear();
        if (today.getMonth() < dob.getMonth() || (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())) { y--; }

        let alertClass = 'bg-emerald-50 border-emerald-100 text-emerald-700';
        let alertIcon = 'fa-check-circle text-emerald-500';
        let alertMsg = '<span class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Lansia/Pra-Lansia Valid</span>';
        
        if(y < 45) {
            alertClass = 'bg-rose-50 border-rose-200 text-rose-700';
            alertIcon = 'fa-exclamation-triangle text-rose-500';
            alertMsg = '<span class="text-[9px] bg-rose-500 text-white px-2 py-0.5 rounded ml-2 shadow-sm uppercase tracking-widest">Di Bawah Kriteria</span>';
        }

        helper.innerHTML = `<div class="mt-3 inline-flex items-center ${alertClass} border px-4 py-2 rounded-xl text-xs font-bold shadow-sm animate-slide-up"><i class="fas ${alertIcon} mr-2 text-lg"></i> Usia tercatat: ${y} Tahun ${alertMsg}</div>`;
    }

    if(document.getElementById('tanggal_lahir').value) calculateAge();

    // 3. AUTO-CALC IMT REAL-TIME
    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        const preview = document.getElementById('imt-preview');
        
        if (!bb || !tb || tb < 50) { preview.classList.add('hidden'); return; }
        
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk' : 'Obesitas'));
        let color = imt < 18.5 ? 'bg-amber-500 text-white border-amber-600' : (imt < 25 ? 'bg-emerald-500 text-white border-emerald-600' : 'bg-rose-500 text-white border-rose-600');
        
        document.getElementById('imt-angka').textContent = imt;
        document.getElementById('imt-label').textContent = kat;
        preview.className = `border rounded-[16px] p-4 flex items-center gap-4 mt-3 transition-all shadow-sm ${color}`;
        preview.classList.remove('hidden');
    }
    
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);
    
    // Tembakkan kalkulasi awal jika data lama (edit) tersedia
    if(document.getElementById('berat_badan').value && document.getElementById('tinggi_badan').value) hitungIMT();

    // 4. LOADER & SUBMIT SECURITY
    const hideLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 300); } 
        const btn = document.getElementById('btnSubmit');
        if(btn) { btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Koreksi'; btn.classList.remove('opacity-75', 'cursor-wait'); btn.disabled = false;}
    };
    const showLoader = () => { 
        const l = document.getElementById('smoothLoader'); 
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0','pointer-events-none'); l.classList.add('opacity-100','pointer-events-auto'); } 
    };

    window.onload = hideLoader;
    document.addEventListener('DOMContentLoaded', hideLoader);
    window.addEventListener('pageshow', hideLoader);

    document.getElementById('formEditLansia').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-wait');
        btn.disabled = true;
        showLoader();
    });

    // 5. SERVER ERROR HANDLER (SWEETALERT)
    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Pembaruan Gagal',
            html: `<p class="mt-2 text-sm text-slate-600">{!! addslashes(session('error')) !!}</p>`,
            confirmButtonText: 'Saya Mengerti', buttonsStyling: false,
            customClass: { 
                popup: 'rounded-[32px] p-8 bg-white/95 backdrop-blur-xl border border-slate-100 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]', 
                confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-full font-black text-[11px] uppercase tracking-widest transition-all shadow-md mt-4' 
            }
        });
    @endif
</script>
@endpush
@endsection