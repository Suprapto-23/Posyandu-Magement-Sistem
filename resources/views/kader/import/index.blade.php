@extends('layouts.kader')
@section('title', 'Import Data Masal')
@section('page-name', 'Smart Import Center')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .animate-fade-in { opacity: 0; animation: fadeIn 1s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    .tech-bg {
        background-color: #1e1b4b;
        background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.4) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.4) 0px, transparent 50%);
    }
    .floating-icon { animation: float 6s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto pb-10">
    
    {{-- HERO SECTION (TEKNOLOGI AI) --}}
    <div class="relative tech-bg rounded-[32px] sm:rounded-[40px] p-8 sm:p-12 md:p-16 mb-10 overflow-hidden shadow-[0_20px_50px_-10px_rgba(49,46,129,0.3)] animate-fade-in border border-indigo-500/30">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none mix-blend-overlay"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="max-w-2xl text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-md shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> AI Engine Active
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-[1.1] font-poppins tracking-tight mb-6 drop-shadow-md">
                    Pindahkan Database ke Sistem dalam <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-emerald-300">Hitungan Detik.</span>
                </h1>
                
                <p class="text-[15px] text-indigo-100/90 font-medium leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                    Selamat tinggal proses *input* data manual. Unggah file Excel/CSV, dan biarkan kecerdasan buatan (AI) PosyanduCare mencocokkan kolom secara otomatis dan mengeliminasi data ganda.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('kader.import.create') }}" class="spa-route px-8 py-4 bg-white text-indigo-900 font-black text-[13px] uppercase tracking-widest rounded-[16px] hover:bg-indigo-50 transition-all shadow-[0_0_30px_rgba(255,255,255,0.2)] hover:-translate-y-1 flex items-center justify-center gap-3">
                        <i class="fas fa-rocket text-lg"></i> Mulai Import Data
                    </a>
                    <a href="{{ route('kader.import.history') }}" class="spa-route px-8 py-4 bg-indigo-900/40 border border-indigo-400/30 text-white font-bold text-[13px] uppercase tracking-widest rounded-[16px] hover:bg-indigo-800/60 transition-all backdrop-blur-md flex items-center justify-center gap-3 hover:shadow-lg hover:-translate-y-1">
                        <i class="fas fa-server text-lg"></i> Log Riwayat
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:flex relative items-center justify-center">
                <div class="w-72 h-72 bg-gradient-to-tr from-cyan-400 to-emerald-400 rounded-[40px] rotate-12 absolute inset-0 opacity-40 blur-2xl animate-pulse"></div>
                <div class="w-72 h-72 bg-white/10 backdrop-blur-xl border border-white/30 rounded-[40px] -rotate-6 flex items-center justify-center relative shadow-2xl floating-icon">
                    <i class="fas fa-database text-[110px] text-white drop-shadow-lg"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- INFORMASI FITUR (FIX CSS CLASS) --}}
    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 md:p-10 animate-slide-up delay-100">
        <h6 class="text-[13px] font-black text-slate-800 uppercase tracking-widest mb-8 flex items-center gap-3 font-poppins">
            <i class="fas fa-shield-check text-indigo-500 text-xl"></i> Informasi Mesin Validasi
        </h6>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-slate-50/50 border border-slate-100 p-8 rounded-[28px] flex flex-col sm:flex-row gap-6 hover:bg-white hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
                <div class="w-16 h-16 bg-indigo-50 rounded-[20px] flex items-center justify-center shrink-0 text-indigo-600 text-3xl border border-indigo-100 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-file-excel"></i>
                </div>
                <div>
                    <p class="text-slate-800 font-black text-[16px] font-poppins mb-2">Dukungan Multi Format</p>
                    <p class="text-slate-500 text-[13px] leading-relaxed font-medium">Bebas menggunakan file versi apapun. Sistem kami merekomendasikan penggunaan format <b>.xlsx</b> bawaan Microsoft Excel untuk menghindari kerusakan regional bahasa pada file CSV.</p>
                </div>
            </div>
            
            <div class="bg-slate-50/50 border border-slate-100 p-8 rounded-[28px] flex flex-col sm:flex-row gap-6 hover:bg-white hover:border-rose-200 hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 group">
                <div class="w-16 h-16 bg-rose-50 rounded-[20px] flex items-center justify-center shrink-0 text-rose-600 text-3xl border border-rose-100 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <p class="text-slate-800 font-black text-[16px] font-poppins mb-2">Anti-Duplikasi & Tabrakan Data</p>
                    <p class="text-slate-500 text-[13px] leading-relaxed font-medium">Mesin AI akan mendeteksi dan melewati baris data yang memiliki <b>NIK (Nomor Induk Kependudukan)</b> ganda di dalam database, mencegah error dan menjaga kebersihan data.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection