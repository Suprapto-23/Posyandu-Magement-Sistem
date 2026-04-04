@extends('layouts.kader')
@section('title', 'Detail Imunisasi')
@section('page-name', 'Log Medis')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .sertifikat-bg { background-image: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent 40%), radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.05), transparent 40%); }
</style>
@endpush

@section('content')
<div class="max-w-[800px] mx-auto animate-slide-up text-center sm:text-left">

    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.imunisasi.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Arsip Vaksin</h1>
            </div>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-black text-[12px] rounded-xl hover:bg-slate-50 shadow-sm transition-all flex items-center gap-2 uppercase tracking-widest w-full sm:w-auto justify-center">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
    </div>

    {{-- KARTU SERTIFIKAT --}}
    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] overflow-hidden mb-8 relative sertifikat-bg">
        
        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-bl-full pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-32 h-32 bg-pink-500/10 rounded-tr-full pointer-events-none"></div>

        <div class="p-8 md:p-12 text-center border-b border-slate-100">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-4xl mb-5 shadow-inner border border-indigo-100 transform rotate-3">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-3">Telah Diberikan</div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 font-poppins mb-2 tracking-tight">{{ $imunisasi->vaksin }}</h2>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-[11px]">{{ $imunisasi->jenis_imunisasi }} &bull; Dosis Ke-{{ $imunisasi->dosis }}</p>
        </div>

        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-2 gap-8 text-left relative z-10">
            
            <div class="space-y-6">
                <div class="p-5 bg-slate-50 border border-slate-100 rounded-[20px]">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Identitas Penerima</p>
                    <p class="text-lg font-black text-slate-800 font-poppins">{{ $imunisasi->profil_pasien?->nama_lengkap ?? 'Data Tidak Ditemukan' }}</p>
                    <p class="text-[11px] font-bold text-slate-500 mt-0.5"><i class="fas fa-id-card mr-1"></i> {{ $imunisasi->profil_pasien?->nik ?? '-' }}</p>
                </div>
                
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori Warga</p>
                    @php
                        $tipePasienRaw = class_basename($imunisasi->kunjungan?->pasien_type);
                        $badge = match($tipePasienRaw) {
                            'Balita'   => 'bg-violet-100 text-violet-700',
                            'Remaja'   => 'bg-sky-100 text-sky-700',
                            'IbuHamil' => 'bg-pink-100 text-pink-700',
                            'Lansia'   => 'bg-emerald-100 text-emerald-700',
                            default    => 'bg-slate-100 text-slate-700'
                        };
                    @endphp
                    <span class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider {{ $badge }}">
                        {{ match($tipePasienRaw) { 'IbuHamil' => 'Ibu Hamil', default => $tipePasienRaw } }}
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Pelaksanaan</p>
                    <p class="text-[14px] font-bold text-slate-800"><i class="far fa-calendar-check text-indigo-500 mr-1.5"></i> {{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->translatedFormat('l, d F Y') }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Batch</p>
                        <p class="text-[13px] font-bold text-slate-800 font-mono bg-slate-50 px-2 py-1 rounded inline-block">{{ $imunisasi->batch_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tgl Kadaluarsa</p>
                        <p class="text-[13px] font-bold {{ $imunisasi->expiry_date ? 'text-slate-800' : 'text-slate-400' }}">{{ $imunisasi->expiry_date ? \Carbon\Carbon::parse($imunisasi->expiry_date)->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Penyelenggara / Faskes</p>
                    <p class="text-[13px] font-bold text-slate-800">{{ $imunisasi->penyelenggara ?? 'Posyandu Terpadu' }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diberikan Oleh (Tenaga Medis)</p>
                    <p class="text-[14px] font-black text-indigo-900 flex items-center gap-2">
                        <i class="fas fa-user-md"></i> {{ $imunisasi->kunjungan?->petugas?->name ?? 'Bidan Desa' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection