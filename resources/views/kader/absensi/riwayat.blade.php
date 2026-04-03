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
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Kategori</label>
                <select name="kategori"
                        class="w-full border-2 border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-emerald-400 transition-colors"
                        onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="bayi"   {{ $kategori=='bayi'   ? 'selected':'' }}>🍼 Bayi (0–11 bln)</option>
                    <option value="balita" {{ $kategori=='balita' ? 'selected':'' }}>👶 Balita (12–59 bln)</option>
                    <option value="remaja" {{ $kategori=='remaja' ? 'selected':'' }}>🎓 Remaja</option>
                    <option value="lansia" {{ $kategori=='lansia' ? 'selected':'' }}>👴 Lansia</option>
                </select>
            </div>

            <div class="flex-1 min-w-[140px]">
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">
                    Pertemuan ke-
                </label>
                <select name="pertemuan"
                        class="w-full border-2 border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-emerald-400 transition-colors">
                    <option value="">Semua Pertemuan</option>
                    @for($p = 1; $p <= max($maxPertemuan, 1); $p++)
                        <option value="{{ $p }}" {{ $pertemuan == $p ? 'selected':'' }}>
                            Pertemuan {{ $p }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-28">
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1.5">Tahun</label>
                <select name="tahun"
                        class="w-full border-2 border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 bg-white focus:outline-none focus:border-emerald-400 transition-colors">
                    @for($y = now()->year; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $tahun==$y ? 'selected':'' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-slate-800 text-white font-extrabold text-sm rounded-xl hover:bg-slate-900 transition-colors">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                @if($kategori || $pertemuan)
                <a href="{{ route('kader.absensi.riwayat') }}"
                   class="px-4 py-2.5 bg-slate-100 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-200 transition-colors flex items-center">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse min-w-[700px]">
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
                        'bayi'   => 'bg-sky-50 text-sky-700 border-sky-100',
                        'balita' => 'bg-violet-50 text-violet-700 border-violet-100',
                        'remaja' => 'bg-amber-50 text-amber-700 border-amber-100',
                        'lansia' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        default  => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                @endphp
                <tr class="hover:bg-slate-50/60 transition-colors">

                    {{-- Pertemuan --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                <span class="text-base font-black text-slate-700">{{ $abs->nomor_pertemuan }}</span>
                            </div>
                            <div>
                                <p class="font-black text-slate-800 text-sm">Pertemuan {{ $abs->nomor_pertemuan }}</p>
                                <p class="text-[10px] font-mono text-slate-400">{{ $abs->kode_absensi }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Kategori --}}
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black border {{ $badgeColor }}">
                            {{ $abs->kategori_label }}
                        </span>
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-slate-800">
                            {{ $abs->tanggal_posyandu->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium">{{ $abs->bulan_label }} {{ $abs->tahun }}</p>
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
                                <div class="h-full bg-emerald-400 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $pct }}%</span>
                        </div>
                    </td>

                    {{-- Dicatat Oleh --}}
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-slate-700">{{ $abs->pencatat?->name ?? '-' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $abs->created_at->diffForHumans() }}</p>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('kader.absensi.show', $abs->id) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-colors">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <i class="fas fa-folder-open text-3xl text-slate-200 mb-3 block"></i>
                        <p class="font-black text-slate-700">Belum ada riwayat absensi</p>
                        <p class="text-sm text-slate-400 mt-1">Ubah filter atau buat absensi baru.</p>
                        <a href="{{ route('kader.absensi.index') }}"
                           class="inline-flex items-center gap-2 mt-4 text-emerald-600 font-bold text-sm hover:text-emerald-700">
                            <i class="fas fa-plus"></i> Buat Absensi Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($riwayat->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            {{ $riwayat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection