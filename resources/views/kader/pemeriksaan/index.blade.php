@extends('layouts.kader')
@section('title', 'Data Pemeriksaan')
@section('page-name', 'Pemeriksaan Pasien')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .search-input { width: 100%; background: rgba(255,255,255,0.9); border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 0.85rem 1rem 0.85rem 3rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); }
    .search-input:focus { border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    #tableLoaderOverlay { opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
    #tableLoaderOverlay.active { opacity: 1; pointer-events: auto; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8 text-center sm:text-left">
        <div class="flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-3xl shadow-lg shrink-0 transform -rotate-3">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Riwayat Pemeriksaan</h1>
                <p class="text-slate-500 mt-1 font-medium text-[14px]">Sistem Live-Search pintar untuk melacak log ukuran fisik warga.</p>
            </div>
        </div>
        <a href="{{ route('kader.pemeriksaan.create') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-indigo-600 text-white font-black text-[13px] rounded-2xl hover:bg-indigo-700 shadow-md transition-all uppercase tracking-widest w-full sm:w-auto">
            <i class="fas fa-plus"></i> Input Baru
        </a>
    </div>

    <div class="bg-white rounded-[28px] border border-slate-200/80 shadow-sm p-4 mb-8 flex flex-col lg:flex-row items-center justify-between gap-4 relative z-20">
        <div class="flex gap-2 w-full lg:w-auto overflow-x-auto custom-scrollbar pb-2 sm:pb-0 justify-start sm:justify-center">
            @php $currentCat = request('kategori', 'semua'); @endphp
            @foreach(['semua'=>'Semua', 'balita'=>'👶 Balita', 'remaja'=>'🎓 Remaja', 'ibu_hamil'=>'🤰 Ibu Hamil', 'lansia'=>'👴 Lansia'] as $k => $l)
                <a href="{{ request()->fullUrlWithQuery(['kategori'=>$k, 'page'=>1]) }}" 
                   class="px-5 py-2.5 rounded-xl font-extrabold text-[11px] uppercase tracking-wider whitespace-nowrap transition-all border 
                   {{ $currentCat == $k ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-slate-100 hover:text-slate-800' }}">
                    {{ $l }}
                </a>
            @endforeach
        </div>
        <div class="w-full lg:w-[400px] relative group">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" id="searchInput" value="{{ $search ?? '' }}" placeholder="Ketik nama pasien..." class="search-input" autocomplete="off">
            <div id="searchSpinner" class="absolute right-5 top-1/2 -translate-y-1/2 hidden"><i class="fas fa-circle-notch fa-spin text-indigo-500 text-lg"></i></div>
        </div>
    </div>

    <div id="table-container" class="relative">
        <div id="tableLoaderOverlay" class="absolute inset-0 bg-white/70 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center rounded-[32px]">
            <div class="px-6 py-4 bg-white border border-indigo-100 shadow-xl rounded-2xl flex items-center gap-3"><i class="fas fa-sync-alt fa-spin text-indigo-600 text-xl"></i><span class="font-black text-slate-800 text-[12px] uppercase tracking-widest">Menyinkronkan...</span></div>
        </div>

        <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl / Kategori</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas Pasien</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data Utama (BB/TB)</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status Validasi</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pemeriksaans as $p)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-8 py-4 align-middle">
                                <p class="font-black text-slate-800 text-[13px] mb-1">{{ \Carbon\Carbon::parse($p->tanggal_periksa)->format('d M Y') }}</p>
                                @if($p->kategori_pasien == 'balita') <span class="px-2 py-1 bg-violet-100 text-violet-700 text-[9px] font-black rounded uppercase border border-violet-200">Balita</span>
                                @elseif($p->kategori_pasien == 'remaja') <span class="px-2 py-1 bg-sky-100 text-sky-700 text-[9px] font-black rounded uppercase border border-sky-200">Remaja</span>
                                @elseif($p->kategori_pasien == 'ibu_hamil') <span class="px-2 py-1 bg-pink-100 text-pink-700 text-[9px] font-black rounded uppercase border border-pink-200">Ibu Hamil</span>
                                @else <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded uppercase border border-emerald-200">Lansia</span> @endif
                            </td>
                            <td class="px-8 py-4 align-middle">
                                <p class="font-black text-slate-800 text-[14px]">{{ $p->nama_pasien }}</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-0.5 truncate max-w-[200px]"><i class="fas fa-comment-medical text-indigo-400 mr-1"></i> "{{ $p->keluhan ?? 'Tidak ada keluhan' }}"</p>
                            </td>
                            <td class="px-8 py-4 align-middle">
                                <div class="flex items-center gap-2">
                                    <div class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center shadow-sm"><span class="text-[8px] font-black text-slate-400 uppercase mr-1">BB</span><span class="text-[12px] font-black text-slate-800">{{ $p->berat_badan ?? '-' }}</span></div>
                                    <div class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center shadow-sm"><span class="text-[8px] font-black text-slate-400 uppercase mr-1">TB</span><span class="text-[12px] font-black text-slate-800">{{ $p->tinggi_badan ?? '-' }}</span></div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center align-middle">
                                @if($p->status_verifikasi == 'verified') <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-100 shadow-sm uppercase"><i class="fas fa-check-double text-emerald-500"></i> ACC Bidan</span>
                                @else <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100 shadow-sm uppercase"><i class="fas fa-hourglass-half text-amber-500 animate-pulse"></i> Menunggu</span> @endif
                            </td>
                            <td class="px-8 py-4 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kader.pemeriksaan.show', $p->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm transition-all" title="Detail"><i class="fas fa-folder-open"></i></a>
                                    <a href="{{ route('kader.pemeriksaan.edit', $p->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-amber-50 shadow-sm transition-all" title="Edit"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('kader.pemeriksaan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ukuran ini secara permanen?');" class="inline-block m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 shadow-sm transition-all" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-8 py-20 text-center"><div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100"><i class="fas fa-box-open"></i></div><h3 class="font-black text-slate-800 text-lg">Riwayat Kosong</h3><p class="text-xs text-slate-500">Tidak ada data pemeriksaan ditemukan.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div id="paginationArea" class="px-8 py-4 border-t border-slate-100 bg-slate-50/50">@if(isset($pemeriksaans) && $pemeriksaans->hasPages()) {{ $pemeriksaans->links() }} @endif</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('table-container');
    const overlay = document.getElementById('tableLoaderOverlay');
    const spinner = document.getElementById('searchSpinner');

    async function fetchRealTimeData(url, isSearch = false) {
        if(isSearch) spinner.classList.remove('hidden');
        else overlay.classList.add('active');

        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            tableContainer.innerHTML = doc.getElementById('table-container').innerHTML;
            window.history.pushState({}, '', url);
            bindPagination();
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