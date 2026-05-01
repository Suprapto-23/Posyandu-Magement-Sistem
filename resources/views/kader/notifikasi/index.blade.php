@extends('layouts.kader')
@section('title', 'Pusat Sinyal & Notifikasi')
@section('page-name', 'Manajemen Pesan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* NEXUS ANIMATION SYSTEM */
    .fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* GLASS CARD BASE */
    .nexus-card { 
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 32px;
        box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.03);
    }

    /* NOTIF ITEM STYLING (THE NEXUS WAY) */
    .notif-item { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        border-left: 5px solid transparent;
        position: relative;
    }
    .notif-item:hover { 
        background: #f8fafc; 
        transform: translateX(8px);
        box-shadow: -15px 0 30px -10px rgba(79, 70, 229, 0.08);
    }
    .notif-unread { 
        background: rgba(79, 70, 229, 0.03); 
    }
    
    /* NEXUS CAPSULE TABS */
    .nexus-tabs { background: #f1f5f9; padding: 5px; border-radius: 9999px; display: inline-flex; gap: 4px; }
    .tab-item { 
        padding: 8px 24px; border-radius: 9999px; font-size: 11px; font-weight: 900; 
        text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s;
    }
    .tab-item.active { background: #ffffff; color: #4f46e5; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .tab-item.inactive { color: #64748b; }
    .tab-item.inactive:hover { background: rgba(255,255,255,0.5); }

    /* BUTTONS */
    .btn-nexus-sm {
        padding: 6px 16px; border-radius: 12px; font-size: 10px; font-weight: 900;
        text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s;
    }

    /* SWEETALERT OVERRIDE MUTLAK (KONSISTEN) */
    div:where(.swal2-container) { backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.5) !important; }
    div:where(.swal2-popup) {
        border-radius: 36px !important; padding: 2.5rem 2rem !important;
        background: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 25px 60px -15px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto fade-in-up pb-16 relative z-10">

    {{-- Latar Belakang Gradien --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-indigo-50/50 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

    {{-- 1. HEADER BANNER (NEXUS GLASS) --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[36px] border border-white p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] flex flex-col md:flex-row items-center justify-between gap-8 z-10">
        
        <div class="flex items-center gap-6 relative z-10">
            <div class="w-16 h-16 rounded-[22px] bg-indigo-600 text-white flex items-center justify-center text-3xl shadow-[0_10px_20px_rgba(79,70,229,0.3)] shrink-0 transform -rotate-3">
                <i class="fas fa-satellite-dish"></i>
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 border-4 border-white rounded-full animate-bounce"></span>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Pusat Sinyal</h1>
                <p class="text-slate-500 font-medium text-sm mt-1">
                    Terdeteksi <strong class="text-indigo-600 font-black">{{ $unreadCount }}</strong> pesan baru yang memerlukan atensi Anda.
                </p>
            </div>
        </div>

        @if($unreadCount > 0)
        <form action="{{ route('kader.notifikasi.markAllRead') }}" method="POST" class="shrink-0 relative z-10">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 text-white font-black text-[11px] uppercase tracking-widest rounded-full hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-200 hover:-translate-y-1">
                <i class="fas fa-check-double text-indigo-300"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- 2. TAB FILTER CAPSULE --}}
    <div class="flex justify-between items-center mb-6 px-2">
        <div class="nexus-tabs border border-slate-200 shadow-sm">
            <a href="{{ route('kader.notifikasi.index', ['filter' => 'semua']) }}" class="tab-item {{ $filter == 'semua' ? 'active' : 'inactive' }}">
                Seluruh Arsap
            </a>
            <a href="{{ route('kader.notifikasi.index', ['filter' => 'belum_dibaca']) }}" class="tab-item {{ $filter == 'belum_dibaca' ? 'active' : 'inactive' }}">
                Belum Dibaca <span class="ml-1 opacity-50">({{ $unreadCount }})</span>
            </a>
        </div>
        
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Update Terakhir: {{ now()->translatedFormat('H:i') }} WIB</span>
    </div>

    {{-- 3. NOTIFICATION LIST --}}
    <div class="nexus-card overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($notifikasis as $notif)
                @php
                    // Memanfaatkan Helper dari Super Model
                    $isUnread = !$notif->is_read;
                    $borderCol = $isUnread ? 'border-l-indigo-500' : '';
                @endphp
                
                <div class="notif-item p-6 md:p-8 flex gap-6 {{ $isUnread ? 'notif-unread ' . $borderCol : 'bg-white' }} group">
                    
                    {{-- Ikon Tipe Berdasarkan Model --}}
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border transition-all duration-500 group-hover:scale-110 {{ $isUnread ? 'bg-'.$notif->tipe_color.'-100 text-'.$notif->tipe_color.'-600 border-'.$notif->tipe_color.'-200 shadow-sm' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
                        <i class="{{ $notif->tipe_icon }} text-xl"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <h4 class="text-lg font-black font-poppins truncate {{ $isUnread ? 'text-slate-900' : 'text-slate-500' }}">{{ $notif->judul }}</h4>
                                @if($isUnread)
                                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                @endif
                            </div>
                            <span class="text-[10px] font-black px-3 py-1 rounded-full {{ $isUnread ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400' }} whitespace-nowrap inline-flex items-center gap-1.5 w-max">
                                <i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <p class="text-[14px] {{ $isUnread ? 'text-slate-700 font-medium' : 'text-slate-400' }} leading-relaxed mb-5 max-w-3xl">
                            {{ $notif->pesan }}
                        </p>
                        
                        {{-- Action Buttons (Appears on Hover) --}}
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            @if($isUnread)
                            <form action="{{ route('kader.notifikasi.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-nexus-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100">
                                    Tandai Dibaca
                                </button>
                            </form>
                            @endif
                            
                            {{-- Gunakan link jika ada --}}
                            @if($notif->link && $notif->link != '#')
                                <a href="{{ $notif->link }}" class="btn-nexus-sm bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-100">
                                    Lihat Detail
                                </a>
                            @endif

                            <button type="button" onclick="confirmDelete('{{ $notif->id }}')" class="btn-nexus-sm bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white border border-rose-100">
                                <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                            </button>
                            
                            <form id="delete-form-{{ $notif->id }}" action="{{ route('kader.notifikasi.destroy', $notif->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 text-4xl shadow-inner border border-slate-100">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 font-poppins mb-1 uppercase tracking-tight">Kotak Masuk Steril</h3>
                    <p class="text-sm font-medium text-slate-400 max-w-xs mx-auto leading-relaxed">
                        Tidak ada sinyal notifikasi {{ $filter == 'belum_dibaca' ? 'yang belum dibaca' : 'apapun saat ini' }}.
                    </p>
                </div>
            @endforelse
        </div>

        @if($notifikasis->hasPages())
            <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Success Message (Nexus Style)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            iconColor: '#6366f1',
            title: '<span class="font-black text-xl text-slate-800 font-poppins tracking-tight">Berhasil</span>',
            html: '<p class="text-sm text-slate-500 font-medium">{{ session("success") }}</p>',
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'nexus-popup' }
        });
    @endif

    // 2. Delete Confirmation (Consistent Nexus UI)
    function confirmDelete(id) {
        Swal.fire({
            title: '<span class="font-black text-2xl text-slate-800 font-poppins tracking-tight">Hapus Arsip?</span>',
            html: '<p class="text-[13px] text-slate-500 font-medium leading-relaxed mt-2">Notifikasi ini akan dihapus permanen dari riwayat sinyal sistem Anda.</p>',
            icon: 'warning',
            iconColor: '#f43f5e',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-popup',
                confirmButton: 'bg-rose-500 hover:bg-rose-600 text-white font-black text-[11px] uppercase tracking-widest px-8 py-3.5 rounded-full transition-all shadow-lg mx-2',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-widest px-8 py-3.5 rounded-full transition-all mx-2',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush