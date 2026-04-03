@extends('layouts.kader')

@section('title', 'Detail Absensi')
@section('page-name', 'Detail Absensi')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kader.absensi.riwayat') }}"
           class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-black text-slate-900">
                Pertemuan {{ $absensi->nomor_pertemuan }} — {{ $absensi->kategori_label }}
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-0.5">
                {{ $absensi->tanggal_posyandu->translatedFormat('l, d F Y') }}
                <span class="ml-2 font-mono text-[11px] text-slate-400">{{ $absensi->kode_absensi }}</span>
            </p>
        </div>
    </div>

    {{-- NAVIGASI PERTEMUAN --}}
    <div class="flex items-center justify-between mb-5 bg-white border border-slate-200 rounded-2xl p-3 shadow-sm">
        @if($sebelumnya)
        <a href="{{ route('kader.absensi.show', $sebelumnya->id) }}"
           class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-100 transition-colors">
            <i class="fas fa-chevron-left"></i> Pertemuan {{ $sebelumnya->nomor_pertemuan }}
        </a>
        @else
        <div></div>
        @endif

        <div class="text-center">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Pertemuan</p>
            <p class="text-xl font-black text-slate-800">{{ $absensi->nomor_pertemuan }}</p>
        </div>

        @if($berikutnya)
        <a href="{{ route('kader.absensi.show', $berikutnya->id) }}"
           class="flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-100 transition-colors">
            Pertemuan {{ $berikutnya->nomor_pertemuan }} <i class="fas fa-chevron-right"></i>
        </a>
        @else
        <div></div>
        @endif
    </div>

    {{-- RINGKASAN STAT --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-black text-slate-900">{{ $totalPasien }}</p>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Warga</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-black text-emerald-600">{{ $totalHadir }}</p>
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mt-1">Hadir</p>
        </div>
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-3xl font-black text-rose-600">{{ $totalAbsen }}</p>
            <p class="text-xs font-bold text-rose-400 uppercase tracking-wider mt-1">Tidak Hadir</p>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-center shadow-sm">
            @php $pct = $totalPasien > 0 ? round($totalHadir/$totalPasien*100) : 0; @endphp
            <p class="text-3xl font-black {{ $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                {{ $pct }}%
            </p>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Kehadiran</p>
        </div>
    </div>

    {{-- INFO SESI --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-5">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Kode Absensi</p>
                <p class="font-mono font-bold text-slate-700">{{ $absensi->kode_absensi }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Dicatat Oleh</p>
                <p class="font-bold text-slate-700">{{ $absensi->pencatat?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Waktu Pencatatan</p>
                <p class="font-bold text-slate-700">{{ $absensi->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            @if($absensi->catatan)
            <div class="col-span-2 sm:col-span-3">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">Catatan</p>
                <p class="text-slate-600">{{ $absensi->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- TABEL KEHADIRAN --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-sm">Daftar Kehadiran</h3>
            <div class="flex gap-2">
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 text-[11px] font-black rounded-lg">
                    <i class="fas fa-check mr-1"></i> {{ $totalHadir }} Hadir
                </span>
                <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-100 text-[11px] font-black rounded-lg">
                    <i class="fas fa-times mr-1"></i> {{ $totalAbsen }} Absen
                </span>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest w-8">#</th>
                    <th class="px-5 py-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Nama Warga</th>
                    <th class="px-5 py-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest hidden sm:table-cell">NIK</th>
                    <th class="px-5 py-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-5 py-3.5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest hidden sm:table-cell">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                {{-- Hadir dulu, lalu absen --}}
                @foreach($details->sortByDesc('hadir') as $i => $detail)
                <tr class="hover:bg-slate-50/60 transition-colors {{ $detail->hadir ? '' : 'opacity-55' }}">
                    <td class="px-5 py-3.5 text-xs font-bold text-slate-400">{{ $i+1 }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shrink-0
                                {{ $detail->hadir ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-400' }}">
                                {{ strtoupper(substr($detail->pasien_data?->nama_lengkap ?? '?', 0, 1)) }}
                            </div>
                            <p class="font-extrabold text-slate-800 text-sm">
                                {{ $detail->pasien_data?->nama_lengkap ?? '— Data tidak ditemukan —' }}
                            </p>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-xs font-mono text-slate-500 hidden sm:table-cell">
                        {{ $detail->pasien_data?->nik ?? '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($detail->hadir)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-black rounded-lg border border-emerald-100">
                                <i class="fas fa-check text-[9px]"></i> Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-600 text-[11px] font-black rounded-lg border border-rose-100">
                                <i class="fas fa-times text-[9px]"></i> Absen
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-slate-500 hidden sm:table-cell">
                        {{ $detail->keterangan ?? '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection