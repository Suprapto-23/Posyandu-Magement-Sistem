@extends('layouts.kader')
@section('title', 'Jadwal Posyandu')
@section('page-name', 'Kelola Jadwal Acara')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .search-input {
        width: 100%; background: rgba(255,255,255,0.9); border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.75rem 1rem 0.75rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 600;
    }
    .search-input:focus { background-color: #ffffff; border-color: #6366f1; box-shadow: 0 4px 20px -3px rgba(99, 102, 241, 0.15); }
    
    .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); }
    
    .jadwal-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .jadwal-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15); border-color: rgba(199, 210, 254, 0.8); }
    
    #data-wrapper { transition: opacity 0.3s ease; }
    .loading-state { opacity: 0.4; pointer-events: none; filter: grayscale(30%); }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up pb-10">

    {{-- HEADER BRANDING --}}
    <div class="bg-white rounded-[28px] p-6 md:p-8 mb-6 flex flex-col md:flex-row justify-between items-center gap-6 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] border border-slate-100">
        <div class="flex items-center gap-5 w-full md:w-auto">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-50 to-violet-50 text-indigo-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-indigo-100">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black font-poppins text-slate-800 tracking-tight">Manajemen Agenda</h2>
                <p class="text-slate-500 text-[13px] font-medium mt-1">Atur dan pantau kegiatan Posyandu dengan mudah.</p>
            </div>
        </div>
        
        <a href="{{ route('kader.jadwal.create') }}" class="loader-trigger w-full md:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-indigo-600 text-white font-black text-[13px] rounded-[14px] hover:bg-indigo-700 shadow-[0_8px_20px_rgba(99,102,241,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-widest">
            <i class="fas fa-plus-circle text-lg"></i> Buat Agenda Baru
        </a>
    </div>

    {{-- FILTER & SEARCH (AJAX READY) --}}
    <div class="glass-card rounded-[24px] p-3 mb-8 flex flex-col xl:flex-row items-center justify-between gap-4 relative z-20">
        <form id="filterForm" action="{{ route('kader.jadwal.index') }}" method="GET" class="hidden">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
            <input type="hidden" name="status" id="hiddenStatus" value="{{ request('status', 'semua') }}">
        </form>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 w-full xl:w-auto overflow-x-auto custom-scrollbar pb-2 sm:pb-0 justify-start bg-slate-50/80 p-1.5 rounded-[20px] border border-slate-100">
            @php 
                $tabs = [
                    'semua'      => ['label' => 'Semua', 'icon' => 'fa-layer-group'],
                    'aktif'      => ['label' => 'Aktif', 'icon' => 'fa-clock'],
                    'selesai'    => ['label' => 'Selesai', 'icon' => 'fa-check-circle'],
                    'dibatalkan' => ['label' => 'Batal', 'icon' => 'fa-times-circle']
                ];
                $currentStatus = request('status', 'semua'); 
            @endphp
            
            @foreach($tabs as $k => $v)
                <button type="button" data-status="{{ $k }}" class="tab-btn px-5 py-2 rounded-xl font-bold text-[12px] whitespace-nowrap transition-all flex items-center gap-2 border {{ $currentStatus == $k ? 'bg-white text-indigo-600 border-slate-200 shadow-sm active-tab' : 'bg-transparent text-slate-500 border-transparent hover:bg-white hover:border-slate-200' }}">
                   <i class="fas {{ $v['icon'] }} {{ $currentStatus == $k ? 'text-indigo-500' : 'text-slate-400' }}"></i> {{ $v['label'] }}
                </button>
            @endforeach
        </div>
        
        {{-- Live Search --}}
        <div class="w-full xl:w-96 relative group">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari agenda atau lokasi..." class="search-input">
            <div id="searchSpinner" class="absolute right-5 top-1/2 -translate-y-1/2 hidden"><i class="fas fa-circle-notch fa-spin text-indigo-500"></i></div>
        </div>
    </div>

    {{-- MAIN CARD GRID AREA (AJAX TARGET) --}}
    <div id="mainContentArea" class="relative">
        <div id="data-wrapper">
            @if(isset($jadwals) && $jadwals->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($jadwals as $jadwal)
                    <div class="jadwal-card bg-white rounded-[24px] p-6 border border-slate-100 flex flex-col shadow-sm relative group overflow-hidden">
                        
                        {{-- Background Aksen Blur --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-50 group-hover:bg-indigo-100 transition-colors pointer-events-none"></div>

                        {{-- Bagian Atas: Badge --}}
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-slate-200 shadow-sm">
                                <i class="fas fa-users text-slate-400"></i> {{ str_replace('_', ' ', $jadwal->target_peserta) }}
                            </span>
                            
                            @if($jadwal->status == 'aktif')
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1 shadow-sm"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif</span>
                            @elseif($jadwal->status == 'selesai')
                                <span class="bg-slate-50 text-slate-500 border border-slate-200 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1 shadow-sm"><i class="fas fa-check"></i> Selesai</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1 shadow-sm"><i class="fas fa-times"></i> Batal</span>
                            @endif
                        </div>

                        {{-- Bagian Tengah: Kalender & Info --}}
                        <div class="flex items-start gap-4 mb-4 relative z-10">
                            <div class="w-[60px] h-[64px] rounded-[16px] bg-indigo-50/80 border border-indigo-100 flex flex-col items-center justify-center text-indigo-600 shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-inner">
                                <span class="text-[10px] font-bold uppercase tracking-widest mb-0.5">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M') }}</span>
                                <span class="text-[22px] font-black font-poppins leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-[16px] font-black text-slate-800 font-poppins leading-tight truncate mb-1 group-hover:text-indigo-700 transition-colors" title="{{ $jadwal->judul }}">{{ $jadwal->judul }}</h3>
                                <p class="text-[11px] font-bold text-slate-500 mb-1 flex items-center gap-1.5"><i class="far fa-clock text-indigo-400 w-3 text-center"></i> {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB</p>
                                <p class="text-[11px] font-bold text-slate-500 truncate flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-rose-400 w-3 text-center"></i> {{ $jadwal->lokasi }}</p>
                            </div>
                        </div>

                        {{-- SMART WARNING LOGIC --}}
                        @php 
                            $isPast = \Carbon\Carbon::parse($jadwal->tanggal)->isPast() && !\Carbon\Carbon::parse($jadwal->tanggal)->isToday();
                        @endphp
                        @if($isPast && $jadwal->status == 'aktif')
                            <div class="mb-4 bg-rose-50/80 border border-rose-100 text-rose-600 text-[10px] font-bold px-3 py-2 rounded-xl flex items-start gap-2 relative z-10">
                                <i class="fas fa-exclamation-circle mt-0.5 text-rose-500"></i> 
                                <span>Waktu terlewat! Klik "Edit" dan ubah status menjadi Selesai.</span>
                            </div>
                        @endif

                        {{-- Bagian Bawah: Aksi --}}
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between relative z-10">
                            <span class="text-[11px] font-bold text-slate-400 capitalize"><i class="fas fa-tag mr-1 text-slate-300"></i> {{ str_replace('_', ' ', $jadwal->kategori) }}</span>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('kader.jadwal.show', $jadwal->id) }}" class="loader-trigger w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-indigo-600 hover:text-white transition-colors shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-eye text-[13px]"></i>
                                </a>
                                <a href="{{ route('kader.jadwal.edit', $jadwal->id) }}" class="loader-trigger w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-amber-500 hover:text-white transition-colors shadow-sm" title="Edit">
                                    <i class="fas fa-pen text-[13px]"></i>
                                </a>
                                <form action="{{ route('kader.jadwal.destroy', $jadwal->id) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $jadwal->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-rose-500 hover:text-white transition-colors shadow-sm" title="Hapus">
                                        <i class="fas fa-trash text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div id="paginationArea" class="mt-8">
                    @if(isset($jadwals) && $jadwals->hasPages()) {{ $jadwals->links() }} @endif
                </div>

            @else
                {{-- EMPTY STATE --}}
                <div class="py-20 text-center bg-white border border-slate-100 rounded-[32px] shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 transform rotate-3">
                        <i class="far fa-calendar-times text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="font-black text-slate-800 text-xl font-poppins mb-2">Tidak Ada Data</h3>
                    <p class="text-[13px] text-slate-500 font-medium max-w-md mx-auto leading-relaxed">Sistem tidak menemukan jadwal kegiatan untuk kriteria pencarian ini. Pastikan filter status Anda sudah benar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('liveSearchInput');
    const tableWrapper = document.getElementById('data-wrapper');
    const spinner = document.getElementById('searchSpinner');
    const form = document.getElementById('filterForm');

    // 1. AJAX FETCH DOM PARSER (Anti-Bug Layout Berlapis)
    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch && spinner) spinner.classList.remove('hidden');
        tableWrapper.classList.add('loading-state');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            // Ambil HANYA bagian data-wrapper untuk menghindari tumpang tindih Layout
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newContent = doc.getElementById('data-wrapper');
            
            if(newContent) {
                tableWrapper.innerHTML = newContent.innerHTML;
            }
            
            window.history.pushState({}, '', url);
            bindPagination();
        } catch (error) { console.error("Koneksi gagal"); } 
        finally {
            if(spinner) spinner.classList.add('hidden');
            tableWrapper.classList.remove('loading-state');
        }
    }

    // 2. LIVE SEARCH
    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            document.getElementById('hiddenSearch').value = e.target.value;
            typingTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', e.target.value);
                url.searchParams.set('status', document.getElementById('hiddenStatus').value);
                fetchRealTimeData(url.toString(), true);
            }, 400); 
        });
    }

    // 3. TAB STATUS FILTER
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-white', 'text-indigo-600', 'border-slate-200', 'shadow-sm', 'active-tab');
                b.classList.add('bg-transparent', 'text-slate-500', 'border-transparent');
                const icon = b.querySelector('i');
                if(icon) { icon.classList.remove('text-indigo-500'); icon.classList.add('text-slate-400'); }
            });
            
            this.classList.remove('bg-transparent', 'text-slate-500', 'border-transparent');
            this.classList.add('bg-white', 'text-indigo-600', 'border-slate-200', 'shadow-sm', 'active-tab');
            const myIcon = this.querySelector('i');
            if(myIcon) { myIcon.classList.remove('text-slate-400'); myIcon.classList.add('text-indigo-500'); }

            const status = this.dataset.status;
            document.getElementById('hiddenStatus').value = status;
            
            const url = new URL(form.action);
            url.searchParams.set('status', status);
            url.searchParams.set('search', document.getElementById('hiddenSearch').value);
            fetchRealTimeData(url.toString(), false);
        });
    });

    // 4. BINDING PAGINATION
    function bindPagination() {
        document.querySelectorAll('#paginationArea a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                fetchRealTimeData(this.href, false);
            });
        });
    }
    
    window.addEventListener('popstate', function() { fetchRealTimeData(window.location.href, false); });
    bindPagination();
});

// FUNGSI HAPUS MENGGUNAKAN SWEETALERT
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Agenda?', text: "Jadwal ini akan dihapus secara permanen.",
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', reverseButtons: true,
        customClass: { popup: 'rounded-[24px] font-poppins' }
    }).then((result) => {
        if (result.isConfirmed) event.target.closest('form').submit();
    });
}
</script>
@endpush
@endsection