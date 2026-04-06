@extends('layouts.bidan')

@section('title', 'Kelola Jadwal Posyandu')
@section('page-name', 'Manajemen Jadwal')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .table-row-hover:hover { background-color: #f8fafc; transform: scale-[1.002]; transition: all 0.2s ease-in-out; box-shadow: 0 4px 12px rgba(0,0,0,0.02); z-index: 10; position: relative; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-calendar-alt text-cyan-600 text-2xl animate-pulse"></i>
    </div>
    <p class="text-cyan-800 font-poppins font-extrabold tracking-widest text-sm animate-pulse" id="loaderText">MEMUAT DATA...</p>
</div>

<div class="max-w-[1300px] mx-auto animate-slide-up pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-cyan-400 to-sky-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(6,182,212,0.25)] border border-cyan-300">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Jadwal Kegiatan</h1>
                <p class="text-slate-500 mt-1.5 font-medium text-sm">Kelola agenda posyandu dan sistem akan menginformasikannya otomatis ke aplikasi warga.</p>
            </div>
        </div>
        <a href="{{ route('bidan.jadwal.create') }}" class="smooth-route inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-gradient-to-r from-cyan-500 to-sky-600 text-white font-black text-[13px] uppercase tracking-widest rounded-xl hover:from-cyan-600 hover:to-sky-700 shadow-[0_8px_20px_rgba(6,182,212,0.3)] hover:-translate-y-0.5 transition-all duration-300 shrink-0">
            <i class="fas fa-plus-circle text-lg"></i> Buat Jadwal Baru
        </a>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col">
        
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-list-ul"></i></div>
                <h3 class="font-black text-slate-800 font-poppins">Daftar Agenda Tersimpan</h3>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar flex-1">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-white border-b border-slate-200">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Tanggal</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Kegiatan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Target Layanan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manajemen Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jadwals as $index => $jadwal)
                    <tr class="table-row-hover bg-white group">
                        <td class="px-6 py-5 text-sm font-black text-slate-400 align-top">{{ $jadwals->firstItem() + $index }}</td>
                        
                        <td class="px-6 py-5 align-top">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-[9px] font-black text-cyan-600 uppercase">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M') }}</span>
                                    <span class="text-[18px] font-black text-slate-800 leading-none">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] mb-1.5">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, Y') }}</p>
                                    <p class="text-[11px] font-bold text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded-md border border-slate-200 shadow-sm">
                                        <i class="fas fa-clock text-cyan-500 mr-1"></i> {{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 align-top">
                            <p class="font-black text-slate-800 text-[15px] mb-1.5 text-wrap">{{ $jadwal->judul }}</p>
                            <p class="text-[12px] font-medium text-slate-500 line-clamp-2 mb-2 leading-relaxed">{{ $jadwal->deskripsi ?? 'Tidak ada deskripsi tambahan untuk kegiatan ini.' }}</p>
                            <p class="text-[11px] font-bold text-slate-600 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-rose-500"></i> {{ $jadwal->lokasi }}</p>
                        </td>

                        <td class="px-6 py-5 align-top">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-lg border border-indigo-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-tags text-indigo-400"></i> {{ $jadwal->kategori }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-users text-emerald-400"></i> {{ str_replace('_', ' ', $jadwal->target_peserta) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center align-middle">
                            @php
                                $statusConf = match($jadwal->status) {
                                    'aktif' => ['bg-cyan-50 text-cyan-700 border-cyan-200', 'Agenda Aktif', 'fa-calendar-check'],
                                    'selesai' => ['bg-slate-100 text-slate-500 border-slate-200', 'Selesai', 'fa-check-circle'],
                                    'dibatalkan' => ['bg-rose-50 text-rose-600 border-rose-200', 'Dibatalkan', 'fa-times-circle'],
                                    default => ['bg-slate-100 text-slate-600', $jadwal->status, 'fa-info-circle']
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusConf[0] }}">
                                <i class="fas {{ $statusConf[2] }}"></i> {{ $statusConf[1] }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-right align-middle">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('bidan.jadwal.edit', $jadwal->id) }}" class="smooth-route w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white hover:shadow-md transition-all border border-amber-100 hover:border-amber-500" title="Edit Jadwal">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bidan.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus jadwal akan menghilangkan data ini dari HP Warga secara permanen. Lanjutkan?');" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="showLoader('MENGHAPUS JADWAL...')" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white hover:shadow-md transition-all border border-rose-100 hover:border-rose-600" title="Hapus Jadwal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-20 bg-slate-50/50">
                            <div class="w-20 h-20 bg-white rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-sm border border-slate-200"><i class="fas fa-calendar-times"></i></div>
                            <h4 class="font-black text-slate-800 text-lg font-poppins mb-1">Database Jadwal Kosong</h4>
                            <p class="text-[13px] font-medium text-slate-500 mt-1 max-w-sm mx-auto">Klik "Buat Jadwal Baru" di pojok kanan atas untuk mulai merencanakan kegiatan medis posyandu.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jadwals->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/80 pagination-wrapper">
            {{ $jadwals->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };
    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });
    document.querySelectorAll('.smooth-route, .pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                showLoader('MEMUAT HALAMAN...');
            }
        });
    });
</script>
@endpush