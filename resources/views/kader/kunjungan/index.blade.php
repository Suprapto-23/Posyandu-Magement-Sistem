@extends('layouts.kader')

@section('title', 'Buku Kehadiran')
@section('page-name', 'Logbook Kunjungan')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .search-input {
        width: 100%; background: rgba(255,255,255,0.95); border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 3rem;
        outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .search-input:focus { border-color: #0ea5e9; box-shadow: 0 4px 15px -3px rgba(14, 165, 233, 0.15); }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    #tableLoaderOverlay { opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
    #tableLoaderOverlay.active { opacity: 1; pointer-events: auto; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="bg-white rounded-[32px] p-8 mb-6 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm border border-slate-200/80 text-center sm:text-left">
        <div class="absolute left-0 bottom-0 w-64 h-full bg-cyan-500/5 blur-3xl rounded-tr-full pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-cyan-100 transform -rotate-3">
                <i class="fas fa-clipboard-user"></i>
            </div>
            <div>
                <h2 class="text-2xl sm:text-3xl font-black font-poppins text-slate-800 tracking-tight">Buku Kehadiran Warga</h2>
                <p class="text-slate-500 text-sm font-medium">Logbook resepsionis otomatis pencatat kunjungan Posyandu.</p>
            </div>
        </div>
        
        <div class="relative z-10 inline-flex items-center gap-2.5 bg-slate-50 border border-slate-200 text-slate-600 px-5 py-3 rounded-xl shadow-sm">
            <i class="fas fa-robot text-cyan-500 text-lg"></i>
            <div class="text-left">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none mb-0.5">Sistem AI</p>
                <p class="text-xs font-black">Dicatat Otomatis</p>
            </div>
        </div>
    </div>

    {{-- FILTER & PENCARIAN --}}
    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4 relative z-20">
        
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-2 sm:pb-0 justify-start sm:justify-center">
            @php $currentCat = request('kategori', 'semua'); @endphp
            @foreach([
                'semua'     => ['label' => 'Semua Kunjungan', 'icon' => 'fa-list'],
                'balita'    => ['label' => '👶 Balita', 'icon' => ''],
                'remaja'    => ['label' => '🎓 Remaja', 'icon' => ''],
                'ibu_hamil' => ['label' => '🤰 Ibu Hamil', 'icon' => ''],
                'lansia'    => ['label' => '👴 Lansia', 'icon' => '']
            ] as $k => $v)
                <a href="{{ request()->fullUrlWithQuery(['kategori'=>$k, 'page'=>1]) }}" 
                   class="kategori-btn px-5 py-2.5 rounded-xl font-extrabold text-[11px] uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2
                   {{ $currentCat == $k ? 'bg-cyan-600 text-white border-cyan-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800' }}">
                   @if($v['icon']) <i class="fas {{ $v['icon'] }}"></i> @endif
                   {{ $v['label'] }}
                </a>
            @endforeach
        </div>
        
        <div class="w-full lg:w-96 relative group">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-cyan-500 transition-colors"></i>
            <input type="text" id="searchInput" value="{{ $search ?? '' }}" placeholder="Cari nama warga yang hadir..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-5 top-1/2 -translate-y-1/2 hidden"><i class="fas fa-circle-notch fa-spin text-cyan-500 text-lg"></i></div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div id="table-container" class="relative transition-opacity duration-300">
        <div id="tableLoaderOverlay" class="absolute inset-0 bg-white/70 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center rounded-[24px]">
            <div class="px-6 py-4 bg-white border border-cyan-100 shadow-xl rounded-2xl flex items-center gap-3"><i class="fas fa-sync-alt fa-spin text-cyan-600 text-xl"></i><span class="font-black text-slate-800 text-[12px] uppercase tracking-widest">Menyinkronkan...</span></div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead class="bg-slate-50/80 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Waktu Kedatangan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Profil Pasien</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Layanan Diterima</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center border-r border-slate-200/50">Petugas Resepsionis</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Buka Nota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kunjungans as $kunjungan)
                        <tr class="hover:bg-cyan-50/30 transition-colors even:bg-slate-50/40 group">
                            
                            <td class="px-6 py-3.5 border-r border-slate-200/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-black shadow-sm border border-cyan-100 shrink-0">
                                        {{ \Carbon\Carbon::parse($kunjungan->created_at)->format('d') }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-[13px]">{{ \Carbon\Carbon::parse($kunjungan->created_at)->format('M Y') }}</p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5"><i class="fas fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($kunjungan->created_at)->format('H:i') }} WIB</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-3.5 border-r border-slate-200/50">
                                <p class="font-black text-slate-800 text-[13px] mb-1 truncate max-w-[200px]">{{ $kunjungan->pasien->nama_lengkap ?? 'Unknown Pasien' }}</p>
                                @php 
                                    $tipe = class_basename($kunjungan->pasien_type); 
                                    $badge = match($tipe) {
                                        'Balita'   => 'bg-violet-100 text-violet-700 border-violet-200',
                                        'Remaja'   => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'IbuHamil' => 'bg-pink-100 text-pink-700 border-pink-200',
                                        'Lansia'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        default    => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-block px-2 py-0.5 text-[9px] font-black uppercase tracking-widest border rounded {{ $badge }}">
                                    {{ match($tipe) { 'IbuHamil' => 'Ibu Hamil', default => $tipe } }}
                                </span>
                            </td>

                            <td class="px-6 py-3.5 border-r border-slate-200/50">
                                <div class="flex flex-wrap gap-2 mb-1.5">
                                    @if($kunjungan->pemeriksaan)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-indigo-50 text-indigo-600 text-[10px] font-black border border-indigo-100"><i class="fas fa-stethoscope"></i> Cek Fisik</span>
                                    @endif
                                    @if($kunjungan->imunisasis && $kunjungan->imunisasis->count() > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-teal-50 text-teal-600 text-[10px] font-black border border-teal-100"><i class="fas fa-syringe"></i> Vaksin</span>
                                    @endif
                                    @if(!$kunjungan->pemeriksaan && (!$kunjungan->imunisasis || $kunjungan->imunisasis->count() == 0))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-black border border-slate-200"><i class="fas fa-comment"></i> Konsultasi Umum</span>
                                    @endif
                                </div>
                                @if($kunjungan->keluhan)
                                    <p class="text-[10px] font-bold text-slate-500 italic max-w-[200px] truncate">"{{ $kunjungan->keluhan }}"</p>
                                @endif
                            </td>

                            <td class="px-6 py-3.5 text-center border-r border-slate-200/50">
                                <div class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[9px] font-black text-slate-500 shrink-0">
                                        {{ strtoupper(substr($kunjungan->petugas->name ?? 'K', 0, 1)) }}
                                    </div>
                                    <p class="font-bold text-slate-700 text-[11px]">{{ $kunjungan->petugas->name ?? 'Sistem' }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-3.5 text-center">
                                <a href="{{ route('kader.kunjungan.show', $kunjungan->id) }}" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-400 hover:text-cyan-600 hover:border-cyan-300 hover:bg-cyan-50 shadow-sm transition-all" title="Buka Nota Kunjungan">
                                    <i class="fas fa-file-invoice text-sm"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg font-poppins">Buku Tamu Kosong</h3>
                                <p class="text-sm text-slate-500 mt-1 font-medium">Data kunjungan akan otomatis terisi saat Anda menginput pengukuran fisik.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div id="paginationArea" class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                @if(isset($kunjungans) && $kunjungans->hasPages()) {{ $kunjungans->links() }} @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('table-container');
    const spinner = document.getElementById('searchSpinner');
    const overlay = document.getElementById('tableLoaderOverlay');

    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch) spinner.classList.remove('hidden');
        else overlay.classList.add('active');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            
            if (newTable) {
                tableContainer.innerHTML = newTable.innerHTML;
                bindPagination();
            }
            window.history.pushState({}, '', url);
        } catch (error) {} 
        finally {
            spinner.classList.add('hidden');
            const newOverlay = document.getElementById('tableLoaderOverlay');
            if(newOverlay) newOverlay.classList.remove('active');
        }
    }

    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', e.target.value);
                url.searchParams.delete('page');
                fetchRealTimeData(url.toString(), true);
            }, 300);
        });
    }

    document.querySelectorAll('.kategori-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            
            document.querySelectorAll('.kategori-btn').forEach(b => {
                b.classList.remove('bg-cyan-600', 'text-white', 'border-cyan-600', 'shadow-md');
                b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
            });
            
            this.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
            this.classList.add('bg-cyan-600', 'text-white', 'border-cyan-600', 'shadow-md');

            const url = new URL(this.href);
            if(searchInput && searchInput.value) url.searchParams.set('search', searchInput.value);
            fetchRealTimeData(url.toString(), false);
        });
    });

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