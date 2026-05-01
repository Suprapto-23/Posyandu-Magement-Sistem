@extends('layouts.kader')
@section('title', 'Manajemen Agenda Posyandu')
@section('page-name', 'Kelola Jadwal Acara')

@push('styles')
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (ULTIMATE FLUID & LIVELY EDITION)
       ================================================================= */
    
    .animate-fade-in { opacity: 0; animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Animasi Domino Baris Data */
    .stagger-item { opacity: 0; transform: translateY(10px); animation: slideUp 0.3s ease-out forwards; }
    @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }

    /* Animasi Melayang Tingkat Lanjut (Empty State) */
    .animate-float { animation: float 3s ease-in-out infinite; }
    @keyframes float { 0%, 100% { transform: translateY(0px) rotate(3deg); } 50% { transform: translateY(-10px) rotate(0deg); } }

    /* 1. APP WINDOW (FLEXBOX MASTER) */
    .nexus-app-window {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 20px 25px -5px rgba(0,0,0,0.03);
        display: flex; flex-direction: column; overflow: hidden;
        height: calc(100vh - 190px); min-height: 600px; 
        margin-top: 1.5rem; position: relative; z-index: 20;
    }

    /* 2. APP HEADER (TOOLBAR TETAP) */
    .nexus-app-header {
        background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px);
        border-bottom: 1px solid #f1f5f9; padding: 1rem 1.5rem;
        display: flex; flex-direction: column; gap: 1rem; flex-shrink: 0; z-index: 30;
    }
    @media (min-width: 1024px) { .nexus-app-header { flex-direction: row; align-items: center; justify-content: space-between; } }

    /* Segmented Control */
    .segment-control { display: flex; background: #f8fafc; padding: 0.3rem; border-radius: 12px; gap: 0.15rem; border: 1px solid #f1f5f9; overflow-x: auto; scrollbar-width: none; }
    .segment-btn {
        padding: 0.6rem 1.15rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; color: #64748b; 
        text-transform: uppercase; letter-spacing: 0.04em; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); 
        white-space: nowrap; display: flex; align-items: center; gap: 0.4rem; border: 1px solid transparent;
    }
    .segment-btn:hover { color: #334155; }
    .segment-btn.active { background: #ffffff; color: #4f46e5; border-color: #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }

    /* Search Input (Sleek & Active) */
    .search-wrapper { position: relative; width: 100%; transition: all 0.3s; }
    @media (min-width: 1024px) { .search-wrapper { width: 340px; } }
    .search-wrapper:focus-within { width: 100%; }
    @media (min-width: 1024px) { .search-wrapper:focus-within { width: 380px; } }

    .nexus-search { 
        width: 100%; background: #ffffff; border: 1px solid #e2e8f0; color: #0f172a; 
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 500; 
        border-radius: 12px; padding: 0.65rem 2.5rem; outline: none; transition: all 0.2s ease; 
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
    }
    .nexus-search:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); background: #ffffff; }
    .nexus-search::placeholder { color: #94a3b8; font-weight: 400; }

    /* 3. APP BODY (SCROLL AREA) */
    .nexus-app-body {
        flex: 1; overflow-y: auto; background: #fcfcfd; padding: 1.5rem;
        display: flex; flex-direction: column; scroll-behavior: smooth;
    }
    
    .nexus-app-body::-webkit-scrollbar { width: 6px; }
    .nexus-app-body::-webkit-scrollbar-track { background: transparent; }
    .nexus-app-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .nexus-app-body::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* =================================================================
       AGENDA ROW (PROFESSIONAL NON-LEBAY)
       ================================================================= */
    .agenda-row {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1rem 1.25rem;
        display: flex; flex-direction: column; gap: 1rem; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative; overflow: hidden; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.01);
    }
    @media (min-width: 768px) { .agenda-row { flex-direction: row; align-items: center; gap: 1.5rem; padding: 1.25rem 1.5rem; } }
    .agenda-row:hover { border-color: #cbd5e1; box-shadow: 0 8px 20px -5px rgba(15, 23, 42, 0.05); transform: translateY(-1px); }
    
    .accent-line { position: absolute; left: 0; top: 1.25rem; bottom: 1.25rem; width: 4px; border-radius: 0 4px 4px 0; }

    .date-cube {
        width: 65px; height: 70px; border-radius: 14px; display: flex; flex-direction: column;
        align-items: center; justify-content: center; flex-shrink: 0; color: white;
        box-shadow: inset 0 2px 4px rgba(255,255,255,0.25), 0 4px 10px -2px rgba(0,0,0,0.1);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .agenda-row:hover .date-cube { transform: scale(1.05); }

    .meta-tag {
        display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 8px; 
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; 
        border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; transition: border-color 0.2s;
    }

    .action-divider { display: none; width: 1px; height: 40px; background-color: #f1f5f9; margin: 0 0.5rem; }
    @media (min-width: 768px) { .action-divider { display: block; } }

    .action-btn {
        width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        background: #f8fafc; border: 1px solid transparent; color: #64748b; transition: all 0.2s; cursor: pointer;
    }
    .action-btn:hover { background: #ffffff; border-color: #cbd5e1; color: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,0.03); }

    /* Mulus saat AJAX (Cross-fade) */
    #data-wrapper { transition: opacity 0.15s ease; }

    /* =================================================================
       SWEETALERT 2 ISOLATION
       ================================================================= */
    body.swal2-shown:not(.swal2-toast-shown) .swal2-container { z-index: 100000 !important; backdrop-filter: blur(4px) !important; background: rgba(15, 23, 42, 0.3) !important; }
    .nexus-modal { border-radius: 24px !important; padding: 2rem !important; background: #ffffff !important; width: 24em !important; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15) !important; border: 1px solid #f1f5f9 !important; }
    .nexus-modal .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 700 !important; font-size: 1.25rem !important; color: #0f172a !important; margin-bottom: 0.2rem !important; }
    .btn-swal-danger { background: #ef4444 !important; color: white !important; border-radius: 8px !important; padding: 10px 20px !important; font-weight: 600 !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; margin-right: 8px !important; transition: 0.2s !important; }
    .btn-swal-danger:hover { background: #dc2626 !important; transform: translateY(-1px) !important; box-shadow: 0 4px 12px rgba(220,38,38,0.2) !important;}
    .btn-swal-cancel { background: #f1f5f9 !important; color: #475569 !important; border-radius: 8px !important; padding: 10px 20px !important; font-weight: 600 !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; transition: 0.2s !important; }
    .btn-swal-cancel:hover { background: #e2e8f0 !important; }
</style>
@endpush

@section('content')
<div class="max-w-[1150px] mx-auto animate-fade-in pb-10 relative z-10 mt-2">

    {{-- AURA BACKGROUND --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-violet-500/5 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    {{-- HEADER UTAMA --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-[16px] bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl border border-indigo-100 shadow-sm shrink-0">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight font-poppins mb-0.5">Manajemen Agenda</h1>
                <p class="text-slate-500 font-medium text-[13px]">Pantau dan kelola jadwal pelayanan Posyandu secara terpusat.</p>
            </div>
        </div>
        <a href="{{ route('kader.jadwal.create') }}" class="px-6 py-3 bg-[#0f172a] text-white font-semibold text-[11.5px] rounded-[12px] hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/20 transition-all uppercase tracking-widest flex items-center justify-center gap-2 shrink-0">
            <i class="fas fa-plus"></i> Rancang Agenda
        </a>
    </div>

    {{-- MASTER KANVAS (APP WINDOW) --}}
    <div class="nexus-app-window">
        
        {{-- A. TOOLBAR (TETAP DI ATAS) --}}
        <div class="nexus-app-header">
            <form id="filterForm" action="{{ route('kader.jadwal.index') }}" method="GET" class="hidden">
                <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
                <input type="hidden" name="status" id="hiddenStatus" value="{{ request('status', 'semua') }}">
            </form>

            {{-- Tabs --}}
            <div class="segment-control w-full lg:w-auto">
                @php $currentStatus = request('status', 'semua'); @endphp
                @foreach([
                    'semua' => ['label' => 'Semua Jadwal', 'icon' => 'fa-border-all'],
                    'aktif' => ['label' => 'Akan Datang', 'icon' => 'fa-clock'],
                    'selesai' => ['label' => 'Selesai', 'icon' => 'fa-check-circle'],
                    'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'fa-times-circle']
                ] as $key => $val)
                    <div class="segment-btn {{ $currentStatus == $key ? 'active' : '' }}" data-status="{{ $key }}">
                        <i class="fas {{ $val['icon'] }} {{ $currentStatus == $key ? 'text-indigo-500' : 'text-slate-400' }} text-[13px]"></i> {{ $val['label'] }}
                    </div>
                @endforeach
            </div>

            {{-- Search Berdenyut --}}
            <div class="search-wrapper group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[0.8rem] transition-colors group-focus-within:text-indigo-500"></i>
                <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari agenda atau lokasi..." class="nexus-search" autocomplete="off" autofocus>
                <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden text-indigo-500">
                    <i class="fas fa-circle-notch fa-spin text-sm"></i>
                </div>
            </div>
        </div>

        {{-- B. LIST AREA (DAPAT DISKROL) --}}
        <div class="nexus-app-body" id="agenda-list-container">
            <div id="data-wrapper">
                @if(isset($jadwals) && $jadwals->count() > 0)
                    <div class="flex flex-col gap-3">
                        @foreach($jadwals as $index => $jadwal)
                        @php
                            $color = $jadwal->theme_color ?? 'violet';
                            $statusData = $jadwal->status_badge;
                        @endphp
                        
                        {{-- STAGGERED ITEM: Muncul bergelombang --}}
                        <div class="agenda-row group stagger-item" style="animation-delay: {{ $index * 0.05 }}s;">
                            {{-- Garis Aksen --}}
                            <div class="accent-line bg-{{ $color }}-400 group-hover:bg-{{ $color }}-500 transition-colors"></div>

                            {{-- Date Cube --}}
                            <div class="date-cube bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-500 ml-1 md:ml-3">
                                <span class="text-[8px] font-bold uppercase tracking-widest opacity-90">{{ $jadwal->tanggal->translatedFormat('l') }}</span>
                                <span class="text-[24px] font-black font-poppins leading-none my-0.5">{{ $jadwal->tanggal->format('d') }}</span>
                                <span class="text-[9px] font-bold uppercase tracking-widest opacity-90">{{ $jadwal->tanggal->translatedFormat('M') }}</span>
                            </div>

                            {{-- Konten --}}
                            <div class="flex-1 min-w-0 pl-1 md:pl-2">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="meta-tag group-hover:border-slate-300">
                                        <i class="fas fa-users text-slate-400"></i> {{ $jadwal->label_target }}
                                    </span>
                                    <span class="meta-tag group-hover:border-slate-300">
                                        <i class="fas {{ $statusData['icon'] }} {{ $jadwal->status == 'aktif' ? 'text-emerald-500 animate-pulse' : 'text-slate-400' }}"></i> {{ $statusData['text'] }}
                                    </span>
                                </div>
                                
                                <h3 class="text-[16px] md:text-[17px] font-black text-slate-800 group-hover:text-{{ $color }}-700 font-poppins leading-tight truncate mb-1.5 transition-colors">
                                    {{ $jadwal->judul }}
                                </h3>
                                
                                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-[11.5px] font-medium text-slate-500">
                                    <span class="flex items-center gap-1.5"><i class="far fa-clock text-slate-400"></i> {{ $jadwal->waktu_lengkap }}</span>
                                    <span class="flex items-center gap-1.5 truncate max-w-[200px] sm:max-w-none"><i class="fas fa-map-marker-alt text-slate-400"></i> {{ $jadwal->lokasi }}</span>
                                    <span class="flex items-center gap-1.5 capitalize"><i class="fas fa-tag text-slate-400"></i> {{ str_replace('_', ' ', $jadwal->kategori) }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="action-divider group-hover:bg-slate-200 transition-colors"></div>
                            <div class="flex items-center gap-1.5 shrink-0 pt-3 md:pt-0 justify-end mt-2 md:mt-0">
                                <a href="{{ route('kader.jadwal.show', $jadwal->id) }}" class="action-btn hover:!bg-indigo-50 hover:!text-indigo-600 hover:!border-indigo-200" title="Lihat Detail">
                                    <i class="fas fa-eye text-[13px]"></i>
                                </a>
                                @if($jadwal->status != 'selesai')
                                <a href="{{ route('kader.jadwal.edit', $jadwal->id) }}" class="action-btn hover:!bg-amber-50 hover:!text-amber-600 hover:!border-amber-200" title="Koreksi">
                                    <i class="fas fa-pen text-[13px]"></i>
                                </a>
                                @endif
                                <form action="{{ route('kader.jadwal.destroy', $jadwal->id) }}" method="POST" class="m-0 delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="action-btn btn-delete hover:!bg-rose-50 hover:!text-rose-600 hover:!border-rose-200" title="Hapus">
                                        <i class="fas fa-trash-alt text-[13px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div id="paginationArea" class="mt-6 flex justify-center md:justify-end">
                        @if($jadwals->hasPages()) {{ $jadwals->links() }} @endif
                    </div>

                @else
                    {{-- HOLOGRAPHIC EMPTY STATE (SANGAT HIDUP) --}}
                    <div class="h-full flex flex-col items-center justify-center text-center py-20 px-4 stagger-item">
                        <div class="relative w-28 h-28 mb-8">
                            {{-- Gelombang Hologram Belakang --}}
                            <div class="absolute inset-0 bg-indigo-200 rounded-full animate-ping opacity-20"></div>
                            <div class="absolute inset-2 bg-sky-200 rounded-full animate-pulse opacity-40"></div>
                            
                            {{-- Kalender Utama Melayang --}}
                            <div class="absolute inset-4 bg-white rounded-[20px] shadow-xl border border-slate-100 flex items-center justify-center animate-float z-10">
                                <i class="far fa-calendar-times text-4xl text-indigo-300"></i>
                            </div>
                            
                            {{-- Kaca Pembesar Kecil --}}
                            <div class="absolute -right-2 -bottom-2 w-10 h-10 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center border-2 border-white shadow-md z-20 animate-bounce" style="animation-duration: 2s;">
                                <i class="fas fa-search text-[12px]"></i>
                            </div>
                        </div>
                        
                        <h3 class="font-black text-slate-800 text-[18px] font-poppins mb-2 tracking-tight">Oopss! Agenda Tidak Ditemukan</h3>
                        
                        {{-- Deteksi Keyword Dinamis --}}
                        <p class="text-[13px] text-slate-500 font-medium max-w-sm leading-relaxed mb-6">
                            Sistem tidak menemukan jadwal yang cocok <span id="empty-keyword-text" class="hidden">dengan kata <strong class="text-indigo-600 font-bold" id="empty-keyword-value"></strong></span> pada filter saat ini.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const contentArea = document.getElementById('data-wrapper');
    const spinner = document.getElementById('searchSpinner');
    const form = document.getElementById('filterForm');
    
    // ENGINE ANTI-TABRAKAN (AbortController)
    let currentAbortController = null;

    async function fetchRealTimeData(url, isSearch = false) {
        // BATALKAN REQUEST SEBELUMNYA JIKA ADA (Zero-Latency Guarantee)
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        if(isSearch) spinner.classList.remove('hidden');
        contentArea.style.opacity = '0.6'; // Cross-fade halus, tanpa scale
        
        try {
            const response = await fetch(url, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: currentAbortController.signal
            });
            const html = await response.text();
            
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newContent = doc.getElementById('data-wrapper');
            
            if(newContent) {
                // DOM Replace instan, memicu animasi 'stagger-item' CSS secara otomatis
                contentArea.innerHTML = newContent.innerHTML;
            }
            
            window.history.pushState({}, '', url);
            bindEvents();
            updateDynamicEmptyState();

        } catch (error) { 
            if (error.name === 'AbortError') return; // Abaikan error pembatalan disengaja
            console.error("Sinkronisasi data gagal.", error); 
        } finally {
            if(isSearch) spinner.classList.add('hidden');
            contentArea.style.opacity = '1';
        }
    }

    // PENCARIAN INSTAN (0ms Delay! Bereaksi pada setiap huruf)
    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            document.getElementById('hiddenSearch').value = e.target.value;
            
            const url = new URL(form.action);
            url.searchParams.set('search', e.target.value);
            url.searchParams.set('status', document.getElementById('hiddenStatus').value);
            
            // Langsung tembak ke server tanpa delay! AbortController akan mengamankannya.
            fetchRealTimeData(url.toString(), true);
        });
    }

    // Tabs Filter
    document.querySelectorAll('.segment-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.segment-btn').forEach(b => {
                b.classList.remove('active');
                const icon = b.querySelector('i');
                if(icon) { icon.classList.remove('text-indigo-500'); icon.classList.add('text-slate-400'); }
            });
            
            this.classList.add('active');
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

    // Menghidupkan Empty State dengan teks pencarian
    function updateDynamicEmptyState() {
        const val = document.getElementById('hiddenSearch').value;
        const textWrapper = document.getElementById('empty-keyword-text');
        const valWrapper = document.getElementById('empty-keyword-value');
        if(val && textWrapper && valWrapper) {
            valWrapper.innerText = `"${val}"`;
            textWrapper.classList.remove('hidden');
        } else if(textWrapper) {
            textWrapper.classList.add('hidden');
        }
    }

    // Events Re-binder
    function bindEvents() {
        document.querySelectorAll('#paginationArea a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                fetchRealTimeData(this.href, false);
            });
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Hapus Agenda?',
                    html: 'Jadwal akan dihapus secara <strong class="text-rose-500 font-semibold">permanen</strong>.',
                    icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', 
                    reverseButtons: true, buttonsStyling: false,
                    customClass: { popup: 'nexus-modal', confirmButton: 'btn-swal-danger', cancelButton: 'btn-swal-cancel' }
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });
        });
    }
    
    window.addEventListener('popstate', function() { fetchRealTimeData(window.location.href, false); });
    bindEvents();
    updateDynamicEmptyState(); // Jalankan saat pertama muat
});
</script>
@endpush
@endsection