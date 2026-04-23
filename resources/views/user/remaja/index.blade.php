@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-50 rounded-full shadow-sm border border-indigo-100 mb-4">
                <i class="fas fa-user-graduate text-indigo-500"></i>
                <span class="text-[11px] font-black tracking-widest uppercase text-indigo-700">Pemantauan Remaja</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Kesehatan Remaja 🎓</h1>
            <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Pantau perkembangan fisik, status gizi, dan cegah anemia sejak dini bersama Bidan Posyandu.</p>
        </div>
        
        <div class="shrink-0">
            <a href="{{ route('user.konseling.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                <i class="fas fa-comments-medical"></i> Chat Bidan Sekarang
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-violet-400"></div>
                
                <div class="relative z-10 flex flex-col items-center mt-6">
                    <div class="w-24 h-24 bg-white rounded-full border-4 border-white shadow-md flex items-center justify-center text-5xl mb-3">
                        <i class="fas fa-user-graduate text-indigo-400"></i>
                    </div>
                    <h2 class="text-center text-lg font-black text-slate-800">{{ $remaja->nama_lengkap ?? $remaja->nama ?? 'Nama Remaja' }}</h2>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $remaja->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                
                <div class="mt-8 space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">NIK</span>
                        <span class="text-sm font-bold text-slate-800">{{ $remaja->nik ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tgl Lahir</span>
                        <span class="text-sm font-bold text-slate-800">{{ $remaja->tanggal_lahir ? \Carbon\Carbon::parse($remaja->tanggal_lahir)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Usia</span>
                        <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                            {{ $remaja->tanggal_lahir ? \Carbon\Carbon::parse($remaja->tanggal_lahir)->age . ' Tahun' : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-violet-500 to-indigo-400 rounded-3xl p-6 text-white shadow-md relative overflow-hidden">
                <i class="fas fa-apple-alt absolute -right-4 -bottom-4 text-6xl text-white opacity-20"></i>
                <div class="relative z-10">
                    <h3 class="text-base font-black mb-1">Cegah Anemia!</h3>
                    <p class="text-xs font-medium text-indigo-50 leading-relaxed opacity-90 mb-4">Konsumsi makanan bergizi dan minum Tablet Tambah Darah (khusus remaja putri) untuk menjaga konsentrasi belajar.</p>
                </div>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">
            
            @if($pemeriksaanTerakhir)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest"><i class="fas fa-clipboard-check text-indigo-500 mr-2"></i> Kondisi Fisik Terkini</h2>
                            <p class="text-xs font-medium text-slate-400 mt-1">Divalidasi Bidan pada: {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                        </div>
                        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Sah
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Berat</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $pemeriksaanTerakhir->berat_badan ?? '-' }}<span class="text-xs text-slate-500 ml-1">kg</span></h3>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tinggi</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $pemeriksaanTerakhir->tinggi_badan ?? '-' }}<span class="text-xs text-slate-500 ml-1">cm</span></h3>
                        </div>
                        <div class="bg-violet-50 rounded-2xl p-4 border border-violet-100 text-center">
                            <p class="text-[10px] font-black text-violet-400 uppercase tracking-widest mb-2">Tensi</p>
                            <h3 class="text-xl font-black text-violet-700">{{ $pemeriksaanTerakhir->tekanan_darah ?? '-' }}</h3>
                        </div>
                        <div class="bg-rose-50 rounded-2xl p-4 border border-rose-100 text-center">
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-2">Hemoglobin</p>
                            <h3 class="text-xl font-black text-rose-600">{{ $pemeriksaanTerakhir->hemoglobin ?? $pemeriksaanTerakhir->hb ?? '-' }}</h3>
                        </div>
                    </div>

                    @if($pemeriksaanTerakhir->keterangan)
                        <div class="mt-6 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Catatan Bidan</p>
                            <p class="text-xs font-bold text-slate-700 leading-relaxed italic">"{{ $pemeriksaanTerakhir->keterangan }}"</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8">
                <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest mb-6"><i class="fas fa-history text-slate-400 mr-2"></i> Riwayat Pemeriksaan</h2>
                
                <div class="overflow-x-auto custom-scrollbar">
                    @if(isset($riwayatPemeriksaan) && $riwayatPemeriksaan->isNotEmpty())
                        <table class="w-full text-left min-w-[500px]">
                            <thead>
                                <tr>
                                    <th class="py-3 px-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100">Tanggal</th>
                                    <th class="py-3 px-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">BB/TB</th>
                                    <th class="py-3 px-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">Tensi</th>
                                    <th class="py-3 px-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100 text-center">HB</th>
                                    <th class="py-3 px-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100">Status Gizi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPemeriksaan as $riwayat)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 px-2 border-b border-slate-50 text-sm font-bold text-slate-700">
                                            {{ \Carbon\Carbon::parse($riwayat->tanggal_periksa)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-4 px-2 border-b border-slate-50 text-center text-xs font-bold text-slate-600">
                                            {{ $riwayat->berat_badan ?? '-' }}kg / {{ $riwayat->tinggi_badan ?? '-' }}cm
                                        </td>
                                        <td class="py-4 px-2 border-b border-slate-50 text-center text-xs font-bold text-slate-600">
                                            {{ $riwayat->tekanan_darah ?? '-' }}
                                        </td>
                                        <td class="py-4 px-2 border-b border-slate-50 text-center text-xs font-bold text-rose-500">
                                            {{ $riwayat->hemoglobin ?? $riwayat->hb ?? '-' }}
                                        </td>
                                        <td class="py-4 px-2 border-b border-slate-50">
                                            @if($riwayat->status_gizi)
                                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded">{{ $riwayat->status_gizi }}</span>
                                            @else
                                                -
                                            @endif
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
                            <p class="text-sm font-bold text-slate-600">Belum ada riwayat tervalidasi</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection