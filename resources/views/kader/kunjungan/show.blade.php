@extends('layouts.kader')
@section('title', 'Detail Kunjungan')
@section('page-name', 'Nota Kehadiran')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Desain Nota Pattern */
    .nota-bg { 
        background-color: #ffffff;
        background-image: radial-gradient(circle at top right, rgba(6, 182, 212, 0.05), transparent 40%), radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.03), transparent 40%); 
    }
    
    /* CSS Khusus Fitur Cetak (Print) */
    .print-only { display: none; }
    @media print {
        body * { visibility: hidden; }
        .cetak-area, .cetak-area * { visibility: visible; }
        .cetak-area { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
        .no-print { display: none !important; }
        .print-only { display: block; margin-top: 30px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px dashed #cbd5e1; padding-top: 15px;}
    }
</style>
@endpush

@section('content')
<div class="max-w-[850px] mx-auto animate-slide-up pb-10">
    
    {{-- TOMBOL NAVIGASI & CETAK --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.kunjungan.index') }}" class="loader-trigger w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-200 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-poppins">Arsip Nota</h1>
                <p class="text-[11px] font-bold text-slate-500 mt-0.5">Rekam Jejak Kunjungan Warga</p>
            </div>
        </div>
        
        <button onclick="window.print()" class="btn-press px-6 py-3.5 bg-white border border-slate-200 text-cyan-600 font-black text-[12px] rounded-xl hover:bg-cyan-50 hover:border-cyan-200 shadow-sm transition-all flex items-center gap-2 uppercase tracking-widest w-full sm:w-auto justify-center">
            <i class="fas fa-print text-sm"></i> Cetak Bukti Layanan
        </button>
    </div>

    {{-- AREA KERTAS NOTA (YANG AKAN DICETAK) --}}
    <div class="cetak-area bg-white rounded-[32px] border border-slate-200/80 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.06)] overflow-hidden mb-8 relative nota-bg">
        
        {{-- HEADER NOTA (Pita Atas) --}}
        <div class="p-8 md:p-10 border-b-2 border-dashed border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center sm:items-start gap-6 relative">
            
            {{-- Efek sobekan struk (Kiri & Kanan) --}}
            <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-[#f1f5f9] rounded-full border-t-2 border-r-2 border-dashed border-slate-200 hidden sm:block"></div>
            <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-[#f1f5f9] rounded-full border-t-2 border-l-2 border-dashed border-slate-200 hidden sm:block"></div>

            <div class="w-24 h-24 rounded-[24px] bg-white text-cyan-500 border border-cyan-100 flex items-center justify-center text-4xl shadow-sm shrink-0 transform -rotate-3">
                <i class="fas fa-hospital-user drop-shadow-sm"></i>
            </div>
            
            <div class="text-center sm:text-left flex-1">
                @php 
                    $tipe = class_basename($kunjungan->pasien_type); 
                    $badge = match($tipe) {
                        'Balita'   => 'bg-violet-100 text-violet-700',
                        'Remaja'   => 'bg-sky-100 text-sky-700',
                        'IbuHamil' => 'bg-pink-100 text-pink-700',
                        'Lansia'   => 'bg-emerald-100 text-emerald-700',
                        default    => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <span class="inline-block px-3 py-1 text-[10px] font-black uppercase rounded-lg tracking-widest mb-2 border {{ $badge }} shadow-sm">{{ match($tipe) { 'IbuHamil' => 'Ibu Hamil', default => $tipe } }}</span>
                <h2 class="text-3xl font-black text-slate-800 font-poppins mb-1">{{ $kunjungan->pasien->nama_lengkap ?? 'Pasien Terhapus' }}</h2>
                <p class="text-xs font-bold text-slate-400 font-mono"><i class="fas fa-id-card mr-1"></i> ID Pasien: {{ $kunjungan->pasien->nik ?? $kunjungan->pasien->kode_balita ?? '-' }}</p>
            </div>
            
            <div class="text-center sm:text-right shrink-0 mt-4 sm:mt-0">
                <div class="inline-flex flex-col p-3 bg-white border border-slate-200 shadow-sm rounded-xl">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Check-in Kehadiran</p>
                    <p class="text-lg font-black text-slate-800">{{ \Carbon\Carbon::parse($kunjungan->created_at)->format('d M Y') }}</p>
                    <p class="text-xs font-bold text-cyan-600 mt-1"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kunjungan->created_at)->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- BADAN NOTA (Rincian Layanan) --}}
        <div class="p-8 md:p-12 space-y-8">
            
            {{-- Kotak ID Nota Unik --}}
            <div class="flex items-center justify-center gap-3 no-print">
                <div class="h-px bg-slate-200 flex-1"></div>
                <span class="px-4 py-1.5 bg-slate-100 text-slate-500 font-black text-[10px] uppercase tracking-widest rounded-full border border-slate-200">
                    <i class="fas fa-ticket-alt mr-1"></i> ID Tiket: {{ $kunjungan->kode_kunjungan }}
                </span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>

            {{-- Blok Keluhan --}}
            <div class="p-6 bg-slate-50 border border-slate-200 rounded-[24px] relative group transition-colors hover:bg-white">
                <div class="absolute -left-3 -top-3 w-10 h-10 rounded-full bg-amber-100 text-amber-500 border-4 border-white flex items-center justify-center shadow-sm"><i class="fas fa-comment-medical text-sm"></i></div>
                <div class="pl-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tujuan / Keluhan Awal Kedatangan</p>
                    <p class="text-[14px] font-bold text-slate-700 italic leading-relaxed">"{{ $kunjungan->keluhan ?? 'Melakukan kunjungan rutin operasional posyandu bulanan.' }}"</p>
                </div>
            </div>

            {{-- Timeline Layanan --}}
            <div class="pt-4">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-5 border-b border-slate-100 pb-2"><i class="fas fa-tasks text-cyan-400 mr-1"></i> Layanan yang Diterima (Rekam Medis)</h3>

                <div class="space-y-4">
                    {{-- 1. Pengukuran Fisik --}}
                    @if($kunjungan->pemeriksaan)
                    <div class="p-5 border border-indigo-100 rounded-[20px] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white hover:border-indigo-300 transition-colors shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-50 rounded-[14px] flex items-center justify-center text-indigo-600 border border-indigo-100 shrink-0"><i class="fas fa-stethoscope text-xl"></i></div>
                            <div>
                                <p class="font-black text-indigo-900 text-[14px]">Pengukuran & Cek Medis Dasar</p>
                                <p class="text-[11px] font-bold text-slate-500 mt-0.5">Telah dilakukan pengukuran antropometri dan cek laboratorium.</p>
                            </div>
                        </div>
                        <a href="{{ route('kader.pemeriksaan.show', $kunjungan->pemeriksaan->id) }}" class="no-print w-full sm:w-auto px-5 py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white border border-indigo-100 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all text-center">Buka Hasil <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    @endif

                    {{-- 2. Imunisasi --}}
                    @if($kunjungan->imunisasis && $kunjungan->imunisasis->count() > 0)
                    <div class="p-5 bg-teal-50/50 border border-teal-100 rounded-[20px] shadow-sm">
                        <div class="flex items-center gap-3 mb-4 border-b border-teal-100 pb-3">
                            <div class="w-8 h-8 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 shrink-0"><i class="fas fa-shield-virus"></i></div>
                            <p class="font-black text-teal-900 text-[14px]">Menerima Vaksinasi Bidan</p>
                        </div>
                        <div class="space-y-3 pl-11">
                            @foreach($kunjungan->imunisasis as $imun)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-4 rounded-xl border border-teal-100 shadow-sm gap-3">
                                <div>
                                    <p class="text-[14px] font-black text-slate-800">{{ $imun->vaksin }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-0.5"><i class="fas fa-caret-right text-teal-400 mr-1"></i> Tipe: {{ $imun->jenis_imunisasi }}</p>
                                </div>
                                <span class="inline-block px-4 py-2 bg-teal-50 border border-teal-100 text-teal-700 text-[10px] font-black uppercase tracking-widest rounded-lg text-center">Dosis Ke-{{ $imun->dosis }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- 3. Kosong --}}
                    @if(!$kunjungan->pemeriksaan && (!$kunjungan->imunisasis || $kunjungan->imunisasis->count() == 0))
                    <div class="p-8 text-center border-2 border-dashed border-slate-200 rounded-[24px] bg-slate-50">
                        <i class="fas fa-file-excel text-4xl text-slate-300 mb-3 drop-shadow-sm"></i>
                        <p class="text-[14px] font-bold text-slate-600">Pelayanan Khusus Kosong</p>
                        <p class="text-[12px] font-medium text-slate-400 mt-1">Kunjungan ini hanya berupa presensi kehadiran / konsultasi umum.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- FOOTER OTORISASI --}}
            <div class="mt-8 pt-6 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between text-left gap-4 bg-slate-50 p-6 rounded-[24px]">
                <div class="flex items-center gap-4 w-full">
                    <div class="w-12 h-12 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center text-lg font-black shadow-sm">
                        {{ strtoupper(substr($kunjungan->petugas->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Otoritas Resepsionis / Meja 1</p>
                        <p class="text-[14px] font-black text-slate-800 font-poppins">{{ $kunjungan->petugas->name ?? 'Sistem Posyandu' }}</p>
                    </div>
                </div>
                <div class="shrink-0 text-center sm:text-right hidden sm:block">
                    <i class="fas fa-stamp text-cyan-600/20 text-4xl transform rotate-12"></i>
                </div>
            </div>

            {{-- Tulisan Khusus Cetak --}}
            <div class="print-only">
                Dokumen Bukti Layanan Posyandu ini sah dan dicetak secara otomatis melalui Sistem Database.<br>
                Tanggal Cetak Dokumen: {{ now()->translatedFormat('d F Y - H:i:s') }} WIB
            </div>

        </div>

    </div>
</div>
@endsection