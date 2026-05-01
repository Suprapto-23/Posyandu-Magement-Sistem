@extends('layouts.bidan')

@section('title', 'Ruang Validasi Medis')
@section('page-name', 'Meja 5 — Validasi Klinis')

@push('styles')
<style>
    .fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .clinical-card { background: white; border-radius: 28px; border: 1px solid #f1f5f9; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.03); overflow: hidden; }
    
    .med-input { 
        width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 18px; color: #0f172a; font-weight: 700; font-size: 13px; 
        transition: all 0.3s ease; outline: none; appearance: none;
    }
    .med-input:focus { background: #fff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { display: block; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }

    .swal2-popup.nexus-swal { border-radius: 32px !important; padding: 2rem !important; border: 1px solid #f1f5f9 !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1) !important; }
    .swal2-confirm, .swal2-cancel { border-radius: 14px !important; font-weight: 900 !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; font-size: 12px !important; padding: 14px 28px !important; }
</style>
@endpush

@section('content')
@php
    $namaPasien = $pemeriksaan->nama_pasien;
    $kategori = strtolower($pemeriksaan->kategori_pasien ?? 'umum');
    $isVerified = $pemeriksaan->status_verifikasi === 'verified';

    $config = match($kategori) {
        'balita', 'bayi' => ['col' => 'sky', 'ico' => 'fa-baby', 'label' => 'Anak & Balita'],
        'remaja'         => ['col' => 'violet', 'ico' => 'fa-user-graduate', 'label' => 'Usia Remaja'],
        'ibu_hamil'      => ['col' => 'pink', 'ico' => 'fa-female', 'label' => 'Ibu Hamil'],
        'lansia'         => ['col' => 'emerald', 'ico' => 'fa-user-clock', 'label' => 'Lansia'],
        default          => ['col' => 'slate', 'ico' => 'fa-user', 'label' => 'Umum'],
    };
@endphp

<div class="max-w-[1200px] mx-auto space-y-6 fade-in-up pb-20">

    {{-- HEADER --}}
    <div class="clinical-card p-6 md:p-8 border-l-[10px] border-l-cyan-500 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-[0.03] text-[150px] pointer-events-none"><i class="fas {{ $config['ico'] }}"></i></div>
        
        <div class="flex items-center gap-6 relative z-10 w-full md:w-auto">
            <div class="w-[72px] h-[72px] rounded-[22px] bg-cyan-50 text-cyan-600 flex items-center justify-center text-[32px] shadow-inner border border-cyan-100 shrink-0">
                <i class="fas {{ $config['ico'] }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-2.5 py-1 bg-cyan-100 text-cyan-700 text-[9px] font-black uppercase rounded-md border border-cyan-200">Meja 5 Bidan</span>
                    <span class="text-[10px] font-bold text-slate-400">KODE: #{{ str_pad($pemeriksaan->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="text-[26px] font-black text-slate-800 tracking-tight font-poppins leading-none mb-1.5">{{ $namaPasien }}</h1>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-{{$config['col']}}-50 text-{{$config['col']}}-600 text-[9px] font-black uppercase rounded border border-{{$config['col']}}-100">{{ $config['label'] }}</span>
                    <span class="text-[11px] font-bold text-slate-400">NIK: {{ $pemeriksaan->nik_pasien }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50 rounded-[20px] p-5 border border-slate-100 text-left md:text-right w-full md:w-auto relative z-10 shrink-0">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pemeriksa Awal (Kader)</p>
            <p class="text-[14px] font-black text-indigo-600 font-poppins flex items-center md:justify-end gap-2">
                <i class="fas fa-id-badge text-indigo-300"></i> {{ $pemeriksaan->pemeriksa->name ?? 'Sistem Bidan' }}
            </p>
            <p class="text-[10px] font-medium text-slate-400 mt-1 italic"><i class="far fa-clock mr-1"></i>Diukur {{ $pemeriksaan->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <form id="formValidasi" action="{{ route('bidan.pemeriksaan.update', $pemeriksaan->id) }}" method="POST">
        @csrf @method('PUT')
        
        {{-- MENGGUNAKAN ITEMS-STRETCH AGAR KOLOM KIRI DAN KANAN SAMA TINGGI --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            {{-- KOLOM KIRI: DATA FISIK KADER --}}
            <div class="lg:col-span-4">
                {{-- CLASS H-FULL MEMAKSA KARTU INI MENGISI SELURUH RUANG KE BAWAH --}}
                <div class="clinical-card p-6 md:p-8 bg-slate-50/50 h-full flex flex-col border-2 border-slate-100">
                    <h3 class="text-[14px] font-black text-slate-800 mb-5 flex items-center gap-2 border-b border-slate-200/60 pb-4 shrink-0">
                        <i class="fas fa-clipboard-list text-cyan-500"></i> Hasil Ukur Fisik Kader
                    </h3>
                    
                    <div class="space-y-6 flex-1">
                        <div class="bg-rose-50 p-3 rounded-xl border border-rose-100 flex gap-3 items-start">
                            <i class="fas fa-info-circle text-rose-400 mt-0.5"></i>
                            <p class="text-[10px] font-bold text-rose-500 leading-relaxed">Bidan berhak mengoreksi angka di bawah jika kader melakukan kesalahan input.</p>
                        </div>
                        
                        <div>
                            <label class="med-label">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat_badan" value="{{ $pemeriksaan->berat_badan }}" class="med-input bg-white">
                        </div>
                        <div>
                            <label class="med-label">Tinggi/Panjang Badan (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" value="{{ $pemeriksaan->tinggi_badan }}" class="med-input bg-white">
                        </div>
                        <div>
                            <label class="med-label">Lingkar Lengan / LiLA (cm)</label>
                            <input type="number" step="0.1" name="lingkar_lengan" value="{{ $pemeriksaan->lingkar_lengan }}" class="med-input bg-white">
                        </div>
                        @if(in_array($kategori, ['balita', 'bayi']))
                        <div>
                            <label class="med-label">Lingkar Kepala (cm)</label>
                            <input type="number" step="0.1" name="lingkar_kepala" value="{{ $pemeriksaan->lingkar_kepala }}" class="med-input bg-white">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM MEDIS BIDAN --}}
            <div class="lg:col-span-8">
                <div class="clinical-card border-2 border-cyan-500/20 h-full flex flex-col">
                    
                    <div class="px-8 py-5 bg-gradient-to-r from-cyan-600 to-blue-700 text-white shrink-0">
                        <h3 class="text-[15px] font-black font-poppins flex items-center gap-2">
                            <i class="fas fa-stethoscope"></i> Analisa Medis & Tindakan Bidan
                        </h3>
                    </div>
                    
                    <div class="p-8 space-y-8 flex-1 flex flex-col">
                        
                        {{-- 1. Pengukuran Vital --}}
                        <div>
                            <h4 class="text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-4 border-b border-cyan-100 pb-2">1. Pengukuran Vital Tambahan</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                <div>
                                    <label class="med-label">Suhu Tubuh (°C)</label>
                                    <input type="number" step="0.1" name="suhu_tubuh" value="{{ $pemeriksaan->suhu_tubuh }}" class="med-input" placeholder="36.5">
                                </div>
                                <div>
                                    <label class="med-label">Tensi Darah (mmHg)</label>
                                    <input type="text" name="tekanan_darah" value="{{ $pemeriksaan->tekanan_darah }}" class="med-input" placeholder="120/80">
                                </div>
                                
                                @if(in_array($kategori, ['balita', 'bayi']))
                                <div>
                                    <label class="med-label">Status Gizi (BB/TB)</label>
                                    <select name="status_gizi" class="med-input cursor-pointer">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Gizi Buruk" {{ $pemeriksaan->status_gizi == 'Gizi Buruk' ? 'selected' : '' }}>Gizi Buruk</option>
                                        <option value="Gizi Kurang" {{ $pemeriksaan->status_gizi == 'Gizi Kurang' ? 'selected' : '' }}>Gizi Kurang</option>
                                        <option value="Gizi Baik" {{ $pemeriksaan->status_gizi == 'Gizi Baik' ? 'selected' : '' }}>Gizi Baik (Normal)</option>
                                        <option value="Gizi Lebih" {{ $pemeriksaan->status_gizi == 'Gizi Lebih' ? 'selected' : '' }}>Gizi Lebih (Obesitas)</option>
                                    </select>
                                </div>
                                @elseif($kategori == 'remaja')
                                <div>
                                    <label class="med-label">Status Anemia (HB)</label>
                                    <select name="status_anemia" class="med-input cursor-pointer">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Tidak Anemia" {{ $pemeriksaan->status_anemia == 'Tidak Anemia' ? 'selected' : '' }}>Tidak Anemia</option>
                                        <option value="Anemia Ringan" {{ $pemeriksaan->status_anemia == 'Anemia Ringan' ? 'selected' : '' }}>Anemia Ringan</option>
                                        <option value="Anemia Berat" {{ $pemeriksaan->status_anemia == 'Anemia Berat' ? 'selected' : '' }}>Anemia Berat</option>
                                    </select>
                                </div>
                                @elseif($kategori == 'ibu_hamil')
                                <div>
                                    <label class="med-label text-pink-600">Risiko Kehamilan</label>
                                    <select name="status_risiko" class="med-input cursor-pointer border-pink-200">
                                        <option value="">-- Kategori Risiko --</option>
                                        <option value="Risiko Rendah" {{ $pemeriksaan->status_risiko == 'Risiko Rendah' ? 'selected' : '' }}>Risiko Rendah (Normal)</option>
                                        <option value="Risiko Tinggi" {{ $pemeriksaan->status_risiko == 'Risiko Tinggi' ? 'selected' : '' }}>Risiko Tinggi</option>
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- 2. Diagnosa --}}
                        <div class="flex-1">
                            <h4 class="text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-4 border-b border-cyan-100 pb-2">2. Kesimpulan Medis</h4>
                            <div class="space-y-6">
                                <div>
                                    <label class="med-label">Hasil Diagnosa Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea name="diagnosa" rows="3" required class="med-input resize-none" placeholder="Tuliskan diagnosa dari hasil pemeriksaan fisik dan vital... (misal: Pertumbuhan normal, tidak ada keluhan).">{{ $pemeriksaan->diagnosa }}</textarea>
                                </div>
                                <div>
                                    <label class="med-label">Tindakan / Terapi / Saran <span class="text-rose-500">*</span></label>
                                    <textarea name="tindakan" rows="2" required class="med-input resize-none" placeholder="Berikan resep vitamin, tindakan medis, atau saran edukasi...">{{ $pemeriksaan->tindakan }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Keputusan Final --}}
                        <div>
                            <h4 class="text-[11px] font-black text-cyan-600 uppercase tracking-widest mb-4 border-b border-cyan-100 pb-2">3. Keputusan Akhir Antrian</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-stretch">
                                
                                <label class="relative cursor-pointer group h-full">
                                    <input type="radio" name="status_verifikasi" value="verified" class="peer sr-only" {{ $isVerified ? 'checked' : '' }}>
                                    <div class="h-full p-5 border-2 border-slate-200 rounded-2xl flex items-center gap-4 bg-white transition-all duration-300
                                                group-hover:border-cyan-200 peer-checked:border-cyan-500 peer-checked:bg-cyan-50 peer-checked:shadow-[0_8px_20px_rgba(6,182,212,0.15)] peer-checked:-translate-y-1">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl shrink-0 transition-all duration-300 peer-checked:bg-cyan-500 peer-checked:text-white">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-slate-800 text-[14px] leading-tight mb-1">Sahkan & Verifikasi</p>
                                            <p class="text-[10px] font-bold text-slate-500 leading-snug">Data valid, simpan ke rekam medis (EMR).</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group h-full">
                                    <input type="radio" name="status_verifikasi" value="ditolak" class="peer sr-only" {{ $pemeriksaan->status_verifikasi == 'ditolak' ? 'checked' : '' }}>
                                    <div class="h-full p-5 border-2 border-slate-200 rounded-2xl flex items-center gap-4 bg-white transition-all duration-300
                                                group-hover:border-rose-200 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:shadow-[0_8px_20px_rgba(244,63,94,0.15)] peer-checked:-translate-y-1">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl shrink-0 transition-all duration-300 peer-checked:bg-rose-500 peer-checked:text-white">
                                            <i class="fas fa-undo-alt"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-black text-slate-800 text-[14px] leading-tight mb-1">Kembalikan ke Kader</p>
                                            <p class="text-[10px] font-bold text-slate-500 leading-snug">Data fisik error, minta kader ukur ulang.</p>
                                        </div>
                                    </div>
                                </label>

                            </div>
                        </div>

                        {{-- Footer Submit --}}
                        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 mt-auto shrink-0">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Sesi Validasi Aktif</p>
                            </div>
                            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-cyan-600 to-blue-700 text-white font-black text-[12px] uppercase tracking-widest rounded-2xl hover:shadow-[0_15px_30px_rgba(6,182,212,0.3)] transition-all hover:-translate-y-1 active:scale-95 shadow-lg">
                                <i class="fas fa-save mr-2"></i> Simpan Hasil Validasi
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('formValidasi').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const status = document.querySelector('input[name="status_verifikasi"]:checked');
        if(!status) {
            Swal.fire({ 
                icon: 'warning', 
                title: 'Keputusan Belum Dipilih', 
                text: 'Silakan pilih apakah data ini "Disahkan" atau "Dikembalikan ke Kader".', 
                confirmButtonColor: '#06b6d4', 
                customClass: { popup: 'nexus-swal' } 
            });
            return;
        }

        const isVerified = status.value === 'verified';

        Swal.fire({
            title: isVerified ? 'Verifikasi & Simpan EMR?' : 'Kembalikan ke Kader?',
            text: isVerified ? "Data diagnosa Anda akan dikunci dan menjadi rekam medis resmi warga." : "Kader akan menerima notifikasi bahwa pengukuran fisiknya salah.",
            icon: isVerified ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isVerified ? '#0891b2' : '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: isVerified ? 'Ya, Sahkan Data' : 'Ya, Tolak Data',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'nexus-swal' }
        }).then((res) => {
            if (res.isConfirmed) {
                const btn = document.getElementById('btnSubmit');
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
                btn.classList.add('opacity-70', 'cursor-not-allowed', 'scale-95');
                this.submit();
            }
        });
    });
</script>
@endpush