@extends('layouts.kader')
@section('title', 'Detail Pemeriksaan Fisik')
@section('page-name', 'Log Ukur Antropometri')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.08); }
    .nota-bg { background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 20px 20px; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-file-medical-alt text-2xl animate-pulse"></i></div>
    </div>
</div>

<div class="max-w-4xl mx-auto animate-slide-up relative z-10 pb-10">
    
    <div class="mb-6 flex items-center justify-between relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        @if($pemeriksaan->status_verifikasi == 'verified')
            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-100 text-emerald-700 font-black text-xs uppercase tracking-widest rounded-xl border border-emerald-200 shadow-sm"><i class="fas fa-check-circle"></i> Validasi Bidan Selesai</span>
        @else
            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-100 text-amber-700 font-black text-xs uppercase tracking-widest rounded-xl border border-amber-200 shadow-sm animate-pulse"><i class="fas fa-clock"></i> Menunggu Meja 5 (Bidan)</span>
        @endif
    </div>

    <div class="glass-card rounded-[32px] overflow-hidden shadow-xl border border-slate-200">
        
        {{-- KOP NOTA --}}
        <div class="bg-slate-800 text-white p-8 md:p-10 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-40 h-40 bg-indigo-500/30 rounded-bl-full blur-2xl"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-3xl shrink-0">
                        <i class="fas fa-clipboard-list text-indigo-200"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-indigo-300 uppercase tracking-widest mb-1">Nota Fisik Posyandu</p>
                        <h2 class="text-3xl font-black font-poppins tracking-tight">{{ $pemeriksaan->nama_pasien }}</h2>
                        <p class="text-sm font-medium text-slate-300 mt-1">ID Kunjungan: <span class="font-mono text-white">{{ $pemeriksaan->kunjungan->kode_kunjungan ?? 'AUTO-SYNC' }}</span></p>
                    </div>
                </div>
                <div class="text-left md:text-right bg-black/20 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-1">Waktu Pengukuran</p>
                    <p class="text-lg font-black">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- ISI DATA FISIK --}}
        <div class="p-8 md:p-10 nota-bg bg-slate-50/50">
            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2"><i class="fas fa-ruler text-indigo-400 mr-1"></i> Antropometri & Fisik Dasar</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded-[20px] border border-slate-200 shadow-sm text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Berat Badan</p>
                    <p class="text-2xl font-black text-indigo-600 font-poppins">{{ $pemeriksaan->berat_badan ?? '-' }}<span class="text-xs text-slate-400 ml-1">kg</span></p>
                </div>
                <div class="bg-white p-4 rounded-[20px] border border-slate-200 shadow-sm text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tinggi Badan</p>
                    <p class="text-2xl font-black text-emerald-600 font-poppins">{{ $pemeriksaan->tinggi_badan ?? '-' }}<span class="text-xs text-slate-400 ml-1">cm</span></p>
                </div>
                @if($pemeriksaan->kategori_pasien != 'balita')
                <div class="bg-white p-4 rounded-[20px] border border-slate-200 shadow-sm text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">IMT</p>
                    <p class="text-2xl font-black text-rose-500 font-poppins">{{ $pemeriksaan->imt ?? '-' }}</p>
                </div>
                @endif
                @if($pemeriksaan->kategori_pasien == 'balita')
                <div class="bg-white p-4 rounded-[20px] border border-slate-200 shadow-sm text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">L. Kepala</p>
                    <p class="text-2xl font-black text-sky-500 font-poppins">{{ $pemeriksaan->lingkar_kepala ?? '-' }}<span class="text-xs text-slate-400 ml-1">cm</span></p>
                </div>
                @endif
            </div>

            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2"><i class="fas fa-vial text-rose-400 mr-1"></i> Pemeriksaan Khusus Kategori: {{ strtoupper(str_replace('_', ' ', $pemeriksaan->kategori_pasien)) }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if(in_array($pemeriksaan->kategori_pasien, ['ibu_hamil', 'remaja']))
                    <div class="bg-rose-50 p-4 rounded-xl border border-rose-100">
                        <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-0.5">LILA</p>
                        <p class="text-lg font-black text-rose-700">{{ $pemeriksaan->lingkar_lengan ?? '-' }} cm</p>
                    </div>
                @endif
                
                @if(in_array($pemeriksaan->kategori_pasien, ['lansia', 'remaja']))
                    <div class="bg-sky-50 p-4 rounded-xl border border-sky-100">
                        <p class="text-[10px] font-black text-sky-400 uppercase tracking-widest mb-0.5">Tensi</p>
                        <p class="text-lg font-black text-sky-700">{{ $pemeriksaan->tekanan_darah ?? '-' }}</p>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                        <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-0.5">Gula Darah</p>
                        <p class="text-lg font-black text-amber-700">{{ $pemeriksaan->gula_darah ?? '-' }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                        <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest mb-0.5">Kolesterol / A.Urat</p>
                        <p class="text-sm font-black text-purple-700">K: {{ $pemeriksaan->kolesterol ?? '-' }} / A: {{ $pemeriksaan->asam_urat ?? '-' }}</p>
                    </div>
                @endif
            </div>

            {{-- CATATAN BIDAN JIKA SUDAH DIVALIDASI --}}
            <div class="mt-10">
                @if($pemeriksaan->status_verifikasi == 'verified')
                    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 rounded-r-2xl shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-md text-indigo-500 text-xl"></i>
                            <h4 class="font-black text-indigo-900 uppercase tracking-widest text-sm">Hasil Diagnosa Bidan</h4>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-indigo-100 mt-3 text-sm font-medium text-slate-700 leading-relaxed italic">
                            "{{ $pemeriksaan->diagnosa ?? 'Tidak ada catatan diagnosa tambahan dari Bidan.' }}"
                        </div>
                    </div>
                @else
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center bg-white/50">
                        <i class="fas fa-lock text-3xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Kolom Diagnosa Bidan Terkunci</p>
                        <p class="text-xs text-slate-400 mt-1">Akan terbuka setelah Bidan selesai melakukan pemeriksaan pada meja 5.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.classList.remove('opacity-100', 'pointer-events-auto'); 
            l.classList.add('opacity-0', 'pointer-events-none'); 
            setTimeout(() => l.style.display = 'none', 300); 
        }
    };
    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.style.display = 'flex'; 
            l.classList.remove('opacity-0', 'pointer-events-none'); 
            l.classList.add('opacity-100', 'pointer-events-auto'); 
        }
    };

    window.addEventListener('pageshow', function() {
        window.hideLoader(); // Paksa matikan loading saat halaman muncul dari cache browser
    });
    document.addEventListener('DOMContentLoaded', window.hideLoader);
</script>
@endpush
@endsection