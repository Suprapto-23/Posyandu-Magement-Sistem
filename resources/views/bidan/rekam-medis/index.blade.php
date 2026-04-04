@extends('layouts.bidan')

@section('title', 'Buku Rekam Medis')
@section('page-name', 'Database Medis Warga')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .tab-active { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
</style>

<div class="max-w-[1200px] mx-auto animate-slide-up pb-10">

    {{-- HEADER BANNER (Medical Glassmorphism) --}}
    <div class="bg-gradient-to-br from-cyan-500 via-sky-600 to-blue-700 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_10px_30px_rgba(6,182,212,0.3)] flex flex-col md:flex-row items-center gap-6 border border-cyan-400">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="w-20 h-20 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-inner relative z-10 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="relative z-10 w-full text-center md:text-left">
            <h2 class="text-3xl md:text-4xl font-black text-white font-poppins tracking-tight mb-2">Arsip Rekam Medis</h2>
            <p class="text-cyan-50 text-[13px] sm:text-sm font-medium max-w-2xl mx-auto md:mx-0 leading-relaxed">
                Pusat data klinis longitudinal. Cari pasien untuk melihat riwayat kunjungan, grafik pertumbuhan antropometri, dan rekam jejak diagnosa medis dari waktu ke waktu.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden flex flex-col">
        
        {{-- TABS & SEARCH BAR --}}
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col gap-5">
            
            {{-- Tabs Filter --}}
            <div class="flex flex-wrap items-center gap-2.5">
                @php
                    $tabs = [
                        ['id' => 'balita', 'label' => 'Balita', 'icon' => 'fa-baby', 'col' => 'rose'],
                        ['id' => 'ibu_hamil', 'label' => 'Ibu Hamil', 'icon' => 'fa-female', 'col' => 'pink'],
                        ['id' => 'remaja', 'label' => 'Remaja', 'icon' => 'fa-user-graduate', 'col' => 'indigo'],
                        ['id' => 'lansia', 'label' => 'Lansia', 'icon' => 'fa-wheelchair', 'col' => 'emerald'],
                    ];
                @endphp

                @foreach($tabs as $t)
                    <a href="?type={{ $t['id'] }}&search={{ request('search') }}" 
                       class="smooth-route px-5 py-2.5 rounded-xl text-[13px] font-bold transition-all flex items-center gap-2 border {{ $type == $t['id'] ? 'bg-'.$t['col'].'-600 text-white border-'.$t['col'].'-600 tab-active' : 'bg-white text-slate-500 border-slate-200 hover:border-'.$t['col'].'-300 hover:text-'.$t['col'].'-600 shadow-sm' }}">
                        <i class="fas {{ $t['icon'] }}"></i> <span class="hidden sm:inline">{{ $t['label'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Form Pencarian --}}
            <form action="{{ route('bidan.rekam-medis.index') }}" method="GET" id="searchForm" class="w-full relative group">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIK pasien, lalu tekan Enter..." 
                       class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-[14px] font-semibold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all shadow-sm">
                <button type="submit" class="absolute inset-y-1.5 right-1.5 px-6 bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-bold uppercase tracking-widest rounded-xl transition-colors shadow-sm hidden sm:flex items-center">
                    Cari Data
                </button>
            </form>
        </div>

        {{-- PATIENT LIST --}}
        <div class="divide-y divide-slate-50/80">
            @forelse($data as $pasien)
            <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 hover:bg-slate-50/80 transition-colors group">
                <div class="flex items-center gap-4">
                    @php
                        if($type == 'balita') { $bgC = 'bg-rose-50'; $txC = 'text-rose-500'; $icn = 'fa-baby'; $bdC = 'border-rose-100'; }
                        elseif($type == 'remaja') { $bgC = 'bg-indigo-50'; $txC = 'text-indigo-500'; $icn = 'fa-user-graduate'; $bdC = 'border-indigo-100'; }
                        elseif($type == 'ibu_hamil') { $bgC = 'bg-pink-50'; $txC = 'text-pink-500'; $icn = 'fa-female'; $bdC = 'border-pink-100'; }
                        else { $bgC = 'bg-emerald-50'; $txC = 'text-emerald-500'; $icn = 'fa-wheelchair'; $bdC = 'border-emerald-100'; }
                    @endphp
                    
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl shrink-0 {{ $bgC }} {{ $txC }} border {{ $bdC }} shadow-inner group-hover:scale-110 transition-transform">
                        <i class="fas {{ $icn }}"></i>
                    </div>
                    
                    <div>
                        <h4 class="font-black text-slate-800 text-[16px] mb-1 group-hover:text-cyan-600 transition-colors">{{ $pasien->nama_lengkap }}</h4>
                        <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <span class="bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm"><i class="fas fa-id-card text-slate-400 mr-1"></i> {{ $pasien->nik ?? 'TIDAK ADA NIK' }}</span>
                            <span class="bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm"><i class="fas fa-birthday-cake text-slate-400 mr-1"></i> {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} THN</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('bidan.rekam-medis.show', ['pasien_type' => $type, 'pasien_id' => $pasien->id]) }}" class="smooth-route inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-cyan-700 font-bold text-[12px] uppercase tracking-widest rounded-xl hover:bg-cyan-50 hover:border-cyan-200 transition-all shadow-sm shrink-0">
                    <i class="fas fa-folder-open"></i> Buka Berkas
                </a>
            </div>
            @empty
            <div class="text-center py-20 px-4">
                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h4 class="font-black text-slate-800 text-lg font-poppins mb-1">Database Kosong</h4>
                <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto">Tidak ada pasien yang sesuai dengan kata kunci atau kategori yang Anda pilih.</p>
            </div>
            @endforelse
        </div>
        
        @if($data->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
            {{ $data->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>
@endsection