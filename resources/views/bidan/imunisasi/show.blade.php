@extends('layouts.bidan')
@section('title', 'Detail Imunisasi')
@section('page-name', 'Detail Imunisasi')

@section('content')
<div class="max-w-4xl mx-auto animate-pop">

    <div class="bg-white rounded-[32px] p-8 md:p-10 mb-8 border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-white opacity-50"></div>
        <div class="relative z-10">
            <div class="w-20 h-20 mx-auto rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-4xl mb-4 border-4 border-white shadow-md">
                <i class="fas fa-shield-virus"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 font-poppins mb-1">{{ $imunisasi->vaksin }}</h2>
            <p class="text-cyan-700 font-bold uppercase tracking-widest text-xs">{{ $imunisasi->jenis_imunisasi }} &middot; Dosis {{ $imunisasi->dosis }}</p>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 mb-8">
        <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 border-b border-slate-100 pb-4"><i class="fas fa-user-injured text-cyan-500 mr-2"></i> Informasi Pasien</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pasien</p>
                <p class="text-sm font-bold text-slate-800">{{ $imunisasi->kunjungan->pasien->nama_lengkap ?? 'Tidak diketahui' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                <p class="text-sm font-bold text-slate-800">{{ class_basename($imunisasi->kunjungan->pasien_type) }}</p>
            </div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Pemberian Vaksin</p>
                <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->translatedFormat('l, d F Y') }}</p>
            </div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Petugas (Bidan)</p>
                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-4 pb-10">
        <a href="{{ route('bidan.imunisasi.index') }}" class="px-8 py-3 rounded-xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 smooth-route"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
        
        <a href="{{ route('bidan.imunisasi.edit', $imunisasi->id) }}" class="px-8 py-3 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 smooth-route"><i class="fas fa-edit mr-2"></i> Edit Data</a>
    </div>

</div>
@endsection