@extends('layouts.bidan')

@section('title', 'Antrian Pemeriksaan Klinis')
@section('page-name', 'Manajemen Antrian')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Styling khusus untuk Tab Navigasi */
    .tab-btn { position: relative; overflow: hidden; transition: all 0.3s ease; }
    .tab-btn.active { background: #ffffff; color: #0891b2; box-shadow: 0 4px 15px -3px rgba(8, 145, 178, 0.15); border-color: #cffafe; }
    .tab-btn.inactive { background: transparent; color: #64748b; border-color: transparent; }
    .tab-btn.inactive:hover { background: #f8fafc; color: #334155; }
    
    /* Efek hover pada baris tabel */
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.002]; box-shadow: 0 4px 10px -5px rgba(0,0,0,0.05); border-radius: 16px; }
</style>

<div class="space-y-6 lg:space-y-8 animate-slide-up">

    {{-- ================================================================
         1. HERO HEADER (Informasi Modul)
         ================================================================ --}}
    <div class="bg-white rounded-[32px] p-6 md:p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-gradient-to-tl from-cyan-100 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-inner border border-cyan-100">
                <i class="fas fa-procedures"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight font-poppins mb-1">Antrian Meja 5</h1>
                <p class="text-[13px] font-medium text-slate-500">Menerima data fisik dari Kader secara *real-time* untuk divalidasi.</p>
            </div>
        </div>

        {{-- Tombol Input Manual (Jika Bidan yang mengukur langsung) --}}
        <a href="{{ route('bidan.pemeriksaan.create') }}" class="relative z-10 inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 text-white text-[12px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-[0_10px_20px_rgba(0,0,0,0.1)] hover:-translate-y-1 w-full sm:w-auto justify-center">
            <i class="fas fa-plus-circle text-lg text-cyan-400"></i> Input Mandiri
        </a>
    </div>

    {{-- ================================================================
         2. AREA KERJA (Filter & Tabel)
         ================================================================ --}}
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col overflow-hidden">
        
        {{-- Kontrol Navigasi (Tabs & Search) --}}
        <div class="p-5 md:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            
            {{-- Tabs --}}
            <div class="flex items-center p-1.5 bg-slate-200/60 rounded-[20px] w-full lg:w-auto">
                <a href="{{ route('bidan.pemeriksaan.index', ['tab' => 'pending']) }}" class="tab-btn flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-[16px] text-[11px] font-black uppercase tracking-widest border {{ $tab == 'pending' ? 'active' : 'inactive' }}">
                    <i class="fas fa-clock text-sm"></i> Perlu Validasi
                    @if($pendingCount > 0)
                        <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shadow-sm ml-1 animate-pulse">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('bidan.pemeriksaan.index', ['tab' => 'verified']) }}" class="tab-btn flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 rounded-[16px] text-[11px] font-black uppercase tracking-widest border {{ $tab == 'verified' ? 'active' : 'inactive' }}">
                    <i class="fas fa-check-double text-sm"></i> Riwayat Selesai
                </a>
            </div>
            
            {{-- Search Bar --}}
            <form method="GET" action="{{ route('bidan.pemeriksaan.index') }}" class="relative w-full lg:w-96">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama warga atau NIK..." class="w-full bg-white border border-slate-200 rounded-[16px] pl-11 pr-4 py-3.5 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
                @if(request('search'))
                    <a href="{{ route('bidan.pemeriksaan.index', ['tab' => $tab]) }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabel Antrian --}}
        <div class="flex-1 overflow-x-auto custom-scrollbar p-2 md:p-4 min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pasien & Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Fisik Awal (Kader)</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu Masuk</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemeriksaans as $pem)
                    @php
                        // Deteksi Kategori & Pewarnaan per Klaster
                        $namaPasien = $pem->balita->nama_lengkap 
                                   ?? $pem->remaja->nama_lengkap 
                                   ?? $pem->lansia->nama_lengkap 
                                   ?? $pem->ibuHamil->nama_lengkap 
                                   ?? 'Anonim';
                        $kategoriRaw = strtolower(class_basename($pem->kategori_pasien ?? $pem->pasien_type));
                        
                        if ($kategoriRaw == 'bayi')       { $nCol = 'cyan';   $nIco = 'baby';          $kategori = 'Bayi';       $kategoriSub = '0–12 bln'; }
                        elseif ($kategoriRaw == 'balita') { $nCol = 'sky';    $nIco = 'child';         $kategori = 'Balita';     $kategoriSub = '1–5 thn'; }
                        elseif ($kategoriRaw == 'remaja') { $nCol = 'violet'; $nIco = 'user-graduate'; $kategori = 'Remaja';     $kategoriSub = '10–18 thn'; }
                        elseif (in_array($kategoriRaw, ['ibu_hamil','ibuhamil','bumil'])) 
                                                          { $nCol = 'pink';   $nIco = 'female';        $kategori = 'Ibu Hamil';  $kategoriSub = 'Bumil'; }
                        else                              { $nCol = 'emerald';$nIco = 'user-clock';    $kategori = 'Lansia';     $kategoriSub = '≥60 thn'; }
                        
                        // Ambil field utama sesuai kategori untuk ditampilkan di tabel
                        $bb  = $pem->berat_badan ? $pem->berat_badan.' kg' : '-';
                        $tb  = $pem->tinggi_badan ? $pem->tinggi_badan.' cm' : '-';
                        $extra = '';
                        if (in_array($kategoriRaw, ['remaja','lansia','ibu_hamil','ibuhamil','bumil']) && $pem->tekanan_darah) {
                            $extra = 'Tensi: '.$pem->tekanan_darah.' mmHg';
                        } elseif (in_array($kategoriRaw, ['balita','bayi']) && $pem->lingkar_kepala) {
                            $extra = 'LK: '.$pem->lingkar_kepala.' cm';
                        }
                    @endphp
                    
                    <tr class="table-row-hover transition-all duration-200 group border-b border-slate-50 last:border-0">
                        
                        {{-- Identitas --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[14px] flex items-center justify-center shrink-0 border shadow-inner group-hover:scale-110 transition-transform
                                            {{ $nCol == 'sky' ? 'bg-sky-50 text-sky-600 border-sky-100' : '' }}
                                            {{ $nCol == 'cyan' ? 'bg-cyan-50 text-cyan-600 border-cyan-100' : '' }}
                                            {{ $nCol == 'violet' ? 'bg-violet-50 text-violet-600 border-violet-100' : '' }}
                                            {{ $nCol == 'pink' ? 'bg-pink-50 text-pink-600 border-pink-100' : '' }}
                                            {{ $nCol == 'emerald' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}">
                                    <i class="fas fa-{{$nIco}} text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-0.5 group-hover:text-cyan-600 transition-colors">{{ $namaPasien }}</p>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded border shadow-sm
                                                     {{ $nCol == 'sky' ? 'text-sky-600 bg-white border-sky-200' : '' }}
                                                     {{ $nCol == 'cyan' ? 'text-cyan-600 bg-white border-cyan-200' : '' }}
                                                     {{ $nCol == 'violet' ? 'text-violet-600 bg-white border-violet-200' : '' }}
                                                     {{ $nCol == 'pink' ? 'text-pink-600 bg-white border-pink-200' : '' }}
                                                     {{ $nCol == 'emerald' ? 'text-emerald-600 bg-white border-emerald-200' : '' }}">
                                            {{ $kategori }}
                                        </span>
                                        <span class="text-[9px] font-medium text-slate-400">{{ $kategoriSub }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Fisik Awal (dari Kader) --}}
                        <td class="py-4 px-6 align-middle">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg shadow-sm">BB: {{ $bb }}</span>
                                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg shadow-sm">TB: {{ $tb }}</span>
                            </div>
                            @if($extra)
                                <p class="text-[10px] font-bold text-slate-500 mt-1.5">
                                    <i class="fas fa-heartbeat text-rose-400"></i> {{ $extra }}
                                </p>
                            @endif
                            @if($pem->lila ?? $pem->lingkar_lengan)
                                <p class="text-[10px] font-medium text-slate-400 mt-0.5">
                                    LiLA: {{ $pem->lila ?? $pem->lingkar_lengan }} cm
                                </p>
                            @endif
                        </td>

                        {{-- Waktu Masuk & Kader --}}
                        <td class="py-4 px-6 align-middle">
                            <p class="font-bold text-slate-700 text-[13px]"><i class="far fa-calendar-alt text-slate-400 mr-1"></i> {{ \Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] font-medium text-slate-500 mt-1"><i class="far fa-clock text-slate-400 mr-1"></i> Pukul {{ $pem->created_at->format('H:i') }} WIB</p>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 border-t border-slate-100 pt-1 inline-block">Kader: {{ Str::words($pem->pemeriksa->name ?? 'Sistem', 2, '') }}</p>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-6 text-right align-middle">
                            @if($tab == 'pending')
                                <a href="{{ route('bidan.pemeriksaan.show', $pem->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm transform hover:-translate-y-0.5">
                                    <i class="fas fa-stethoscope"></i> Validasi Medis
                                </a>
                            @else
                                <a href="{{ route('bidan.pemeriksaan.show', $pem->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white border border-emerald-200 hover:border-emerald-500 text-[11px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-file-medical"></i> Lihat Hasil
                                </a>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 border border-slate-100 text-slate-300 mb-4 shadow-inner">
                                <i class="fas fa-{{ $tab == 'pending' ? 'procedures' : 'check-double' }} text-5xl"></i>
                            </div>
                            <h3 class="text-[16px] font-black text-slate-800 font-poppins mb-1">{{ $tab == 'pending' ? 'Hore! Antrian Kosong' : 'Belum Ada Riwayat' }}</h3>
                            <p class="text-[12px] font-medium text-slate-500 max-w-sm mx-auto">
                                {{ $tab == 'pending' ? 'Tidak ada warga yang menunggu validasi saat ini. Anda bisa bersantai sejenak.' : 'Belum ada data pemeriksaan yang diselesaikan pada periode ini.' }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($pemeriksaans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $pemeriksaans->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection