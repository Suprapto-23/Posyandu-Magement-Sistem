@extends('layouts.kader')

@section('title', 'Riwayat Absensi')
@section('page-name', 'Riwayat Absensi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(226, 232, 240, 0.8); }
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    
    /* Custom Select Modern */
    select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 1rem center; background-repeat: no-repeat; background-size: 1.2em 1.2em; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto pb-10">

    {{-- HEADER (Fokus pada Arsip, Tanpa Tombol Buat Baru) --}}
    <div class="flex items-center gap-5 mb-8 animate-slide-up">
        <div class="w-16 h-16 bg-white rounded-[20px] flex items-center justify-center text-indigo-500 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-100 shrink-0">
            <i class="fas fa-history text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins">Arsip Sesi Absensi</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Cari, filter, dan pantau riwayat kehadiran warga Posyandu dari waktu ke waktu.</p>
        </div>
    </div>

    {{-- FILTER PANEL (CLEAN & MODERN) --}}
    <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-6 sm:p-8 mb-10 animate-slide-up-delay-1 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>

        <form method="GET" action="{{ route('kader.absensi.riwayat') }}" class="flex flex-col lg:flex-row gap-5 lg:items-end relative z-10">

            <div class="flex-1 w-full">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-layer-group text-indigo-400 mr-1"></i> Filter Kategori Modul</label>
                <select name="kategori" class="w-full border-2 border-slate-200 rounded-2xl px-5 py-4 text-[14px] font-bold text-slate-800 bg-slate-50 hover:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    <option value="bayi"      {{ request('kategori') == 'bayi'      ? 'selected' : '' }}>🍼 Bayi (0–11 Bulan)</option>
                    <option value="balita"    {{ request('kategori') == 'balita'    ? 'selected' : '' }}>👶 Balita (12–59 Bulan)</option>
                    <option value="remaja"    {{ request('kategori') == 'remaja'    ? 'selected' : '' }}>🎓 Remaja Umum</option>
                    <option value="ibu_hamil" {{ request('kategori') == 'ibu_hamil' ? 'selected' : '' }}>🤰 Ibu Hamil</option>
                    <option value="lansia"    {{ request('kategori') == 'lansia'    ? 'selected' : '' }}>👴 Lansia (Lanjut Usia)</option>
                </select>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-calendar-alt text-indigo-400 mr-1"></i> Filter Bulan Eksekusi</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="w-full border-2 border-slate-200 rounded-2xl px-5 py-4 text-[14px] font-bold text-slate-800 bg-slate-50 hover:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer" 
                       onchange="this.form.submit()">
            </div>

            <div class="w-full lg:w-auto flex gap-3">
                <button type="submit" class="flex-1 lg:flex-none px-10 py-4 bg-slate-800 text-white font-black text-[13px] rounded-2xl hover:bg-slate-900 transition-all shadow-md uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Terapkan
                </button>
                @if(request('kategori') || request('bulan'))
                <a href="{{ route('kader.absensi.riwayat') }}" class="px-5 py-4 bg-rose-50 text-rose-600 border border-rose-200 font-black text-[13px] rounded-2xl hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Bersihkan Filter">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABEL RIWAYAT --}}
    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgba(0,0,0,0.03)] overflow-hidden animate-slide-up-delay-2">
        <div class="table-container overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Informasi Sesi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Kategori</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Waktu Pelaksanaan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-100">Tingkat Kehadiran</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $abs)
                    @php
                        $hadir = $abs->details->where('hadir', true)->count();
                        $total = $abs->details->count();
                        $pct   = $total > 0 ? round($hadir/$total*100) : 0;
                        
                        $badgeStyle = match($abs->kategori) {
                            'bayi'      => 'bg-sky-50 text-sky-600 border-sky-100',
                            'balita'    => 'bg-violet-50 text-violet-600 border-violet-100',
                            'remaja'    => 'bg-amber-50 text-amber-600 border-amber-100',
                            'ibu_hamil' => 'bg-pink-50 text-pink-600 border-pink-100',
                            'lansia'    => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            default     => 'bg-slate-50 text-slate-600 border-slate-100',
                        };

                        $progressColor = $pct >= 75 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-amber-500' : 'bg-rose-500');
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group">

                        {{-- Pertemuan --}}
                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-[16px] bg-slate-50 flex flex-col items-center justify-center shrink-0 border border-slate-200 group-hover:bg-indigo-50 group-hover:border-indigo-200 group-hover:text-indigo-600 transition-all shadow-sm">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Sesi</span>
                                    <span class="text-xl font-black font-poppins leading-none">{{ $abs->nomor_pertemuan }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[14px] group-hover:text-indigo-600 transition-colors font-poppins">{{ $abs->kode_absensi }}</p>
                                    <div class="flex items-center gap-1 mt-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <i class="fas fa-user-edit text-slate-300"></i> Pencatat: {{ $abs->pencatat?->name ?? 'Sistem' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <span class="inline-flex px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $badgeStyle }}">
                                {{ $abs->kategori_label }}
                            </span>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <p class="text-[14px] font-black text-slate-800">
                                {{ $abs->tanggal_posyandu->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-[11px] text-slate-500 font-bold mt-1"><i class="far fa-clock text-slate-400 mr-1"></i> Pukul {{ $abs->created_at->format('H:i') }} WIB</p>
                        </td>

                        {{-- Kehadiran (Progress Bar Clean) --}}
                        <td class="px-6 py-5 border-r border-slate-100/50">
                            <div class="w-full max-w-[200px] mx-auto">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hadir: <span class="text-slate-800 text-[13px]">{{ $hadir }}</span>/{{ $total }}</p>
                                    <p class="text-[12px] font-black {{ $pct >= 75 ? 'text-emerald-600' : ($pct >= 40 ? 'text-amber-600' : 'text-rose-600') }}">{{ $pct }}%</p>
                                </div>
                                <div class="w-full h-2 bg-slate-100 border border-slate-200/50 rounded-full overflow-hidden p-[1.5px]">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $progressColor }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('kader.absensi.show', $abs->id) }}"
                                   class="w-10 h-10 rounded-[14px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm hover:-translate-y-0.5" title="Buka Detail Ringkasan">
                                    <i class="fas fa-folder-open"></i>
                                </a>
                                
                                <form action="{{ route('kader.absensi.destroy', $abs->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN! Menghapus arsip ini akan menghilangkan data kehadiran secara permanen. Lanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-[14px] bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm hover:-translate-y-0.5 flex items-center justify-center" title="Hapus Permanen">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center bg-slate-50/30">
                            <div class="w-24 h-24 mx-auto bg-white rounded-[24px] flex items-center justify-center mb-5 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] transform rotate-3">
                                <i class="fas fa-box-open text-4xl text-slate-300"></i>
                            </div>
                            <p class="font-black text-slate-800 text-xl font-poppins mb-1">Arsip Belum Tersedia</p>
                            <p class="text-sm font-medium text-slate-500 max-w-sm mx-auto">Sistem tidak menemukan log kehadiran pada kriteria pencarian ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/30">
            {{ $riwayat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection