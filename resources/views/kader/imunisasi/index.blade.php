@extends('layouts.kader')
@section('title', 'Register Imunisasi')
@section('page-name', 'Riwayat Imunisasi')

@push('styles')
<style>
    /* Animasi Masuk */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Input Pencarian Premium */
    .search-input {
        width: 100%; background-color: #f8fafc; border: 1px solid #e2e8f0; color: #0f172a;
        font-size: 0.875rem; border-radius: 16px; padding: 0.85rem 1rem 0.85rem 2.75rem;
        outline: none; transition: all 0.3s ease; font-weight: 600;
    }
    .search-input:focus {
        background-color: #ffffff; border-color: #4f46e5; /* Indigo 600 */
        box-shadow: 0 4px 15px -3px rgba(79, 70, 229, 0.15);
    }
    .search-input::placeholder { color: #94a3b8; font-weight: 500; }
    
    /* State Loading Halus */
    .table-loading { 
        opacity: 0.6; pointer-events: none; filter: blur(1px) grayscale(30%); transition: all 0.2s ease; 
    }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up">

    <div class="bg-white rounded-[32px] p-8 mb-6 relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm border border-slate-200/80">
        <div class="absolute right-0 top-0 w-64 h-full bg-indigo-500/5 blur-3xl rounded-full pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-indigo-100 transform -rotate-3 hover:rotate-0 transition-transform">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black font-poppins text-slate-800 mb-1">Register Imunisasi</h2>
                <p class="text-slate-500 text-sm font-medium max-w-lg">Buku log riwayat pemberian vaksin posyandu.</p>
            </div>
        </div>
        
        <div class="relative z-10 inline-flex items-center gap-2.5 bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-2.5 rounded-xl shadow-sm">
            <i class="fas fa-info-circle text-lg"></i>
            <span class="text-xs font-bold uppercase tracking-wider">Input Data Hanya Oleh Bidan</span>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4 relative z-20">
        
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-1 sm:pb-0">
            @php $currentCat = request('kategori', 'semua'); @endphp
            @foreach(['semua'=>'Semua Data', 'balita'=>'Balita', 'remaja'=>'Remaja (HPV/TT)', 'lansia'=>'Lansia'] as $k => $l)
                <a href="{{ request()->fullUrlWithQuery(['kategori'=>$k, 'page'=>1]) }}" 
                   data-kategori="{{ $k }}"
                   class="kategori-btn px-5 py-2.5 rounded-xl font-bold text-[13px] whitespace-nowrap transition-all duration-300 border flex items-center gap-2 
                   {{ $currentCat == $k ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800' }}">
                   
                   @if($k == 'balita') <i class="fas fa-baby {{ $currentCat == $k ? 'text-white' : 'text-rose-400' }}"></i> 
                   @elseif($k == 'remaja') <i class="fas fa-user-graduate {{ $currentCat == $k ? 'text-white' : 'text-sky-400' }}"></i> 
                   @elseif($k == 'lansia') <i class="fas fa-wheelchair {{ $currentCat == $k ? 'text-white' : 'text-amber-400' }}"></i> 
                   @endif
                   {{ $l }}
                </a>
            @endforeach
        </div>
        
        <div class="w-full lg:w-96 relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" value="{{ $search ?? '' }}" placeholder="Cari nama pasien atau vaksin..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                <i class="fas fa-circle-notch fa-spin text-indigo-500"></i>
            </div>
        </div>
    </div>

    <div id="table-container" class="transition-opacity duration-300">
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Pemberian</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pasien & Kategori</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Vaksin</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Bidan Bertugas</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($imunisasis ?? [] as $imun)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex flex-col items-center justify-center text-indigo-700 border border-indigo-100">
                                        <span class="text-[14px] font-black leading-none">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d') }}</span>
                                        <span class="text-[9px] font-bold uppercase">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->translatedFormat('M') }}</span>
                                    </div>
                                    <div class="text-[12px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('Y') }}</div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $namaPasien = $imun->kunjungan?->pasien?->nama_lengkap ?? 'Unknown';
                                    $tipePasien = class_basename($imun->kunjungan?->pasien_type);
                                    $badge = match($tipePasien) {
                                        'Balita' => ['bg-rose-50', 'text-rose-600', 'border-rose-200'],
                                        'Remaja' => ['bg-sky-50', 'text-sky-600', 'border-sky-200'],
                                        'Lansia' => ['bg-amber-50', 'text-amber-600', 'border-amber-200'],
                                        default  => ['bg-slate-50', 'text-slate-600', 'border-slate-200']
                                    };
                                @endphp
                                <p class="font-bold text-slate-800 text-sm mb-1 font-poppins">{{ $namaPasien }}</p>
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest border {{ $badge[0] }} {{ $badge[1] }} {{ $badge[2] }}">
                                    {{ $tipePasien }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm border border-indigo-100 shrink-0">
                                        <i class="fas fa-syringe text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-slate-800 mb-0.5">{{ $imun->vaksin }}</p>
                                        <p class="text-[11px] font-bold text-slate-500">Dosis: <span class="text-indigo-600">{{ $imun->dosis }}</span> &middot; {{ $imun->jenis_imunisasi }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                                    <i class="fas fa-user-md text-slate-400"></i>
                                    <span class="text-[11px] font-bold text-slate-600 truncate max-w-[100px]">{{ $imun->kunjungan?->petugas?->name ?? 'Bidan Desa' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kader.imunisasi.show', $imun->id) }}" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 shadow-sm transition-all" title="Detail Imunisasi">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100">
                                    <i class="fas fa-shield-virus"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-base font-poppins">Riwayat Imunisasi Kosong</h3>
                                <p class="text-sm text-slate-500 mt-1 font-medium">Data akan otomatis muncul di sini setelah Bidan memverifikasi pemberian vaksin.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($imunisasis) && $imunisasis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
                {{ $imunisasis->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // LOGIKA AJAX REAL-TIME (TANPA REFRESH HALAMAN)
    document.addEventListener('DOMContentLoaded', function() {
        let typingTimer;
        const searchInput = document.getElementById('searchInput');
        const tableContainer = document.getElementById('table-container');
        const spinner = document.getElementById('searchSpinner');

        async function fetchRealTimeData(url) {
            tableContainer.classList.add('table-loading');
            spinner.classList.remove('hidden');
            
            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                const html = await response.text();
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('table-container');
                
                if (newTable) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                tableContainer.classList.remove('table-loading');
                spinner.classList.add('hidden');
            }
        }

        if(searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', e.target.value);
                    url.searchParams.set('page', 1);
                    fetchRealTimeData(url.toString());
                }, 400);
            });
        }

        const categoryButtons = document.querySelectorAll('.kategori-btn');
        categoryButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                categoryButtons.forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');
                    b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
                    const icon = b.querySelector('i');
                    if(icon && b.dataset.kategori === 'balita') icon.className = 'fas fa-baby text-rose-400';
                    if(icon && b.dataset.kategori === 'remaja') icon.className = 'fas fa-user-graduate text-sky-400';
                    if(icon && b.dataset.kategori === 'lansia') icon.className = 'fas fa-wheelchair text-amber-400';
                });
                
                this.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');
                this.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-md');
                const activeIcon = this.querySelector('i');
                if(activeIcon) {
                    activeIcon.className = activeIcon.className.replace(/text-\w+-400/, 'text-white');
                }

                const url = new URL(this.href);
                if(searchInput && searchInput.value) {
                    url.searchParams.set('search', searchInput.value);
                }
                fetchRealTimeData(url.toString());
            });
        });

        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('.pagination-wrapper a');
            if (pageLink) {
                e.preventDefault();
                fetchRealTimeData(pageLink.href);
            }
        });

        window.addEventListener('popstate', function() { 
            fetchRealTimeData(window.location.href); 
        });
    });
</script>
@endpush
@endsection