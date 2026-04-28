@extends('layouts.kader')
@section('title', 'Detail Arsip Vaksinasi')
@section('page-name', 'Log Vaksinasi Warga')

@push('styles')
<style>
    /* NEXUS ANIMATION SYSTEM */
    .fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .stagger-1 { animation-delay: 0.1s; } .stagger-2 { animation-delay: 0.2s; }
    
    /* NEXUS CARD & GLASS EFFECT */
    .nexus-glass-card {
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);
        border: 1px solid #ffffff; border-radius: 32px;
        box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.05); overflow: hidden; relative;
    }
    
    /* DETAIL GRID ITEM */
    .info-box {
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 20px;
        padding: 1.25rem; transition: all 0.3s ease; box-shadow: 0 2px 10px -2px rgba(0,0,0,0.02);
    }
    .info-box:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(99,102,241,0.1); border-color: #e0e7ff; }

    .pill-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; }

    /* PRINT LAYOUT OPTIMIZATION */
    .print-watermark { display: none; }
    @media print {
        body * { visibility: hidden; }
        .nexus-glass-card, .nexus-glass-card * { visibility: visible; }
        .nexus-glass-card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; background: white !important; filter: none !important; border-radius: 0 !important; }
        .no-print { display: none !important; }
        .info-box { border: 1px solid #cbd5e1 !important; box-shadow: none !important; transform: none !important; break-inside: avoid; }
        .print-watermark { display: block; margin-top: 40px; text-align: center; font-size: 11px; color: #64748b; font-family: monospace; border-top: 1px dashed #cbd5e1; padding-top: 10px; }
    }
</style>
@endpush

@section('content')
<div class="max-w-[900px] mx-auto fade-in-up pb-12 relative z-10">

    {{-- Latar Belakang Abstrak --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-96 bg-gradient-to-b from-indigo-50/80 to-transparent rounded-full blur-3xl pointer-events-none z-0 no-print"></div>

    {{-- 1. HEADER NAVIGASI (NO PRINT) --}}
    <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-5 relative z-10 no-print">
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <a href="{{ route('kader.imunisasi.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm group shrink-0">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Arsip Medis</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Terekam di Database Aman</span>
                </div>
            </div>
        </div>
        
        <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-slate-200 text-slate-700 font-black text-[11px] rounded-[16px] hover:bg-slate-800 hover:text-white hover:border-slate-800 shadow-sm transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
            <i class="fas fa-print text-sm"></i> Cetak Dokumen
        </button>
    </div>

    {{-- 2. KARTU DETAIL UTAMA (NEXUS GLASS CARD) --}}
    <div class="nexus-glass-card relative z-10">
        
        {{-- Ornamen Kartu Internal --}}
        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-bl-full pointer-events-none blur-3xl no-print"></div>
        <div class="absolute left-0 bottom-0 w-64 h-64 bg-rose-500/10 rounded-tr-full pointer-events-none blur-3xl no-print"></div>

        {{-- Banner Atas (Vaksin Highlight) --}}
        <div class="p-8 md:p-10 border-b border-slate-100 relative z-10 flex flex-col md:flex-row items-center md:items-start gap-6 text-center md:text-left">
            <div class="w-24 h-24 rounded-[24px] bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 text-indigo-600 flex items-center justify-center text-4xl shadow-inner shrink-0 transform -rotate-3">
                <i class="fas fa-shield-virus drop-shadow-sm"></i>
            </div>
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-md mb-3 shadow-sm">
                    <i class="fas fa-check-circle"></i> Pemberian Sukses
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 font-poppins mb-2 tracking-tight">{{ $imunisasi->vaksin }}</h2>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                    <span class="pill-badge bg-indigo-600 text-white shadow-sm">Dosis Ke-{{ $imunisasi->dosis }}</span>
                    <span class="pill-badge bg-slate-100 text-slate-600 border border-slate-200">{{ $imunisasi->jenis_imunisasi }}</span>
                </div>
            </div>
        </div>

        {{-- Grid Informasi Rinci --}}
        <div class="p-8 md:p-10 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                {{-- KOLOM KIRI: Identitas --}}
                <div class="space-y-6 stagger-1">
                    
                    <div class="info-box group">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><i class="fas fa-user-check text-slate-300 mr-1.5"></i> Identitas Penerima</p>
                            <i class="fas fa-id-card text-slate-200 text-xl group-hover:text-indigo-100 transition-colors"></i>
                        </div>
                        <p class="text-lg font-black text-slate-800 font-poppins leading-tight">{{ $imunisasi->kunjungan?->pasien?->nama_lengkap ?? 'Data Dihapus' }}</p>
                        <p class="text-[11px] font-bold text-slate-500 font-mono mt-1 bg-slate-50 inline-block px-2 py-0.5 rounded border border-slate-100">NIK: {{ $imunisasi->kunjungan?->pasien?->nik ?? 'TIDAK TERSEDIA' }}</p>
                    </div>
                    
                    <div class="info-box">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3"><i class="fas fa-users text-slate-300 mr-1.5"></i> Target Sasaran</p>
                        @php
                            $tipePasienRaw = class_basename($imunisasi->kunjungan?->pasien_type);
                            $badge = match($tipePasienRaw) {
                                'Balita'   => 'bg-sky-50 text-sky-700 border-sky-200',
                                'IbuHamil' => 'bg-pink-50 text-pink-700 border-pink-200',
                                default    => 'bg-slate-50 text-slate-700 border-slate-200'
                            };
                            $iconK = match($tipePasienRaw) { 'Balita'=>'fa-baby', 'IbuHamil'=>'fa-female', default=>'fa-user' };
                            $labelK = match($tipePasienRaw) { 'IbuHamil'=>'Ibu Hamil (TT)', 'Balita'=>'Balita Dasar', default=>$tipePasienRaw };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider border {{ $badge }}">
                            <i class="fas {{ $iconK }}"></i> {{ $labelK }}
                        </span>
                    </div>

                    <div class="info-box">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-hospital text-slate-300 mr-1.5"></i> Lokasi Pelaksanaan</p>
                        <p class="text-[13px] font-black text-slate-700">{{ $imunisasi->penyelenggara ?? 'Posyandu Terpadu / Puskesmas Desa' }}</p>
                    </div>

                </div>

                {{-- KOLOM KANAN: Detail Medis --}}
                <div class="space-y-6 stagger-2">
                    
                    {{-- FIX LOKALISASI & ZONA WAKTU (Mencegah "Tuesday" dan Waktu UTC) --}}
                    <div class="info-box bg-indigo-50/50 border-indigo-100">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1.5"><i class="far fa-calendar-check mr-1.5"></i> Waktu Eksekusi Medis</p>
                        <p class="text-[15px] font-black text-indigo-900">
                            {{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </p>
                        <p class="text-[10px] font-bold text-indigo-500 mt-0.5">
                            Tercatat pada: {{ \Carbon\Carbon::parse($imunisasi->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="info-box">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-barcode text-slate-300 mr-1"></i> No. Batch</p>
                            <p class="text-[12px] font-black text-slate-700 font-mono">{{ $imunisasi->batch_number ?? 'TIDAK DICATAT' }}</p>
                        </div>
                        <div class="info-box">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-hourglass-end text-slate-300 mr-1"></i> Exp Date</p>
                            <p class="text-[12px] font-black text-slate-700">
                                {{ $imunisasi->expiry_date ? \Carbon\Carbon::parse($imunisasi->expiry_date)->format('d / m / Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="info-box">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3"><i class="fas fa-user-md text-slate-300 mr-1.5"></i> Tenaga Kesehatan (Otoritas)</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-black shrink-0">
                                {{ strtoupper(substr($imunisasi->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[13px] font-black text-slate-800 font-poppins">{{ $imunisasi->kunjungan?->petugas?->name ?? 'Bidan Desa (Sistem)' }}</p>
                                <p class="text-[10px] font-bold text-slate-400">Verifikator Meja 5</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            
            {{-- Footer Print Khusus Cetak Fisik --}}
            <div class="print-watermark">
                DOKUMEN RESMI SISTEM INFORMASI POSYANDU TERPADU<br>
                Dicetak pada: {{ now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y HH:mm:ss') }} WIB | ID Dokumen: {{ md5($imunisasi->id . $imunisasi->created_at) }}
            </div>
        </div>
    </div>
</div>
@endsection