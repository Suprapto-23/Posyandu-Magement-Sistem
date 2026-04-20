@extends('layouts.kader')
@section('title', 'Riwayat Import')
@section('page-name', 'Log & History')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8 mt-4">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-white text-indigo-600 border border-indigo-100 flex items-center justify-center text-3xl shadow-sm shrink-0">
                <i class="fas fa-server"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight font-poppins">Log Server</h1>
                <p class="text-slate-500 mt-1 font-medium text-[13px]">Jejak rekam aktivitas *mass-upload* database posyandu.</p>
            </div>
        </div>
        <a href="{{ route('kader.import.create') }}" class="loader-trigger inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-indigo-600 text-white font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-indigo-700 shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus"></i> Import Baru
        </a>
    </div>

    @if($imports->count() > 0)
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/90 backdrop-blur-sm border-b border-slate-100">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100/50">Detail File</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100/50">Waktu Eksekusi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-100/50">Status Mesin</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-100/50">Target Modul</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($imports as $import)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        
                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[12px] bg-slate-100 text-slate-400 flex items-center justify-center text-lg border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-500 group-hover:border-indigo-200 transition-colors shrink-0">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] font-poppins mb-0.5 truncate max-w-[250px]">{{ $import->nama_file }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID Log: #{{ str_pad($import->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <p class="font-bold text-slate-800 text-[13px] mb-1">{{ $import->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded inline-block"><i class="far fa-clock"></i> {{ $import->created_at->format('H:i:s') }} WIB</p>
                        </td>

                        <td class="px-6 py-5 text-center border-r border-slate-100/50">
                            @if($import->status == 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </span>
                            @elseif($import->status == 'processing')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-widest border border-amber-200 shadow-sm animate-pulse">
                                    <i class="fas fa-sync fa-spin"></i> Proses
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-black uppercase tracking-widest border border-rose-200 shadow-sm">
                                    <i class="fas fa-times-circle"></i> Gagal
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-center border-r border-slate-100/50">
                            <span class="inline-flex px-3 py-1 bg-white text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200 shadow-sm">
                                <i class="fas fa-database mr-1 text-slate-400"></i> {{ $import->jenis_data }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kader.import.show', $import->id) }}" class="loader-trigger inline-flex w-10 h-10 rounded-[12px] bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 shadow-sm transition-all hover:scale-110" title="Lihat Terminal Log">
                                    <i class="fas fa-terminal"></i>
                                </a>
                                <form action="{{ route('kader.import.destroy', $import->id) }}" method="POST" onsubmit="return confirm('Hapus jejak log ini?');" class="inline-block m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-press inline-flex w-10 h-10 rounded-[12px] bg-white border border-slate-200 items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 shadow-sm transition-all" title="Hapus Riwayat">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
            {{ $imports->links() }}
        </div>
    </div>
    @else
    <div class="text-center py-24 relative overflow-hidden bg-white rounded-[32px] border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)]">
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] text-[300px] pointer-events-none"><i class="fas fa-server"></i></div>
        <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-6 text-4xl shadow-inner border border-slate-100 relative z-10"><i class="fas fa-box-open"></i></div>
        <h4 class="text-xl font-black text-slate-800 font-poppins relative z-10">Database Log Kosong</h4>
        <p class="text-[14px] text-slate-500 mt-2 max-w-sm mx-auto relative z-10 font-medium">Anda belum pernah melakukan import massal. Riwayat masih bersih.</p>
        <a href="{{ route('kader.import.create') }}" class="loader-trigger inline-flex items-center gap-2 mt-8 text-indigo-600 font-black uppercase tracking-widest text-[12px] hover:text-white relative z-10 bg-indigo-50 hover:bg-indigo-600 px-6 py-3.5 rounded-xl transition-colors border border-indigo-200 shadow-sm">
            <i class="fas fa-bolt text-lg"></i> Mulai Eksekusi Pertama
        </a>
    </div>
    @endif

</div>
@endsection