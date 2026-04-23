@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="mb-8">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-pink-50 rounded-full shadow-sm border border-pink-100 mb-4">
            <i class="fas fa-heart text-pink-500 animate-pulse"></i>
            <span class="text-[11px] font-black tracking-widest uppercase text-pink-700">Buku KIA Digital</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Kesehatan Kandungan Ibu 🤰</h1>
        <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Pantau perkembangan janin, usia kehamilan, dan riwayat pemeriksaan rutin Anda bersama Bidan Posyandu.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <div class="bg-gradient-to-br from-pink-500 to-rose-400 rounded-3xl p-5 text-white shadow-md relative overflow-hidden">
            <i class="fas fa-baby absolute -right-4 -bottom-4 text-6xl text-white opacity-20"></i>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-widest text-pink-100 mb-1">Usia Kehamilan</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black">{{ $usiaHamilMinggu ?? '-' }}</span>
                    <span class="text-sm font-bold text-pink-100">Minggu</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Perkiraan Lahir (HPL)</p>
            <h3 class="text-lg font-black text-slate-800 mt-1">
                {{ $ibuHamil->hpl ? \Carbon\Carbon::parse($ibuHamil->hpl)->translatedFormat('d F Y') : 'Belum Ditentukan' }}
            </h3>
            <p class="text-[10px] font-bold text-sky-500 mt-2 bg-sky-50 inline-block px-2 py-0.5 rounded">Tandai Kalender</p>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Hari Pertama Haid (HPHT)</p>
            <h3 class="text-lg font-black text-slate-800 mt-1">
                {{ $ibuHamil->hpht ? \Carbon\Carbon::parse($ibuHamil->hpht)->translatedFormat('d M Y') : '-' }}
            </h3>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Dasar perhitungan usia</p>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Status Kehamilan</p>
            <div class="mt-1">
                @if(strtolower($ibuHamil->status ?? 'aktif') == 'aktif')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-black uppercase tracking-wider rounded-lg">AKTIF</span>
                @else
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-sm font-black uppercase tracking-wider rounded-lg">SELESAI</span>
                @endif
            </div>
            <p class="text-[10px] font-bold text-slate-400 mt-2">Dipantau oleh Posyandu</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8">
                <div class="w-16 h-16 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fas fa-id-card-alt"></i>
                </div>
                <h2 class="text-center text-lg font-black text-slate-800 mb-6">Data Identitas Ibu</h2>
                
                <div class="space-y-4">
                    <div class="pb-3 border-b border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                        <p class="text-sm font-bold text-slate-800">{{ $ibuHamil->nama_lengkap ?? $user->name }}</p>
                    </div>
                    <div class="pb-3 border-b border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Induk Kependudukan (NIK)</p>
                        <p class="text-sm font-bold text-slate-800">{{ $ibuHamil->nik }}</p>
                    </div>
                    <div class="pb-3 border-b border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Suami</p>
                        <p class="text-sm font-bold text-slate-800">{{ $ibuHamil->nama_suami ?? '-' }}</p>
                    </div>
                    <div class="pb-3 border-b border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Riwayat G-P-A (Gravida-Partus-Abortus)</p>
                        <p class="text-sm font-bold text-slate-800">
                            @if(isset($ibuHamil->gravida))
                                G: {{ $ibuHamil->gravida }} | P: {{ $ibuHamil->partus ?? 0 }} | A: {{ $ibuHamil->abortus ?? 0 }}
                            @else
                                <span class="text-slate-400 text-xs italic">Data G-P-A belum lengkap</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="text-[11px] font-medium text-amber-700 leading-relaxed"><i class="fas fa-info-circle mr-1"></i> Jika ada kesalahan pada data identitas atau HPHT, segera laporkan kepada Kader Posyandu saat kunjungan berikutnya.</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 h-full flex flex-col">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest">Riwayat Pemeriksaan (Bidan)</h2>
                    </div>
                </div>

                <div class="flex-1">
                    @forelse($riwayatPemeriksaan as $periksa)
                        <div class="mb-5 last:mb-0 relative pl-6 md:pl-8 py-2">
                            <div class="absolute left-0 top-0 bottom-0 w-px bg-slate-100"></div>
                            <div class="absolute left-[-4px] top-4 w-2 h-2 rounded-full bg-pink-400 ring-4 ring-white"></div>
                            
                            <div class="bg-slate-50 hover:bg-pink-50/50 border border-slate-100 hover:border-pink-100 rounded-2xl p-5 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                                    <div>
                                        <h4 class="text-sm font-black text-slate-800">{{ \Carbon\Carbon::parse($periksa->tanggal_periksa)->translatedFormat('l, d F Y') }}</h4>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5">Pemeriksaan Rutin</p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-emerald-100 self-start">
                                        <i class="fas fa-check-circle"></i> Terverifikasi Bidan
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Berat Badan</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $periksa->berat_badan ?? '-' }} <span class="text-[10px] text-slate-400">kg</span></p>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Tekanan Darah</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $periksa->tekanan_darah ?? '-' }}</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">TFU (Tinggi Fundus)</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $periksa->tfu ?? '-' }} <span class="text-[10px] text-slate-400">cm</span></p>
                                    </div>
                                    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Detak Jantung Janin</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $periksa->djj ?? '-' }} <span class="text-[10px] text-slate-400">bpm</span></p>
                                    </div>
                                </div>
                                
                                @if(!empty($periksa->keterangan))
                                    <div class="mt-3 pt-3 border-t border-slate-100">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1"><i class="fas fa-comment-medical mr-1"></i> Catatan Bidan:</p>
                                        <p class="text-xs font-medium text-slate-700 leading-relaxed italic">"{{ $periksa->keterangan }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white text-slate-300 rounded-full flex items-center justify-center text-2xl shadow-sm mb-4">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-700">Belum Ada Riwayat</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1 max-w-sm leading-relaxed">Anda belum memiliki riwayat pemeriksaan kandungan yang divalidasi oleh Bidan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection