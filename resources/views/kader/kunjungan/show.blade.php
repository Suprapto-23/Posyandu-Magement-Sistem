@extends('layouts.kader')
@section('title', 'Detail Kunjungan')
@section('page-name', 'Nota Kehadiran')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .nota-bg { background-image: radial-gradient(circle at top right, rgba(6, 182, 212, 0.05), transparent 40%); }
</style>
@endpush

@section('content')
<div class="max-w-[800px] mx-auto animate-slide-up text-center sm:text-left">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.kunjungan.index') }}" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-cyan-50 hover:text-cyan-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-poppins">Nota Kunjungan</h1>
            </div>
        </div>
        
        <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-white font-black text-[11px] uppercase tracking-widest rounded-xl shadow-sm w-full sm:w-auto justify-center">
            <i class="fas fa-ticket-alt"></i> {{ $kunjungan->kode_kunjungan }}
        </span>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.06)] overflow-hidden mb-8 relative nota-bg">
        
        {{-- HEADER NOTA --}}
        <div class="p-8 md:p-10 border-b-2 border-dashed border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center sm:items-start gap-6 relative">
            
            <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-[#f8fafc] rounded-full border-t-2 border-r-2 border-dashed border-slate-200 hidden sm:block"></div>
            <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-[#f8fafc] rounded-full border-t-2 border-l-2 border-dashed border-slate-200 hidden sm:block"></div>

            <div class="w-24 h-24 rounded-[24px] bg-white text-cyan-500 border border-cyan-100 flex items-center justify-center text-4xl shadow-sm shrink-0 transform -rotate-3">
                <i class="fas fa-user-check"></i>
            </div>
            
            <div class="text-center sm:text-left flex-1">
                @php 
                    $tipe = class_basename($kunjungan->pasien_type); 
                    $badge = match($tipe) {
                        'Balita'   => 'bg-violet-100 text-violet-700',
                        'Remaja'   => 'bg-sky-100 text-sky-700',
                        'IbuHamil' => 'bg-pink-100 text-pink-700',
                        'Lansia'   => 'bg-emerald-100 text-emerald-700',
                        default    => 'bg-slate-200 text-slate-600'
                    };
                @endphp
                <span class="inline-block px-3 py-1 text-[10px] font-black uppercase rounded-lg tracking-widest mb-2 border {{ $badge }}">{{ match($tipe) { 'IbuHamil' => 'Ibu Hamil', default => $tipe } }}</span>
                <h2 class="text-3xl font-black text-slate-800 font-poppins">{{ $kunjungan->pasien->nama_lengkap ?? 'Pasien Terhapus' }}</h2>
                <p class="text-xs font-bold text-slate-400 mt-1 font-mono"><i class="fas fa-id-card mr-1"></i> ID: {{ $kunjungan->pasien->nik ?? $kunjungan->pasien->kode_balita ?? '-' }}</p>
            </div>
            
            <div class="text-center sm:text-right shrink-0 mt-4 sm:mt-0">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Check-in Server</p>
                <p class="text-lg font-black text-slate-800">{{ \Carbon\Carbon::parse($kunjungan->created_at)->format('d M Y') }}</p>
                <p class="text-xs font-bold text-cyan-600 bg-cyan-50 px-2 py-1 rounded inline-block mt-1">{{ \Carbon\Carbon::parse($kunjungan->created_at)->format('H:i') }} WIB</p>
            </div>
        </div>

        {{-- RINCIAN LAYANAN --}}
        <div class="p-8 md:p-10 space-y-6">
            
            <div class="p-5 bg-slate-50 border border-slate-100 rounded-[20px] flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm border border-slate-200 shrink-0"><i class="fas fa-comment-medical"></i></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Keluhan / Alasan Kedatangan</p>
                    <p class="text-sm font-bold text-slate-800 italic">"{{ $kunjungan->keluhan ?? 'Hanya melakukan kunjungan rutin posyandu tanpa keluhan spesifik.' }}"</p>
                </div>
            </div>

            <div class="pt-2">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Log Layanan Medis</h3>

                @if($kunjungan->pemeriksaan)
                <div class="mb-4 p-4 border border-indigo-100 rounded-[20px] flex items-center justify-between group cursor-pointer hover:bg-indigo-50/50 transition-colors" onclick="window.location.href='{{ route('kader.pemeriksaan.show', $kunjungan->pemeriksaan->id) }}'">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 shrink-0"><i class="fas fa-stethoscope text-xl"></i></div>
                        <div>
                            <p class="font-black text-indigo-900 text-sm">Ukuran Fisik & Diagnosa</p>
                            <p class="text-[11px] font-bold text-indigo-500 mt-0.5">Telah diukur dan diperiksa.</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-500 shadow-sm group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors hidden sm:block">Buka Rekam <i class="fas fa-arrow-right ml-1"></i></div>
                </div>
                @endif

                @if($kunjungan->imunisasis && $kunjungan->imunisasis->count() > 0)
                <div class="mb-4 p-5 bg-teal-50/50 border border-teal-100 rounded-[20px]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600 shrink-0"><i class="fas fa-shield-virus"></i></div>
                        <p class="font-black text-teal-900 text-sm">Menerima Vaksinasi</p>
                    </div>
                    <div class="space-y-3 pl-11">
                        @foreach($kunjungan->imunisasis as $imun)
                        <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-teal-50 shadow-sm">
                            <div>
                                <p class="text-[13px] font-black text-slate-800">{{ $imun->vaksin }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">{{ $imun->jenis_imunisasi }}</p>
                            </div>
                            <span class="px-3 py-1.5 bg-teal-100 text-teal-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Dosis {{ $imun->dosis }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if(!$kunjungan->pemeriksaan && (!$kunjungan->imunisasis || $kunjungan->imunisasis->count() == 0))
                <div class="p-6 text-center border-2 border-dashed border-slate-200 rounded-[20px]">
                    <i class="fas fa-file-excel text-3xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-bold text-slate-500">Belum ada layanan medis yang dicatat pada kunjungan ini.</p>
                </div>
                @endif
            </div>
            
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-left">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Resepsionis Bertugas</p>
                    <p class="text-sm font-black text-slate-700"><i class="fas fa-user-edit text-slate-400 mr-1.5"></i> {{ $kunjungan->petugas->name ?? 'Sistem Otomatis' }}</p>
                </div>
                <div class="text-right">
                    <i class="fas fa-check-circle text-cyan-500 text-2xl"></i>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection