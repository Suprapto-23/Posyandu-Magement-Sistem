@extends('layouts.kader')

@section('title', 'Edit Pemeriksaan')
@section('page-name', 'Koreksi Data')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
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
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/80 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-amber-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-edit text-amber-500 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-amber-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase" id="loaderText">MENYIAPKAN DATA...</p>
</div>

<div class="max-w-[1000px] mx-auto animate-slide-up relative pb-12">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="smooth-route w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-amber-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(245,158,11,0.4)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="w-24 h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib"></i>
        </div>
        <div class="text-center md:text-left">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest">
                <i class="fas fa-exclamation-circle text-white"></i> Mode Koreksi Aktif
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight font-poppins mb-2">Edit Data Pengukuran</h1>
            <p class="text-amber-50 font-medium text-[14px] max-w-xl">Lakukan koreksi pada data antropometri yang telah dimasukkan jika terdapat kesalahan ketik atau salah ukur pasien.</p>
        </div>
    </div>

    <form action="{{ route('kader.pemeriksaan.update', $pemeriksaan->id) }}" method="POST" id="formPemeriksaan" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col mb-8">
            
            <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-2xl font-black shadow-inner border border-slate-300">
                        {{ strtoupper(substr($pemeriksaan->nama_pasien, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800 font-poppins">{{ $pemeriksaan->nama_pasien }}</h4>
                        <p class="text-[12px] font-bold text-slate-400 mt-1"><i class="far fa-calendar-check text-amber-500 mr-1"></i> Tgl Diperiksa: {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="px-5 py-2 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Kategori Warga</span>
                    <span class="text-[14px] font-black text-amber-600 uppercase tracking-wider">{{ $pemeriksaan->kategori_pasien }}</span>
                </div>
            </div>

            <div class="p-8 md:p-12 bg-white">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-8">
                    <div class="col-span-1">
                        <label class="form-label">Berat B. (kg) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="berat_badan" value="{{ old('berat_badan', $pemeriksaan->berat_badan) }}" required class="form-input text-center text-lg">
                    </div>
                    <div class="col-span-1">
                        <label class="form-label">Tinggi B. (cm) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="tinggi_badan" value="{{ old('tinggi_badan', $pemeriksaan->tinggi_badan) }}" required class="form-input text-center text-lg">
                    </div>

                    @if(in_array($pemeriksaan->kategori_pasien, ['balita', 'remaja']))
                    <div class="col-span-1">
                        <label class="form-label text-indigo-500">L. Lengan (cm)</label>
                        <input type="number" step="0.1" name="lingkar_lengan" value="{{ old('lingkar_lengan', $pemeriksaan->lingkar_lengan) }}" class="form-input text-center">
                    </div>
                    @endif

                    @if($pemeriksaan->kategori_pasien == 'balita')
                    <div class="col-span-1">
                        <label class="form-label text-rose-500">L. Kepala (cm)</label>
                        <input type="number" step="0.1" name="lingkar_kepala" value="{{ old('lingkar_kepala', $pemeriksaan->lingkar_kepala) }}" class="form-input text-center">
                    </div>
                    @endif

                    @if(in_array($pemeriksaan->kategori_pasien, ['remaja', 'lansia']))
                    <div class="col-span-2 sm:col-span-2">
                        <label class="form-label text-rose-500">Tensi Darah (mmHg)</label>
                        <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah', $pemeriksaan->tekanan_darah) }}" placeholder="Contoh: 120/80" class="form-input text-center bg-rose-50/30 border-rose-200 focus:border-rose-500">
                    </div>
                    @endif

                    @if($pemeriksaan->kategori_pasien == 'lansia')
                    <div class="col-span-2 sm:col-span-4 mt-4">
                        <div class="p-6 rounded-[24px] bg-emerald-50/50 border border-emerald-100">
                            <h4 class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fas fa-vial"></i> Pengecekan Lab Lansia</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <label class="form-label text-emerald-700">Gula Darah (mg/dL)</label>
                                    <input type="text" name="gula_darah" value="{{ old('gula_darah', $pemeriksaan->gula_darah) }}" class="form-input text-center border-emerald-200 focus:border-emerald-500 bg-white">
                                </div>
                                <div>
                                    <label class="form-label text-emerald-700">Asam Urat (mg/dL)</label>
                                    <input type="number" step="0.1" name="asam_urat" value="{{ old('asam_urat', $pemeriksaan->asam_urat) }}" class="form-input text-center border-emerald-200 focus:border-emerald-500 bg-white">
                                </div>
                                <div>
                                    <label class="form-label text-emerald-700">Kolesterol (mg/dL)</label>
                                    <input type="number" name="kolesterol" value="{{ old('kolesterol', $pemeriksaan->kolesterol) }}" class="form-input text-center border-emerald-200 focus:border-emerald-500 bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-8 border-t border-slate-100 pt-8">
                    <label class="form-label flex items-center gap-2"><i class="fas fa-comment-medical text-amber-500"></i> Keluhan Utama Pasien</label>
                    <textarea name="keluhan" rows="3" class="form-input resize-none bg-white">{{ old('keluhan', $pemeriksaan->keluhan) }}</textarea>
                </div>
            </div>
            
        </div>
        
        <div class="sticky bottom-6 z-40 bg-white/90 backdrop-blur-xl border border-slate-200 p-5 rounded-[24px] shadow-[0_20px_40px_rgba(0,0,0,0.1)] flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('kader.pemeriksaan.index') }}" class="smooth-route w-full sm:w-auto px-8 py-4 bg-slate-100 text-slate-600 font-black text-[13px] rounded-xl hover:bg-slate-200 transition-colors text-center uppercase tracking-widest">
                Batalkan
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                <i class="fas fa-save"></i> Update Koreksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const forceHideLoader = () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
        const btn = document.getElementById('btnSubmit');
        if(btn) {
            btn.innerHTML = '<i class="fas fa-save"></i> UPDATE KOREKSI';
            btn.classList.remove('opacity-75', 'cursor-wait');
        }
    };

    document.addEventListener('DOMContentLoaded', forceHideLoader);
    window.addEventListener('pageshow', forceHideLoader);

    document.getElementById('formPemeriksaan').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
    });
</script>
@endpush
@endsection