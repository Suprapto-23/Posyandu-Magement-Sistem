@extends('layouts.bidan')

@section('title', 'Sistem E-Rujukan Medis')
@section('page-name', 'Manajemen Rujukan')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .table-row-hover:hover { 
        background-color: #f8fafc; 
        transform: scale-[1.005]; 
        box-shadow: 0 4px 15px -5px rgba(0,0,0,0.05); 
        border-radius: 16px; 
    }
    
    /* Efek Kertas Cetak pada tombol */
    .btn-print { position: relative; overflow: hidden; transition: all 0.3s ease; }
    .btn-print::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(255,255,255,0.2), transparent); opacity: 0; transition: opacity 0.3s; }
    .btn-print:hover::after { opacity: 1; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6 lg:space-y-8 animate-slide-up pb-10">

    {{-- ================================================================
         1. HERO HEADER (Desain Alert/Warning Estetik)
         ================================================================ --}}
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[32px] p-8 md:p-10 text-white relative overflow-hidden shadow-[0_10px_40px_rgba(245,158,11,0.3)]">
        {{-- Dekorasi Latar --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-20 pointer-events-none hidden md:block">
            <i class="fas fa-file-medical-alt text-9xl"></i>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-[20px] bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl shrink-0 shadow-inner">
                    <i class="fas fa-ambulance"></i>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2 backdrop-blur-md border border-white/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Auto-Detect System
                    </span>
                    <h1 class="text-3xl font-black tracking-tight leading-none mb-2 font-poppins">E-Rujukan Terintegrasi</h1>
                    <p class="text-[13px] font-medium text-amber-50 max-w-xl leading-relaxed">
                        Sistem mendeteksi <b>{{ $rujukans->total() }} warga</b> yang membutuhkan penanganan lebih lanjut di Puskesmas berdasarkan hasil deteksi Stunting, Hipertensi, atau catatan tindakan Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         2. AREA KERJA (Tabel Daftar Rujukan)
         ================================================================ --}}
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col overflow-hidden">
        
        {{-- Header Kontrol (Search) --}}
        <div class="p-5 md:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-[15px] font-black text-slate-800 font-poppins flex items-center gap-2">
                <i class="fas fa-list-ul text-amber-500"></i> Daftar Tunggu Rujukan
            </h3>
            
            <form method="GET" action="{{ route('bidan.rujukan.index') }}" class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien rujukan..." class="w-full bg-white border border-slate-200 rounded-[16px] pl-11 pr-4 py-3 text-[12px] font-bold text-slate-700 focus:border-amber-500 focus:ring-4 focus:ring-amber-50 outline-none transition-all shadow-sm">
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="flex-1 overflow-x-auto custom-scrollbar p-2 md:p-4 min-h-[300px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pasien & Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Indikasi Rujukan Medis</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tgl Pemeriksaan</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Tindakan Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rujukans as $rujuk)
                    @php
                        // Deteksi Kategori
                        $nama = $rujuk->balita->nama_lengkap ?? $rujuk->remaja->nama_lengkap ?? $rujuk->lansia->nama_lengkap ?? $rujuk->ibuHamil->nama_lengkap ?? 'Anonim';
                        $kategoriRaw = strtolower(class_basename($rujuk->kategori_pasien ?? $rujuk->pasien_type));
                        
                        if($kategoriRaw == 'balita') { $nCol = 'sky'; $nIco = 'baby'; $kat = 'Balita'; }
                        elseif($kategoriRaw == 'remaja') { $nCol = 'indigo'; $nIco = 'user-graduate'; $kat = 'Remaja'; }
                        elseif(in_array($kategoriRaw, ['ibu_hamil','ibuhamil','bumil'])) { $nCol = 'pink'; $nIco = 'female'; $kat = 'Ibu Hamil'; }
                        else { $nCol = 'emerald'; $nIco = 'user-clock'; $kat = 'Lansia';}

                        // Algoritma Penentuan Alasan Rujukan (Cerdas)
                        $alasanRujukan = [];
                        if (str_contains(strtolower($rujuk->indikasi_stunting), 'stunting')) {
                            $alasanRujukan[] = ['text' => 'Stunting', 'color' => 'rose', 'icon' => 'exclamation-triangle'];
                        }
                        if ($rujuk->tekanan_darah && intval(explode('/', $rujuk->tekanan_darah)[0]) >= 140) {
                            $alasanRujukan[] = ['text' => 'Hipertensi', 'color' => 'amber', 'icon' => 'heartbeat'];
                        }
                        if (str_contains(strtolower($rujuk->tindakan), 'rujuk')) {
                            $alasanRujukan[] = ['text' => 'Saran Bidan', 'color' => 'cyan', 'icon' => 'comment-medical'];
                        }
                        // Fallback jika tidak terdeteksi spesifik
                        if(empty($alasanRujukan)) {
                            $alasanRujukan[] = ['text' => 'Evaluasi Klinis', 'color' => 'slate', 'icon' => 'stethoscope'];
                        }
                    @endphp
                    
                    <tr class="table-row-hover transition-all duration-200 group border-b border-slate-50 last:border-0">
                        
                        {{-- Kolom 1: Identitas --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[14px] bg-{{$nCol}}-50 text-{{$nCol}}-600 flex items-center justify-center shrink-0 border border-{{$nCol}}-100 shadow-inner group-hover:scale-110 transition-transform">
                                    <i class="fas fa-{{$nIco}} text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-1 group-hover:text-amber-600 transition-colors">{{ $nama }}</p>
                                    <span class="text-[9px] font-black text-{{$nCol}}-600 uppercase tracking-widest border border-{{$nCol}}-200 bg-white px-2 py-0.5 rounded shadow-sm">{{ $kat }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom 2: Indikasi Medis Cerdas --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex flex-wrap gap-2">
                                @foreach($alasanRujukan as $alasan)
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-{{$alasan['color']}}-50 border border-{{$alasan['color']}}-200 rounded-lg text-{{$alasan['color']}}-600">
                                        <i class="fas fa-{{$alasan['icon']}} text-[10px]"></i>
                                        <span class="text-[11px] font-bold">{{ $alasan['text'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] font-medium text-slate-500 mt-2 truncate max-w-xs" title="{{ $rujuk->diagnosa }}">
                                <i class="fas fa-quote-left text-slate-300 mr-1"></i> {{ Str::words($rujuk->diagnosa ?? 'Tidak ada catatan diagnosa', 6) }}
                            </p>
                        </td>

                        {{-- Kolom 3: Waktu --}}
                        <td class="py-4 px-6 align-middle">
                            <p class="font-bold text-slate-700 text-[12px]"><i class="far fa-calendar-alt text-slate-400 mr-1"></i> {{ \Carbon\Carbon::parse($rujuk->tanggal_periksa)->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Status: EMR Valid</p>
                        </td>

                        {{-- Kolom 4: Tombol Cetak --}}
                        <td class="py-4 px-6 text-right align-middle">
                            <a href="{{ route('bidan.rujukan.cetak', $rujuk->id) }}" target="_blank" class="btn-print inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-[14px] hover:bg-amber-500 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-print"></i> Cetak Surat
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-400 mb-5 shadow-inner relative">
                                <i class="fas fa-shield-alt text-5xl"></i>
                                <div class="absolute inset-0 border-2 border-emerald-400 rounded-full animate-ping opacity-20"></div>
                            </div>
                            <h3 class="text-[18px] font-black text-slate-800 font-poppins mb-2">Kondisi Warga Aman!</h3>
                            <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto leading-relaxed">
                                Hebat! Tidak ada sistem deteksi risiko tinggi (Stunting/Hipertensi) dari hasil pemeriksaan EMR terbaru. Tidak ada surat rujukan yang perlu dicetak saat ini.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($rujukans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $rujukans->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection