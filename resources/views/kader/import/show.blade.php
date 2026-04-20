@extends('layouts.kader')
@section('title', 'Terminal Log Detail')
@section('page-name', 'Log Analisis Sistem')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .terminal-window {
        background: #0f172a; border-radius: 24px; color: #10b981; font-family: 'Courier New', monospace;
        padding: 2rem; overflow-x: auto; box-shadow: inset 0 10px 30px rgba(0,0,0,0.5), 0 10px 40px -10px rgba(0,0,0,0.2);
        border: 1px solid #334155; position: relative;
    }
    .terminal-window::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
        background-size: 100% 2px, 3px 100%; z-index: 10; opacity: 0.1;
    }
    .blink { animation: blinker 1s linear infinite; }
    @keyframes blinker { 50% { opacity: 0; } }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-slide-up pb-10">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.import.history') }}" class="loader-trigger w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Laporan Eksekusi Server</h1>
                <p class="text-slate-500 mt-1 font-medium text-[13px]">Detail log analitik untuk: <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $import->nama_file }}</span></p>
            </div>
        </div>
    </div>

    <div class="premium-card p-8 md:p-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[24px] p-6 border border-emerald-100 flex gap-5 relative overflow-hidden shadow-sm">
                <div class="absolute -right-6 -bottom-6 text-emerald-500/10 text-8xl pointer-events-none"><i class="fas fa-database"></i></div>
                <div class="w-14 h-14 rounded-2xl bg-white text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl shrink-0 z-10 shadow-sm"><i class="fas fa-check"></i></div>
                <div class="z-10">
                    <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-1.5 opacity-80">Total Tersimpan</p>
                    <p class="text-4xl font-black text-emerald-600 font-poppins leading-none">{{ $import->data_tersimpan ?? '0' }} <span class="text-sm font-bold text-emerald-500 ml-1">Baris</span></p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-[24px] p-6 border border-indigo-100 flex gap-5 relative overflow-hidden shadow-sm">
                <div class="absolute -right-6 -bottom-6 text-indigo-500/10 text-8xl pointer-events-none"><i class="fas fa-microchip"></i></div>
                <div class="w-14 h-14 rounded-2xl bg-white text-indigo-600 border border-indigo-100 flex items-center justify-center text-2xl shrink-0 z-10 shadow-sm"><i class="fas fa-cogs"></i></div>
                <div class="z-10">
                    <p class="text-[10px] font-black text-indigo-700 uppercase tracking-widest mb-1.5 opacity-80">Modul Tujuan</p>
                    <p class="text-2xl mt-1 font-black text-indigo-700 font-poppins uppercase tracking-wider">{{ str_replace('_', ' ', $import->jenis_data) }}</p>
                </div>
            </div>
        </div>

        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 pl-2"><i class="fas fa-terminal text-indigo-400"></i> Command Line Interface (CLI)</h3>
        
        <div class="terminal-window">
            {{-- Mac OS Buttons Style --}}
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-emerald-900/40">
                <div class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_5px_rgba(244,63,94,0.5)]"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.5)]"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></div>
                <span class="text-slate-500 text-[10px] ml-3 font-bold font-sans tracking-widest uppercase bg-slate-800 px-3 py-1 rounded-md">KaderCare_System_v2.0</span>
            </div>
            
            <p class="text-[13px] leading-loose break-words whitespace-pre-wrap font-bold relative z-20 text-emerald-400">
> [{{ $import->created_at->format('H:i:s') }}] Menginisialisasi koneksi aman ke database...
> [{{ $import->created_at->format('H:i:s') }}] Membaca struktur file: {{ $import->nama_file }}

<span class="{{ $import->status == 'failed' ? 'text-rose-400' : 'text-emerald-300' }}">{{ $import->catatan }}</span>

> [{{ $import->updated_at->format('H:i:s') }}] Memory usage normal. Menutup koneksi...
> [{{ $import->updated_at->format('H:i:s') }}] Eksekusi dihentikan. <span class="blink">_</span>
            </p>
        </div>

    </div>
</div>
@endsection