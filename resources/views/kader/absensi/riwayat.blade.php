@extends('layouts.kader')

@section('title', 'Riwayat Absensi')
@section('page-name', 'Riwayat Absensi')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.absensi.index') }}"
               class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Riwayat Absensi</h1>
                <p class="text-sm text-slate-500 mt-0.5">Cari & lihat absensi berdasarkan pertemuan dan kategori.</p>
            </div>
        </div>
        <a href="{{ route('kader.absensi.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white font-extrabold text-sm rounded-xl hover:bg-emerald-600 shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Absensi Baru
        </a>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('kader.absensi.riwayat') }}" class="flex flex-wrap gap-4 items-end">

            <div class="flex-1 min-w-[150px]">
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Modul</label>
                <select name="kategori"
                        class="w-full border-2 border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-emerald-400 transition-colors cursor-pointer"
                        onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    <option value="bayi"      {{ request('kategori') == 'bayi'      ? 'selected' : '' }}>🍼 Bayi (0–11 bln)</option>
                    <option value="balita"    {{ request('kategori') == 'balita'    ? 'selected' : '' }}>👶 Balita (12–59 bln)</option>
                    <option value="remaja"    {{ request('kategori') == 'remaja'    ? 'selected' : '' }}>🎓 Remaja</option>
                    <option value="ibu_hamil" {{ request('kategori') == 'ibu_hamil' ? 'selected' : '' }}>🤰 Ibu Hamil</option>
                    <option value="lansia"    {{ request('kategori') == 'lansia'    ? 'selected' : '' }}>👴 Lansia</option>
                </select>
            </div>

            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">
                    Bulan Pelaksanaan
                </label>
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="w-full border-2 border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-emerald-400 transition-colors" 
                       onchange="this.form.submit()">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-slate-800 text-white font-extrabold text-sm rounded-xl hover:bg-slate-900 transition-colors">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                @if(request('kategori') || request('bulan'))
                <a href="{{ route('kader.absensi.riwayat') }}"
                   class="px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 font-extrabold text-sm rounded-xl hover:bg-rose-100 transition-colors flex items-center" title="Reset Filter">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Pertemuan</th>
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Tanggal Pelaksanaan</th>
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Kehadiran</th>
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Dicatat Oleh</th>
                        <th class="px-5 py-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $abs)
                    @php
                        $hadir = $abs->details->where('hadir', true)->count();
                        $total = $abs->details->count();
                        $pct   = $total > 0 ? round($hadir/$total*100) : 0;
                        $badgeColor = match($abs->kategori) {
                            'bayi'      => 'bg-sky-50 text-sky-700 border-sky-100',
                            'balita'    => 'bg-violet-50 text-violet-700 border-violet-100',
                            'remaja'    => 'bg-amber-50 text-amber-700 border-amber-100',
                            'ibu_hamil' => 'bg-pink-50 text-pink-700 border-pink-100',
                            'lansia'    => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            default     => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors group">

                        {{-- Pertemuan --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                    <span class="text-base font-black text-slate-700">{{ $abs->nomor_pertemuan }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm">Sesi {{ $abs->nomor_pertemuan }}</p>
                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $abs->kode_absensi }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $badgeColor }}">
                                {{ $abs->kategori_label }}
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-5 py-4">
                            <p class="text-sm font-bold text-slate-800">
                                {{ $abs->tanggal_posyandu->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5"><i class="far fa-clock"></i> {{ $abs->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- Kehadiran --}}
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex flex-col items-center">
                                <p class="text-sm font-black text-slate-800">
                                    <span class="text-emerald-600">{{ $hadir }}</span>
                                    <span class="text-slate-300 mx-0.5">/</span>
                                    <span>{{ $total }}</span>
                                </p>
                                <div class="w-20 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full bg-emerald-400 rounded-full transition-all" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold mt-1">{{ $pct }}% Hadir</span>
                            </div>
                        </td>

                        {{-- Dicatat Oleh --}}
                        <td class="px-5 py-4">
                            <p class="text-sm font-bold text-slate-700">{{ $abs->pencatat?->name ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $abs->created_at->diffForHumans() }}</p>
                        </td>

                        {{-- Aksi Terpadu (Detail & Hapus) --}}
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('kader.absensi.show', $abs->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition-colors" title="Lihat Rekapan Absensi">
                                    <i class="fas fa-folder-open"></i> Detail
                                </a>
                                
                                <form action="{{ route('kader.absensi.destroy', $abs->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN! Menghapus sesi ini akan menghilangkan seluruh data centang kehadiran pada pertemuan tersebut secara permanen. Lanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 bg-white border border-slate-200 text-slate-400 hover:text-white hover:bg-rose-500 hover:border-rose-500 font-bold text-xs rounded-xl shadow-sm transition-all" title="Hapus Permanen Sesi Ini">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <i class="fas fa-folder-open text-3xl text-slate-200 mb-3 block"></i>
                            <p class="font-black text-slate-700">Belum ada riwayat absensi</p>
                            <p class="text-sm text-slate-400 mt-1">Ubah filter atau buat sesi absensi baru.</p>
                            <a href="{{ route('kader.absensi.index') }}"
                               class="inline-flex items-center gap-2 mt-4 text-emerald-600 font-bold text-sm hover:text-emerald-700 transition-colors">
                                <i class="fas fa-plus"></i> Buat Absensi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            {{ $riwayat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection