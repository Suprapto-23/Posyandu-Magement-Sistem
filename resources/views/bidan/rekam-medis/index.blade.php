@extends('layouts.bidan')
@section('title', 'Buku Induk EMR')
@section('page-name', 'Arsip Digital Warga')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .mark-search { background-color: #fef08a; color: #854d0e; padding: 0 3px; border-radius: 3px; font-weight: 900; }
    .tab-active { background: #0891b2; color: white; border-color: #0891b2; box-shadow: 0 4px 10px rgba(8, 145, 178, 0.2); }
    .tab-inactive { background: white; color: #64748b; border-color: #e2e8f0; }
    .tab-inactive:hover { background: #f8fafc; color: #475569; }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')

@php
    // ANTI-CRASH: Fungsi ini sekarang tahan terhadap nilai NULL dari Database
    function safeHighlight($text, $search) {
        if (empty($text)) return '-';
        if (!$search || strlen($search) < 3) return htmlspecialchars($text);
        $pattern = '/' . preg_quote($search, '/') . '/i';
        return preg_replace($pattern, "<span class='mark-search'>$0</span>", htmlspecialchars($text));
    }
@endphp

<div class="space-y-5 animate-slide-up pb-10" x-data="emrSearchApp()">
    
    {{-- 1. HEADER & SEARCH COMPACT --}}
    <div class="bg-white rounded-[20px] p-5 md:p-6 border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-5 relative z-20">
        
        <div class="flex items-center gap-4 w-full lg:w-auto">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl border border-cyan-100 shadow-inner shrink-0">
                <i class="fas fa-notes-medical"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight font-poppins">Buku Rekam Medis</h1>
                <p class="text-[12px] font-medium text-slate-500">Ketik minimal 3 huruf untuk mencari data otomatis.</p>
            </div>
        </div>
        
        <div class="relative w-full lg:w-[400px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400" x-show="!isSearching"></i>
                <i class="fas fa-circle-notch fa-spin text-cyan-500" x-show="isSearching" x-cloak></i>
            </div>
            
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari nama atau NIK..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-[14px] pl-10 pr-10 py-3 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-100 outline-none transition-all">
            
            <button type="button" x-show="searchQuery !== ''" @click="clearSearch()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600 transition-colors" x-cloak>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- 2. TABS NAVIGASI COMPACT --}}
    <div class="flex gap-3 overflow-x-auto pb-1 hide-scrollbar">
        @php $tabs = ['balita' => ['icon'=>'baby', 'label'=>'Bayi & Balita'], 'ibu_hamil' => ['icon'=>'female', 'label'=>'Ibu Hamil'], 'remaja' => ['icon'=>'user-graduate', 'label'=>'Remaja'], 'lansia' => ['icon'=>'user-clock', 'label'=>'Lansia']]; @endphp
        
        @foreach($tabs as $key => $t)
            <button type="button" 
                    @click="switchTab('{{ $key }}')" 
                    class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all border flex items-center gap-2 shrink-0"
                    :class="currentType === '{{ $key }}' ? 'tab-active' : 'tab-inactive'">
                <i class="fas fa-{{ $t['icon'] }} text-sm"></i> {{ $t['label'] }}
            </button>
        @endforeach
    </div>

    {{-- 3. GRID KARTU PASIEN (AJAX TARGET) --}}
    <div id="emr-grid-container" class="relative min-h-[300px] transition-opacity duration-300" :class="isSearching ? 'opacity-50' : 'opacity-100'">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
            @forelse($data as $row)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-lg hover:border-cyan-200 transition-all group flex flex-col relative overflow-hidden">
                
                <div class="flex items-start justify-between mb-3 border-b border-slate-50 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-lg text-slate-400 group-hover:bg-cyan-50 group-hover:text-cyan-500 transition-colors shrink-0">
                            <i class="fas fa-{{ $type == 'balita' ? 'baby' : ($type == 'ibu_hamil' ? 'female' : 'user') }}"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="text-[14px] font-black text-slate-800 truncate group-hover:text-cyan-700 transition-colors">
                                {!! safeHighlight($row->nama_lengkap, $search) !!}
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 truncate mt-0.5">NIK: {!! safeHighlight($row->nik, $search) !!}</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <div class="flex flex-wrap gap-2 text-[11px] font-bold text-slate-600 mb-4">
                        <span class="bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="fas fa-venus-mars text-slate-300 mr-1"></i> {{ $row->jenis_kelamin == 'L' ? 'L' : 'P' }}</span>
                        {{-- ANTI-CRASH: Cek null pada tanggal_lahir --}}
                        <span class="bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="fas fa-calendar-alt text-slate-300 mr-1"></i> {{ $row->tanggal_lahir ? \Carbon\Carbon::parse($row->tanggal_lahir)->age : 0 }} Thn</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-50 flex items-center justify-between mt-auto">
                    <div class="text-[9px] font-bold text-slate-400">
                        Update:<br>
                        {{-- ANTI-CRASH: Cek null pada updated_at --}}
                        <span class="text-slate-500">{{ $row->updated_at ? $row->updated_at->diffForHumans() : 'Sistem' }}</span>
                    </div>
                    <a href="{{ route('bidan.rekam-medis.show', ['pasien_type' => $type, 'pasien_id' => $row->id]) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-cyan-600 hover:text-white transition-all">
                        Buka EMR <i class="fas fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fas fa-folder-open text-4xl text-slate-200 mb-3"></i>
                <h3 class="text-[15px] font-black text-slate-700 font-poppins mb-1">Data Tidak Ditemukan</h3>
                <p class="text-[12px] font-medium text-slate-500 max-w-xs mx-auto">
                    @if($search)
                        Tidak ada warga bernama/NIK <b class="text-slate-700">"{{ $search }}"</b> di kategori ini.
                    @else
                        Belum ada data warga di kategori ini.
                    @endif
                </p>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($data->hasPages())
        <div class="mt-5 bg-white py-3 px-4 rounded-[16px] border border-slate-200 shadow-sm flex justify-center">
            {{ $data->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('emrSearchApp', () => ({
            searchQuery: '{{ $search ?? '' }}',
            currentType: '{{ $type ?? 'balita' }}',
            isSearching: false,

            init() {
                this.$watch('searchQuery', Alpine.debounce((value) => {
                    const trimmed = value.trim();
                    if (trimmed.length >= 3 || trimmed.length === 0) {
                        this.fetchData();
                    }
                }, 400));
            },

            switchTab(type) {
                if(this.currentType === type) return; 
                this.currentType = type;
                this.searchQuery = ''; 
                this.fetchData();
            },

            clearSearch() {
                this.searchQuery = '';
            },

            async fetchData() {
                this.isSearching = true;
                
                let url = new URL(window.location.origin + '{{ route('bidan.rekam-medis.index', [], false) }}');
                url.searchParams.append('type', this.currentType);
                if(this.searchQuery.trim() !== '') {
                    url.searchParams.append('search', this.searchQuery.trim());
                }
                
                window.history.pushState({}, '', url);

                try {
                    let response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    
                    // FALLBACK: Jika server gagal (500 Error), hentikan proses agar tidak stuck
                    if (!response.ok) throw new Error("Server mengembalikan error: " + response.status);
                    
                    let html = await response.text();
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    let container = doc.getElementById('emr-grid-container');
                    if (container) {
                        document.getElementById('emr-grid-container').innerHTML = container.innerHTML;
                    } else {
                        throw new Error("Layout rusak, container tidak ditemukan.");
                    }
                } catch (e) {
                    console.error('AJAX Error:', e);
                    // Jika gagal, paksa browser untuk memuat ulang halaman secara tradisional
                    window.location.reload(); 
                } finally {
                    this.isSearching = false;
                }
            }
        }));
    });
</script>
@endpush
@endsection