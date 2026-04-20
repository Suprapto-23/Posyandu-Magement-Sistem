@extends('layouts.kader')
@section('title', 'Tracker Imunisasi')
@section('page-name', 'Log Vaksinasi Warga')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .search-input {
        width: 100%; background-color: rgba(255,255,255,0.9); border: 2px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02);
    }
    .search-input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); }
    .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.08); }
    #tableContainer { transition: opacity 0.3s ease-in-out; }
    .loading-table { opacity: 0.4; pointer-events: none; filter: grayscale(20%); }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up pb-10">

    {{-- HEADER WITH AUTHORITY BADGE --}}
    <div class="bg-gradient-to-r from-white to-slate-50 rounded-[32px] p-8 md:p-10 mb-6 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm border border-slate-200 text-center sm:text-left">
        <div class="absolute right-0 top-0 w-64 h-full bg-indigo-500/10 blur-3xl rounded-full pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-40 h-40 bg-pink-500/10 blur-3xl rounded-full pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-white text-indigo-600 flex items-center justify-center text-3xl shrink-0 shadow-[0_8px_20px_rgba(79,70,229,0.15)] border border-indigo-100 transform -rotate-3 hover:rotate-0 transition-transform">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div>
                <h2 class="text-2xl sm:text-3xl font-black font-poppins text-slate-800 tracking-tight">Log Imunisasi & Vaksin</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Pantau riwayat pemberian vaksin warga untuk evaluasi pelayanan.</p>
            </div>
        </div>
        
        <div class="relative z-10 inline-flex flex-col items-center sm:items-end gap-1.5">
            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2">
                <i class="fas fa-lock text-sm"></i>
                <span class="text-[11px] font-black uppercase tracking-widest">Wewenang Medis (Meja 5)</span>
            </div>
            <p class="text-[10px] font-bold text-slate-400">Data hanya bisa diinput oleh Bidan.</p>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="glass-card rounded-[24px] p-3 mb-6 flex flex-col xl:flex-row items-center justify-between gap-4 relative z-20">
        
        <form id="filterForm" action="{{ route('kader.imunisasi.index') }}" method="GET" class="w-full xl:w-auto">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
            <input type="hidden" name="kategori" id="hiddenKategori" value="{{ request('kategori', 'semua') }}">
        </form>

        <div class="flex gap-2 w-full xl:w-auto overflow-x-auto custom-scrollbar pb-2 sm:pb-0 justify-start sm:justify-center bg-slate-100/50 p-1.5 rounded-[20px] border border-slate-200/50">
            @php 
                $tabs = [
                    'semua'     => ['label' => 'Semua', 'icon' => 'fa-list'],
                    'balita'    => ['label' => 'Balita', 'icon' => 'fa-baby'],
                    'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female'],
                    'remaja'    => ['label' => 'Remaja', 'icon' => 'fa-user-graduate'],
                    'lansia'    => ['label' => 'Lansia', 'icon' => 'fa-user-clock']
                ];
                $currentCat = request('kategori', 'semua'); 
            @endphp
            
            @foreach($tabs as $k => $v)
                <button type="button" data-kategori="{{ $k }}" class="tab-btn px-5 py-2.5 rounded-xl font-extrabold text-[11px] uppercase tracking-wider whitespace-nowrap transition-all flex items-center gap-2 border {{ $currentCat == $k ? 'bg-indigo-600 text-white border-indigo-600 shadow-md active-tab' : 'bg-transparent text-slate-500 border-transparent hover:bg-white hover:border-slate-200 hover:shadow-sm' }}">
                   @if($v['icon']) <i class="fas {{ $v['icon'] }} {{ $currentCat == $k ? 'text-indigo-200' : 'text-slate-400' }}"></i> @endif
                   {{ $v['label'] }}
                </button>
            @endforeach
        </div>
        
        <div class="w-full xl:w-80 relative group">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" id="liveSearchInput" value="{{ request('search') }}" placeholder="Ketik nama warga / jenis vaksin..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-5 top-1/2 -translate-y-1/2 hidden">
                <i class="fas fa-circle-notch fa-spin text-indigo-500"></i>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT AREA --}}
    <div id="mainContentArea">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-[0_5px_20px_-10px_rgba(0,0,0,0.05)] overflow-hidden" id="tableContainer">
            <div class="overflow-x-auto custom-scrollbar min-h-[400px]">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead class="bg-slate-50/90 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Waktu Pemberian</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Identitas Pasien</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Detail Vaksin / Obat</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-100">Otoritas (Medis)</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($imunisasis ?? [] as $imun)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <td class="px-6 py-4 border-r border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-[14px] bg-slate-100 flex flex-col items-center justify-center text-slate-600 border border-slate-200 shrink-0 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                        <span class="text-[10px] font-bold uppercase tracking-widest leading-none mb-0.5">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->translatedFormat('M') }}</span>
                                        <span class="text-[16px] font-black leading-none font-poppins">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-bold text-slate-500"><i class="far fa-clock"></i> {{ $imun->created_at->format('H:i') }} WIB</p>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $imun->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 border-r border-slate-100">
                                @php
                                    $tipePasienRaw = class_basename($imun->kunjungan?->pasien_type);
                                    $tipeLengkap = match($tipePasienRaw) {
                                        'Balita'   => 'Balita & Anak',
                                        'Remaja'   => 'Usia Remaja',
                                        'IbuHamil' => 'Ibu Hamil',
                                        'Lansia'   => 'Lanjut Usia',
                                        default    => 'Tidak Diketahui'
                                    };
                                    $badge = match($tipePasienRaw) {
                                        'Balita'   => 'bg-sky-50 text-sky-600 border-sky-200',
                                        'Remaja'   => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                        'IbuHamil' => 'bg-pink-50 text-pink-600 border-pink-200',
                                        'Lansia'   => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        default    => 'bg-slate-50 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                {{-- KODE YANG DIPERBAIKI (Tarik nama dari relasi kunjungan->pasien) --}}
                                <p class="font-black text-slate-800 text-[14px] mb-1.5 truncate max-w-[200px] font-poppins group-hover:text-indigo-700 transition-colors">{{ $imun->kunjungan?->pasien?->nama_lengkap ?? 'Data Dihapus' }}</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border shadow-sm {{ $badge }}">
                                    <i class="fas fa-tag"></i> {{ $tipeLengkap }}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-[12px] bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm border border-indigo-100 shrink-0">
                                        <i class="fas fa-syringe text-[16px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-black text-slate-800 font-poppins">{{ $imun->vaksin }}</p>
                                        <div class="flex items-center gap-2 mt-1 text-[11px]">
                                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">Dosis {{ $imun->dosis }}</span>
                                            <span class="font-bold text-slate-500">{{ $imun->jenis_imunisasi }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center border-r border-slate-100">
                                <div class="inline-flex flex-col items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 mb-1.5">
                                        {{ strtoupper(substr($imun->kunjungan?->petugas?->name ?? 'B', 0, 1)) }}
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $imun->kunjungan?->petugas?->name ?? 'Bidan Desa' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kader.imunisasi.show', $imun->id) }}" class="loader-trigger inline-flex w-10 h-10 rounded-[12px] bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 shadow-sm transition-all hover:scale-105" title="Lihat Sertifikat Vaksin">
                                    <i class="fas fa-file-invoice text-[15px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center bg-slate-50/30">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-slate-300 mx-auto mb-5 shadow-sm border border-slate-100">
                                    <i class="fas fa-shield-virus text-4xl"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg font-poppins mb-1">Riwayat Imunisasi Kosong</h3>
                                <p class="text-sm text-slate-500 font-medium">Belum ada catatan penyuntikan vaksin atau imunisasi di database posyandu Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div id="paginationArea" class="px-6 py-4 border-t border-slate-100 bg-slate-50/80">
                @if(isset($imunisasis) && $imunisasis->hasPages()) {{ $imunisasis->links() }} @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('liveSearchInput');
    const tableContainer = document.getElementById('tableContainer');
    const spinner = document.getElementById('searchSpinner');
    const form = document.getElementById('filterForm');

    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch) spinner.classList.remove('hidden');
        tableContainer.classList.add('loading-table');
        
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('mainContentArea').innerHTML = doc.getElementById('mainContentArea').innerHTML;
            
            window.history.pushState({}, '', url);
            bindPagination();
        } catch (error) {
            console.error("Gagal sinkronisasi data:", error);
        } finally {
            spinner.classList.add('hidden');
            document.getElementById('tableContainer')?.classList.remove('loading-table');
        }
    }

    if(searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(typingTimer);
            document.getElementById('hiddenSearch').value = e.target.value;
            
            typingTimer = setTimeout(() => {
                const url = new URL(form.action);
                url.searchParams.set('search', e.target.value);
                url.searchParams.set('kategori', document.getElementById('hiddenKategori').value);
                fetchRealTimeData(url.toString(), true);
            }, 400); 
        });
    }

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'active-tab');
                b.classList.add('bg-transparent', 'text-slate-500', 'border-transparent');
                const icon = b.querySelector('i');
                if(icon) { icon.classList.remove('text-indigo-200'); icon.classList.add('text-slate-400'); }
            });
            
            this.classList.remove('bg-transparent', 'text-slate-500', 'border-transparent');
            this.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md', 'active-tab');
            const myIcon = this.querySelector('i');
            if(myIcon) { myIcon.classList.remove('text-slate-400'); myIcon.classList.add('text-indigo-200'); }

            const kat = this.dataset.kategori;
            document.getElementById('hiddenKategori').value = kat;
            
            const url = new URL(form.action);
            url.searchParams.set('kategori', kat);
            url.searchParams.set('search', document.getElementById('hiddenSearch').value);
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