@extends('layouts.kader')
@section('title', 'Input Pemeriksaan Fisik')
@section('page-name', 'Rekam Antropometri & Klinis')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (UNIFIED FORM EDITION)
       ================================================================= */
    
    /* Animasi Masuk Halus */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Tipografi Label */
    .nexus-label { display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem; }
    
    /* Input Fields (Sleek & Clean) */
    .nexus-input, .nexus-textarea {
        width: 100%; background-color: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; 
        font-family: 'Inter', sans-serif; font-size: 0.9rem; font-weight: 500;
        border-radius: 12px; padding: 0.8rem 1rem; outline: none; transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .nexus-input:focus, .nexus-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .nexus-input::placeholder, .nexus-textarea::placeholder { color: #94a3b8; font-weight: 400; }
    
    /* Satuan pada Input */
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper .unit { position: absolute; right: 1rem; font-size: 0.75rem; font-weight: 700; color: #64748b; pointer-events: none; }
    .input-wrapper .nexus-input { padding-right: 2.5rem; text-align: left; }

    /* Segmented Control (Pilihan Kategori) */
    .segment-control { display: flex; flex-wrap: wrap; background: #f1f5f9; padding: 0.35rem; border-radius: 14px; gap: 0.25rem; }
    .segment-btn {
        flex: 1; text-align: center; padding: 0.7rem 0.5rem; border-radius: 10px; font-size: 0.75rem; font-weight: 700; 
        color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all 0.2s ease;
        border: 1px solid transparent; min-width: 120px;
    }
    .segment-btn:hover { color: #334155; background: #e2e8f0; }
    .segment-btn.active { background: #ffffff; color: #4f46e5; border-color: #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

    /* =================================================================
       SOLUSI MUTLAK: COMBOBOX BEBAS PENJARA (ANTI-CLIPPING)
       ================================================================= */
    .combo-wrapper { position: relative; width: 100%; }
    .combo-trigger {
        width: 100%; background-color: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; 
        font-size: 0.9rem; font-weight: 500; border-radius: 12px; padding: 0.8rem 1rem; cursor: pointer;
        display: flex; justify-content: space-between; align-items: center; transition: all 0.2s;
    }
    .combo-trigger:focus, .combo-trigger.active { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); outline: none; }
    
    /* Menu dibiarkan melayang dengan z-index absolut tertinggi */
    .combo-menu {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 9999;
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15); overflow: hidden;
        opacity: 0; visibility: hidden; transform: translateY(-5px); transition: all 0.2s ease;
    }
    .combo-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }
    .combo-search-box { padding: 10px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; position: relative; }
    .combo-search-input { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px 8px 36px; font-size: 0.85rem; outline: none; }
    .combo-search-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .combo-search-icon { position: absolute; left: 22px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem; }
    .combo-list { max-height: 240px; overflow-y: auto; padding: 6px; }
    .combo-option { padding: 10px 12px; border-radius: 8px; cursor: pointer; display: flex; flex-direction: column; gap: 2px; transition: all 0.1s; }
    .combo-option:hover { background: #f1f5f9; }
    .combo-option.selected { background: #eef2ff; color: #4f46e5; }
    
    .combo-list::-webkit-scrollbar { width: 5px; }
    .combo-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* Dynamic Form Wrapper (Tanpa overflow hidden!) */
    .dynamic-form-group { display: block; animation: fadeIn 0.4s ease forwards; }
    .hidden-group { display: none; }

    /* Isolasi SweetAlert Anti-Bug */
    body.swal2-shown:not(.swal2-toast-shown) .swal2-container { z-index: 100000 !important; backdrop-filter: blur(4px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .nexus-modal { border-radius: 24px !important; padding: 2rem !important; background: #ffffff !important; width: 24em !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2) !important; border: 1px solid #f1f5f9 !important; }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto animate-fade-in pb-20 relative z-10 mt-2">

    {{-- AURA BACKGROUND (Elegan) --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-sky-500/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    {{-- HEADER SAAS MINIMALIST --}}
    <div class="flex items-center gap-5 mb-8">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[14px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight font-poppins mb-1">Input Pemeriksaan Fisik</h1>
            <p class="text-slate-500 font-medium text-[13px]">Rekam data klinis & antropometri warga secara terpusat.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-[16px] p-5 mb-8 flex items-start gap-4 shadow-sm animate-fade-in">
        <i class="fas fa-exclamation-circle text-rose-500 text-xl mt-0.5"></i>
        <div>
            <p class="text-[11px] uppercase tracking-widest font-bold text-rose-800 mb-1">Gagal Menyimpan Data</p>
            <p class="text-[13px] text-rose-600 font-medium">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="formPemeriksaan">
        @csrf
        
        {{-- ==========================================================
             KANVAS UTAMA (UNIFIED SEAMLESS FORM)
             Tidak ada "overflow: hidden" yang mengikat dropdown!
             ========================================================== --}}
        <div class="bg-white/80 backdrop-blur-xl border border-slate-200 rounded-[28px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8">
            
            {{-- BAGIAN 1: IDENTITAS & KATEGORI --}}
            <div class="p-6 md:p-10 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm border border-indigo-100"><i class="fas fa-id-badge"></i></div>
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-widest">Identitas Sasaran</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- Kategori (Segmented Control Horizontal) --}}
                    <div class="lg:col-span-12">
                        <label class="nexus-label mb-2">1. Pilih Kategori Pemeriksaan <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="kategori_pasien" id="kategori_pasien" value="{{ old('kategori_pasien', $kategori_awal) }}" required>
                        <input type="hidden" id="kategori_ui" value="{{ old('kategori_pasien', $kategori_awal) == 'balita' ? 'balita' : old('kategori_pasien', $kategori_awal) }}">
                        
                        <div class="segment-control">
                            <div class="segment-btn" data-val="balita" data-ui="bayi">
                                <i class="fas fa-baby block text-lg mb-1"></i> Bayi <span class="block text-[9px] font-medium mt-0.5 normal-case opacity-70">0-11 Bulan</span>
                            </div>
                            <div class="segment-btn" data-val="balita" data-ui="balita">
                                <i class="fas fa-child block text-lg mb-1"></i> Balita <span class="block text-[9px] font-medium mt-0.5 normal-case opacity-70">1-5 Tahun</span>
                            </div>
                            <div class="segment-btn" data-val="ibu_hamil" data-ui="ibu_hamil">
                                <i class="fas fa-female block text-lg mb-1"></i> Ibu Hamil <span class="block text-[9px] font-medium mt-0.5 normal-case opacity-70">Bumil</span>
                            </div>
                            <div class="segment-btn" data-val="remaja" data-ui="remaja">
                                <i class="fas fa-user-graduate block text-lg mb-1"></i> Remaja <span class="block text-[9px] font-medium mt-0.5 normal-case opacity-70">Skrining</span>
                            </div>
                            <div class="segment-btn" data-val="lansia" data-ui="lansia">
                                <i class="fas fa-wheelchair block text-lg mb-1"></i> Lansia <span class="block text-[9px] font-medium mt-0.5 normal-case opacity-70">PTM</span>
                            </div>
                        </div>
                    </div>

                    {{-- Nama Pasien (Combobox Anti-Bug) --}}
                    <div class="lg:col-span-8 relative z-50">
                        <label class="nexus-label">2. Nama Pasien / Warga <span class="text-rose-500">*</span></label>
                        <div class="combo-wrapper" id="comboContainer">
                            <input type="hidden" name="pasien_id" id="pasien_id" required>
                            
                            <div class="combo-trigger" id="comboTrigger" tabindex="0">
                                <span id="comboSelectedText" class="text-slate-400 font-normal">Pilih kategori di atas...</span>
                                <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform" id="comboArrow"></i>
                            </div>

                            <div class="combo-menu" id="comboMenu">
                                <div class="combo-search-box">
                                    <i class="fas fa-search combo-search-icon"></i>
                                    <input type="text" id="comboSearchInput" class="combo-search-input" placeholder="Ketik nama atau NIK warga..." autocomplete="off">
                                </div>
                                <ul class="combo-list" id="comboList"></ul>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="lg:col-span-4 relative z-10">
                        <label class="nexus-label">3. Tgl Pemeriksaan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_periksa" value="{{ old('tanggal_periksa', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}" class="nexus-input">
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: UKUR DASAR (SEMUA KATEGORI) --}}
            <div class="p-6 md:p-10 border-b border-slate-100 bg-slate-50/30">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm border border-emerald-100"><i class="fas fa-weight"></i></div>
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-widest">Antropometri Dasar</h2>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 items-start">
                    <div>
                        <label class="nexus-label">Berat Badan <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ old('berat_badan') }}" required class="nexus-input text-lg font-bold text-slate-800" placeholder="0.0">
                            <span class="unit">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="nexus-label">Tinggi/Panjang <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ old('tinggi_badan') }}" required class="nexus-input text-lg font-bold text-slate-800" placeholder="0.0">
                            <span class="unit">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="nexus-label">Suhu Tubuh</label>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh') }}" class="nexus-input text-lg font-bold text-slate-800" placeholder="36.5">
                            <span class="unit">°C</span>
                        </div>
                    </div>

                    {{-- Widget IMT Simetris --}}
                    <div class="col-span-2 lg:col-span-1 bg-slate-800 rounded-xl p-3 flex flex-col justify-center items-center shadow-inner relative overflow-hidden h-full min-h-[76px]">
                        <div class="absolute -right-2 -top-2 text-white/5 text-4xl"><i class="fas fa-calculator"></i></div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 relative z-10">Nilai IMT</p>
                        <span id="imt-val" class="text-2xl font-black text-white leading-none relative z-10 font-poppins">0.0</span>
                        <span id="imt-kat" class="absolute bottom-1 right-2 text-[8px] font-bold text-slate-500 uppercase tracking-widest">-</span>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 3: FORM DINAMIS KATEGORI --}}
            <div class="p-6 md:p-10 border-b border-slate-100 relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors" id="dynamicIconBox">
                        <i id="dynamicIcon" class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 uppercase tracking-widest" id="dynamicTitle">Pemeriksaan Khusus</h2>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5" id="dynamicDesc">Pilih kategori untuk memuat form.</p>
                    </div>
                </div>

                {{-- Container Dinamis --}}
                <div id="dynamicFormContainer">
                    
                    {{-- A. BALITA & BAYI --}}
                    <div id="form_balita" class="dynamic-form-group">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="nexus-label text-sky-700">Lingkar Kepala</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="lingkar_kepala" value="{{ old('lingkar_kepala') }}" class="nexus-input focus:border-sky-500 focus:ring-sky-100" placeholder="0.0">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            <div>
                                <label class="nexus-label text-sky-700">Lingkar Lengan Atas (LiLA)</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="nexus-input focus:border-sky-500 focus:ring-sky-100" placeholder="0.0">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B. IBU HAMIL --}}
                    <div id="form_ibu_hamil" class="dynamic-form-group hidden-group">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label class="nexus-label text-pink-700">Usia Kehamilan</label>
                                <div class="input-wrapper">
                                    <input type="number" name="usia_kehamilan" value="{{ old('usia_kehamilan') }}" class="nexus-input focus:border-pink-500 focus:ring-pink-100" placeholder="Misal: 12">
                                    <span class="unit">mgg</span>
                                </div>
                            </div>
                            <div>
                                <label class="nexus-label text-pink-700">Tensi Darah</label>
                                <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" class="nexus-input font-mono focus:border-pink-500 focus:ring-pink-100" placeholder="120/80">
                            </div>
                            <div class="bg-rose-50 p-3 rounded-xl border border-rose-100">
                                <label class="nexus-label text-rose-700">Lingkar Lengan (LiLA) <span class="text-rose-500">*</span></label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" id="lila_bumil" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="nexus-input border-rose-200 focus:border-rose-500" placeholder="0.0">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- C. REMAJA --}}
                    <div id="form_remaja" class="dynamic-form-group hidden-group">
                        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="col-span-1">
                                <label class="nexus-label text-indigo-700">Tensi Darah</label>
                                <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" class="nexus-input font-mono focus:border-indigo-500" placeholder="110/80">
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-indigo-700">Hemoglobin</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="hemoglobin" value="{{ old('hemoglobin') }}" class="nexus-input focus:border-indigo-500" placeholder="0.0">
                                    <span class="unit">g/dL</span>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-indigo-700">Gula Darah</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="gula_darah" value="{{ old('gula_darah') }}" class="nexus-input focus:border-indigo-500" placeholder="0.0">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-indigo-700">L. Perut</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="lingkar_perut" value="{{ old('lingkar_perut') }}" class="nexus-input focus:border-indigo-500" placeholder="0.0">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            <div class="col-span-2 lg:col-span-1">
                                <label class="nexus-label text-rose-500">LiLA <span class="font-normal text-slate-500 normal-case">(Putri)</span></label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" id="lila_remaja" name="lingkar_lengan" value="{{ old('lingkar_lengan') }}" class="nexus-input border-rose-200 focus:border-rose-400" placeholder="0.0">
                                    <span class="unit text-rose-400">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- D. LANSIA --}}
                    <div id="form_lansia" class="dynamic-form-group hidden-group">
                        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="col-span-1">
                                <label class="nexus-label text-emerald-800">Tensi Darah</label>
                                <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" class="nexus-input font-mono focus:border-emerald-500" placeholder="130/90">
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-emerald-800">Gula Darah</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="gula_darah" value="{{ old('gula_darah') }}" class="nexus-input focus:border-emerald-500" placeholder="0.0">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-emerald-800">Kolesterol</label>
                                <div class="input-wrapper">
                                    <input type="number" name="kolesterol" value="{{ old('kolesterol') }}" class="nexus-input focus:border-emerald-500" placeholder="0">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div class="col-span-1">
                                <label class="nexus-label text-emerald-800">Asam Urat</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="asam_urat" value="{{ old('asam_urat') }}" class="nexus-input focus:border-emerald-500" placeholder="0.0">
                                    <span class="unit">mg/dL</span>
                                </div>
                            </div>
                            <div class="col-span-2 lg:col-span-1">
                                <label class="nexus-label text-emerald-800">Lingkar Perut</label>
                                <div class="input-wrapper">
                                    <input type="number" step="0.1" name="lingkar_perut" value="{{ old('lingkar_perut') }}" class="nexus-input focus:border-emerald-500" placeholder="0.0">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- WARNING KEK (Peringatan Pintar) --}}
                <div id="warn_kek" class="mt-6 bg-rose-50 border border-rose-200 rounded-xl p-4 hidden items-center gap-4 shadow-sm transition-all duration-300">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 text-xl shrink-0"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <p class="text-[12px] font-bold text-rose-800 uppercase tracking-widest mb-0.5">Peringatan: Risiko KEK</p>
                        <p class="text-[12px] font-medium text-rose-600 leading-relaxed">Nilai LiLA < 23.5 cm. Terdapat indikasi Kurang Energi Kronis. Data akan disorot untuk Bidan.</p>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 4: CATATAN --}}
            <div class="p-6 md:p-10 bg-slate-50/30">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-sm border border-amber-100"><i class="fas fa-comment-medical"></i></div>
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-widest">Catatan Lapangan</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="nexus-label">Keluhan Utama (Opsional)</label>
                        <textarea name="keluhan" rows="2" class="nexus-textarea resize-none" placeholder="Tuliskan keluhan yang dirasakan pasien...">{{ old('keluhan') }}</textarea>
                    </div>
                    <div>
                        <label class="nexus-label">Catatan Kader (Opsional)</label>
                        <textarea name="catatan_kader" rows="2" class="nexus-textarea resize-none" placeholder="Pesan untuk diteruskan ke Bidan...">{{ old('catatan_kader') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- ACTION BAR BAWAH --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-4 mb-10">
            <a href="{{ route('kader.pemeriksaan.index') }}" class="w-full sm:w-auto px-8 py-3 rounded-xl font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 transition-colors uppercase text-[11px] tracking-widest text-center shadow-sm">
                Batalkan
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3 rounded-xl font-bold text-white bg-slate-900 hover:bg-indigo-600 transition-all shadow-md hover:shadow-lg uppercase text-[11px] tracking-widest flex items-center justify-center gap-2 hover:-translate-y-0.5">
                <i class="fas fa-save text-sm"></i> Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // =========================================================
    // 1. ENGINE KATEGORI CERDAS (UI PILLS -> DB LOGIC)
    // =========================================================
    const hiddenKategori = document.getElementById('kategori_pasien');
    const hiddenUI = document.getElementById('kategori_ui');
    const catBtns = document.querySelectorAll('.segment-btn');

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            catBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const valDB = this.getAttribute('data-val'); 
            const valUI = this.getAttribute('data-ui');  
            hiddenKategori.value = valDB;
            hiddenUI.value = valUI;
            
            loadPasienAPI(valDB);
            toggleForms(valDB, valUI);
            document.getElementById('warn_kek').classList.add('hidden');
            document.getElementById('warn_kek').classList.remove('flex');
        });
    });

    // =========================================================
    // 2. ENGINE COMBOBOX (SEARCHABLE DROPDOWN - ANTI BUG!)
    // =========================================================
    let pasienData = [];
    const oldPasienId = "{{ old('pasien_id', $pasien_id_awal) }}";
    
    const comboContainer = document.getElementById('comboContainer');
    const comboTrigger = document.getElementById('comboTrigger');
    const comboMenu = document.getElementById('comboMenu');
    const comboSearch = document.getElementById('comboSearchInput');
    const comboList = document.getElementById('comboList');
    const hiddenPasienId = document.getElementById('pasien_id');
    const comboText = document.getElementById('comboSelectedText');
    const comboArrow = document.getElementById('comboArrow');

    function openMenu() { 
        comboMenu.classList.add('open'); 
        comboTrigger.classList.add('border-indigo-500');
        comboArrow.style.transform = 'rotate(180deg)'; 
    }
    function closeMenu() { 
        comboMenu.classList.remove('open'); 
        comboTrigger.classList.remove('border-indigo-500');
        comboArrow.style.transform = 'rotate(0deg)'; 
    }

    comboTrigger.addEventListener('click', (e) => {
        if(pasienData.length === 0) return;
        const isO = comboMenu.classList.contains('open');
        closeMenu();
        if(!isO) {
            openMenu();
            setTimeout(() => comboSearch.focus(), 50);
        }
        e.stopPropagation();
    });

    document.addEventListener('click', (e) => { 
        const isClickInside = document.getElementById('comboContainer').contains(e.target);
        if(!isClickInside) {
            closeMenu();
            if(!hiddenPasienId.value) comboSearch.value = '';
        } 
    });

    function renderComboList(data) {
        comboList.innerHTML = '';
        if(data.length === 0) {
            comboList.innerHTML = '<li class="p-3 text-center text-xs font-medium text-slate-400">Data tidak ditemukan.</li>';
            return;
        }
        
        data.forEach(p => {
            const li = document.createElement('li');
            const isSelected = p.id == hiddenPasienId.value;
            li.className = `combo-option ${isSelected ? 'selected' : ''}`;
            li.innerHTML = `
                <div class="flex justify-between items-center w-full">
                    <span class="op-name font-semibold">${p.nama}</span>
                    <span class="op-nik text-[10px] font-mono opacity-70">NIK: ${p.nik || '-'}</span>
                </div>
            `;
            
            li.addEventListener('click', () => {
                comboSearch.value = ''; 
                hiddenPasienId.value = p.id;
                comboText.innerHTML = `<span class="text-slate-800 font-bold">${p.nama}</span>`;
                
                hiddenPasienId.dataset.jk = p.jenis_kelamin || '';
                hiddenPasienId.dispatchEvent(new Event('change'));
                closeMenu();
            });
            comboList.appendChild(li);
        });
    }

    comboSearch.addEventListener('input', (e) => {
        hiddenPasienId.value = ''; 
        const val = e.target.value.toLowerCase();
        const filtered = pasienData.filter(p => p.nama.toLowerCase().includes(val) || (p.nik && p.nik.toLowerCase().includes(val)));
        renderComboList(filtered);
    });

    async function loadPasienAPI(kategoriDB) {
        comboText.textContent = 'Memuat pangkalan data...';
        comboTrigger.style.pointerEvents = 'none';
        pasienData = [];
        
        try {
            const url = `{{ route('kader.pemeriksaan.api') }}?kategori=${kategoriDB}`;
            const response = await fetch(url);
            const res = await response.json();
            
            if(res.status === 'success' && res.data.length > 0) {
                pasienData = res.data;
                const existing = pasienData.find(p => p.id == oldPasienId);
                
                if(existing) {
                    hiddenPasienId.value = existing.id;
                    hiddenPasienId.dataset.jk = existing.jenis_kelamin || '';
                    comboText.innerHTML = `<span class="text-slate-800 font-bold">${existing.nama}</span>`;
                } else {
                    hiddenPasienId.value = '';
                    comboText.innerHTML = `<span class="text-slate-400 font-normal">Klik untuk mencari warga...</span>`;
                }
                
                renderComboList(pasienData);
                comboTrigger.style.pointerEvents = 'auto';
            } else {
                comboText.textContent = 'Database sasaran kosong.';
            }
        } catch (error) {
            comboText.textContent = 'Gagal terhubung ke API.';
        }
    }

    // =========================================================
    // 3. LOGIKA FORM DINAMIS (SAAS TRANSITION)
    // =========================================================
    function toggleForms(kategoriDB, kategoriUI) {
        ['form_balita', 'form_ibu_hamil', 'form_remaja', 'form_lansia'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.classList.add('hidden-group');
                el.querySelectorAll('input').forEach(inp => { if(inp.dataset.name) inp.name = ''; });
            }
        });

        const activeEl = document.getElementById('form_' + kategoriDB);
        if (activeEl) {
            activeEl.classList.remove('hidden-group');
            activeEl.querySelectorAll('input').forEach(inp => { 
                if(!inp.dataset.name) inp.dataset.name = inp.name; 
                inp.name = inp.dataset.name; 
            });
        }

        const iconEl = document.getElementById('dynamicIconBox');
        const iconI = document.getElementById('dynamicIcon');
        const titleEl = document.getElementById('dynamicTitle');
        const descEl = document.getElementById('dynamicDesc');

        if(kategoriDB === 'balita') {
            if(kategoriUI === 'bayi') {
                iconEl.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors bg-sky-50 text-sky-600 border-sky-100';
                iconI.className = 'fas fa-baby';
                titleEl.textContent = 'Pemeriksaan Bayi';
                descEl.textContent = 'Pengukuran spesifik awal (0-11 bln).';
            } else {
                iconEl.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors bg-sky-50 text-sky-600 border-sky-100';
                iconI.className = 'fas fa-child';
                titleEl.textContent = 'Pemeriksaan Balita';
                descEl.textContent = 'Pengukuran tumbuh kembang (1-5 thn).';
            }
        } else if(kategoriDB === 'ibu_hamil') {
            iconEl.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors bg-pink-50 text-pink-600 border-pink-100';
            iconI.className = 'fas fa-female';
            titleEl.textContent = 'Pemeriksaan Ibu Hamil';
            descEl.textContent = 'Pantauan medis kehamilan.';
        } else if(kategoriDB === 'remaja') {
            iconEl.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors bg-indigo-50 text-indigo-600 border-indigo-100';
            iconI.className = 'fas fa-user-graduate';
            titleEl.textContent = 'Pemeriksaan Remaja';
            descEl.textContent = 'Skrining fisik berkala.';
        } else if(kategoriDB === 'lansia') {
            iconEl.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-sm border shadow-sm transition-colors bg-emerald-50 text-emerald-600 border-emerald-100';
            iconI.className = 'fas fa-wheelchair';
            titleEl.textContent = 'Cek Medis Lansia';
            descEl.textContent = 'Indikator penyakit tidak menular.';
        }
    }

    // INIT SAAT HALAMAN DIMUAT
    const initDB = hiddenKategori.value || 'balita';
    const initUI = hiddenUI.value || 'balita';
    const activeBtn = Array.from(catBtns).find(b => b.getAttribute('data-ui') === initUI);
    if(activeBtn) activeBtn.classList.add('active');

    loadPasienAPI(initDB);
    toggleForms(initDB, initUI);

    // =========================================================
    // 4. KALKULATOR IMT CERDAS
    // =========================================================
    const bbInput = document.getElementById('berat_badan'), tbInput = document.getElementById('tinggi_badan');
    function hitungIMT() {
        const bb = parseFloat(bbInput.value), tb = parseFloat(tbInput.value) / 100;
        if(bb > 0 && tb > 0) {
            const imt = (bb / (tb * tb)).toFixed(2);
            let color = 'text-slate-400';
            
            if(imt < 18.5) { color = 'text-amber-500'; }
            else if(imt >= 25 && imt < 27) { color = 'text-rose-400'; }
            else if(imt >= 27) { color = 'text-rose-600'; }
            
            document.getElementById('imt-val').textContent = imt;
            document.getElementById('imt-val').className = `text-2xl font-black leading-none relative z-10 font-poppins ${color}`;
        }
    }
    bbInput.addEventListener('input', hitungIMT); tbInput.addEventListener('input', hitungIMT); 
    if(bbInput.value && tbInput.value) hitungIMT();

    // =========================================================
    // 5. DETEKSI DINI RISIKO KEK
    // =========================================================
    function cekLila(e) {
        const val = parseFloat(e.target.value);
        const kat = document.getElementById('kategori_pasien').value; 
        const jk = hiddenPasienId.dataset.jk;
        const warn = document.getElementById('warn_kek');

        if (val > 0 && val < 23.5 && (kat === 'ibu_hamil' || (kat === 'remaja' && jk === 'P'))) {
            warn.classList.remove('hidden'); warn.classList.add('flex');
        } else {
            warn.classList.add('hidden'); warn.classList.remove('flex');
        }
    }
    document.getElementById('lila_bumil')?.addEventListener('input', cekLila);
    document.getElementById('lila_remaja')?.addEventListener('input', cekLila);
    hiddenPasienId.addEventListener('change', function() {
        const lilaInput = document.getElementById('lila_remaja') || document.getElementById('lila_bumil');
        if(lilaInput && lilaInput.value) { lilaInput.dispatchEvent(new Event('input')); }
    });

    // =========================================================
    // 6. UX SUBMIT FORM & LOADING MODAL
    // =========================================================
    document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
        if(!hiddenPasienId.value) {
            e.preventDefault();
            Swal.fire({ 
                icon: 'warning', 
                title: 'Identitas Kosong', 
                html: '<p class="text-slate-500 text-sm">Nama Pasien wajib dipilih dari menu pencarian di atas sebelum menyimpan data.</p>', 
                confirmButtonText: 'Mengerti',
                buttonsStyling: false,
                backdrop: true, 
                customClass: { popup: 'nexus-modal', confirmButton: 'btn-swal-primary' } 
            });
            return;
        }

        Swal.fire({
            title: 'Menyimpan Data...',
            html: '<p class="text-slate-500 mt-2 text-sm">Sistem sedang memvalidasi dan merekam data medis ke server...</p>',
            allowOutsideClick: false, showConfirmButton: false, backdrop: true,
            customClass: { popup: 'nexus-modal' },
            willOpen: () => { Swal.showLoading(); }
        });

        const btn = document.getElementById('btnSubmit');
        btn.classList.add('opacity-50', 'cursor-wait');
        btn.disabled = true;
    });
</script>
@endpush
@endsection