@extends('layouts.bidan')
@section('title', 'Antrian Pemeriksaan Medis')
@section('page-name', 'Validasi Medis')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .tab-active { background-color: #06b6d4; color: #ffffff; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3); font-weight: 900; }
    .tab-inactive { background-color: transparent; color: #64748b; font-weight: 600; }
    .tab-inactive:hover { background-color: #f1f5f9; color: #334155; }
</style>

<div class="max-w-[1400px] mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 bg-white p-6 rounded-[24px] border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-2xl border border-cyan-100 shrink-0">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight font-poppins">Antrian Meja 5</h1>
                <p class="text-slate-500 mt-1 font-medium text-[13px]">Validasi pengukuran kader dan berikan tindakan klinis.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden flex flex-col">
        
        {{-- Tabs & Search --}}
        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2 p-1 bg-slate-200/50 rounded-xl max-w-max">
                <a href="{{ route('bidan.pemeriksaan.index', ['tab' => 'pending']) }}" class="px-5 py-2.5 rounded-lg text-[12px] uppercase tracking-widest transition-all smooth-route flex items-center gap-2 {{ $tab == 'pending' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-procedures"></i> Menunggu Validasi 
                    @if($pendingCount > 0)
                        <span class="w-5 h-5 rounded-full bg-white text-cyan-600 flex items-center justify-center text-[10px] shadow-sm ml-1">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('bidan.pemeriksaan.index', ['tab' => 'verified']) }}" class="px-5 py-2.5 rounded-lg text-[12px] uppercase tracking-widest transition-all smooth-route flex items-center gap-2 {{ $tab == 'verified' ? 'tab-active' : 'tab-inactive' }}">
                    <i class="fas fa-check-double"></i> Telah Selesai
                </a>
            </div>
            
            <form method="GET" action="{{ route('bidan.pemeriksaan.index') }}" class="relative w-full md:w-72">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien..." class="w-full bg-white border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 outline-none transition-all shadow-sm">
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto custom-scrollbar flex-1">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data Pasien</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Daftar</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengukur (Kader)</th>
                        <th class="py-4 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Tindakan Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pemeriksaans as $pem)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        
                        <td class="py-4 px-6 align-middle">
                            @php
                                $namaPasien = $pem->balita->nama_lengkap ?? $pem->remaja->nama_lengkap ?? $pem->lansia->nama_lengkap ?? 'Ibu Hamil';
                                $kat = strtolower($pem->kategori_pasien);
                                if($kat == 'balita') { $bCol = 'rose'; $bIcon = 'baby'; }
                                elseif($kat == 'remaja') { $bCol = 'sky'; $bIcon = 'user-graduate'; }
                                elseif(in_array($kat, ['ibu_hamil','bumil'])) { $bCol = 'pink'; $bIcon = 'female'; $kat = 'Bumil'; }
                                else { $bCol = 'emerald'; $bIcon = 'wheelchair'; }
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-{{$bCol}}-50 text-{{$bCol}}-500 flex items-center justify-center shrink-0 border border-{{$bCol}}-100 shadow-inner">
                                    <i class="fas fa-{{$bIcon}} text-[14px]"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-0.5 truncate max-w-[200px]">{{ $namaPasien }}</p>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">{{ $kat }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-6 align-middle">
                            <p class="font-bold text-slate-700 text-[13px]">{{ \Carbon\Carbon::parse($pem->tanggal_periksa)->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5"><i class="far fa-clock"></i> {{ $pem->created_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="py-4 px-6 align-middle">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-black border border-slate-200 shrink-0">
                                    {{ strtoupper(substr($pem->pemeriksa->name ?? 'K', 0, 1)) }}
                                </div>
                                <span class="text-[12px] font-bold text-slate-600 truncate max-w-[150px]">{{ $pem->pemeriksa->name ?? 'Kader Tidak Terdeteksi' }}</span>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-right align-middle">
                            @if($tab == 'pending')
                                <a href="{{ route('bidan.pemeriksaan.validasi', $pem->id) }}" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-500 text-[12px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-stethoscope"></i> Periksa Sekarang
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-black uppercase tracking-widest rounded-xl">
                                    <i class="fas fa-check-double"></i> Tervalidasi
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-[20px] bg-slate-50 border border-slate-100 text-slate-300 mb-4 shadow-inner">
                                <i class="fas fa-{{ $tab == 'pending' ? 'procedures' : 'check-double' }} text-3xl"></i>
                            </div>
                            <h3 class="text-[15px] font-black text-slate-700 font-poppins tracking-wide mb-1">{{ $tab == 'pending' ? 'Antrian Kosong' : 'Riwayat Kosong' }}</h3>
                            <p class="text-[12px] font-medium text-slate-400 max-w-sm mx-auto">Tidak ada data pasien yang sesuai dengan filter saat ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pemeriksaans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
            {{ $pemeriksaans->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection