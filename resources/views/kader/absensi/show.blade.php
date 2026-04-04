@extends('layouts.kader')

@section('title', 'Detail Absensi')
@section('page-name', 'Detail Absensi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('kader.absensi.riwayat') }}" class="w-12 h-12 bg-white border border-slate-200 rounded-[18px] flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Sesi {{ $absensi->nomor_pertemuan }}</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-bold mt-1">
                <span class="text-indigo-600 uppercase tracking-widest">{{ $absensi->kategori_label }}</span> &bull; 
                {{ $absensi->tanggal_posyandu->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>

    {{-- GRID STATISTIK COMPACT UNTUK MOBILE --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Warga</p>
            <p class="text-3xl font-black text-slate-800">{{ $totalPasien }}</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1.5">Hadir</p>
            <p class="text-3xl font-black text-emerald-600">{{ $totalHadir }}</p>
        </div>
        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 text-center shadow-sm">
            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1.5">Absen</p>
            <p class="text-3xl font-black text-rose-600">{{ $totalAbsen }}</p>
        </div>
        <div class="bg-slate-800 rounded-2xl p-5 text-center shadow-sm text-white">
            @php $pct = $totalPasien > 0 ? round($totalHadir/$totalPasien*100) : 0; @endphp
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Persentase</p>
            <p class="text-3xl font-black">{{ $pct }}%</p>
        </div>
    </div>

    {{-- NAVIGASI SESI (SEBELUMNYA & BERIKUTNYA) --}}
    <div class="flex items-center justify-between mb-6 bg-white border border-slate-200/80 rounded-[18px] p-3 sm:p-4 shadow-sm">
        @if($sebelumnya)
            <a href="{{ route('kader.absensi.show', $sebelumnya->id) }}" class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-wider rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center gap-2"><i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Sesi Sebelumnya</span></a>
        @else
            <div></div>
        @endif
        
        <div class="text-center">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Lembar Absen</p>
            <p class="font-mono font-bold text-slate-800 bg-slate-100 px-3 py-1 rounded-md text-[11px]">{{ $absensi->kode_absensi }}</p>
        </div>

        @if($berikutnya)
            <a href="{{ route('kader.absensi.show', $berikutnya->id) }}" class="px-4 py-2 bg-slate-50 border border-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-wider rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center gap-2"><span class="hidden sm:inline">Sesi Berikutnya</span> <i class="fas fa-chevron-right"></i></a>
        @else
            <div></div>
        @endif
    </div>

    {{-- TABEL RINCIAN ALA EXCEL --}}
    <div class="bg-white rounded-[20px] border border-slate-200/80 shadow-sm overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-black text-slate-800 text-sm flex items-center gap-2"><i class="fas fa-list-ul text-indigo-500"></i> Rekap Tabel Kehadiran</h3>
        </div>

        <div class="table-container overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[600px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center w-12 border-r border-slate-200/50">No</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Identitas Warga</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center border-r border-slate-200/50">Kehadiran</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Catatan / Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($details->sortByDesc('hadir') as $index => $detail)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $detail->hadir ? 'bg-emerald-50/10' : 'opacity-80' }}">
                        <td class="px-5 py-3 text-center border-r border-slate-200/50 text-xs font-bold text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        
                        <td class="px-5 py-3 border-r border-slate-200/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-[11px] shrink-0 {{ $detail->hadir ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                    {{ strtoupper(substr($detail->pasien_data?->nama_lengkap ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[13px]">{{ $detail->pasien_data?->nama_lengkap ?? '— Data Dihapus —' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 font-mono">{{ $detail->pasien_data?->nik ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3 text-center border-r border-slate-200/50">
                            @if($detail->hadir)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded border border-emerald-200">
                                    <i class="fas fa-check"></i> Hadir
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-wider rounded border border-rose-200">
                                    <i class="fas fa-times"></i> Absen
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-3 text-[11px] font-bold text-slate-600 italic">
                            {{ $detail->keterangan ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection