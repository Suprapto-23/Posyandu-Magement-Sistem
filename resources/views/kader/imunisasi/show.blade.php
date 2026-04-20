@extends('layouts.kader')
@section('title', 'Sertifikat Imunisasi')
@section('page-name', 'Detail Vaksinasi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .sertifikat-bg { 
        background-color: #ffffff;
        background-image: 
            radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.05) 0px, transparent 50%),
            linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)),
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23cbd5e1' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .print-only { display: none; }
    @media print {
        body * { visibility: hidden; }
        .sertifikat-card, .sertifikat-card * { visibility: visible; }
        .sertifikat-card { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
        .no-print { display: none !important; }
        .print-only { display: block; margin-top: 40px; text-align: center; font-size: 12px; color: #64748b; }
    }
</style>
@endpush

@section('content')
<div class="max-w-[850px] mx-auto animate-slide-up pb-10">

    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 no-print">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.imunisasi.index') }}" class="loader-trigger w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Arsip Vaksin</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200"><i class="fas fa-check-circle"></i> Valid</span>
                    <span class="text-[11px] font-bold text-slate-500">Terekam di Database Medis</span>
                </div>
            </div>
        </div>
        <button onclick="window.print()" class="btn-press px-6 py-3.5 bg-white border border-slate-200 text-indigo-600 font-black text-[12px] rounded-[16px] hover:bg-indigo-50 hover:border-indigo-200 shadow-sm transition-all flex items-center gap-2 uppercase tracking-widest w-full sm:w-auto justify-center hover:shadow-md">
            <i class="fas fa-print text-sm"></i> Cetak Sertifikat
        </button>
    </div>

    {{-- KARTU SERTIFIKAT DIGITAL --}}
    <div class="sertifikat-card bg-white rounded-[32px] border border-slate-200/80 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.06)] overflow-hidden relative sertifikat-bg">
        
        <div class="absolute right-0 top-0 w-48 h-48 bg-indigo-500/10 rounded-bl-full pointer-events-none blur-2xl"></div>
        <div class="absolute left-0 bottom-0 w-48 h-48 bg-pink-500/10 rounded-tr-full pointer-events-none blur-2xl"></div>

        <div class="p-8 md:p-12 text-center border-b border-slate-100 relative z-10">
            <div class="w-24 h-24 mx-auto rounded-[24px] bg-gradient-to-br from-indigo-50 to-white text-indigo-600 flex items-center justify-center text-4xl mb-6 shadow-inner border border-indigo-100 transform rotate-3">
                <i class="fas fa-shield-virus drop-shadow-sm"></i>
            </div>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-4 shadow-sm">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div> Telah Diberikan
            </div>
            
            <h2 class="text-3xl md:text-5xl font-black text-slate-800 font-poppins mb-3 tracking-tight">{{ $imunisasi->vaksin }}</h2>
            
            <div class="flex items-center justify-center gap-3 text-[12px]">
                <span class="font-bold text-slate-500 bg-white px-3 py-1 rounded-lg border border-slate-200 shadow-sm uppercase tracking-wider">{{ $imunisasi->jenis_imunisasi }}</span>
                <span class="font-black text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200 shadow-sm uppercase tracking-wider">Dosis Ke-{{ $imunisasi->dosis }}</span>
            </div>
        </div>

        <div class="p-8 md:p-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                
                {{-- Kiri: Identitas Pasien --}}
                <div class="space-y-6">
                    <div class="p-6 bg-slate-50/80 backdrop-blur-sm border border-slate-200 rounded-[24px] shadow-sm relative overflow-hidden group hover:bg-white transition-colors">
                        <div class="absolute right-4 top-4 text-slate-200 text-5xl opacity-30 group-hover:text-indigo-100 transition-colors"><i class="fas fa-user-check"></i></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 relative z-10">Identitas Penerima Vaksin</p>
                        {{-- KODE YANG DIPERBAIKI --}}
                        <p class="text-xl font-black text-slate-800 font-poppins relative z-10">{{ $imunisasi->kunjungan?->pasien?->nama_lengkap ?? 'Data Dihapus' }}</p>
                        <div class="flex items-center gap-2 mt-2 relative z-10">
                            <span class="text-[11px] font-bold text-slate-500 font-mono bg-white px-2 py-0.5 rounded border border-slate-200"><i class="fas fa-id-card text-slate-400 mr-1"></i> NIK: {{ $imunisasi->kunjungan?->pasien?->nik ?? 'TIDAK TERSEDIA' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-users text-slate-300 mr-1"></i> Kategori Warga</p>
                        @php
                            $tipePasienRaw = class_basename($imunisasi->kunjungan?->pasien_type);
                            $badge = match($tipePasienRaw) {
                                'Balita'   => 'bg-sky-50 text-sky-700 border-sky-200',
                                'Remaja'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'IbuHamil' => 'bg-pink-50 text-pink-700 border-pink-200',
                                'Lansia'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default    => 'bg-slate-50 text-slate-700 border-slate-200'
                            };
                            $iconK = match($tipePasienRaw) { 'Balita'=>'fa-baby', 'Remaja'=>'fa-user-graduate', 'IbuHamil'=>'fa-female', 'Lansia'=>'fa-user-clock', default=>'fa-user' };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[12px] font-black uppercase tracking-wider border shadow-sm {{ $badge }}">
                            <i class="fas {{ $iconK }}"></i> {{ match($tipePasienRaw) { 'IbuHamil' => 'Ibu Hamil', default => $tipePasienRaw } }}
                        </span>
                    </div>
                </div>

                {{-- Kanan: Detail Medis --}}
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><i class="far fa-calendar-check text-slate-300 mr-1"></i> Waktu Pelaksanaan</p>
                        <p class="text-[16px] font-bold text-slate-800">{{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->translatedFormat('l, d F Y') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-barcode text-slate-300 mr-1"></i> No. Batch</p>
                            <p class="text-[13px] font-black text-slate-700 font-mono tracking-wider">{{ $imunisasi->batch_number ?? 'TIDAK DICATAT' }}</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-hourglass-end text-slate-300 mr-1"></i> Exp Date</p>
                            @if($imunisasi->expiry_date)
                                <p class="text-[13px] font-black text-slate-700">{{ \Carbon\Carbon::parse($imunisasi->expiry_date)->format('d/m/Y') }}</p>
                            @else
                                <p class="text-[13px] font-black text-slate-300">-</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-hospital text-slate-300 mr-1"></i> Penyelenggara / Faskes</p>
                        <p class="text-[14px] font-bold text-slate-800">{{ $imunisasi->penyelenggara ?? 'Posyandu Terpadu / Puskesmas Desa' }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200/60">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-user-md text-slate-300 mr-1"></i> Otoritas Medis (Tenaga Kesehatan)</p>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-500 font-black">
                                {{ strtoupper(substr($imunisasi->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[14px] font-black text-indigo-900 font-poppins">{{ $imunisasi->kunjungan?->petugas?->name ?? 'Bidan Desa (Sistem)' }}</p>
                                <p class="text-[10px] font-bold text-slate-400">Petugas Meja 5 (Validasi)</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="print-only">
                Dokumen ini diterbitkan secara otomatis oleh Sistem Informasi Posyandu Terpadu.
                <br>Waktu cetak: {{ now()->translatedFormat('d F Y H:i:s') }}
            </div>
        </div>
    </div>
</div>
@endsection