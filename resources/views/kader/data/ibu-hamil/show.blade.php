@extends('layouts.kader')
@section('title', 'Detail Ibu Hamil')
@section('page-name', 'Detail Profil')

@push('styles')
<style>
    .animate-slide-up { opacity:0; animation:slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .progress-bar { height:10px; border-radius:9999px; background:#fce7f3; overflow:hidden; }
    .progress-fill { height:100%; border-radius:9999px; background:linear-gradient(to right,#f9a8d4,#ec4899); transition:width 1s ease; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.data.ibu-hamil.index') }}"
               class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-black text-slate-900">Profil Ibu Hamil</h1>
        </div>
        <a href="{{ route('kader.data.ibu-hamil.edit', $ibuHamil->id) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-extrabold text-sm rounded-xl hover:bg-amber-600 shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-edit"></i> Edit Data
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KOLOM KIRI --}}
        <div class="lg:col-span-4 space-y-5">

            {{-- KARTU PROFIL --}}
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-b from-pink-500 to-rose-600 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px"></div>
                    <div class="w-24 h-24 mx-auto bg-white rounded-[20px] shadow-xl flex items-center justify-center text-pink-500 text-5xl font-black border-4 border-pink-200/30 relative z-10">
                        {{ strtoupper(substr($ibuHamil->nama_lengkap,0,1)) }}
                    </div>
                    <h2 class="text-xl font-extrabold text-white mt-4 relative z-10">{{ $ibuHamil->nama_lengkap }}</h2>
                    @if($ibuHamil->kode_hamil)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-pink-900/30 text-pink-50 text-xs font-bold rounded-lg mt-2 relative z-10 border border-white/10">
                        <i class="fas fa-id-card"></i> {{ $ibuHamil->kode_hamil }}
                    </div>
                    @endif
                </div>

                <div class="p-6 space-y-3">
                    @if($ibuHamil->nama_suami)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suami</span>
                        <span class="text-sm font-bold text-slate-800">{{ $ibuHamil->nama_suami }}</span>
                    </div>
                    @endif
                    @if($ibuHamil->nik)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">NIK</span>
                        <span class="text-sm font-mono font-bold text-slate-800">{{ $ibuHamil->nik }}</span>
                    </div>
                    @endif
                    @if($ibuHamil->telepon_ortu)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon</span>
                        <span class="text-sm font-bold text-slate-800">{{ $ibuHamil->telepon_ortu }}</span>
                    </div>
                    @endif
                    @if($ibuHamil->golongan_darah)
                    <div class="flex items-center justify-between p-3 bg-rose-50 rounded-xl border border-rose-100">
                        <span class="text-xs font-bold text-rose-400 uppercase tracking-wider">Gol. Darah</span>
                        <span class="text-lg font-black text-rose-600">{{ $ibuHamil->golongan_darah }}</span>
                    </div>
                    @endif
                    @if($ibuHamil->alamat)
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="text-sm font-bold text-slate-800 leading-snug">{{ $ibuHamil->alamat }}</p>
                    </div>
                    @endif
                    @if($ibuHamil->riwayat_penyakit)
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl">
                        <p class="text-[10px] font-extrabold text-amber-500 uppercase tracking-widest mb-1">Riwayat Penyakit</p>
                        <p class="text-sm font-bold text-amber-800">{{ $ibuHamil->riwayat_penyakit }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- DATA FISIK --}}
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6">
                <h6 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Data Fisik (Kader)</h6>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-3 bg-slate-50 rounded-xl text-center">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">BB</p>
                        <p class="text-lg font-black text-slate-800">{{ $ibuHamil->berat_badan ?? '-' }} <span class="text-xs font-medium text-slate-400">kg</span></p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl text-center">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">TB</p>
                        <p class="text-lg font-black text-slate-800">{{ $ibuHamil->tinggi_badan ?? '-' }} <span class="text-xs font-medium text-slate-400">cm</span></p>
                    </div>
                </div>
                @if($ibuHamil->imt)
                <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                    <p class="text-[10px] font-extrabold text-emerald-500 uppercase tracking-widest mb-0.5">IMT</p>
                    <p class="text-2xl font-black text-emerald-700">{{ $ibuHamil->imt }}</p>
                    @php
                        $imt = $ibuHamil->imt;
                        $imtKat = $imt < 18.5 ? 'Kurus' : ($imt < 25 ? 'Normal' : ($imt < 27 ? 'Gemuk Ringan' : 'Obesitas'));
                    @endphp
                    <p class="text-xs font-bold text-emerald-600 mt-0.5">{{ $imtKat }}</p>
                </div>
                @else
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center">
                    <p class="text-sm text-slate-400 font-medium">IMT belum tersedia</p>
                    <a href="{{ route('kader.data.ibu-hamil.edit', $ibuHamil->id) }}" class="text-xs text-pink-600 font-bold mt-0.5 inline-block hover:underline">Isi BB/TB →</a>
                </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- KARTU KEHAMILAN --}}
            @php
                $minggu   = $ibuHamil->usia_kehamilan;
                $trimNo   = $ibuHamil->trimester_angka;
                $sisaHari = $ibuHamil->sisa_hari;
                $pct      = $minggu ? min(100, round($minggu / 40 * 100)) : 0;
                $trimColor = match($trimNo) { 1=>'sky', 2=>'violet', 3=>'rose', default=>'slate' };
            @endphp
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm p-6 md:p-8">
                <h3 class="font-extrabold text-slate-800 text-base mb-5 flex items-center gap-2">
                    <i class="fas fa-baby-carriage text-pink-400"></i> Status Kehamilan
                </h3>

                @if($minggu !== null)
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex-1 min-w-[120px] p-4 bg-{{ $trimColor }}-50 border border-{{ $trimColor }}-100 rounded-2xl text-center">
                        <p class="text-3xl font-black text-{{ $trimColor }}-600">{{ $minggu }}</p>
                        <p class="text-[10px] font-extrabold text-{{ $trimColor }}-400 uppercase tracking-widest mt-0.5">Minggu</p>
                    </div>
                    <div class="flex-1 min-w-[120px] p-4 bg-pink-50 border border-pink-100 rounded-2xl text-center">
                        <p class="text-lg font-black text-pink-600">{{ $ibuHamil->trimester }}</p>
                        <p class="text-[10px] font-extrabold text-pink-400 uppercase tracking-widest mt-0.5">Status</p>
                    </div>
                    @if($sisaHari !== null)
                    <div class="flex-1 min-w-[120px] p-4 {{ $sisaHari <= 30 ? 'bg-amber-50 border-amber-100' : 'bg-slate-50 border-slate-100' }} border rounded-2xl text-center">
                        <p class="text-3xl font-black {{ $sisaHari <= 30 ? 'text-amber-600' : 'text-slate-700' }}">
                            {{ $sisaHari > 0 ? $sisaHari : '≈ 0' }}
                        </p>
                        <p class="text-[10px] font-extrabold {{ $sisaHari <= 30 ? 'text-amber-400' : 'text-slate-400' }} uppercase tracking-widest mt-0.5">Hari Menuju HPL</p>
                    </div>
                    @endif
                </div>

                {{-- Progress Bar --}}
                <div class="mb-2 flex items-center justify-between text-xs font-bold text-slate-400">
                    <span>0 minggu</span>
                    <span class="text-pink-600 font-black">{{ $minggu }} / 40 minggu</span>
                    <span>40 minggu</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium mt-1.5 text-right">{{ $pct }}% perjalanan kehamilan</p>

                @else
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                    <p class="text-sm text-slate-400 font-medium">HPHT belum diisi — usia kehamilan tidak dapat dihitung</p>
                    <a href="{{ route('kader.data.ibu-hamil.edit', $ibuHamil->id) }}" class="text-xs text-pink-600 font-bold mt-1 inline-block hover:underline">Lengkapi data →</a>
                </div>
                @endif

                @if($ibuHamil->hpht || $ibuHamil->hpl)
                <div class="grid grid-cols-2 gap-4 mt-5">
                    @if($ibuHamil->hpht)
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-0.5">HPHT</p>
                        <p class="text-sm font-bold text-slate-800">{{ $ibuHamil->hpht->translatedFormat('d F Y') }}</p>
                    </div>
                    @endif
                    @if($ibuHamil->hpl)
                    <div class="p-3 bg-pink-50 border border-pink-100 rounded-xl">
                        <p class="text-[10px] font-extrabold text-pink-400 uppercase tracking-widest mb-0.5">HPL (Perkiraan Lahir)</p>
                        <p class="text-sm font-bold text-pink-800">{{ $ibuHamil->hpl->translatedFormat('d F Y') }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- INFO BIDAN --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-[24px] p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-indigo-900 text-sm mb-1">Pemeriksaan Mendalam oleh Bidan</h4>
                        <p class="text-xs text-indigo-700 leading-relaxed">
                            Data fisik dasar sudah dicatat oleh kader. Untuk pemeriksaan lebih lanjut seperti
                            <strong>tekanan darah, detak jantung janin, vaksin TT, USG, dan lab darah</strong>
                            akan dilakukan oleh bidan pada halaman Pemeriksaan Medis.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection