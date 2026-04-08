@extends('layouts.kader')

@section('title', 'Riwayat Absensi')
@section('page-name', 'Riwayat Absensi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    
    .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3); }
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    
    /* Custom Select */
    select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.absensi.index') }}"
               class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-800 hover:text-white transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Arsip Absensi</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Cari, filter, dan pantau riwayat presensi warga.</p>
            </div>
        </div>
        <a href="{{ route('kader.absensi.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-black text-sm rounded-xl hover:shadow-[0_8px_20px_rgba(16,185,129,0.3)] transition-all hover:-translate-y-0.5 uppercase tracking-widest">
            <i class="fas fa-plus-circle text-lg"></i> Buat Sesi Baru
        </a>
    </div>

    {{-- FILTER PANEL (GLASSMORPHISM) --}}
    <div class="glass-panel rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 sm:p-6 mb-8 animate-slide-up-delay-1 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
        <form method="GET" action="{{ route('kader.absensi.riwayat') }}" class="flex flex-col sm:flex-row flex-wrap gap-4 items-end">

            <div class="flex-1 w-full sm:min-w-[200px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-layer-group text-indigo-400 mr-1"></i> Kategori Modul</label>
                <select name="kategori" class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 bg-slate-50 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer" onchange="this.form.submit()">
                    <option value="">Semua Kategori Warga</option>
                    <option value="bayi"      {{ request('kategori') == 'bayi'      ? 'selected' : '' }}>🍼 Bayi (0–11 bln)</option>
                    <option value="balita"    {{ request('kategori') == 'balita'    ? 'selected' : '' }}>👶 Balita (12–59 bln)</option>
                    <option value="remaja"    {{ request('kategori') == 'remaja'    ? 'selected' : '' }}>🎓 Remaja</option>
                    <option value="ibu_hamil" {{ request('kategori') == 'ibu_hamil' ? 'selected' : '' }}>🤰 Ibu Hamil</option>
                    <option value="lansia"    {{ request('kategori') == 'lansia'    ? 'selected' : '' }}>👴 Lansia</option>
                </select>
            </div>

            <div class="flex-1 w-full sm:min-w-[200px]">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-calendar-alt text-indigo-400 mr-1"></i> Bulan Pelaksanaan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 bg-slate-50 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all" 
                       onchange="this.form.submit()">
            </div>

            <div class="w-full sm:w-auto flex gap-3">
                <button type="submit" class="flex-1 sm:flex-none px-8 py-3 bg-slate-800 text-white font-black text-sm rounded-xl hover:bg-slate-900 transition-all shadow-md hover:shadow-lg uppercase tracking-widest">
                    <i class="fas fa-filter mr-2"></i> Terapkan
                </button>
                @if(request('kategori') || request('bulan'))
                <a href="{{ route('kader.absensi.riwayat') }}" class="px-5 py-3 bg-rose-50 text-rose-600 border border-rose-100 font-black text-sm rounded-xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Reset Filter">
                    <i class="fas fa-sync-alt"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm overflow-hidden animate-slide-up-delay-2">
        <div class="table-container overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Sesi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelaksanaan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tingkat Kehadiran</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penanggung Jawab</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $abs)
                    @php
                        $hadir = $abs->details->where('hadir', true)->count();
                        $total = $abs->details->count();
                        $pct   = $total > 0 ? round($hadir/$total*100) : 0;
                        
                        // Palet Warna Kategori yang lebih "Pop"
                        $badgeStyle = match($abs->kategori) {
                            'bayi'      => 'bg-gradient-to-r from-sky-400 to-blue-500 text-white shadow-sky-200',
                            'balita'    => 'bg-gradient-to-r from-violet-400 to-purple-500 text-white shadow-violet-200',
                            'remaja'    => 'bg-gradient-to-r from-amber-400 to-orange-500 text-white shadow-amber-200',
                            'ibu_hamil' => 'bg-gradient-to-r from-pink-400 to-rose-500 text-white shadow-pink-200',
                            'lansia'    => 'bg-gradient-to-r from-emerald-400 to-teal-500 text-white shadow-emerald-200',
                            default     => 'bg-slate-200 text-slate-600',
                        };

                        // Warna Progress Bar
                        $progressColor = $pct >= 75 ? 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)]' : ($pct >= 40 ? 'bg-amber-400 shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'bg-rose-400 shadow-[0_0_10px_rgba(251,113,133,0.5)]');
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group">

                        {{-- Pertemuan --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-[14px] bg-slate-100 flex flex-col items-center justify-center shrink-0 border border-slate-200 group-hover:bg-white group-hover:border-indigo-200 transition-all group-hover:shadow-sm">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sesi</span>
                                    <span class="text-lg font-black text-slate-800 leading-none mt-0.5">{{ $abs->nomor_pertemuan }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $abs->kode_absensi }}</p>
                                    <p class="text-[11px] font-semibold text-slate-500 mt-0.5 line-clamp-1 max-w-[150px]">{{ $abs->catatan ?? 'Tidak ada topik khusus' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm {{ $badgeStyle }}">
                                {{ $abs->kategori_label }}
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-5">
                            <p class="text-sm font-black text-slate-800">
                                {{ $abs->tanggal_posyandu->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-bold mt-1 bg-slate-100 inline-block px-2 py-0.5 rounded-md"><i class="far fa-clock text-slate-300"></i> {{ $abs->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- Kehadiran (Progress Bar) --}}
                        <td class="px-6 py-5">
                            <div class="w-full max-w-[160px] mx-auto">
                                <div class="flex justify-between items-end mb-1.5">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hadir: <span class="text-slate-800 text-xs">{{ $hadir }}</span>/{{ $total }}</p>
                                    <p class="text-[11px] font-black {{ $pct >= 75 ? 'text-emerald-500' : ($pct >= 40 ? 'text-amber-500' : 'text-rose-500') }}">{{ $pct }}%</p>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $progressColor }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Dicatat Oleh --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center text-[10px] font-black shrink-0">
                                    {{ strtoupper(substr($abs->pencatat?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">{{ $abs->pencatat?->name ?? 'Sistem' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $abs->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kader.absensi.show', $abs->id) }}"
                                   class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all hover:shadow-lg hover:shadow-indigo-200" title="Buka Detail Rekap">
                                    <i class="fas fa-folder-open text-sm"></i>
                                </a>
                                
                                <form action="{{ route('kader.absensi.destroy', $abs->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN! Menghapus sesi ini akan menghilangkan seluruh data centang kehadiran pada pertemuan tersebut secara permanen. Lanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-white hover:bg-rose-500 hover:border-rose-500 transition-all hover:shadow-lg hover:shadow-rose-200 flex items-center justify-center" title="Hapus Permanen Sesi">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-24 text-center">
                            <div class="w-24 h-24 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4 border-8 border-white shadow-sm">
                                <i class="fas fa-box-open text-4xl text-slate-300"></i>
                            </div>
                            <p class="font-black text-slate-800 text-lg">Ruang Arsip Kosong</p>
                            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Tidak ditemukan catatan kehadiran yang sesuai dengan filter Anda saat ini.</p>
                            <a href="{{ route('kader.absensi.index') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 bg-slate-800 text-white font-bold text-xs rounded-full hover:bg-slate-900 transition-all shadow-md uppercase tracking-widest">
                                <i class="fas fa-plus"></i> Mulai Absensi Hari Ini
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50">
            {{ $riwayat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection