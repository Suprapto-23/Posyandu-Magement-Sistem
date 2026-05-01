@extends('layouts.kader')
@section('title', 'Detail Arsip Vaksinasi')
@section('page-name', 'Log Vaksinasi Warga')

@push('styles')
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (PURE DETAIL VIEW)
       ================================================================= */
    
    /* Animasi Masuk */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Kartu Detail Utama */
    .nexus-card {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 28px;
        box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.04); overflow: hidden; position: relative;
    }
    
    /* Blok Data (Soft & Precision UI) */
    .data-block {
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 20px;
        padding: 1.25rem 1.5rem; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.01);
    }
    .data-block:hover { border-color: #cbd5e1; box-shadow: 0 6px 15px -3px rgba(15, 23, 42, 0.03); transform: translateY(-2px); }

    /* Tipografi Lembut (Anti-Kaku) */
    .data-label { display: block; font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; }
    .data-value { font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600; color: #1e293b; line-height: 1.4; display: block; }
    
    .pill-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid transparent; }
</style>
@endpush

@section('content')

@php
    // =================================================================
    // SMART DATA EXTRACTION (Dari Model Imunisasi)
    // =================================================================
    $nama       = $imunisasi->nama_penerima;
    $nik        = $imunisasi->nik_penerima;
    $kategori   = $imunisasi->kategori_sasaran;
    $badgeColor = $imunisasi->kategori_vaksin_badge; // 'sky', 'pink', atau 'indigo'
    
    // Ikon disesuaikan dengan warna tema
    $iconTheme  = match($badgeColor) { 'sky' => 'fa-baby', 'pink' => 'fa-female', default => 'fa-syringe' };
@endphp

<div class="max-w-[950px] mx-auto animate-fade-in pb-16 relative z-10 mt-2">

    {{-- AURA BACKGROUND DYNAMIC --}}
    <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-{{ $badgeColor }}-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    {{-- 1. HEADER NAVIGASI (Bersih, Tanpa Tombol Cetak) --}}
    <div class="mb-8 flex items-center gap-4 relative z-10">
        <a href="{{ route('kader.imunisasi.index') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight font-poppins mb-0.5">Detail Arsip Vaksinasi</h1>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Terekam di Database Sistem</span>
            </div>
        </div>
    </div>

    {{-- 2. KARTU DETAIL UTAMA (Panel Informasi Murni) --}}
    <div class="nexus-card">
        
        {{-- Ornamen Latar Belakang Kartu --}}
        <div class="absolute right-0 top-0 w-64 h-64 bg-{{ $badgeColor }}-500/10 rounded-bl-full pointer-events-none blur-3xl"></div>

        {{-- HERO BANNER (Highlight Vaksin) --}}
        <div class="bg-white p-8 md:p-10 border-b border-slate-100 relative z-10 flex flex-col md:flex-row items-center md:items-start gap-6 text-center md:text-left">
            <div class="w-24 h-24 rounded-[24px] bg-{{ $badgeColor }}-50 border border-{{ $badgeColor }}-100 text-{{ $badgeColor }}-600 flex items-center justify-center text-4xl shadow-sm shrink-0 transform -rotate-3">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div class="flex-1 flex flex-col justify-center">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[9px] font-bold uppercase tracking-widest rounded-md mb-3 shadow-sm w-max mx-auto md:mx-0">
                    <i class="fas fa-check-circle"></i> Pemberian Sukses
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 font-poppins mb-2 tracking-tight">{{ $imunisasi->vaksin }}</h2>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                    <span class="pill-badge bg-{{ $badgeColor }}-600 text-white shadow-sm">Dosis Ke-{{ $imunisasi->dosis }}</span>
                    <span class="pill-badge bg-slate-50 text-slate-600 border border-slate-200">{{ $imunisasi->jenis_imunisasi }}</span>
                </div>
            </div>
        </div>

        {{-- GRID INFORMASI RINCI --}}
        <div class="bg-[#fcfcfd] p-8 md:p-10 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                {{-- KOLOM KIRI: Identitas & Target --}}
                <div class="flex flex-col gap-5">
                    
                    <div class="data-block bg-white border-slate-200 shadow-sm relative overflow-hidden flex-1">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 rounded-bl-full pointer-events-none"></div>
                        <span class="data-label relative z-10"><i class="far fa-user text-slate-300 mr-1.5"></i> Identitas Penerima</span>
                        <span class="data-value text-lg mt-1 relative z-10 font-poppins">{{ $nama }}</span>
                        <span class="text-[11px] font-medium text-slate-500 font-mono mt-1 block relative z-10">NIK: {{ $nik }}</span>
                    </div>
                    
                    <div class="data-block">
                        <span class="data-label mb-3"><i class="fas fa-users text-slate-300 mr-1.5"></i> Kategori Sasaran</span>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-widest border bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-700 border-{{ $badgeColor }}-200">
                            <i class="fas {{ $iconTheme }}"></i> {{ $kategori }}
                        </span>
                    </div>

                    <div class="data-block">
                        <span class="data-label"><i class="far fa-hospital text-slate-300 mr-1.5"></i> Lokasi Pelaksanaan</span>
                        <span class="data-value mt-1 text-[0.95rem] font-medium">{{ $imunisasi->penyelenggara ?? 'Posyandu Terpadu / Puskesmas Desa' }}</span>
                    </div>

                </div>

                {{-- KOLOM KANAN: Medis & Otoritas --}}
                <div class="flex flex-col gap-5">
                    
                    <div class="data-block bg-{{ $badgeColor }}-50/50 border-{{ $badgeColor }}-100">
                        <span class="data-label text-{{ $badgeColor }}-600"><i class="far fa-calendar-check mr-1.5"></i> Waktu Eksekusi Medis</span>
                        <span class="data-value text-{{ $badgeColor }}-900 mt-1 text-[1.1rem]">
                            {{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                        <span class="text-[10px] font-medium text-{{ $badgeColor }}-500 mt-1 block">
                            Tercatat pada: {{ \Carbon\Carbon::parse($imunisasi->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="data-block py-4">
                            <span class="data-label"><i class="fas fa-barcode text-slate-300 mr-1"></i> No. Batch</span>
                            <span class="data-value font-mono text-[0.85rem] mt-1">{{ $imunisasi->batch_number ?? 'KOSONG' }}</span>
                        </div>
                        <div class="data-block py-4">
                            <span class="data-label"><i class="far fa-hourglass text-slate-300 mr-1"></i> Exp Date</span>
                            <span class="data-value mt-1 text-[0.85rem] font-medium">
                                {{ $imunisasi->expiry_date ? \Carbon\Carbon::parse($imunisasi->expiry_date)->format('d / m / Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="data-block flex-1 flex flex-col justify-center">
                        <span class="data-label"><i class="fas fa-user-md text-slate-300 mr-1.5"></i> Tenaga Kesehatan (Otoritas)</span>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold shrink-0">
                                {{ strtoupper(substr($imunisasi->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                            </div>
                            <div>
                                <span class="data-value font-poppins text-[0.95rem]">{{ $imunisasi->kunjungan?->petugas?->name ?? 'Bidan Desa (Sistem)' }}</span>
                                <span class="text-[10px] font-medium text-slate-400 block mt-0.5">Verifikator Medis Meja 5</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection