@extends('layouts.kader')

@section('title', 'Detail Terminal Log')
@section('page-name', 'Log Analisis Sistem')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .terminal-window {
        background: #0f172a; border-radius: 20px; color: #10b981; font-family: 'Courier New', monospace;
        padding: 1.5rem; overflow-x: auto; box-shadow: inset 0 4px 20px rgba(0,0,0,0.5);
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-slide-up">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.import.history') }}" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-2xl flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-poppins">Laporan Server AI</h1>
                <p class="text-slate-500 mt-0.5 font-medium text-[13px]">Detail eksekusi untuk file <span class="font-bold text-indigo-600">{{ $import->nama_file }}</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-emerald-50 rounded-[20px] p-6 border border-emerald-100 flex gap-5 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 text-emerald-500/10 text-8xl pointer-events-none"><i class="fas fa-database"></i></div>
                <div class="w-12 h-12 rounded-full bg-emerald-200/50 text-emerald-600 flex items-center justify-center text-xl shrink-0 z-10"><i class="fas fa-check"></i></div>
                <div class="z-10">
                    <p class="text-[11px] font-black text-emerald-700 uppercase tracking-widest mb-1">Total Data Masuk Database</p>
                    <p class="text-4xl font-black text-emerald-600 font-poppins">{{ $import->data_tersimpan ?? '0' }} <span class="text-sm font-bold text-emerald-500">Baris</span></p>
                </div>
            </div>

            <div class="bg-indigo-50 rounded-[20px] p-6 border border-indigo-100 flex gap-5 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 text-indigo-500/10 text-8xl pointer-events-none"><i class="fas fa-microchip"></i></div>
                <div class="w-12 h-12 rounded-full bg-indigo-200/50 text-indigo-600 flex items-center justify-center text-xl shrink-0 z-10"><i class="fas fa-cogs"></i></div>
                <div class="z-10">
                    <p class="text-[11px] font-black text-indigo-700 uppercase tracking-widest mb-1">Target Modul Tabel</p>
                    <p class="text-2xl mt-1 font-black text-indigo-600 font-poppins uppercase tracking-wide">{{ $import->jenis_data }}</p>
                </div>
            </div>
        </div>

        <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fas fa-terminal"></i> Log Mesin Server</h3>
        
        <div class="terminal-window">
            <div class="flex items-center gap-2 mb-3 pb-3 border-b border-emerald-900/50">
                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                <span class="text-slate-400 text-[10px] ml-2 font-sans tracking-widest uppercase">KaderCare_System_Console</span>
            </div>
            <p class="text-[13px] leading-loose break-words whitespace-pre-wrap">
> Memulai proses koneksi ke database...
> Inisialisasi AI Smart Mapping...
> Membaca struktur kolom pada file {{ $import->nama_file }}...
> Eksekusi query sinkronisasi akun (Radar Sapu Jagat)...

<span class="{{ $import->status == 'failed' ? 'text-rose-400 font-bold' : 'text-emerald-400 font-bold' }}">>> OUTPUT: {{ $import->catatan }}</span>

> Koneksi ditutup. Memory usage normal.
> System Ready.
            </p>
        </div>

    </div>
</div>
@endsection