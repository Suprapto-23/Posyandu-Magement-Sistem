@extends('layouts.bidan')

@section('title', 'Validasi Medis & Diagnosa')
@section('page-name', 'Workspace Diagnosis')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Styling Custom Form Bidan */
    .med-input { 
        width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 18px; color: #0f172a; font-weight: 600; font-size: 14px; 
        transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .med-input:focus { background: #ffffff; border-color: #06b6d4; outline: none; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    
    /* Radio Button Custom untuk Status Validasi */
    .status-radio:checked + div { background-color: #ecfeff; border-color: #06b6d4; transform: translateY(-2px); box-shadow: 0 8px 20px -6px rgba(6,182,212,0.25); }
    .status-radio:checked + div .icon-box { background-color: #06b6d4; color: white; }
    .status-radio-reject:checked + div { background-color: #fff1f2; border-color: #f43f5e; transform: translateY(-2px); box-shadow: 0 8px 20px -6px rgba(244,63,94,0.25); }
    .status-radio-reject:checked + div .icon-box { background-color: #f43f5e; color: white; }
</style>
@endpush

@section('content')

@php
    // Deteksi Kategori Pasien Secara Cerdas (Unified Logic)
    $namaPasien = $pemeriksaan->balita->nama_lengkap ?? $pemeriksaan->remaja->nama_lengkap ?? $pemeriksaan->lansia->nama_lengkap ?? $pemeriksaan->ibuHamil->nama_lengkap ?? 'Pasien Anonim';
    $kategoriRaw = strtolower(class_basename($pemeriksaan->kategori_pasien ?? $pemeriksaan->pasien_type));
    
    if($kategoriRaw == 'balita') { $nCol = 'sky'; $nIco = 'baby'; $kategori = 'Balita'; }
    elseif($kategoriRaw == 'remaja') { $nCol = 'indigo'; $nIco = 'user-graduate'; $kategori = 'Remaja'; }
    elseif(in_array($kategoriRaw, ['ibu_hamil','ibuhamil','bumil'])) { $nCol = 'pink'; $nIco = 'female'; $kategori = 'Ibu Hamil'; }
    else { $nCol = 'emerald'; $nIco = 'user-clock'; $kategori = 'Lansia'; }

    $isVerified = $pemeriksaan->status_verifikasi === 'verified';
@endphp

<div class="max-w-5xl mx-auto space-y-6 animate-slide-up pb-10">

    {{-- Tombol Kembali --}}
    <a href="{{ route('bidan.pemeriksaan.index') }}" class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-400 hover:text-cyan-600 transition-colors">
        <i class="fas fa-arrow-left"></i> Kembali ke Antrian
    </a>

    {{-- ================================================================
         1. IDENTITAS PASIEN & HASIL UKUR KADER (Read-Only)
         ================================================================ --}}
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        
        {{-- Header Identitas --}}
        <div class="p-6 md:p-8 bg-gradient-to-br from-{{$nCol}}-50 to-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border-b border-slate-100 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-{{$nCol}}-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-20 h-20 rounded-[20px] bg-white border-2 border-{{$nCol}}-100 text-{{$nCol}}-500 flex items-center justify-center text-4xl shrink-0 shadow-lg shadow-{{$nCol}}-200/40">
                    <i class="fas fa-{{$nIco}}"></i>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 bg-{{$nCol}}-100 text-{{$nCol}}-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2 shadow-sm">{{ $kategori }}</span>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none mb-1">{{ $namaPasien }}</h2>
                    <p class="text-[12px] font-medium text-slate-500 flex items-center gap-2">
                        <i class="far fa-calendar-check text-slate-400"></i> Diukur oleh Kader Meja 2
                    </p>
                </div>
            </div>

            {{-- Status Verifikasi Badge --}}
            <div class="relative z-10 w-full md:w-auto">
                @if($isVerified)
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 px-4 py-3 rounded-2xl">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center text-lg"><i class="fas fa-check"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Status Pemeriksaan</p>
                            <p class="text-[14px] font-bold text-slate-700">Telah Divalidasi</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 bg-rose-50 border border-rose-100 px-4 py-3 rounded-2xl">
                        <div class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center text-lg animate-pulse"><i class="fas fa-exclamation"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Status Pemeriksaan</p>
                            <p class="text-[14px] font-bold text-slate-700">Menunggu Validasi Medis</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Grid Data Fisik dari Kader --}}
        <div class="p-6 md:p-8">
            <h3 class="text-[14px] font-black text-slate-800 font-poppins mb-5 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-cyan-500"></i> Hasil Pengukuran Fisik (Dari Kader)
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                {{-- Data Standar (Semua Role) --}}
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Berat Badan</p>
                    <p class="text-2xl font-black text-slate-800">{{ $pemeriksaan->berat_badan ?? '-' }} <span class="text-[12px] text-slate-500 font-bold">kg</span></p>
                </div>
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tinggi Badan</p>
                    <p class="text-2xl font-black text-slate-800">{{ $pemeriksaan->tinggi_badan ?? '-' }} <span class="text-[12px] text-slate-500 font-bold">cm</span></p>
                </div>

                {{-- Data Spesifik Balita --}}
                @if($kategoriRaw == 'balita')
                <div class="bg-sky-50 border border-sky-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-sky-500 uppercase tracking-widest mb-1">Lingkar Kepala</p>
                    <p class="text-2xl font-black text-slate-800">{{ $pemeriksaan->lingkar_kepala ?? '-' }} <span class="text-[12px] text-slate-500 font-bold">cm</span></p>
                </div>
                @endif

                {{-- Data Spesifik Dewasa/Lansia/Bumil --}}
                @if(in_array($kategoriRaw, ['remaja', 'lansia', 'ibu_hamil']))
                <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Tekanan Darah</p>
                    <p class="text-2xl font-black text-slate-800">{{ $pemeriksaan->tekanan_darah ?? '-' }} <span class="text-[12px] text-slate-500 font-bold">mmHg</span></p>
                </div>
                <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl">
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Lingkar Lengan (LiLA)</p>
                    <p class="text-2xl font-black text-slate-800">{{ $pemeriksaan->lila ?? '-' }} <span class="text-[12px] text-slate-500 font-bold">cm</span></p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ================================================================
         2. FORM VALIDASI BIDAN (Meja 5)
         ================================================================ --}}
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8">
        
        <h3 class="text-[16px] font-black text-slate-800 font-poppins mb-2 flex items-center gap-2">
            <i class="fas fa-user-md text-cyan-500"></i> Ruang Diagnosa Medis
        </h3>
        <p class="text-[12px] font-medium text-slate-500 mb-8">Berikan kesimpulan klinis, status gizi, dan catatan untuk diteruskan ke pasien.</p>

        <form id="formPemeriksaan" action="{{ route('bidan.pemeriksaan.update', $pemeriksaan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Keputusan Validasi --}}
            <div class="mb-8">
                <label class="med-label">Keputusan Bidan</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="status_verifikasi" value="verified" class="peer sr-only status-radio" {{ $isVerified ? 'checked' : 'checked' }}>
                        <div class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-[20px] transition-all bg-white hover:border-cyan-200">
                            <div class="icon-box w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl transition-colors">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div>
                                <p class="text-[14px] font-black text-slate-800">Validasi & Selesai</p>
                                <p class="text-[10px] font-medium text-slate-500 mt-0.5">Data akurat, simpan ke EMR Warga.</p>
                            </div>
                        </div>
                    </label>

                    <label class="cursor-pointer relative">
                        <input type="radio" name="status_verifikasi" value="ditolak" class="peer sr-only status-radio-reject">
                        <div class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-[20px] transition-all bg-white hover:border-rose-200">
                            <div class="icon-box w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl transition-colors">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <p class="text-[14px] font-black text-slate-800">Tolak Data Kader</p>
                                <p class="text-[10px] font-medium text-slate-500 mt-0.5">Data fisik salah, kembalikan ke Meja 2.</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="w-full h-px bg-slate-100 my-8"></div>

            {{-- FIELD DINAMIS: KHUSUS BALITA --}}
            @if($kategoriRaw == 'balita')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="med-label">Status Gizi (IMT/U)</label>
                    <select name="status_gizi" class="med-input cursor-pointer">
                        <option value="">-- Analisis Gizi --</option>
                        <option value="Gizi Baik" {{ ($pemeriksaan->status_gizi == 'Gizi Baik') ? 'selected' : '' }}>Gizi Baik (Normal)</option>
                        <option value="Gizi Kurang" {{ ($pemeriksaan->status_gizi == 'Gizi Kurang') ? 'selected' : '' }}>Gizi Kurang (Underweight)</option>
                        <option value="Gizi Buruk" {{ ($pemeriksaan->status_gizi == 'Gizi Buruk') ? 'selected' : '' }}>Gizi Buruk (Severe)</option>
                        <option value="Risiko Lebih" {{ ($pemeriksaan->status_gizi == 'Risiko Lebih') ? 'selected' : '' }}>Risiko Berat Badan Lebih</option>
                    </select>
                </div>
                <div>
                    <label class="med-label text-rose-500"><i class="fas fa-exclamation-triangle"></i> Indikasi Stunting (TB/U)</label>
                    <select name="indikasi_stunting" class="med-input cursor-pointer border-rose-200 focus:border-rose-500 focus:ring-rose-50">
                        <option value="Tidak Stunting" {{ ($pemeriksaan->indikasi_stunting == 'Tidak Stunting') ? 'selected' : '' }}>Normal (Tidak Stunting)</option>
                        <option value="Stunting" {{ ($pemeriksaan->indikasi_stunting == 'Stunting') ? 'selected' : '' }}>Terindikasi Stunting</option>
                        <option value="Sangat Stunting" {{ ($pemeriksaan->indikasi_stunting == 'Sangat Stunting') ? 'selected' : '' }}>Sangat Stunting (Parah)</option>
                    </select>
                </div>
            </div>
            @endif

            {{-- FIELD DINAMIS: KHUSUS IBU HAMIL --}}
            @if(in_array($kategoriRaw, ['ibu_hamil', 'ibuhamil', 'bumil']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 bg-pink-50/50 p-5 rounded-[24px] border border-pink-100">
                <div>
                    <label class="med-label text-pink-600">Tinggi Fundus Uteri (TFU)</label>
                    <div class="relative">
                        <input type="text" name="tfu" class="med-input" value="{{ $pemeriksaan->tfu }}" placeholder="Contoh: 28">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-bold text-slate-400">cm</span>
                    </div>
                </div>
                <div>
                    <label class="med-label text-pink-600">Denyut Jantung Janin (DJJ)</label>
                    <div class="relative">
                        <input type="text" name="djj" class="med-input" value="{{ $pemeriksaan->djj }}" placeholder="Contoh: 140">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[12px] font-bold text-slate-400">bpm</span>
                    </div>
                </div>
                <div>
                    <label class="med-label text-pink-600">Posisi Janin</label>
                    <input type="text" name="posisi_janin" class="med-input" value="{{ $pemeriksaan->posisi_janin }}" placeholder="Contoh: Letak Kepala">
                </div>
            </div>
            @endif

            {{-- FIELD DINAMIS: KHUSUS LANSIA (Kemampuan Mandiri ABC) --}}
            @if($kategoriRaw == 'lansia')
            <div class="mb-6 bg-emerald-50/50 p-5 rounded-[24px] border border-emerald-100">
                <label class="med-label text-emerald-600">Skala Kemandirian (Kategori ABC)</label>
                <select name="tingkat_kemandirian" class="med-input cursor-pointer border-emerald-200">
                    <option value="">-- Pilih Skala Kemandirian --</option>
                    <option value="A" {{ ($pemeriksaan->tingkat_kemandirian == 'A') ? 'selected' : '' }}>Kategori A (Mandiri Sepenuhnya)</option>
                    <option value="B" {{ ($pemeriksaan->tingkat_kemandirian == 'B') ? 'selected' : '' }}>Kategori B (Bantuan Sebagian)</option>
                    <option value="C" {{ ($pemeriksaan->tingkat_kemandirian == 'C') ? 'selected' : '' }}>Kategori C (Ketergantungan Total / Stroke)</option>
                </select>
                <p class="text-[10px] mt-2 text-emerald-600 font-medium"><i class="fas fa-info-circle"></i> Standar asuhan keperawatan Lansia.</p>
            </div>
            @endif

            {{-- FIELD UNIVERSAL: DIAGNOSA & TINDAKAN --}}
            <div class="space-y-6">
                <div>
                    <label class="med-label">Kesimpulan / Diagnosa Medis</label>
                    <textarea name="diagnosa" rows="3" class="med-input" placeholder="Tuliskan kesimpulan dari hasil pengukuran fisik... (Misal: Tumbuh kembang anak sesuai umur)">{{ $pemeriksaan->diagnosa }}</textarea>
                </div>
                
                <div>
                    <label class="med-label">Tindakan / Pelayanan yang Diberikan</label>
                    <textarea name="tindakan" rows="2" class="med-input" placeholder="Contoh: Pemberian Vitamin A, Imunisasi BCG, atau Konseling Gizi.">{{ $pemeriksaan->tindakan }}</textarea>
                </div>

                <div class="bg-cyan-50/50 p-5 rounded-[24px] border border-cyan-100">
                    <label class="med-label text-cyan-700"><i class="fas fa-comment-medical"></i> Catatan Edukasi (Akan dibaca Warga)</label>
                    <p class="text-[10px] font-medium text-cyan-600 mb-2">Ini menggantikan fitur Live Chat. Pesan ini akan muncul di Aplikasi Warga sebagai log asuhan medis.</p>
                    <textarea name="catatan_bidan" rows="3" class="med-input border-cyan-200 focus:ring-cyan-100 focus:border-cyan-400" placeholder="Contoh: Ibu tolong perbanyak protein hewani untuk dedek bayi ya...">{{ $pemeriksaan->catatan_bidan }}</textarea>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-4">
                @if($isVerified)
                    <button type="button" onclick="confirmReset()" class="px-6 py-3.5 bg-white border-2 border-rose-100 text-rose-500 font-black text-[12px] uppercase tracking-widest rounded-xl hover:bg-rose-50 transition-colors w-full sm:w-auto">
                        Batal Validasi
                    </button>
                @endif
                
                <button type="submit" id="btnSubmit" class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[12px] uppercase tracking-widest rounded-xl hover:shadow-[0_10px_20px_rgba(6,182,212,0.3)] transition-all transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i> Simpan Diagnosa Medis
                </button>
            </div>
        </form>

        {{-- Form Hidden untuk Reset/Batal Validasi --}}
        @if($isVerified)
        <form id="formReset" action="{{ route('bidan.pemeriksaan.update', $pemeriksaan->id) }}" method="POST" class="hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="status_verifikasi" value="pending">
        </form>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    // Konfirmasi SweetAlert untuk Submit
    document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const status = document.querySelector('input[name="status_verifikasi"]:checked').value;
        const msg = status === 'verified' ? 'Data akan divalidasi dan dikirim ke rekam medis warga.' : 'Data akan ditolak dan dikembalikan ke antrian Kader.';
        const color = status === 'verified' ? '#0891b2' : '#f43f5e'; // Cyan vs Rose

        Swal.fire({
            title: 'Konfirmasi Tindakan',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Cek Lagi',
            reverseButtons: true,
            customClass: { popup: 'rounded-[24px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('btnSubmit');
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
                btn.classList.add('opacity-75', 'cursor-wait');
                this.submit();
            }
        });
    });

    // Konfirmasi Batal Validasi
    function confirmReset() {
        Swal.fire({
            title: 'Buka Kunci EMR?',
            text: "Status akan diubah menjadi 'Pending' dan warga tidak bisa melihat hasil diagnosa ini lagi hingga divalidasi ulang.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tutup',
            reverseButtons: true,
            customClass: { popup: 'rounded-[24px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formReset').submit();
            }
        });
    }
</script>
@endpush