@extends('layouts.kader')
@section('title', 'Pusat Notifikasi')
@section('page-name', 'Semua Notifikasi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .notif-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .notif-card:hover { transform: translateX(8px); box-shadow: -8px 0 20px rgba(79, 70, 229, 0.05); }
    .btn-glowing { position: relative; overflow: hidden; }
    .btn-glowing::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: all 0.5s; }
    .btn-glowing:hover::before { left: 100%; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto animate-slide-up pb-12">

    {{-- HEADER BANNER --}}
    <div class="bg-white rounded-[32px] p-6 md:p-10 mb-8 border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none transform translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-sm border border-indigo-100 transform -rotate-3">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 border-2 border-white rounded-full animate-ping"></span>
                @endif
            </div>
            <div>
                <h2 class="text-3xl font-black font-poppins text-slate-900 tracking-tight">Pusat Sinyal</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Anda memiliki <strong id="header-unread-count" class="text-rose-500 font-black text-base">{{ $unreadCount }}</strong> notifikasi baru.</p>
            </div>
        </div>

        @if($unreadCount > 0)
        <form action="{{ route('kader.notifikasi.markAllRead') }}" method="POST" class="w-full md:w-auto relative z-10">
            @csrf
            <button type="submit" class="btn-glowing w-full px-8 py-3.5 bg-indigo-600 text-white font-black text-[13px] uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-[0_4px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fas fa-check-double text-indigo-200"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- TAB FILTER --}}
    <div class="flex items-center gap-3 mb-6 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm w-max">
        <a href="{{ route('kader.notifikasi.index', ['filter' => 'semua']) }}" class="px-6 py-2.5 rounded-xl text-[13px] font-black uppercase tracking-wider transition-all {{ $filter == 'semua' ? 'bg-slate-800 text-white shadow-md' : 'bg-transparent text-slate-500 hover:bg-slate-50' }}">Semua Riwayat</a>
        <a href="{{ route('kader.notifikasi.index', ['filter' => 'belum_dibaca']) }}" class="px-6 py-2.5 rounded-xl text-[13px] font-black uppercase tracking-wider transition-all {{ $filter == 'belum_dibaca' ? 'bg-indigo-600 text-white shadow-md' : 'bg-transparent text-slate-500 hover:bg-slate-50' }}">
            Belum Dibaca <span class="ml-1 bg-white/20 px-2 py-0.5 rounded-md">{{ $unreadCount }}</span>
        </a>
    </div>

    {{-- DAFTAR NOTIFIKASI --}}
    <div id="main-notif-wrapper" class="relative">
        <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifikasis as $notif)
                    <div class="notif-card p-6 md:p-8 flex flex-col sm:flex-row gap-5 group {{ $notif->is_read ? 'bg-white' : 'bg-indigo-50/40 border-l-4 border-l-indigo-500' }}">
                        
                        @php
                            $icon = 'bell'; $iconColor = $notif->is_read ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-indigo-100 text-indigo-600 border-indigo-200 shadow-sm';
                            $jdl = strtolower($notif->judul);
                            if (str_contains($jdl, 'jadwal')) { $icon = 'calendar-alt'; $iconColor = $notif->is_read ? 'bg-slate-50 text-slate-400' : 'bg-amber-100 text-amber-600 border-amber-200 shadow-sm'; }
                            if (str_contains($jdl, 'import')) { $icon = 'file-excel'; $iconColor = $notif->is_read ? 'bg-slate-50 text-slate-400' : 'bg-emerald-100 text-emerald-600 border-emerald-200 shadow-sm'; }
                        @endphp

                        <div class="w-14 h-14 rounded-full flex items-center justify-center shrink-0 border {{ $iconColor }} transition-all group-hover:scale-110">
                            <i class="fas fa-{{ $icon }} text-xl"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2 mb-2">
                                <h4 class="text-lg font-black font-poppins truncate pr-4 {{ $notif->is_read ? 'text-slate-600' : 'text-slate-900' }}">{{ $notif->judul }}</h4>
                                <span class="text-[11px] font-bold px-3 py-1 rounded-full {{ $notif->is_read ? 'bg-slate-100 text-slate-400' : 'bg-indigo-100 text-indigo-600' }} whitespace-nowrap inline-flex items-center gap-1.5 w-max">
                                    <i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <p class="text-[14px] {{ $notif->is_read ? 'text-slate-500' : 'text-slate-700 font-medium' }} leading-relaxed mb-4 max-w-3xl">{{ $notif->pesan }}</p>
                            
                            <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                @if(!$notif->is_read)
                                <form action="{{ route('kader.notifikasi.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-black text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors uppercase tracking-widest border border-indigo-200">Tandai Dibaca</button>
                                </form>
                                @endif
                                
                                <form action="{{ route('kader.notifikasi.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat notifikasi ini secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] font-black text-rose-500 hover:text-rose-700 hover:bg-rose-100 px-4 py-2 rounded-xl transition-colors uppercase tracking-widest border border-rose-200 flex items-center gap-1.5"><i class="fas fa-trash-alt"></i> Hapus Arsip</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-24 flex flex-col items-center justify-center text-center">
                        <div class="w-28 h-28 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 text-5xl shadow-inner border border-slate-100">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 font-poppins mb-2">Layar Bersih!</h3>
                        <p class="text-base font-medium text-slate-500 max-w-sm">Anda tidak memiliki sinyal notifikasi {{ $filter == 'belum_dibaca' ? 'yang belum dibaca' : 'apapun saat ini' }}. Waktunya bersantai!</p>
                    </div>
                @endforelse
            </div>

            @if($notifikasis->hasPages())
                <div class="p-6 bg-slate-50/80 border-t border-slate-100">
                    {{ $notifikasis->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection