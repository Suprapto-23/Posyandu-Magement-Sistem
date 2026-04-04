@extends('layouts.kader')
@section('title', 'Laporan Balita')
@section('page-name', 'Laporan Posyandu')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Custom Styling untuk Dropdown Select */
    .custom-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    /* Sembunyikan elemen UI Dashboard saat proses Print manual ke PDF */
    @media print {
        body { background-color: white !important; }
        #sidebar, header, .print\:hidden, .flash-message { display: none !important; }
        .lg\:ml-\[280px\] { margin-left: 0 !important; }
        main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .paper-preview { border: none !important; box-shadow: none !important; margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
    }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up">

    {{-- KARTU HEADER: Judul & Tombol Unduh --}}
    <div class="bg-white rounded-[24px] p-6 md:p-8 mb-6 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col md:flex-row items-start md:items-center justify-between gap-6 print:hidden">
        <div class="flex items-center gap-5">
            {{-- Ikon Bulat Estetik --}}
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-3xl shrink-0">
                <i class="fas fa-baby"></i>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-1">Laporan Balita</h2>
                <p class="text-slate-500 text-sm font-medium">Rekapitulasi data pemeriksaan balita bulanan posyandu.</p>
            </div>
        </div>

        <div class="w-full md:w-auto">
            <a href="{{ route('kader.laporan.generate', ['type' => 'balita', 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl transition-all shadow-[0_4px_15px_rgba(225,29,72,0.3)] hover:-translate-y-1">
                <i class="fas fa-file-pdf text-lg"></i> Unduh PDF Resmi
            </a>
        </div>
    </div>

    {{-- KARTU FILTER --}}
    <div class="bg-white rounded-[20px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-4 mb-6 print:hidden">
        <form action="{{ route('kader.laporan.balita') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
            
            {{-- Input Bulan --}}
            <div class="flex-1 w-full relative flex items-center bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus-within:border-rose-400 focus-within:bg-white transition-colors">
                <i class="fas fa-calendar-alt text-slate-400 mr-3 text-lg"></i>
                <select name="bulan" class="w-full bg-transparent text-slate-700 text-sm font-bold outline-none cursor-pointer custom-select">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down text-slate-400 text-xs ml-auto"></i>
            </div>
            
            {{-- Input Tahun --}}
            <div class="flex-1 w-full relative flex items-center bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus-within:border-rose-400 focus-within:bg-white transition-colors">
                <i class="fas fa-calendar-week text-slate-400 mr-3 text-lg"></i>
                <select name="tahun" class="w-full bg-transparent text-slate-700 text-sm font-bold outline-none cursor-pointer custom-select">
                    @foreach(range(date('Y')-2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down text-slate-400 text-xs ml-auto"></i>
            </div>
            
            {{-- Tombol Submit --}}
            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-sm rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                <i class="fas fa-filter"></i> Filter Laporan
            </button>
        </form>
    </div>

    {{-- AREA DOKUMEN (MEJA & KERTAS) --}}
    <div class="bg-slate-100 rounded-[32px] border border-slate-200 p-6 md:p-10 shadow-inner overflow-x-auto print:bg-white print:p-0 print:border-none print:shadow-none">
        
        {{-- Banner Notifikasi Preview --}}
        <div class="mb-8 flex items-start gap-4 max-w-[1122px] mx-auto print:hidden">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-500 flex items-center justify-center shrink-0 shadow-sm border border-amber-200 text-xl">
                <i class="fas fa-eye"></i>
            </div>
            <div class="pt-1">
                <h4 class="text-base font-black text-slate-800 mb-1">Mode Pratinjau (Document Preview)</h4>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">
                    Tampilan di bawah adalah simulasi cetak kertas <span class="font-bold text-slate-700">A4 Landscape</span>. Kop surat resmi, margin, dan ukuran tabel akan otomatis disesuaikan dengan rapi dan sempurna saat Anda menekan tombol <strong class="text-slate-800">Unduh PDF Resmi</strong>.
                </p>
            </div>
        </div>

        {{-- Lembar Kertas Preview --}}
        <div class="paper-preview bg-white mx-auto shadow-[0_15px_50px_rgba(0,0,0,0.08)] border border-slate-200 print:shadow-none print:border-none relative" style="min-width: 1000px; max-width: 1122px; padding: 60px; border-radius: 8px;">
            {{-- Mengambil komponen tabel balita --}}
            @include('kader.laporan.templates.table-balita')
        </div>
    </div>

</div>
@endsection