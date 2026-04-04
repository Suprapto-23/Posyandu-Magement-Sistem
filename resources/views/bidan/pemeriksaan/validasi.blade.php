@extends('layouts.bidan')
@section('title', 'Validasi Klinis')
@section('page-name', 'Rekam Medis (Meja 5)')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .medical-input { width: 100%; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #1e293b; outline: none; transition: all 0.3s; }
    .medical-input:focus { border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1); }
    .kader-data-box { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 16px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
</style>

<div class="max-w-[1300px] mx-auto animate-slide-up pb-8">

    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('bidan.pemeriksaan.index', ['tab'=>'pending']) }}" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrian
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        {{-- PANEL KIRI: DATA FISIK (HASIL KADER) --}}
        <div class="xl:col-span-4 flex flex-col gap-6">
            <div class="bg-white rounded-[24px] p-6 lg:p-8 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-[12px] bg-slate-100 text-slate-500 flex items-center justify-center text-lg border border-slate-200"><i class="fas fa-clipboard-check"></i></div>
                    <div>
                        <h3 class="text-[15px] font-black text-slate-800 font-poppins leading-none">Pengukuran Fisik</h3>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Input Oleh Kader (Meja 2-3)</p>
                    </div>
                </div>

                @php
                    $namaPasien = $pemeriksaan->balita->nama_lengkap ?? $pemeriksaan->remaja->nama_lengkap ?? $pemeriksaan->lansia->nama_lengkap ?? 'Ibu Hamil';
                @endphp
                <div class="mb-6 text-center">
                    <div class="w-20 h-20 mx-auto rounded-[16px] bg-cyan-50 text-cyan-500 flex items-center justify-center text-3xl mb-3 shadow-inner border border-cyan-100">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-800 font-poppins leading-tight px-2">{{ $namaPasien }}</h2>
                    <p class="text-[11px] font-bold text-cyan-600 uppercase tracking-widest mt-1 bg-cyan-50 border border-cyan-100 inline-block px-3 py-1 rounded-md">{{ $pemeriksaan->kategori_pasien }}</p>
                </div>

                <div class="space-y-3 flex-1">
                    <div class="kader-data-box">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider"><i class="fas fa-weight text-slate-300 mr-1.5"></i> Berat Badan</span>
                        <span class="text-[14px] font-black text-slate-800">{{ $pemeriksaan->berat_badan ?? '-' }} <span class="text-[10px] text-slate-400">kg</span></span>
                    </div>
                    <div class="kader-data-box">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider"><i class="fas fa-ruler-vertical text-slate-300 mr-1.5"></i> Tinggi Badan</span>
                        <span class="text-[14px] font-black text-slate-800">{{ $pemeriksaan->tinggi_badan ?? '-' }} <span class="text-[10px] text-slate-400">cm</span></span>
                    </div>
                    @if($pemeriksaan->lingkar_lengan)
                    <div class="kader-data-box">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider"><i class="fas fa-tape text-slate-300 mr-1.5"></i> LiLA</span>
                        <span class="text-[14px] font-black text-slate-800">{{ $pemeriksaan->lingkar_lengan }} <span class="text-[10px] text-slate-400">cm</span></span>
                    </div>
                    @endif
                    @if($pemeriksaan->tekanan_darah)
                    <div class="kader-data-box">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider"><i class="fas fa-heartbeat text-rose-300 mr-1.5"></i> Tekanan Darah</span>
                        <span class="text-[14px] font-black text-rose-600">{{ $pemeriksaan->tekanan_darah }} <span class="text-[10px] text-rose-300">mmHg</span></span>
                    </div>
                    @endif
                    @if($pemeriksaan->status_gizi)
                    <div class="mt-4 p-4 rounded-xl {{ str_contains(strtolower($pemeriksaan->status_gizi), 'baik') || str_contains(strtolower($pemeriksaan->status_gizi), 'normal') ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }} text-center">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Status Gizi (Kalkulasi Sistem)</p>
                        <p class="text-[15px] font-black {{ str_contains(strtolower($pemeriksaan->status_gizi), 'baik') || str_contains(strtolower($pemeriksaan->status_gizi), 'normal') ? 'text-emerald-600' : 'text-rose-600' }} uppercase">{{ $pemeriksaan->status_gizi }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: FORM MEDIS BIDAN --}}
        <div class="xl:col-span-8 bg-white rounded-[24px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden flex flex-col">
            
            <div class="px-6 lg:px-8 py-6 border-b border-slate-100 bg-gradient-to-r from-cyan-50 to-white flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-cyan-600 text-white flex items-center justify-center text-xl shadow-[0_4px_12px_rgba(6,182,212,0.3)]"><i class="fas fa-user-md"></i></div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 font-poppins leading-none">Formulir Validasi Medis</h2>
                    <p class="text-[11px] font-bold text-slate-400 mt-1.5 uppercase tracking-widest">Otoritas Diagnosa Meja 5</p>
                </div>
            </div>

            <form action="{{ route('bidan.pemeriksaan.simpan-validasi', $pemeriksaan->id) }}" method="POST" class="flex-1 flex flex-col">
                @csrf @method('PUT')
                
                <div class="p-6 lg:p-8 space-y-6 flex-1 bg-slate-50/30">
                    
                    {{-- DIAGNOSA MEDIS --}}
                    <div>
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-600 uppercase tracking-widest mb-2 pl-1">
                            <i class="fas fa-clipboard-list text-cyan-500"></i> Analisa & Diagnosa <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="diagnosa" rows="3" required placeholder="Tuliskan hasil diagnosa klinis dari pengukuran fisik kader..." class="medical-input resize-none shadow-sm"></textarea>
                    </div>

                    {{-- TINDAKAN & RESEP OBAT --}}
                    <div>
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-600 uppercase tracking-widest mb-2 pl-1">
                            <i class="fas fa-pills text-amber-500"></i> Tindakan / Resep Obat <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="tindakan" rows="2" required placeholder="Contoh: Pemberian Vitamin A (Merah), Rujukan Puskesmas, dll." class="medical-input resize-none shadow-sm"></textarea>
                    </div>

                    {{-- CONDITIONAL: KHUSUS IBU HAMIL --}}
                    @if(in_array($pemeriksaan->kategori_pasien, ['ibu_hamil', 'bumil', 'IbuHamil']))
                    <div class="p-5 rounded-[20px] bg-pink-50/50 border border-pink-100/60 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-5 text-6xl text-pink-500 pointer-events-none"><i class="fas fa-baby-carriage"></i></div>
                        <h4 class="text-[12px] font-black text-pink-600 uppercase tracking-widest mb-4 flex items-center gap-2"><i class="fas fa-female"></i> Form Pemeriksaan Kehamilan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 relative z-10">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 pl-1">TFU (cm)</label>
                                <input type="text" name="tfu" placeholder="Tinggi Fundus" class="medical-input !py-2.5 !text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 pl-1">DJJ (x/menit)</label>
                                <input type="text" name="djj" placeholder="Detak Jantung" class="medical-input !py-2.5 !text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Letak Janin</label>
                                <input type="text" name="posisi_janin" placeholder="Kepala/Sungsang" class="medical-input !py-2.5 !text-sm">
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CATATAN INTERNAL BIDAN --}}
                    <div>
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-600 uppercase tracking-widest mb-2 pl-1">
                            <i class="fas fa-lock text-slate-400"></i> Catatan Internal (Opsional)
                        </label>
                        <textarea name="catatan_bidan" rows="2" placeholder="Catatan pribadi bidan (hanya bisa dilihat oleh pihak medis)." class="medical-input resize-none bg-slate-50 shadow-inner"></textarea>
                    </div>

                </div>

                {{-- FORM FOOTER --}}
                <div class="px-6 lg:px-8 py-5 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fas fa-fingerprint text-cyan-500 text-sm"></i> Jejak Audit tercatat atas nama Anda
                    </p>
                    <button type="submit" onclick="document.getElementById('globalLoader').style.display='flex'; document.getElementById('globalLoader').classList.remove('opacity-0');" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-black text-white bg-cyan-600 hover:bg-cyan-700 shadow-[0_4px_15px_rgba(6,182,212,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-wide flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Validasi & Simpan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection