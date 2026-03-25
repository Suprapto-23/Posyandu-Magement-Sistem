@extends('layouts.kader')

@section('title', 'Data Balita')
@section('page-name', 'Database Balita')

@push('styles')
<style>
    /* Animasi Masuk */
    .animate-slide-up {
        opacity: 0;
        animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Input Pencarian Premium */
    .search-glass { 
        background: rgba(248, 250, 252, 0.8); 
        backdrop-filter: blur(8px); 
    }
    
    /* Animasi Spinner Lokal */
    .local-spinner { animation: spin 0.8s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* Custom Scrollbar Super Tipis */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-[16px] bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-[0_8px_15px_rgba(79,70,229,0.3)] transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="fas fa-baby"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Database Balita</h1>
                <p class="text-slate-500 mt-1 font-medium text-[13px]">Kelola profil balita dan integrasi akun warga posyandu.</p>
            </div>
        </div>
        <a href="{{ route('kader.data.balita.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold text-[13px] rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus"></i> Tambah Balita Baru
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.03)] p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input type="text" id="liveSearch" value="{{ $search }}" placeholder="Ketik nama balita, NIK, atau ibu..." 
                   class="w-full search-glass border-2 border-slate-200 text-slate-800 text-[13px] rounded-xl pl-11 pr-10 py-3 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold placeholder:font-medium placeholder:text-slate-400">
            
            <div id="searchSpinner" class="absolute inset-y-0 right-0 pr-4 flex items-center opacity-0 transition-opacity duration-200">
                <i class="fas fa-circle-notch local-spinner text-indigo-500"></i>
            </div>
        </div>
        
        <div class="flex items-center gap-2 text-[12px] font-bold text-slate-400">
            <i class="fas fa-bolt text-amber-500"></i> Pencarian Real-Time Aktif
        </div>
    </div>

    <div id="ajaxTableContainer" class="relative">
        
        <div id="tableLoader" class="absolute inset-0 bg-white/50 backdrop-blur-[2px] z-10 hidden flex-col items-center justify-center rounded-[24px] transition-all duration-300">
            <div class="px-5 py-3 bg-white border border-slate-200 shadow-xl rounded-2xl flex items-center gap-3">
                <i class="fas fa-circle-notch local-spinner text-indigo-600 text-lg"></i>
                <span class="font-extrabold text-slate-700 text-[13px]">Menyinkronkan Data...</span>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[850px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Profil Balita</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Usia & Tgl Lahir</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data Orang Tua</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status Akun</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($balitas as $balita)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shadow-sm border {{ $balita->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-500 border-sky-100' : 'bg-rose-50 text-rose-500 border-rose-100' }} group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($balita->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-800 text-[14px] mb-0.5">{{ $balita->nama_lengkap }}</p>
                                        <p class="text-[11px] font-bold text-slate-400 flex items-center gap-1.5">
                                            <i class="fas fa-barcode"></i> NIK: {{ $balita->nik ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                @php
                                    $diff = \Carbon\Carbon::parse($balita->tanggal_lahir)->diff(\Carbon\Carbon::now());
                                    $usia = $diff->y > 0 ? $diff->y . ' Thn ' . $diff->m . ' Bln' : $diff->m . ' Bln';
                                @endphp
                                <div class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-600 text-[11px] font-extrabold rounded-lg mb-1.5 border border-slate-200">
                                    <i class="fas fa-birthday-cake text-amber-500 mr-1.5"></i> {{ $usia }}
                                </div>
                                <p class="text-[11px] font-bold text-slate-400 pl-1">{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                            </td>

                            <td class="px-6 py-5">
                                <p class="font-extrabold text-slate-700 text-[13px] mb-0.5 flex items-center gap-2">
                                    <i class="fas fa-female text-rose-400"></i> {{ $balita->nama_ibu }}
                                </p>
                                <p class="text-[11px] font-bold text-slate-400 pl-5">NIK: {{ $balita->nik_ibu }}</p>
                            </td>

                            <td class="px-6 py-5 text-center">
                                @if($balita->user_id)
                                    <div class="inline-flex flex-col items-center justify-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[11px] font-extrabold border border-emerald-100">
                                            <i class="fas fa-link"></i> Terhubung
                                        </span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center justify-center" title="Warga belum mendaftarkan akun di sistem">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 text-slate-500 text-[11px] font-extrabold border border-slate-200">
                                            <i class="fas fa-unlink"></i> Belum Terhubung
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('kader.data.balita.show', $balita->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 shadow-sm transition-all" title="Lihat Rekam Medis">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                    <a href="{{ route('kader.data.balita.edit', $balita->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:border-amber-300 hover:bg-amber-50 shadow-sm transition-all" title="Edit Profil">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('kader.data.balita.destroy', $balita->id) }}" method="POST" onsubmit="return confirm('Hapus profil balita ini secara permanen?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 shadow-sm transition-all" title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[20px] flex items-center justify-center text-slate-300 mx-auto mb-5 text-4xl shadow-inner border border-slate-100">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-lg font-poppins">Pencarian Tidak Ditemukan</h3>
                                <p class="text-[13px] font-medium text-slate-500 mt-1 max-w-md mx-auto">Tidak ada data balita yang cocok dengan kata kunci tersebut. Coba gunakan NIK atau Nama yang lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($balitas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $balitas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearch');
        const container = document.getElementById('ajaxTableContainer');
        const spinner = document.getElementById('searchSpinner');
        let debounceTimer;

        // FUNGSI UTAMA: Mengambil Data via AJAX
        function fetchResults(url) {
            // 1. Tampilkan Efek Loading Pintar
            spinner.classList.remove('opacity-0');
            
            const loader = document.getElementById('tableLoader');
            if(loader) {
                loader.classList.remove('hidden');
                loader.classList.add('flex');
            }

            // 2. Eksekusi Pengambilan Data di Latar Belakang
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                // 3. Ekstrak hanya bagian tabel dari HTML yang diterima
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('ajaxTableContainer').innerHTML;
                
                // 4. Timpa tabel lama dengan tabel baru
                container.innerHTML = newContent;
                
                // 5. Update URL di browser (agar jika di-refresh halamannya tetap benar)
                window.history.pushState({path: url}, '', url);
                
                // 6. Pasang kembali event click pada tombol halaman (Pagination) yang baru
                bindPagination();
            })
            .catch(error => console.error('Gagal mengambil data:', error))
            .finally(() => {
                // 7. Sembunyikan Efek Loading
                spinner.classList.add('opacity-0');
            });
        }

        // EVENT 1: Saat user mengetik di kotak pencarian
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                // Jeda 400ms (0.4 detik) agar server tidak terbebani saat user mengetik cepat
                debounceTimer = setTimeout(() => {
                    const query = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', query);
                    url.searchParams.delete('page'); // Reset selalu ke halaman 1 saat mencari
                    
                    fetchResults(url.toString());
                }, 400); 
            });
        }

        // EVENT 2: Mencegah tombol pagination loading satu halaman penuh
        function bindPagination() {
            // Mencari semua tag <a> di dalam navigasi pagination Laravel
            const paginationLinks = document.querySelectorAll('#ajaxTableContainer nav a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Cegah fungsi klik bawaan (reload full page)
                    e.stopPropagation(); // Mencegah global loader .smooth-route berjalan
                    
                    fetchResults(this.href); // Pakai AJAX saja
                });
            });
        }

        // EVENT 3: Menangani tombol "Back" atau "Forward" pada browser
        window.addEventListener('popstate', function() {
            fetchResults(window.location.href);
            // Kembalikan isi kotak teks pencarian sesuai dengan URL
            const urlParams = new URLSearchParams(window.location.search);
            if(searchInput) searchInput.value = urlParams.get('search') || '';
        });

        // Inisialisasi awal saat halaman pertama kali dimuat
        bindPagination();
    });
</script>
@endpush