@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-orange-50 rounded-full shadow-sm border border-orange-100 mb-4">
                <i class="fas fa-heartbeat text-orange-500"></i>
                <span class="text-[11px] font-black tracking-widest uppercase text-orange-700">Pemantauan Kesehatan Lansia</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Kesehatan Lanjut Usia 🧓</h1>
            <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Pantau terus tekanan darah, gula darah, dan kondisi fisik secara berkala bersama Kader & Bidan Posyandu.</p>
        </div>
    </div>

    @if(isset($alerts) && count($alerts) > 0)
        <div class="mb-8 space-y-3">
            @foreach($alerts as $alert)
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex gap-4 items-center shadow-sm relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-100 rounded-full blur-xl"></div>
                    <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0 z-10">
                        <i class="fas {{ $alert['tipe'] == 'tensi' ? 'fa-heartbeat' : 'fa-tint' }} animate-pulse"></i>
                    </div>
                    <div class="z-10 flex-1">
                        <h3 class="text-sm font-black text-rose-800 uppercase tracking-wide">Peringatan Medis</h3>
                        <p class="text-xs font-bold text-rose-700 mt-0.5">{{ $alert['pesan'] }}</p>
                    </div>
                    <div class="z-10 shrink-0 hidden sm:block">
                        <a href="{{ route('user.konseling.index') }}" class="px-4 py-2 bg-rose-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg hover:bg-rose-700 transition-colors shadow-sm">
                            Konsul Bidan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-orange-400 to-amber-300"></div>
                
                <div class="relative z-10 flex flex-col items-center mt-6">
                    <div class="w-24 h-24 bg-white rounded-full border-4 border-white shadow-md flex items-center justify-center text-5xl mb-3">
                        <i class="fas fa-wheelchair text-orange-400"></i>
                    </div>
                    <h2 class="text-center text-lg font-black text-slate-800">{{ $lansia->nama_lengkap ?? $lansia->nama ?? 'Nama Lansia' }}</h2>
                    <span class="mt-1 px-3 py-1 bg-orange-100 text-orange-700 text-[10px] font-black uppercase tracking-wider rounded-lg border border-orange-200">
                        USIA: {{ $stats['usia'] ?? '-' }} Tahun
                    </span>
                </div>
                
                <div class="mt-8 space-y-4">
                    <div class="pb-3 border-b border-slate-50 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIK</span>
                        <span class="text-sm font-bold text-slate-800">{{ $lansia->nik ?? '-' }}</span>
                    </div>
                    <div class="pb-3 border-b border-slate-50 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis Kelamin</span>
                        <span class="text-sm font-bold text-slate-800">{{ ($lansia->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="pb-3 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Kunjungan</span>
                        <span class="text-sm font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">{{ $stats['total_kunjungan'] ?? 0 }} Kali</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-amber-500 to-orange-400 rounded-3xl p-6 text-white shadow-md">
                <h3 class="text-base font-black mb-1"><i class="fas fa-running mr-2"></i>Tetap Aktif & Sehat</h3>
                <p class="text-xs font-medium text-amber-50 leading-relaxed opacity-90">Kurangi konsumsi garam dan gula berlebih. Rutin berjalan kaki 30 menit sehari sangat baik untuk menjaga tekanan darah lansia.</p>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">
            
            @if(isset($pemeriksaanTerakhir) && $pemeriksaanTerakhir)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest"><i class="fas fa-notes-medical text-orange-500 mr-2"></i> Pemeriksaan Terakhir</h2>
                            <p class="text-xs font-medium text-slate-400 mt-1">Tanggal: {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                        </div>
                        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-lg border border-emerald-100">
                            <i class="fas fa-check-circle"></i> Tervalidasi Bidan
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 text-center">
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-2">Tekanan Darah</p>
                            <h3 class="text-xl font-black text-rose-600">{{ $pemeriksaanTerakhir->tekanan_darah ?? '-' }}</h3>
                        </div>
                        <div class="bg-sky-50 rounded-2xl p-4 border border-sky-100 text-center">
                            <p class="text-[10px] font-black text-sky-500 uppercase tracking-widest mb-2">Gula Darah</p>
                            <h3 class="text-xl font-black text-sky-600">{{ $pemeriksaanTerakhir->gula_darah ?? '-' }} <span class="text-[10px] text-sky-400 font-medium">mg/dL</span></h3>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Berat</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $pemeriksaanTerakhir->berat_badan ?? '-' }}<span class="text-xs text-slate-500 ml-1">kg</span></h3>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Lingkar Perut</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $pemeriksaanTerakhir->lingkar_perut ?? '-' }}<span class="text-xs text-slate-500 ml-1">cm</span></h3>
                        </div>
                    </div>

                    @if($pemeriksaanTerakhir->keterangan)
                        <div class="mt-6 p-4 bg-orange-50/50 rounded-xl border border-orange-100">
                            <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-1">Catatan Medis</p>
                            <p class="text-xs font-bold text-slate-700 leading-relaxed italic">"{{ $pemeriksaanTerakhir->keterangan }}"</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8">
                <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest mb-6"><i class="fas fa-history text-slate-400 mr-2"></i> Rekam Medis Berkala</h2>
                
                <div class="overflow-x-auto custom-scrollbar">
                    @if(isset($riwayatPemeriksaan) && $riwayatPemeriksaan->isNotEmpty())
                        <table class="w-full text-left min-w-[500px]">
                            <thead>
                                <tr>
                                    <th class="py-3 px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100">Tanggal</th>
                                    <th class="py-3 px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">Tensi</th>
                                    <th class="py-3 px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">Gula Darah</th>
                                    <th class="py-3 px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">Kemandirian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPemeriksaan as $riwayat)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 px-3 border-b border-slate-50 text-sm font-bold text-slate-800">
                                            {{ \Carbon\Carbon::parse($riwayat->tanggal_periksa)->format('d M Y') }}
                                        </td>
                                        <td class="py-4 px-3 border-b border-slate-50 text-center">
                                            <span class="px-2 py-1 bg-rose-50 text-rose-600 text-xs font-bold rounded">{{ $riwayat->tekanan_darah ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 px-3 border-b border-slate-50 text-center">
                                            <span class="px-2 py-1 bg-sky-50 text-sky-600 text-xs font-bold rounded">{{ $riwayat->gula_darah ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 px-3 border-b border-slate-50 text-center">
                                            <span class="text-xs font-bold text-slate-500 uppercase">{{ $riwayat->tingkat_kemandirian ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-10 text-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Belum ada rekam medis lansia</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection