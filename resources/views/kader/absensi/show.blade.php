@extends('layouts.kader')

@section('title', 'Detail Absensi')
@section('page-name', 'Ringkasan Sesi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .card-gradient-1 { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    .card-gradient-2 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .card-gradient-3 { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }
    .card-gradient-4 { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- HEADER (Clean & Minimalist) --}}
    <div class="flex items-center gap-5 mb-8 animate-slide-up">
        <a href="{{ route('kader.absensi.riwayat') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-800 hover:text-white transition-all shadow-sm border border-slate-100 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Ringkasan Sesi <span class="text-indigo-600">#{{ $absensi->nomor_pertemuan }}</span></h1>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded-md border border-indigo-100">
                    {{ $absensi->kategori_label }}
                </span>
                <span class="text-xs font-bold text-slate-500">
                    <i class="far fa-calendar-check mr-1 text-slate-400"></i> {{ $absensi->tanggal_posyandu->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
        
        {{-- Print Action (Placeholder untuk estetika) --}}
        <button onclick="window.print()" class="hidden sm:flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 font-black text-xs rounded-xl hover:bg-slate-50 shadow-sm transition-all uppercase tracking-widest">
            <i class="fas fa-print text-indigo-500"></i> Cetak
        </button>
    </div>

    {{-- GRID STATISTIK "SEXY" (Berdasar Gradien) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 animate-slide-up-delay-1">
        
        <div class="card-gradient-1 rounded-[20px] p-5 shadow-lg relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/5 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Total Terdaftar</p>
            <p class="text-4xl font-black text-white relative z-10">{{ $totalPasien }} <span class="text-sm font-bold text-slate-400">Warga</span></p>
        </div>

        <div class="card-gradient-2 rounded-[20px] p-5 shadow-lg shadow-emerald-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-black text-emerald-200 uppercase tracking-widest mb-1 relative z-10">Total Hadir</p>
            <p class="text-4xl font-black text-white relative z-10">{{ $totalHadir }} <span class="text-sm font-bold text-emerald-200">Orang</span></p>
        </div>

        <div class="card-gradient-3 rounded-[20px] p-5 shadow-lg shadow-rose-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[10px] font-black text-rose-200 uppercase tracking-widest mb-1 relative z-10">Total Absen</p>
            <p class="text-4xl font-black text-white relative z-10">{{ $totalAbsen }} <span class="text-sm font-bold text-rose-200">Orang</span></p>
        </div>

        <div class="card-gradient-4 rounded-[20px] p-5 shadow-lg shadow-indigo-500/20 relative overflow-hidden flex flex-col justify-center">
            @php $pct = $totalPasien > 0 ? round($totalHadir/$totalPasien*100) : 0; @endphp
            <div class="flex items-end justify-between mb-2 relative z-10">
                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Kehadiran</p>
                <p class="text-2xl font-black text-white leading-none">{{ $pct }}%</p>
            </div>
            <div class="w-full h-1.5 bg-white/20 rounded-full overflow-hidden relative z-10">
                <div class="h-full bg-white rounded-full" style="width:{{ $pct }}%"></div>
            </div>
        </div>
    </div>

    {{-- NAVIGASI & INFO SISTEM --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 bg-white border border-slate-200 rounded-[20px] p-2 pr-2 sm:pr-5 shadow-sm animate-slide-up-delay-1">
        
        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start pl-2 sm:pl-0">
            @if($sebelumnya)
                <a href="{{ route('kader.absensi.show', $sebelumnya->id) }}" class="px-5 py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center gap-2"><i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span></a>
            @else
                <div class="px-5 py-2.5 opacity-0 cursor-default"><i class="fas fa-arrow-left"></i></div>
            @endif
            
            @if($berikutnya)
                <a href="{{ route('kader.absensi.show', $berikutnya->id) }}" class="px-5 py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center gap-2"><span class="hidden sm:inline">Berikutnya</span> <i class="fas fa-arrow-right"></i></a>
            @else
                <div class="px-5 py-2.5 opacity-0 cursor-default"><i class="fas fa-arrow-right"></i></div>
            @endif
        </div>

        <div class="flex items-center gap-4 text-right">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Kode Registrasi Berkas</p>
                <p class="font-mono font-bold text-slate-800 text-xs">{{ $absensi->kode_absensi }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                <i class="fas fa-qrcode"></i>
            </div>
        </div>

    </div>

    {{-- TABEL RINCIAN --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-2">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-black text-slate-800 text-sm flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center text-sm"><i class="fas fa-clipboard-check"></i></div>
                Daftar Panggilan Sesi Ini
            </h3>
            <span class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200 px-3 py-1 rounded-full shadow-sm">{{ $details->count() }} Entri Data</span>
        </div>

        <div class="table-container overflow-x-auto max-h-[60vh]">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[600px]">
                <thead class="sticky top-0 bg-white z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-12 border-b border-slate-200">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Identitas Warga</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-b border-slate-200">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Catatan Tambahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Urutkan yang hadir di atas, absen di bawah --}}
                    @foreach($details->sortByDesc('hadir') as $index => $detail)
                    <tr class="hover:bg-slate-50 transition-colors {{ $detail->hadir ? '' : 'bg-slate-50/50 grayscale-[20%]' }}">
                        <td class="px-6 py-4 text-center text-xs font-bold text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Avatar Cerdas --}}
                                @php
                                    $initial = strtoupper(substr($detail->pasien_data?->nama_lengkap ?? '?', 0, 1));
                                    $avatarClass = $detail->hadir ? 'bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-md shadow-emerald-200' : 'bg-slate-200 text-slate-500';
                                @endphp
                                <div class="w-10 h-10 rounded-[12px] flex items-center justify-center font-black text-sm shrink-0 {{ $avatarClass }}">
                                    {{ $initial }}
                                </div>
                                
                                <div>
                                    <p class="font-black text-slate-800 text-[13px]">{{ $detail->pasien_data?->nama_lengkap ?? '— Data Dihapus Sistem —' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5">{{ $detail->pasien_data?->nik ?? 'NIK Tidak Tersedia' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($detail->hadir)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Hadir</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-500 border border-rose-200 rounded-lg opacity-80">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Absen</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-[12px] font-bold {{ $detail->keterangan ? 'text-slate-600' : 'text-slate-300 italic' }}">
                            {{ $detail->keterangan ?? 'Tidak ada catatan' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection