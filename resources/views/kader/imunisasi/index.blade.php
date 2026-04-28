@extends('layouts.kader')
@section('title', 'Tracker Imunisasi')
@section('page-name', 'Log Vaksinasi Warga')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK KILAT (Lebih Cepat & Snappy) */
    .fade-in-up { animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .stagger-1 { animation-delay: 0.05s; } .stagger-2 { animation-delay: 0.1s; } .stagger-3 { animation-delay: 0.15s; }

    /* INPUT PENCARIAN */
    .glass-search {
        width: 100%; background-color: #f8fafc; border: 2px solid transparent; color: #1e293b;
        font-size: 0.85rem; font-weight: 600; border-radius: 9999px; padding: 0.8rem 1.5rem 0.8rem 3rem;
        outline: none; transition: all 0.2s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .glass-search:focus { background-color: #ffffff; border-color: #818cf8; box-shadow: 0 4px 20px -3px rgba(99, 102, 241, 0.15); }

    /* TABEL DALAM KONTENER */
    .table-canvas {
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 32px;
        box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.05); padding: 1.5rem;
    }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .modern-table th {
        color: #94a3b8; font-size: 0.65rem; font-weight: 900; text-transform: uppercase;
        letter-spacing: 0.05em; padding: 0.5rem 1.5rem 1rem 1.5rem; text-align: left; border-bottom: 2px solid #f8fafc;
    }
    
    /* BARIS DATA */
    .data-row {
        background-color: #ffffff; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px; box-shadow: 0 2px 10px -2px rgba(0,0,0,0.02); border: 1px solid #f8fafc;
    }
    .data-row td { padding: 1rem 1.5rem; vertical-align: middle; }
    .data-row td:first-child { border-top-left-radius: 20px; border-bottom-left-radius: 20px; border-left: 4px solid transparent; transition: border-color 0.2s ease; }
    .data-row td:last-child { border-top-right-radius: 20px; border-bottom-right-radius: 20px; }
    
    .data-row:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.15); border-color: transparent; }
    .data-row:hover td:first-child { border-left-color: #6366f1; }

    .pill-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; }

    /* LOADING AJAX SUPER CEPAT (Tanpa Blur Berat) */
    #mainContentArea { transition: opacity 0.15s ease-out; }
    .fast-loading { opacity: 0.5; pointer-events: none; }

    /* ANIMASI SVG EMPTY STATE (Murni CSS, Bebas Localhost Error) */
    @keyframes hoverShield { 0%, 100% { transform: translateY(0); filter: drop-shadow(0 10px 15px rgba(99,102,241,0.2)); } 50% { transform: translateY(-10px); filter: drop-shadow(0 20px 25px rgba(99,102,241,0.4)); } }
    @keyframes pulseRing { 0% { transform: scale(0.7); opacity: 1; } 100% { transform: scale(2.2); opacity: 0; } }
    .svg-shield { animation: hoverShield 3s ease-in-out infinite; }
    .svg-ring { animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; border: 2px solid #818cf8; border-radius: 50%; position: absolute; inset: 0; margin: auto; }
</style>
@endpush

@section('content')
<div class="max-w-[1350px] mx-auto fade-in-up pb-12 relative z-10">

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-gradient-to-b from-indigo-50/80 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

    {{-- 1. HEADER (KARTU KACA MELENGKUNG) --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[36px] border border-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 z-10">
        
        <div class="absolute -left-12 -top-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl"></div>

        <div class="flex items-center gap-5 relative z-10 w-full md:w-auto">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-2xl shadow-[0_8px_20px_rgba(99,102,241,0.3)] shrink-0 transform -rotate-3">
                <i class="fas fa-syringe"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight font-poppins mb-1">Log Imunisasi Warga</h1>
                <p class="text-slate-500 font-medium text-[13px]">Fokus Program: Imunisasi Dasar Balita & Vaksin Tetanus (TT) Ibu Hamil.</p>
            </div>
        </div>
        
        <div class="relative z-10 shrink-0 w-full md:w-auto text-left md:text-right">
            <div class="inline-flex items-center gap-2 bg-rose-50 border border-rose-100 text-rose-600 px-5 py-2.5 rounded-full shadow-sm mb-1.5">
                <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                <span class="text-[10px] font-black uppercase tracking-widest">Wewenang Bidan (Meja 5)</span>
            </div>
            <p class="text-[10px] font-bold text-slate-400 block ml-2 md:ml-0">Akses Input Eksklusif</p>
        </div>
    </div>

    {{-- 2. KENDALI PENCARIAN & TAB --}}
    <div class="mb-6 relative z-20">
        <form id="filterForm" action="{{ route('kader.imunisasi.index') }}" method="GET" class="flex flex-col lg:flex-row items-center justify-between gap-5">
            
            <input type="hidden" name="kategori" id="hiddenKategori" value="{{ request('kategori', 'semua') }}">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">

            <div class="flex gap-2 w-full lg:w-auto overflow-x-auto pb-2 lg:pb-0" style="scrollbar-width: none;">
                @foreach([
                    'semua'     => ['label' => 'Semua Program', 'icon' => 'fa-border-all'],
                    'balita'    => ['label' => 'Imunisasi Balita', 'icon' => 'fa-baby'],
                    'ibu_hamil' => ['label' => 'Vaksin TT (Bumil)', 'icon' => 'fa-female']
                ] as $val => $data)
                    @php $isActive = request('kategori', 'semua') === $val; @endphp
                    <button type="button" data-kategori="{{ $val }}" class="tab-btn px-6 py-3.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all duration-200 whitespace-nowrap {{ $isActive ? 'bg-indigo-600 text-white shadow-[0_8px_20px_rgba(99,102,241,0.3)] transform scale-105' : 'bg-white text-slate-500 hover:bg-slate-50 hover:text-indigo-600 shadow-sm border border-slate-100' }}">
                        <i class="fas {{ $data['icon'] }} mr-2 {{ $isActive ? 'text-indigo-200' : 'text-slate-400' }}"></i> {{ $data['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full lg:w-[450px] group flex items-center">
                <div class="absolute inset-0 bg-indigo-500/5 rounded-full blur-md opacity-0 group-focus-within:opacity-100 transition-opacity"></div>
                <i class="fas fa-search absolute left-6 text-slate-400 text-sm group-focus-within:text-indigo-500 transition-colors z-10"></i>
                <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="Ketik nama balita, ibu, atau vaksin..." class="glass-search relative z-0" autocomplete="off">
                <div id="searchSpinner" class="absolute right-6 hidden z-10">
                    <i class="fas fa-circle-notch fa-spin text-indigo-500"></i>
                </div>
            </div>
            
        </form>
    </div>

    {{-- 3. AREA TABEL / EMPTY STATE (DYNAMIC) --}}
    <div id="mainContentArea" class="relative z-10">
        
        @if(isset($imunisasis) && $imunisasis->count() > 0)
            {{-- DATA TERSEDIA: TAMPILKAN TABEL FULL --}}
            <div class="table-canvas">
                <div class="overflow-x-auto" style="scrollbar-width: thin; min-h: 300px;">
                    <table class="modern-table min-w-[1000px]">
                        <thead>
                            <tr>
                                <th class="w-56 pl-8">Tanggal Pelaksanaan</th>
                                <th class="w-80">Identitas Penerima</th>
                                <th>Detail Imunisasi</th>
                                <th class="w-40 text-center">Tenaga Medis</th>
                                <th class="w-32 text-center pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($imunisasis as $index => $imun)
                            @php
                                $staggerClass = 'stagger-' . (($index % 5) + 1);
                                $tipePasienRaw = class_basename($imun->kunjungan?->pasien_type);
                                $tipeLengkap = $tipePasienRaw === 'IbuHamil' ? 'Ibu Hamil (TT)' : 'Balita Dasar';
                                $badgeColor = $tipePasienRaw === 'IbuHamil' ? 'pink' : 'sky';
                            @endphp
                            
                            <tr class="data-row fade-in-up {{ $staggerClass }}">
                                
                                {{-- 1. WAKTU --}}
                                <td class="pl-8">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col items-center justify-center w-11 h-11 bg-slate-50 rounded-[14px] text-slate-700 shrink-0 border border-slate-100">
                                            <span class="text-[8px] font-black uppercase leading-none mb-1 text-slate-400">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->translatedFormat('M') }}</span>
                                            <span class="text-[16px] font-black leading-none font-poppins">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d') }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-black text-slate-800">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('Y') }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 mt-0.5"><i class="far fa-clock"></i> {{ $imun->created_at->format('H:i') }} WIB</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. IDENTITAS --}}
                                <td>
                                    <div class="flex flex-col gap-1.5 items-start">
                                        <span class="text-[14px] font-black text-slate-800 font-poppins truncate max-w-[240px]" title="{{ $imun->kunjungan?->pasien?->nama_lengkap }}">{{ $imun->kunjungan?->pasien?->nama_lengkap ?? 'Data Terhapus' }}</span>
                                        <span class="pill-badge bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-600 border border-{{ $badgeColor }}-100">
                                            <i class="fas fa-tag"></i> {{ $tipeLengkap }}
                                        </span>
                                    </div>
                                </td>

                                {{-- 3. DETAIL VAKSIN --}}
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0 border border-indigo-100">
                                            <i class="fas fa-syringe text-xs"></i>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[13px] font-black text-slate-800">{{ $imun->vaksin }}</span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="pill-badge bg-indigo-600 text-white shadow-sm">Dosis {{ $imun->dosis }}</span>
                                                <span class="pill-badge bg-slate-50 text-slate-500 border border-slate-200">{{ $imun->jenis_imunisasi }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. OTORITAS --}}
                                <td class="text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-7 h-7 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 text-[10px] font-black mb-1.5">
                                            {{ strtoupper(substr($imun->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-600 truncate max-w-[120px]">{{ $imun->kunjungan?->petugas?->name ?? 'Bidan Desa' }}</span>
                                    </div>
                                </td>

                                {{-- 5. AKSI (SEBELUMNYA SERTIFIKAT) --}}
                                <td class="text-center pr-8">
                                    <a href="{{ route('kader.imunisasi.show', $imun->id) }}" class="inline-flex w-9 h-9 rounded-full bg-slate-50 border border-slate-200 items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-sm transition-all hover:scale-110" title="Detail Arsip">
                                        <i class="fas fa-file-invoice text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($imunisasis->hasPages())
                <div id="paginationArea" class="mt-4 flex justify-center pt-4 border-t border-slate-100">
                    {{ $imunisasis->links() }}
                </div>
                @endif
            </div>

        @else
            {{-- DATA KOSONG: TABEL & HEADER MUSNAH, HANYA TAMPIL ANIMASI --}}
            <div class="table-canvas flex flex-col items-center justify-center p-12 lg:p-24 fade-in-up">
                <div class="relative w-36 h-36 mb-6 flex items-center justify-center">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full blur-2xl opacity-60"></div>
                    
                    {{-- Gelombang Radar --}}
                    <div class="svg-ring w-24 h-24"></div>
                    <div class="svg-ring w-24 h-24" style="animation-delay: 1s;"></div>
                    
                    {{-- Logo Tameng Vaksin 3D Murni --}}
                    <svg class="svg-shield w-20 h-20 text-indigo-500 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 11.99H19C18.47 16.11 15.72 19.78 12 20.92V12H5V6.3L12 3.19V11.99Z"></path>
                    </svg>
                </div>
                <h4 class="text-[18px] font-black text-slate-800 uppercase tracking-widest mb-2 font-poppins">Pencarian Kosong</h4>
                <p class="text-[13px] text-slate-500 font-medium leading-relaxed max-w-sm text-center">Sistem telah memindai data rekam medis, namun tidak ada riwayat imunisasi yang cocok dengan pencarian Anda saat ini.</p>
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

    // ENGINE AJAX SUPER CEPAT
    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch) spinner.classList.remove('hidden');
        
        // Transisi Opasitas Instan (Tanpa Blur yang Bikin Lemot)
        contentArea.classList.add('fast-loading');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            const doc = new DOMParser().parseFromString(html, 'text/html');
            contentArea.innerHTML = doc.getElementById('mainContentArea').innerHTML;
            
            window.history.pushState({}, '', url);
            bindPagination();
        } catch (error) {
            console.error("Gagal memuat data:", error);
        } finally {
            spinner.classList.add('hidden');
            contentArea.classList.remove('fast-loading');
        }
    }

    // PENCARIAN KETIK (DEBOUNCE KILAT: 200ms)
    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            hiddenSearch.value = e.target.value;
            
            typingTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', e.target.value);
                url.searchParams.set('kategori', hiddenKategori.value);
                fetchRealTimeData(url.toString(), true);
            }, 200); // 200ms memberikan respon yang jauh lebih instan & snappy
        });
    }

    // EVENT KLIK TAB KATEGORI
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'shadow-[0_8px_20px_rgba(99,102,241,0.3)]', 'scale-105');
                b.classList.add('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'hover:text-indigo-600');
                const icon = b.querySelector('i');
                if(icon) { icon.classList.remove('text-indigo-200'); icon.classList.add('text-slate-400'); }
            });
            
            this.classList.remove('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'hover:text-indigo-600');
            this.classList.add('bg-indigo-600', 'text-white', 'shadow-[0_8px_20px_rgba(99,102,241,0.3)]', 'scale-105');
            const myIcon = this.querySelector('i');
            if(myIcon) { myIcon.classList.remove('text-slate-400'); myIcon.classList.add('text-indigo-200'); }

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