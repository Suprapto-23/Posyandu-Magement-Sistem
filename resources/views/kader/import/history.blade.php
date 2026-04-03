@extends('layouts.kader')

@section('title', 'Riwayat Import')
@section('page-name', 'Log & History')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto animate-slide-up">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8 mt-4">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-2xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0">
                <i class="fas fa-server"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Log Server Eksekusi</h1>
                <p class="text-slate-500 mt-1 font-medium text-[13px]">Memantau aktivitas mass-upload data ke dalam database.</p>
            </div>
        </div>
        <a href="{{ route('kader.import.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus"></i> Import Baru
        </a>
    </div>

    @if($imports->count() > 0)
    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Detail File</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Waktu Eksekusi</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Status Engine</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-center">Target Modul</th>
                        <th class="px-6 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest text-right">Aksi Server</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($imports as $import)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-lg shadow-sm border border-indigo-100">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-sm mb-0.5">{{ Str::limit($import->nama_file, 25) }}</p>
                                    <p class="text-[11px] font-bold text-slate-400">Ukuran: Disembunyikan</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 text-[13px] mb-0.5">{{ $import->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[11px] font-bold text-slate-400"><i class="far fa-clock mr-1"></i> Pukul {{ $import->created_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($import->status == 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-[11px] font-extrabold border border-emerald-200">
                                    <i class="fas fa-check-circle"></i> Berhasil
                                </span>
                            @elseif($import->status == 'processing')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-[11px] font-extrabold border border-amber-200">
                                    <i class="fas fa-sync fa-spin"></i> Memproses
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-[11px] font-extrabold border border-rose-200">
                                    <i class="fas fa-times-circle"></i> Gagal Eksekusi
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-md border border-slate-200">
                                {{ $import->jenis_data }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kader.import.show', $import->id) }}" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 shadow-sm transition-all" title="Lihat Terminal Log">
                                    <i class="fas fa-terminal"></i>
                                </a>
                                <form action="{{ route('kader.import.destroy', $import->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat eksekusi ini?');" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex w-9 h-9 rounded-xl bg-white border border-slate-200 items-center justify-center text-slate-400 hover:text-white hover:bg-rose-500 hover:border-rose-500 shadow-sm transition-all" title="Hapus Riwayat">
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
    <div class="text-center py-24 relative overflow-hidden bg-white rounded-[32px] border border-slate-200 shadow-sm">
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] text-[250px] pointer-events-none"><i class="fas fa-server"></i></div>
        <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-6 text-4xl shadow-inner border border-slate-100 relative z-10"><i class="fas fa-box-open"></i></div>
        <h4 class="text-xl font-black text-slate-800 font-poppins relative z-10">Database Log Kosong</h4>
        <p class="text-[14px] text-slate-500 mt-2 max-w-sm mx-auto relative z-10 font-medium">Anda belum pernah melakukan import massal. Data server masih kosong.</p>
        <a href="{{ route('kader.import.create') }}" class="inline-flex items-center gap-2 mt-6 text-indigo-600 font-bold hover:text-indigo-700 relative z-10 bg-indigo-50 px-5 py-2.5 rounded-lg border border-indigo-100">
            <i class="fas fa-bolt"></i> Mulai Eksekusi Pertama
        </a>
    </div>
    @endif

</div>
@endsection