@extends('layouts.kader')
@section('title', 'Tracker Imunisasi')
@section('page-name', 'Log Vaksinasi Warga')

@push('styles')
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (DASHBOARD EDITION)
       ================================================================= */
    
    /* Animasi Masuk Beruntun */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.15s; } .delay-300 { animation-delay: 0.2s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Kartu Widget Analitik (Depth & Precision) */
    .stat-card {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 1.5rem;
        box-shadow: 0 4px 20px -10px rgba(15, 23, 42, 0.03); transition: all 0.3s ease;
        display: flex; align-items: center; gap: 1.25rem; position: relative; overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.06); border-color: #cbd5e1; }
    
    /* Filter Tabs (Segmented Control ala iOS) */
    .segment-control { display: flex; background: #f1f5f9; padding: 0.35rem; border-radius: 16px; gap: 0.25rem; overflow-x: auto; scrollbar-width: none; }
    .segment-btn {
        flex: 1; min-width: 130px; text-align: center; padding: 0.65rem 1rem; border-radius: 12px; 
        font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; 
        cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; white-space: nowrap;
    }
    .segment-btn:hover { color: #334155; background: #e2e8f0; }
    .segment-btn.active { background: #ffffff; color: #4f46e5; border-color: #e2e8f0; box-shadow: 0 2px 8px rgba(15,23,42,0.04); }

    /* Input Pencarian Kapsul */
    .nexus-search {
        width: 100%; background-color: #ffffff; border: 1px solid #cbd5e1; color: #1e293b;
        font-family: 'Inter', sans-serif; font-size: 0.875rem; font-weight: 500;
        border-radius: 9999px; padding: 0.75rem 1.5rem 0.75rem 2.75rem; outline: none; transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .nexus-search:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

    /* Tabel Nexus Seamless */
    .nexus-table-container { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 28px; box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.04); overflow: hidden; }
    .nexus-table { width: 100%; border-collapse: collapse; text-align: left; }
    .nexus-table th { background: #fcfcfd; color: #64748b; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; position: sticky; top: 0; z-index: 10; }
    .nexus-table td { padding: 1.25rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; }
    .nexus-table tr:last-child td { border-bottom: none; }
    .nexus-table tr:hover td { background-color: #f8fafc; }

    /* Kapsul Dinamis */
    .pill-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid transparent; }

    /* AJAX Loader Mulus */
    #mainContentArea { transition: opacity 0.3s ease, transform 0.3s ease; }
    .is-loading { opacity: 0.4; transform: scale(0.995); pointer-events: none; }
</style>
@endpush

@section('content')
<div class="max-w-[1250px] mx-auto animate-fade-in pb-20 relative z-10 mt-2">

    {{-- AURA BACKGROUND (Eksklusif & Lembut) --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    {{-- ==========================================================
         1. HEADER & DASHBOARD ANALYTICS (WIDGETS)
         ========================================================== --}}
    <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-8 mb-8">
        
        {{-- Judul Halaman --}}
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-md mb-3 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                <span class="text-[9px] font-bold uppercase tracking-widest">Wewenang Bidan (Read-Only)</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight font-poppins mb-1.5">Cakupan Imunisasi</h1>
            <p class="text-slate-500 font-medium text-[13px]">Monitoring pelaksanaan imunisasi dasar balita dan tetanus ibu hamil.</p>
        </div>

        {{-- Widget Statistik (Didorong dari Scopes Controller) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full xl:w-auto shrink-0 animate-fade-in delay-100">
            <div class="stat-card">
                <div class="w-12 h-12 rounded-[14px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl border border-indigo-100 shrink-0"><i class="fas fa-syringe"></i></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Bulan Ini</p>
                    <p class="text-2xl font-bold text-slate-800 font-poppins leading-none">{{ $statBulanIni ?? 0 }} <span class="text-xs font-semibold text-slate-400 normal-case ml-1">Dosis</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="w-12 h-12 rounded-[14px] bg-sky-50 text-sky-600 flex items-center justify-center text-xl border border-sky-100 shrink-0"><i class="fas fa-baby"></i></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Cakupan Balita</p>
                    <p class="text-2xl font-bold text-slate-800 font-poppins leading-none">{{ $statBalita ?? 0 }} <span class="text-xs font-semibold text-slate-400 normal-case ml-1">Anak</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="w-12 h-12 rounded-[14px] bg-pink-50 text-pink-600 flex items-center justify-center text-xl border border-pink-100 shrink-0"><i class="fas fa-female"></i></div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Vaksin TT Bumil</p>
                    <p class="text-2xl font-bold text-slate-800 font-poppins leading-none">{{ $statBumil ?? 0 }} <span class="text-xs font-semibold text-slate-400 normal-case ml-1">Ibu</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================================
         2. KENDALI PENCARIAN & FILTER (NEXUS SAAS)
         ========================================================== --}}
    <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-[20px] p-4 shadow-sm mb-6 flex flex-col md:flex-row items-center justify-between gap-5 relative z-20 animate-fade-in delay-200">
        
        <form id="filterForm" action="{{ route('kader.imunisasi.index') }}" method="GET" class="w-full flex flex-col md:flex-row items-center justify-between gap-5">
            <input type="hidden" name="kategori" id="hiddenKategori" value="{{ request('kategori', 'semua') }}">
            
            {{-- Segmented Tabs --}}
            <div class="segment-control w-full md:w-auto">
                @php $reqKat = request('kategori', 'semua'); @endphp
                <div class="segment-btn {{ $reqKat === 'semua' ? 'active' : '' }}" data-kategori="semua">
                    <i class="fas fa-border-all mr-1.5 opacity-70"></i> Semua Program
                </div>
                <div class="segment-btn {{ $reqKat === 'balita' ? 'active' : '' }}" data-kategori="balita">
                    <i class="fas fa-baby mr-1.5 opacity-70 text-sky-500"></i> Imunisasi Balita
                </div>
                <div class="segment-btn {{ $reqKat === 'ibu_hamil' ? 'active' : '' }}" data-kategori="ibu_hamil">
                    <i class="fas fa-shield-virus mr-1.5 opacity-70 text-pink-500"></i> Vaksin TT Bumil
                </div>
            </div>

            {{-- Live Search Kapsul --}}
            <div class="relative w-full md:w-[350px]">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[0.8rem]"></i>
                <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari nama warga, NIK, atau jenis vaksin..." class="nexus-search" autocomplete="off">
                <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden text-indigo-500">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </form>
    </div>

    {{-- ==========================================================
         3. KANVAS TABEL / EMPTY STATE (DYNAMIC AREA)
         ========================================================== --}}
    <div id="mainContentArea" class="animate-fade-in delay-300">
        
        @if(isset($imunisasis) && $imunisasis->count() > 0)
            <div class="nexus-table-container flex flex-col min-h-[400px]">
                <div class="overflow-x-auto overflow-y-auto custom-scroll flex-1 max-h-[650px]">
                    <table class="nexus-table min-w-[1050px]">
                        <thead>
                            <tr>
                                <th class="w-48 pl-6 text-center">Waktu Eksekusi</th>
                                <th class="w-72">Identitas Penerima</th>
                                <th>Detail Vaksinasi</th>
                                <th class="w-56">Otoritas Medis</th>
                                <th class="w-24 text-center pr-6">Arsip</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($imunisasis as $imun)
                            @php
                                // Mengambil properti virtual cerdas dari Model Imunisasi
                                $nama = $imun->nama_penerima;
                                $nik = $imun->nik_penerima;
                                $kategori = $imun->kategori_sasaran; // "Balita", "Ibu Hamil", dll
                                $badgeColor = $imun->kategori_vaksin_badge; // "sky", "pink", "indigo"
                                
                                // Ikon dinamis berdasarkan warna badge
                                $icon = match($badgeColor) { 'sky' => 'fa-baby', 'pink' => 'fa-female', default => 'fa-syringe' };
                            @endphp
                            
                            <tr>
                                {{-- 1. WAKTU --}}
                                <td class="pl-6 text-center border-r border-slate-50">
                                    <span class="block text-[13px] font-bold text-slate-800">{{ $imun->tanggal_imunisasi->format('d M Y') }}</span>
                                    <span class="block text-[10px] font-medium text-slate-500 mt-1"><i class="far fa-clock"></i> {{ $imun->created_at->format('H:i') }} WIB</span>
                                </td>

                                {{-- 2. IDENTITAS --}}
                                <td>
                                    <h4 class="text-[13px] font-semibold text-slate-800 truncate mb-1" title="{{ $nama }}">{{ $nama }}</h4>
                                    <div class="flex items-center gap-1.5">
                                        <i class="far fa-address-card text-slate-300 text-[10px]"></i>
                                        <span class="text-[11px] font-mono text-slate-500 tracking-wide">{{ $nik }}</span>
                                    </div>
                                </td>

                                {{-- 3. DETAIL VAKSIN (Dengan Smart Badging dari Model) --}}
                                <td>
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-xl bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-600 flex items-center justify-center text-sm border border-{{ $badgeColor }}-100 shrink-0 shadow-sm">
                                            <i class="fas {{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-[14px] font-bold text-slate-800">{{ $imun->vaksin }}</h4>
                                                <span class="text-[9px] font-bold text-{{ $badgeColor }}-700 bg-{{ $badgeColor }}-100 border border-{{ $badgeColor }}-200 px-2 py-0.5 rounded uppercase tracking-widest shadow-sm">Dosis {{ $imun->dosis }}</span>
                                            </div>
                                            <p class="text-[11px] font-medium text-slate-500">{{ $imun->jenis_imunisasi }} &bull; Kategori: <span class="font-semibold">{{ $kategori }}</span></p>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. OTORITAS --}}
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 text-[10px] font-bold shrink-0">
                                            {{ strtoupper(substr($imun->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[12px] font-semibold text-slate-700 truncate">{{ $imun->kunjungan?->petugas?->name ?? 'Bidan Desa' }}</p>
                                            <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest truncate mt-0.5">{{ $imun->penyelenggara }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. AKSI (READ ONLY) --}}
                                <td class="text-center pr-6">
                                    <a href="{{ route('kader.imunisasi.show', $imun->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-colors shadow-sm" title="Lihat Sertifikat Vaksin">
                                        <i class="fas fa-file-medical text-[13px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginasi --}}
            @if($imunisasis->hasPages())
            <div id="paginationArea" class="mt-6 flex justify-end">
                {{ $imunisasis->links() }}
            </div>
            @endif

        @else
            {{-- EMPTY STATE (Bersih & Elegan) --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-[28px] border border-slate-200 p-16 md:p-24 text-center shadow-sm">
                <div class="relative w-24 h-24 mx-auto mb-5 flex items-center justify-center">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full blur-xl opacity-60"></div>
                    <div class="w-16 h-16 bg-white rounded-[16px] flex items-center justify-center text-indigo-400 text-2xl border border-indigo-50 shadow-sm relative z-10 transform -rotate-3">
                        <i class="fas fa-shield-virus"></i>
                    </div>
                </div>
                <h4 class="text-[15px] font-bold text-slate-800 uppercase tracking-widest mb-2 font-poppins">Riwayat Kosong</h4>
                <p class="text-[13px] text-slate-500 font-medium max-w-md mx-auto leading-relaxed">Sistem tidak menemukan arsip imunisasi yang cocok dengan filter atau kata pencarian Anda.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('liveSearchInput');
    const contentArea = document.getElementById('mainContentArea');
    const spinner = document.getElementById('searchSpinner');
    const form = document.getElementById('filterForm');
    const hiddenSearch = document.getElementById('hiddenSearch');
    const hiddenKategori = document.getElementById('hiddenKategori');

    // ENGINE AJAX SUPER MULUS
    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch) spinner.classList.remove('hidden');
        contentArea.classList.add('is-loading');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            const doc = new DOMParser().parseFromString(html, 'text/html');
            contentArea.innerHTML = doc.getElementById('mainContentArea').innerHTML;
            
            window.history.pushState({}, '', url);
            bindPagination();
        } catch (error) {
            console.error("Gagal memuat data EHR:", error);
        } finally {
            spinner.classList.add('hidden');
            contentArea.classList.remove('is-loading');
        }
    }

    // PENCARIAN KETIK (DEBOUNCE)
    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            hiddenSearch.value = e.target.value;
            typingTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', e.target.value);
                url.searchParams.set('kategori', hiddenKategori.value);
                fetchRealTimeData(url.toString(), true);
            }, 350); 
        });
    }

    // EVENT KLIK TAB KATEGORI (SEGMENTED CONTROL)
    document.querySelectorAll('.segment-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.segment-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const kat = this.dataset.kategori;
            hiddenKategori.value = kat;
            
            const url = new URL(form.action);
            url.searchParams.set('kategori', kat);
            url.searchParams.set('search', hiddenSearch.value);
            fetchRealTimeData(url.toString(), false);
        });
    });

    // EVENT PAGINATION AJAX
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
</script>
@endpush
@endsection