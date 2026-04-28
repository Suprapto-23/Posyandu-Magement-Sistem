@extends('layouts.kader')

@section('title', 'Detail Profil Anak')
@section('page-name', 'Buku Medis Anak')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .glass-card { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); }
    
    /* Desain Tabel CRM Terpadu */
    .crm-table th { background: #f8fafc; color: #64748b; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem 1rem; border-bottom: 1px solid #e2e8f0; }
    .crm-table th:first-child { border-top-left-radius: 20px; }
    .crm-table th:last-child { border-top-right-radius: 20px; }
    .crm-table td { padding: 1rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease; }
    .crm-table tr:hover td { background-color: #f8fafc; }
    .crm-table tr:last-child td { border-bottom: none; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-folder-open text-indigo-600 text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-indigo-900 font-black tracking-widest text-[11px] animate-pulse uppercase">MEMBUKA BUKU KIA...</p>
</div>

<div class="max-w-7xl mx-auto relative pb-12 z-10">
    
    {{-- AURA BACKGROUND --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.data.balita.index') }}" onclick="showLoader()" class="w-14 h-14 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-[0_4px_15px_rgba(0,0,0,0.02)] group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-lg"></i>
            </a>
            <div>
                <div class="inline-flex items-center gap-1.5 text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100"><i class="fas fa-book-medical"></i> Buku KIA Digital</div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Profil Pasien Anak</h1>
            </div>
        </div>
        <a href="{{ route('kader.data.balita.edit', $balita->id) }}" onclick="showLoader()" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-slate-700 border border-slate-200 font-black text-[11px] rounded-full hover:bg-amber-500 hover:text-white hover:border-amber-500 shadow-sm transition-all hover:-translate-y-1 uppercase tracking-widest w-full md:w-auto">
            <i class="fas fa-pen-nib"></i> Edit Master Data
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- ========================================== --}}
        {{-- PANEL KIRI: PROFIL IDENTITAS (4 KOLOM)     --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-4 space-y-6 animate-slide-up">
            <div class="glass-card rounded-[32px] overflow-hidden sticky top-24 border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] flex flex-col">
                
                {{-- KARTU NAMA & AVATAR --}}
                <div class="bg-gradient-to-br from-indigo-600 to-violet-600 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-24 h-24 mx-auto bg-white rounded-[24px] shadow-2xl flex items-center justify-center text-indigo-500 text-4xl font-black relative z-10 border-4 border-white/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        {{ strtoupper(substr($balita->nama_lengkap, 0, 1)) }}
                    </div>
                    
                    <h2 class="text-xl font-black text-white mt-5 relative z-10 font-poppins tracking-tight leading-tight">{{ $balita->nama_lengkap }}</h2>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-black/20 text-indigo-50 text-[10px] font-black rounded-full mt-3 relative z-10 backdrop-blur-md border border-white/10 tracking-widest uppercase">
                        <i class="fas fa-fingerprint text-indigo-300"></i> NIK: {{ $balita->nik ?? 'TIDAK ADA' }}
                    </div>
                </div>

                {{-- INFORMASI DASAR --}}
                <div class="p-6 bg-white/60 flex-1 flex flex-col">
                    @php
                        $lahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
                        $sekarang = \Carbon\Carbon::now();
                        $diffM = (int) $lahir->diffInMonths($sekarang);
                        if ($diffM < 12) {
                            $diffD = (int) $lahir->diffInDays($sekarang);
                            $umurCetak = $diffM < 1 ? $diffD . ' Hari' : $diffM . ' Bulan';
                            $kategoriLabel = 'Bayi';
                            $kategoriColor = 'text-sky-500';
                            $kategoriBg = 'bg-sky-50';
                            $kategoriBorder = 'border-sky-100';
                        } else {
                            $t = floor($diffM / 12);
                            $b = $diffM % 12;
                            $umurCetak = $b > 0 ? "{$t} Thn {$b} Bln" : "{$t} Tahun";
                            $kategoriLabel = 'Balita';
                            $kategoriColor = 'text-rose-500';
                            $kategoriBg = 'bg-rose-50';
                            $kategoriBorder = 'border-rose-100';
                        }
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                        <div class="p-4 bg-white rounded-[20px] border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Usia Fisik</p>
                            <p class="text-lg font-black text-slate-800 font-poppins">{{ $umurCetak }}</p>
                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $kategoriBg }} {{ $kategoriColor }} {{ $kategoriBorder }} border mt-1">{{ $kategoriLabel }}</span>
                        </div>
                        <div class="p-4 bg-white rounded-[20px] border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Gender</p>
                            <div class="flex flex-col items-center justify-center h-full">
                                @if($balita->jenis_kelamin == 'L') 
                                    <div class="w-8 h-8 rounded-full bg-sky-50 border border-sky-100 text-sky-500 flex items-center justify-center text-sm mb-1"><i class="fas fa-mars"></i></div>
                                    <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Laki-Laki</p>
                                @else 
                                    <div class="w-8 h-8 rounded-full bg-rose-50 border border-rose-100 text-rose-500 flex items-center justify-center text-sm mb-1"><i class="fas fa-venus"></i></div>
                                    <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Perempuan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-4 p-4 rounded-[20px] bg-slate-50/50 border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0"><i class="fas fa-calendar-alt"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Tanggal Lahir</span>
                                <span class="text-[12px] font-bold text-slate-800">{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 rounded-[20px] bg-slate-50/50 border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0"><i class="fas fa-weight"></i></div>
                            <div class="flex-1 flex justify-between items-center">
                                <div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Berat Lahir</span>
                                    <span class="text-[12px] font-bold text-slate-800">{{ $balita->berat_lahir ?? '-' }} <span class="text-[9px] text-slate-400">kg</span></span>
                                </div>
                                <div class="w-px h-6 bg-slate-200 mx-2"></div>
                                <div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Panjang Lahir</span>
                                    <span class="text-[12px] font-bold text-slate-800">{{ $balita->panjang_lahir ?? '-' }} <span class="text-[9px] text-slate-400">cm</span></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 rounded-[20px] bg-rose-50/30 border border-rose-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-white text-rose-500 flex items-center justify-center shrink-0 border border-rose-100"><i class="fas fa-female"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest block mb-0.5">Nama Ibu (Akses)</span>
                                <span class="text-[12px] font-black text-rose-800 block mb-0.5">{{ $balita->nama_ibu }}</span>
                                <span class="text-[9px] font-bold text-rose-500 font-mono tracking-wide"><i class="fas fa-fingerprint"></i> {{ $balita->nik_ibu }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto border-t border-slate-200/60 pt-6">
                        @if($userTerhubung)
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-[20px] flex items-center gap-4 shadow-sm">
                                <div class="w-10 h-10 bg-white text-emerald-500 rounded-full flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-check-circle text-lg"></i></div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Aplikasi Terhubung</p>
                                    <p class="text-[12px] font-bold text-emerald-800 truncate max-w-[150px]">{{ $userTerhubung->name }}</p>
                                </div>
                            </div>
                        @else
                            {{-- BUG FIX: Menggunakan form agar sinkronisasi berjalan normal tanpa error GET --}}
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-[20px] text-center shadow-sm">
                                <div class="w-10 h-10 bg-white text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm"><i class="fas fa-link-slash text-sm"></i></div>
                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-3">Akun Ibu Belum Terhubung</p>
                                <form action="{{ route('kader.data.balita.sync', $balita->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Pindai ulang dan sambungkan akun dengan NIK Ibu?')" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-slate-300 shadow-sm text-indigo-600 text-[10px] font-black rounded-full hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-colors w-full uppercase tracking-widest">
                                        <i class="fas fa-sync-alt mr-2"></i> Pindai NIK Ibu
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- PANEL KANAN: REKAM MEDIS & LAYANAN (8 COL) --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-8 flex flex-col gap-6">
            
            {{-- WIDGET STATISTIK UNTUK MENGISI RUANG KOSONG --}}
            @php
                $riwayatImunisasi = collect();
                $totalVaksin = 0;
                $kunjunganTerakhir = null;
                
                if($balita->kunjungans) {
                    $riwayatImunisasi = $balita->kunjungans->where('jenis_kunjungan', 'imunisasi');
                    foreach($riwayatImunisasi as $kjg) {
                        $totalVaksin += count($kjg->imunisasis ?? []);
                    }
                    $kunjunganTerakhir = $balita->kunjungans->first();
                }
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-slide-up-delay-1">
                <div class="glass-card bg-white p-5 rounded-[24px] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[14px] bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Layanan</p>
                        <p class="text-xl font-black text-slate-800 font-poppins">{{ count($balita->kunjungans) }} <span class="text-xs font-bold text-slate-500 font-sans">Kali</span></p>
                    </div>
                </div>
                
                <div class="glass-card bg-white p-5 rounded-[24px] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[14px] bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-shield-virus"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Vaksin Diterima</p>
                        <p class="text-xl font-black text-slate-800 font-poppins">{{ $totalVaksin }} <span class="text-xs font-bold text-slate-500 font-sans">Dosis</span></p>
                    </div>
                </div>
                
                <div class="glass-card bg-white p-5 rounded-[24px] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-[14px] bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-history"></i></div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Update Terakhir</p>
                        @if($kunjunganTerakhir)
                            <p class="text-[12px] font-black text-slate-800 font-poppins">{{ \Carbon\Carbon::parse($kunjunganTerakhir->tanggal_kunjungan)->format('d M Y') }}</p>
                        @else
                            <p class="text-[12px] font-bold text-slate-400 italic">Belum ada</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT KUNJUNGAN --}}
            <div class="glass-card rounded-[32px] overflow-hidden border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-slide-up-delay-2 flex-1 flex flex-col">
                <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-white text-indigo-600 flex items-center justify-center text-2xl shadow-sm border border-slate-200"><i class="fas fa-stethoscope"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 font-poppins">Riwayat Medis & Antropometri</h3>
                            <p class="text-[12px] font-medium text-slate-500 mt-1">Log timbangan dan pelayanan Posyandu</p>
                        </div>
                    </div>
                    <a href="{{ route('kader.pemeriksaan.create') }}?kategori=balita&pasien_id={{ $balita->id }}" onclick="showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-black text-[11px] rounded-full hover:bg-indigo-700 shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-all shrink-0 uppercase tracking-widest hover:-translate-y-1 w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Input Kunjungan
                    </a>
                </div>

                <div class="p-4 flex-1">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left whitespace-nowrap min-w-[700px] crm-table">
                            <thead>
                                <tr>
                                    <th class="pl-6">Waktu Kunjungan</th>
                                    <th>Layanan</th>
                                    <th>Hasil Fisik</th>
                                    <th class="text-right pr-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($balita->kunjungans ?? [] as $kunjungan)
                                <tr>
                                    <td class="pl-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-[13px] mb-1">{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}</span>
                                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400 bg-white border border-slate-100 w-max px-2 py-0.5 rounded shadow-sm"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($kunjungan->jenis_kunjungan == 'imunisasi')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-teal-50 text-teal-700 text-[9px] font-black border border-teal-100 uppercase tracking-widest"><i class="fas fa-syringe"></i> Imunisasi</span>
                                        @elseif($kunjungan->jenis_kunjungan == 'pemeriksaan')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-[9px] font-black border border-indigo-100 uppercase tracking-widest"><i class="fas fa-balance-scale"></i> Pemeriksaan Fisik</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-[9px] font-black border border-slate-200 uppercase tracking-widest"><i class="fas fa-user-md"></i> Kunjungan Umum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kunjungan->pemeriksaan)
                                            <div class="flex items-center gap-3">
                                                <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">BB</span>
                                                    <span class="text-[12px] font-black text-indigo-600">{{ $kunjungan->pemeriksaan->berat_badan ?? '-' }}<span class="text-[9px] text-slate-400">kg</span></span>
                                                </div>
                                                <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">TB</span>
                                                    <span class="text-[12px] font-black text-emerald-600">{{ $kunjungan->pemeriksaan->tinggi_badan ?? '-' }}<span class="text-[9px] text-slate-400">cm</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 italic">Tanpa antropometri</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-6">
                                        <a href="{{ route('kader.kunjungan.show', $kunjungan->id) }}" onclick="showLoader()" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center border-none">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-2xl border border-slate-100 shadow-inner"><i class="fas fa-folder-open"></i></div>
                                        <h4 class="font-black text-slate-800 text-lg font-poppins tracking-tight">Data Masih Kosong</h4>
                                        <p class="text-[12px] font-medium text-slate-500 mt-1 max-w-sm mx-auto">Silakan tambahkan data kunjungan baru untuk mengisi riwayat log medis anak ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PANEL IMUNISASI --}}
            @if($riwayatImunisasi->count() > 0)
            <div class="glass-card rounded-[32px] overflow-hidden border border-teal-100 shadow-[0_8px_30px_rgb(20,184,166,0.05)] animate-slide-up-delay-2">
                <div class="p-6 md:p-8 border-b border-teal-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-teal-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-white text-teal-500 flex items-center justify-center text-2xl shadow-sm border border-teal-100"><i class="fas fa-shield-virus"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-teal-900 font-poppins">Sertifikat Imunisasi</h3>
                            <p class="text-[12px] font-bold text-teal-600 mt-0.5">Daftar log vaksin yang telah diberikan</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($riwayatImunisasi as $kjg)
                            @foreach($kjg->imunisasis ?? [] as $imunisasi)
                            <div class="flex items-center justify-between p-5 bg-white rounded-[20px] border border-slate-100 shadow-sm hover:border-teal-300 hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-teal-500 font-black text-lg group-hover:bg-teal-50 transition-colors">
                                        <i class="fas fa-syringe transform -rotate-45"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 text-[13px] mb-1">{{ $imunisasi->vaksin }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $imunisasi->jenis_imunisasi }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 bg-teal-50 border border-teal-100 text-teal-700 text-[9px] font-black rounded-full mb-1.5 uppercase tracking-widest">Dosis {{ $imunisasi->dosis }}</span>
                                    <p class="text-[10px] font-bold text-slate-500"><i class="far fa-calendar-check text-slate-400 mr-1"></i> {{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
    // System Loader Nexus
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.classList.remove('opacity-100', 'pointer-events-auto'); 
            l.classList.add('opacity-0', 'pointer-events-none'); 
            setTimeout(() => l.style.display = 'none', 300); 
        }
    };

    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.style.display = 'flex'; 
            l.classList.remove('opacity-0', 'pointer-events-none'); 
            l.classList.add('opacity-100', 'pointer-events-auto'); 
        }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    
    // Failsafe darurat
    setTimeout(window.hideLoader, 2500); 
</script>
@endpush
@endsection