@extends('layouts.kader')

@section('title', 'Import Data Masal')
@section('page-name', 'Smart Import Center')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in { opacity: 0; animation: fadeIn 1s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.2); border-color: rgba(99, 102, 241, 0.3);
    }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto">
    
    <div class="relative bg-gradient-to-br from-indigo-900 via-indigo-800 to-violet-900 rounded-[32px] sm:rounded-[40px] p-8 sm:p-12 md:p-16 mb-12 overflow-hidden shadow-2xl animate-fade-in">
        <div class="absolute top-0 right-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-violet-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="max-w-2xl text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-indigo-200 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> AI Engine Active
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight font-poppins tracking-tight mb-6">
                    Pindahkan Data Excel ke Sistem dalam <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500">Hitungan Detik.</span>
                </h1>
                <p class="text-lg text-indigo-200 font-medium leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                    Tidak perlu entri data satu-satu. Cukup unggah file Excel/CSV, dan biarkan kecerdasan buatan (AI) kami yang mencocokkan kolom secara otomatis.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('kader.import.create') }}" class="px-8 py-4 bg-white text-indigo-600 font-black rounded-xl hover:bg-indigo-50 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:scale-105 flex items-center justify-center gap-2">
                        <i class="fas fa-rocket"></i> Mulai Wizard Import
                    </a>
                    <a href="{{ route('kader.import.history') }}" class="px-8 py-4 bg-indigo-800/50 border border-indigo-400/30 text-white font-bold rounded-xl hover:bg-indigo-800 transition-all backdrop-blur-md flex items-center justify-center gap-2">
                        <i class="fas fa-history"></i> Lihat Log Riwayat
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="w-72 h-72 bg-gradient-to-tr from-indigo-500 to-violet-400 rounded-[40px] rotate-12 absolute inset-0 opacity-50 blur-lg"></div>
                <div class="w-72 h-72 bg-white/10 backdrop-blur-2xl border border-white/20 rounded-[40px] -rotate-6 flex items-center justify-center relative shadow-2xl p-6">
                    <i class="fas fa-database text-[120px] text-white/90 drop-shadow-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[32px] p-8 md:p-10 border border-slate-200/80 shadow-sm animate-slide-up mb-10">
        <h6 class="text-[13px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <i class="fas fa-shield-alt text-indigo-500"></i> Informasi & Validasi Mesin
        </h6>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-indigo-50/50 border border-indigo-100 p-6 rounded-2xl flex gap-5 hover:bg-indigo-50 transition-colors">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm text-indigo-600 text-xl border border-indigo-100">
                    <i class="fas fa-file-excel"></i>
                </div>
                <div>
                    <p class="text-slate-800 font-extrabold text-sm mb-1.5">Mendukung .XLSX & .CSV</p>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">Bebas menggunakan file Excel versi apapun. Disarankan menggunakan .xlsx untuk menghindari error format koma pada regional Indonesia.</p>
                </div>
            </div>
            <div class="bg-rose-50/50 border border-rose-100 p-6 rounded-2xl flex gap-5 hover:bg-rose-50 transition-colors">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm text-rose-600 text-xl border border-rose-100">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <p class="text-slate-800 font-extrabold text-sm mb-1.5">Anti-Duplikasi Data (NIK)</p>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">Data dengan NIK yang sudah ada di database akan dilewati secara otomatis oleh sistem agar data Anda tetap bersih tanpa ganda.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection