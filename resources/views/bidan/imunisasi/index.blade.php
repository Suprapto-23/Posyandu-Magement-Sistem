@extends('layouts.bidan')
@section('title', 'Register Imunisasi')
@section('page-name', 'Riwayat Imunisasi')

@push('styles')
<style>
    .animate-fade { opacity: 0; animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .table-row-hover:hover td { background-color: #f8fafc; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-fade">

    <div class="bg-white rounded-[24px] p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm border border-slate-200">
        <div class="absolute right-0 top-0 w-64 h-full bg-cyan-500/5 blur-3xl rounded-full pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-cyan-100">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black font-poppins text-slate-800 mb-1">Register Imunisasi</h2>
                <p class="text-slate-500 text-sm font-medium max-w-lg">Buku log riwayat pemberian vaksin dan imunisasi. Data yang Anda input di sini akan otomatis tampil di akun Kader dan Warga.</p>
            </div>
        </div>
        
        <a href="{{ route('bidan.imunisasi.create') }}" class="relative z-10 bg-cyan-500 hover:bg-cyan-600 text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(6,182,212,0.3)] hover:-translate-y-1 flex items-center gap-2 smooth-route whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Imunisasi
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-[20px] p-5 mb-8 flex items-center gap-3 text-emerald-700 font-bold text-sm shadow-sm animate-fade">
        <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-[20px] p-5 mb-8 flex items-center gap-3 text-rose-700 font-bold text-sm shadow-sm animate-fade">
        <i class="fas fa-exclamation-circle text-xl"></i> {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center gap-3 mb-6 overflow-x-auto pb-2 custom-scrollbar">
        <a href="{{ route('bidan.imunisasi.index') }}" class="px-5 py-2.5 bg-cyan-600 text-white text-[13px] font-bold rounded-xl shadow-sm whitespace-nowrap">Semua Data</a>
        
        <form method="GET" action="{{ route('bidan.imunisasi.index') }}" class="ml-auto relative w-full sm:w-80">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien atau vaksin..." class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-semibold focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none shadow-sm">
        </form>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-5 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Tgl Pemberian</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Pasien</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Detail Vaksin</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Petugas (Bidan)</th>
                        <th class="py-5 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($imunisasis ?? [] as $imun)
                    <tr class="table-row-hover transition-colors">
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-cyan-50 flex flex-col items-center justify-center text-cyan-700 border border-cyan-100">
                                    <span class="text-[14px] font-black leading-none">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d') }}</span>
                                    <span class="text-[9px] font-bold uppercase">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->translatedFormat('M') }}</span>
                                </div>
                                <div class="text-[12px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('Y') }}</div>
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            @php
                                $namaPasien = $imun->kunjungan?->pasien?->nama_lengkap ?? 'Tidak Diketahui';
                                $tipePasien = class_basename($imun->kunjungan?->pasien_type);
                                
                                $badgeColor = match($tipePasien) {
                                    'Balita' => 'bg-rose-50 text-rose-600 border-rose-200',
                                    'Remaja' => 'bg-sky-50 text-sky-600 border-sky-200',
                                    'Lansia' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <div class="text-[14px] font-bold text-slate-800 mb-1">{{ $namaPasien }}</div>
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest border {{ $badgeColor }}">
                                {{ $tipePasien }}
                            </span>
                        </td>

                        <td class="py-4 px-6">
                            <div class="text-[13px] font-bold text-slate-800 mb-0.5">{{ $imun->vaksin }}</div>
                            <div class="text-[11px] font-semibold text-slate-500">
                                Dosis: <span class="text-cyan-600 font-bold">{{ $imun->dosis }}</span> &middot; {{ $imun->jenis_imunisasi }}
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-[10px] font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-[12px] font-bold text-slate-600">{{ Auth::user()->name }}</span>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('bidan.imunisasi.show', $imun->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 flex items-center justify-center hover:bg-cyan-500 hover:text-white hover:border-cyan-500 transition-all smooth-route" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                                
                                <form action="{{ route('bidan.imunisasi.destroy', $imun->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus data imunisasi ini? Catatan: Data tidak bisa dikembalikan.')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 border border-rose-200 flex items-center justify-center hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all" title="Hapus"><i class="fas fa-trash-alt text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 text-slate-300 mb-4">
                                <i class="fas fa-shield-virus text-3xl"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-700 font-poppins uppercase tracking-widest mb-1">Belum Ada Riwayat Imunisasi</h3>
                            <p class="text-xs font-medium text-slate-400">Klik tombol Tambah Imunisasi di atas untuk mencatat pemberian vaksin.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if(isset($imunisasis) && $imunisasis->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $imunisasis->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection