
<?php $__env->startSection('title', 'Pemeriksaan Medis Mandiri'); ?>
<?php $__env->startSection('page-name', 'Input EMR Terpadu'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .med-input { 
        width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 18px; color: #0f172a; font-weight: 600; font-size: 14px; 
        transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); outline: none;
    }
    .med-input:focus { background: #ffffff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    
    .input-group { position: relative; display: flex; align-items: center; }
    .input-group input { padding-right: 3.5rem; }
    .input-group .unit { position: absolute; right: 1.25rem; font-size: 12px; font-weight: 900; color: #94a3b8; pointer-events: none; }

    /* Tab Modern */
    .kat-tab { 
        cursor:pointer; padding: 14px 24px; border-radius: 16px; font-size: 12px; font-weight: 800; 
        letter-spacing: 0.05em; text-transform: uppercase; border: 2px solid transparent; 
        background: white; color: #64748b; transition: all 0.3s ease; 
        display: flex; flex-direction: column; items-center; justify-center; gap: 6px; 
        box-shadow: 0 4px 15px -5px rgba(0,0,0,0.05); min-width: 140px; text-align: center;
    }
    .kat-tab:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08); }
    .kat-tab i { font-size: 24px; margin-bottom: 4px; transition: transform 0.3s ease; }
    
    .kat-tab.active-balita { background: #0ea5e9; color: white; box-shadow: 0 10px 25px -5px rgba(14,165,233,0.4); }
    .kat-tab.active-ibu_hamil { background: #ec4899; color: white; box-shadow: 0 10px 25px -5px rgba(236,72,153,0.4); }
    .kat-tab.active-remaja { background: #8b5cf6; color: white; box-shadow: 0 10px 25px -5px rgba(139,92,246,0.4); }
    .kat-tab.active-lansia { background: #10b981; color: white; box-shadow: 0 10px 25px -5px rgba(16,185,129,0.4); }
    .kat-tab[class*="active-"] i { transform: scale(1.2); }

    .danger-pulse { animation: dangerPulse 2s infinite; }
    @keyframes dangerPulse { 0% { box-shadow: 0 0 0 0 rgba(244,63,94, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(244,63,94, 0); } 100% { box-shadow: 0 0 0 0 rgba(244,63,94, 0); } }
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<?php
    $dbPasien = [
        'balita'    => $balitas->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'nik' => $p->nik, 'jk' => $p->jenis_kelamin ?? 'L', 'info' => 'Usia: ' . (\Carbon\Carbon::parse($p->tanggal_lahir)->age ?? 0) . ' thn'])->toArray(),
        'ibu_hamil' => $ibuHamils->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'nik' => $p->nik, 'jk' => 'P', 'info' => 'HPHT: ' . ($p->hpht ? \Carbon\Carbon::parse($p->hpht)->format('d M Y') : '-')])->toArray(),
        'remaja'    => $remajas->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'nik' => $p->nik, 'jk' => $p->jenis_kelamin ?? 'L', 'info' => 'Sekolah: ' . ($p->sekolah ?? '-')])->toArray(),
        'lansia'    => $lansias->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'nik' => $p->nik, 'jk' => $p->jenis_kelamin ?? 'L', 'info' => 'Usia: ' . (\Carbon\Carbon::parse($p->tanggal_lahir)->age ?? 0) . ' thn'])->toArray(),
    ];
?>


<div x-data="pemeriksaanApp()" x-init="initData(<?php echo \Illuminate\Support\Js::from($dbPasien)->toHtml() ?>)" class="max-w-5xl mx-auto space-y-6 animate-slide-up pb-10">

    
    <div class="flex items-center justify-between gap-4 mb-2">
        <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>" class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-400 hover:text-cyan-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrian
        </a>
    </div>

    <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-[32px] p-8 text-white relative overflow-hidden shadow-[0_10px_40px_rgba(6,182,212,0.3)]">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl shadow-inner">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight leading-none mb-1">Input Pemeriksaan Mandiri</h2>
                <p class="text-[13px] font-medium text-cyan-100">Pilih kategori warga terlebih dahulu untuk membuka formulir cerdas.</p>
            </div>
        </div>
    </div>

    
    <form id="formPemeriksaan" action="<?php echo e(route('bidan.pemeriksaan.store')); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        
        
        <input type="hidden" name="kategori_pasien" x-model="kategori">
        <input type="hidden" name="pasien_id" x-model="pasienId">
        <input type="hidden" name="pasien_type" :value="getModelType()">

        
        
        
        <div>
            <h3 class="text-[14px] font-black text-slate-800 font-poppins mb-4 flex items-center gap-2">
                <i class="fas fa-layer-group text-cyan-500"></i> 1. Pilih Klaster Layanan
            </h3>
            <div class="flex overflow-x-auto pb-4 gap-3 hide-scrollbar">
                <button type="button" @click="setKategori('balita')" class="kat-tab" :class="kategori === 'balita' ? 'active-balita' : ''">
                    <i class="fas fa-child"></i> <span>Balita</span>
                    <span style="font-size:9px;font-weight:700;opacity:0.7">1–5 tahun</span>
                </button>
                
                
                <button type="button" @click="setKategori('bayi')" class="kat-tab" :class="kategori === 'bayi' ? 'active-balita' : ''">
                    <i class="fas fa-baby"></i> <span>Bayi</span>
                    <span style="font-size:9px;font-weight:700;opacity:0.7">0–12 bulan</span>
                </button>
                <button type="button" @click="setKategori('ibu_hamil')" class="kat-tab" :class="kategori === 'ibu_hamil' ? 'active-ibu_hamil' : ''">
                    <i class="fas fa-female"></i> <span>Ibu Hamil</span>
                    <span style="font-size:9px;font-weight:700;opacity:0.7">Bumil</span>
                </button>
                <button type="button" @click="setKategori('remaja')" class="kat-tab" :class="kategori === 'remaja' ? 'active-remaja' : ''">
                    <i class="fas fa-user-graduate"></i> <span>Remaja</span>
                    <span style="font-size:9px;font-weight:700;opacity:0.7">10–18 tahun</span>
                </button>
                <button type="button" @click="setKategori('lansia')" class="kat-tab" :class="kategori === 'lansia' ? 'active-lansia' : ''">
                    <i class="fas fa-user-clock"></i> <span>Lansia</span>
                    <span style="font-size:9px;font-weight:700;opacity:0.7">≥ 60 tahun</span>
                </button>
            </div>
        </div>

        
        
        
        <div x-show="kategori !== ''" x-transition.opacity.duration.300ms x-cloak class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8 relative z-50">
            <h3 class="text-[14px] font-black text-slate-800 font-poppins mb-4 flex items-center gap-2"><i class="fas fa-search text-cyan-500"></i> 2. Cari Identitas Warga</h3>
            
            <div class="relative w-full" @click.away="dropdownOpen = false">
                <label class="med-label" x-text="getLabelPencarian()"></label>
                
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery" 
                        @focus="dropdownOpen = true"
                        @input="dropdownOpen = true"
                        :placeholder="'Ketik nama atau NIK ' + kategori.replace('_', ' ') + '...'" 
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl pl-11 pr-10 py-4 text-sm font-bold text-slate-800 focus:border-cyan-500 focus:bg-white focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-inner"
                    >
                    
                    <button type="button" x-show="pasienId !== ''" @click="resetPasien()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600">
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                </div>

                
                <div x-show="dropdownOpen" x-transition class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 max-h-64 overflow-y-auto custom-scrollbar">
                    
                    <div x-show="filteredPasien().length === 0" class="p-4 text-center text-sm font-bold text-slate-400">
                        Tidak ada warga yang cocok dengan pencarian.
                    </div>

                    <template x-for="p in filteredPasien()" :key="p.id">
                        <div @click="pilihPasien(p)" class="p-4 border-b border-slate-50 hover:bg-cyan-50 cursor-pointer flex items-center gap-3 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                                <i class="fas" :class="p.jk === 'P' ? 'fa-female text-pink-400' : 'fa-male text-sky-400'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800" x-text="p.nama"></p>
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5" x-text="(p.nik ? 'NIK: '+p.nik+' | ' : '') + p.info"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        
        
        <div x-show="pasienId !== ''" x-transition.opacity.duration.500ms x-cloak class="space-y-6">
            
            
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8">
                <h3 class="text-[14px] font-black text-slate-800 font-poppins mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="fas fa-weight text-emerald-500"></i> 3A. Pengukuran Fisik (Antropometri)
                    <span class="ml-auto text-[9px] font-black text-slate-400 uppercase tracking-widest">Meja 2 — Kader</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    
                    
                    <div>
                        <label class="med-label text-emerald-600">
                            Berat Badan <span class="text-rose-500">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="berat_badan" x-model="fisik.bb" required placeholder="0.0" class="med-input focus:border-emerald-400">
                            <span class="unit">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="med-label text-emerald-600">
                            <span x-text="['balita','bayi'].includes(kategori) ? 'Panjang Badan (Telentang)' : 'Tinggi Badan'"></span>
                            <span class="text-rose-500"> *</span>
                        </label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="tinggi_badan" x-model="fisik.tb" required placeholder="0.0" class="med-input focus:border-emerald-400">
                            <span class="unit">cm</span>
                        </div>
                    </div>

                    
                    <div>
                        <label class="med-label">Suhu Tubuh</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="suhu_tubuh" placeholder="36.5" class="med-input" min="35" max="42">
                            <span class="unit">°C</span>
                        </div>
                    </div>

                    
                    <div x-show="!['balita','bayi'].includes(kategori)" x-cloak>
                        <label class="med-label text-rose-500">Tekanan Darah (Tensi)</label>
                        <div class="input-group">
                            <input type="text" name="tekanan_darah" placeholder="120/80" class="med-input border-rose-100 focus:border-rose-400 font-mono">
                            <span class="unit">mmHg</span>
                        </div>
                    </div>

                    
                    <div x-show="['balita','bayi'].includes(kategori)" x-cloak>
                        <label class="med-label text-sky-500">Lingkar Kepala</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lingkar_kepala" placeholder="0.0" class="med-input border-sky-100 focus:border-sky-400">
                            <span class="unit">cm</span>
                        </div>
                    </div>
                    
                    
                    <div x-show="kategori !== ''">
                        <label class="med-label text-pink-600">LiLA (Lingkar Lengan Atas)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lila" x-model="fisik.lila" placeholder="0.0" class="med-input border-pink-100 focus:border-pink-400">
                            <span class="unit">cm</span>
                        </div>
                        
                        <div x-show="isKEK()" x-transition class="mt-2 flex items-center gap-2 px-3 py-2 bg-rose-50 border border-rose-200 rounded-lg danger-pulse">
                            <i class="fas fa-exclamation-circle text-rose-500"></i>
                            <p class="text-[10px] font-bold text-rose-700">Indikator LiLA &lt; 23,5 cm. Bidan yang menentukan status KEK.</p>
                        </div>
                    </div>

                    
                    <div x-show="['lansia','ibu_hamil'].includes(kategori)" x-cloak>
                        <label class="med-label text-emerald-600">Lingkar Perut (LP)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lingkar_perut" placeholder="0.0" class="med-input border-emerald-100 focus:border-emerald-400">
                            <span class="unit">cm</span>
                        </div>
                    </div>

                    
                    <div x-show="kategori === 'ibu_hamil'" x-cloak>
                        <label class="med-label text-pink-600">TFU (Tinggi Fundus Uteri)</label>
                        <div class="input-group">
                            <input type="text" name="tfu" placeholder="28" class="med-input border-pink-100 focus:border-pink-400">
                            <span class="unit">cm</span>
                        </div>
                    </div>

                </div>
            </div>

            
            <div class="bg-white rounded-[32px] border-2 border-cyan-500 shadow-[0_10px_40px_rgba(6,182,212,0.15)] overflow-hidden">
                <div class="px-8 py-5 bg-gradient-to-r from-cyan-600 to-blue-600 flex items-center justify-between">
                    <h3 class="text-[16px] font-black text-white font-poppins flex items-center gap-2">
                        <i class="fas fa-user-md"></i> 3B. Validasi Medis (Meja 5)
                    </h3>
                    <span class="px-3 py-1 bg-white/20 text-white text-[10px] font-black rounded-lg uppercase tracking-widest backdrop-blur-sm" x-text="kategori.replace('_', ' ')"></span>
                </div>
                
                <div class="p-6 md:p-8 space-y-6 bg-slate-50">
                    
                    
                    <div x-show="kategori !== 'balita' && fisik.bb > 0 && fisik.tb > 50" x-transition class="flex items-center justify-between bg-slate-800 text-white p-5 rounded-[20px] shadow-lg">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Analisa Sistem</p>
                            <p class="text-sm font-bold text-slate-200">Indeks Massa Tubuh (IMT)</p>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <p class="text-3xl font-black font-poppins" x-text="hitungIMT().score"></p>
                            <div class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-white shadow-sm" :class="hitungIMT().color" x-text="hitungIMT().status"></div>
                        </div>
                    </div>

                    
                    
                    
                    
                    <div x-show="['balita','bayi'].includes(kategori)" class="grid grid-cols-1 md:grid-cols-3 gap-4 p-5 bg-white border border-slate-200 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-sky-500 uppercase tracking-widest">Status Gizi Balita (Standar WHO/Kemenkes)</p>
                        <div>
                            <label class="med-label text-sky-600">Status BB/U</label>
                            <select name="status_gizi_bb_u" class="med-input border-sky-100 cursor-pointer">
                                <option value="">-- BB/Umur --</option>
                                <option value="BB Sangat Kurang">BB Sangat Kurang (&lt;-3 SD)</option>
                                <option value="BB Kurang">BB Kurang (-3 s/d &lt;-2 SD)</option>
                                <option value="BB Normal">BB Normal (-2 s/d +1 SD)</option>
                                <option value="Risiko BB Lebih">Risiko BB Lebih (&gt;+1 SD)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label text-rose-500">Status TB/U (Stunting)</label>
                            <select name="indikasi_stunting" class="med-input border-rose-200 cursor-pointer bg-rose-50/30">
                                <option value="Normal">Normal (-2 s/d +3 SD)</option>
                                <option value="Pendek">Pendek / Stunted (-3 s/d &lt;-2 SD)</option>
                                <option value="Sangat Pendek">Sangat Pendek (&lt;-3 SD)</option>
                                <option value="Tinggi">Tinggi (&gt;+3 SD)</option>
                            </select>
                        </div>
                        <div>
                            <label class="med-label text-sky-600">Status BB/TB (Wasting)</label>
                            <select name="status_gizi" class="med-input border-sky-100 cursor-pointer">
                                <option value="">-- BB/Tinggi --</option>
                                <option value="Gizi Buruk">Gizi Buruk / Sangat Kurus</option>
                                <option value="Gizi Kurang">Gizi Kurang / Kurus</option>
                                <option value="Gizi Baik">Gizi Baik / Normal</option>
                                <option value="Risiko Lebih">Risiko Berat Lebih</option>
                                <option value="Gizi Lebih">Gizi Lebih / Obesitas</option>
                            </select>
                        </div>
                    </div>

                    
                    <div x-show="kategori === 'ibu_hamil'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-5 bg-pink-50/50 border border-pink-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-pink-600 uppercase tracking-widest">Penilaian Kebidanan & Risiko Kehamilan</p>
                        <div><label class="med-label text-pink-600">HB (Hemoglobin)</label>
                            <div class="input-group"><input type="number" step="0.1" name="hb" class="med-input border-white" placeholder="11.0"><span class="unit">g/dL</span></div></div>
                        <div><label class="med-label text-pink-600">Status Anemia (dari HB)</label>
                            <select name="status_anemia" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak Anemia">Tidak Anemia (HB ≥ 11)</option>
                                <option value="Anemia Ringan">Anemia Ringan (8–10.9)</option>
                                <option value="Anemia Sedang">Anemia Sedang (6–7.9)</option>
                                <option value="Anemia Berat">Anemia Berat (&lt;6)</option>
                            </select></div>
                        <div><label class="med-label text-pink-600">Status KEK (dari LiLA)</label>
                            <select name="status_kek" class="med-input cursor-pointer">
                                <option value="Tidak KEK">Tidak KEK (LiLA ≥ 23.5)</option>
                                <option value="KEK">KEK (LiLA &lt; 23.5 cm)</option>
                            </select></div>
                        <div><label class="med-label text-pink-600">DJJ (Detak Jantung Janin)</label>
                            <div class="input-group"><input type="text" name="djj" class="med-input border-white" placeholder="140"><span class="unit">bpm</span></div></div>
                        <div><label class="med-label text-pink-600">Usia Kehamilan</label>
                            <div class="input-group"><input type="number" step="1" name="usia_kehamilan" class="med-input border-white" placeholder="20"><span class="unit">mgg</span></div></div>
                        <div><label class="med-label text-pink-600">Kategori Risiko</label>
                            <select name="status_risiko" class="med-input cursor-pointer">
                                <option value="">-- Pilih Risiko --</option>
                                <option value="Risiko Rendah">Risiko Rendah</option>
                                <option value="Risiko Sedang">Risiko Sedang</option>
                                <option value="Risiko Tinggi">Risiko Tinggi (Rujuk)</option>
                            </select></div>
                    </div>

                    
                    <div x-show="kategori === 'remaja'" class="grid grid-cols-1 md:grid-cols-3 gap-4 p-5 bg-violet-50/50 border border-violet-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-violet-600 uppercase tracking-widest">Penilaian Gizi & Anemia Remaja</p>
                        <div><label class="med-label text-violet-600">HB (Hemoglobin)</label>
                            <div class="input-group"><input type="number" step="0.1" name="hb" class="med-input border-white" placeholder="12.0"><span class="unit">g/dL</span></div></div>
                        <div><label class="med-label text-violet-600">Status Anemia (dari HB)</label>
                            <select name="status_anemia" class="med-input cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Tidak Anemia">Tidak Anemia (HB ≥ 12)</option>
                                <option value="Anemia Ringan">Anemia Ringan (10–11.9)</option>
                                <option value="Anemia Sedang">Anemia Sedang (8–9.9)</option>
                                <option value="Anemia Berat">Anemia Berat (&lt;8)</option>
                            </select></div>
                        <div><label class="med-label text-violet-600">Status IMT Remaja</label>
                            <select name="status_imt" class="med-input cursor-pointer">
                                <option value="">-- Pilih IMT --</option>
                                <option value="Sangat Kurus">Sangat Kurus</option>
                                <option value="Kurus">Kurus</option>
                                <option value="Normal">Normal</option>
                                <option value="Gemuk">Gemuk</option>
                                <option value="Obesitas">Obesitas</option>
                            </select></div>
                    </div>

                    
                    <div x-show="kategori === 'lansia'" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5 bg-emerald-50/50 border border-emerald-100 rounded-[20px]">
                        <p class="col-span-full text-[9px] font-black text-emerald-600 uppercase tracking-widest">Penilaian Biomedis Lansia</p>
                        <div><label class="med-label text-emerald-600">Gula Darah Sewaktu (GDS) <span class="text-[9px] font-medium text-slate-400 normal-case">jika tersedia alat</span></label>
                            <div class="input-group"><input type="number" step="1" name="gula_darah" class="med-input border-white" placeholder="120"><span class="unit">mg/dL</span></div></div>
                        <div><label class="med-label text-emerald-600">Kolesterol <span class="text-[9px] font-medium text-slate-400 normal-case">jika tersedia alat</span></label>
                            <div class="input-group"><input type="number" step="1" name="kolesterol" class="med-input border-white" placeholder="200"><span class="unit">mg/dL</span></div></div>
                        <div><label class="med-label text-emerald-600">Asam Urat <span class="text-[9px] font-medium text-slate-400 normal-case">jika tersedia alat</span></label>
                            <div class="input-group"><input type="number" step="0.1" name="asam_urat" class="med-input border-white" placeholder="5.5"><span class="unit">mg/dL</span></div></div>
                        <div><label class="med-label text-emerald-600">Skala Kemandirian (Barthel/ABC)</label>
                            <select name="tingkat_kemandirian" class="med-input border-white cursor-pointer">
                                <option value="">-- Pilih Skala --</option>
                                <option value="A">A — Mandiri Sepenuhnya</option>
                                <option value="B">B — Bantuan Sebagian</option>
                                <option value="C">C — Ketergantungan Total</option>
                            </select></div>
                        <div class="md:col-span-2"><label class="med-label text-emerald-600">Status Gizi / IMT Lansia</label>
                            <select name="status_gizi" class="med-input border-white cursor-pointer">
                                <option value="">-- Pilih Status --</option>
                                <option value="Kurus">Kurus (IMT &lt; 18.5)</option>
                                <option value="Normal">Normal (IMT 18.5–24.9)</option>
                                <option value="Gemuk">Gemuk (IMT 25–26.9)</option>
                                <option value="Obesitas">Obesitas (IMT ≥ 27)</option>
                            </select></div>
                    </div>

                    
                    <div class="space-y-5 p-5 bg-white border border-slate-200 rounded-[20px]">
                        <div>
                            <label class="med-label">Kesimpulan / Diagnosa Medis <span class="text-rose-500">*</span></label>
                            <textarea name="diagnosa" rows="2" class="med-input resize-none bg-slate-50" placeholder="Tuliskan diagnosa dari hasil pemeriksaan fisik..."></textarea>
                        </div>
                        <div>
                            <label class="med-label">Tindakan / Resep Obat / Edukasi <span class="text-rose-500">*</span></label>
                            <textarea name="tindakan" rows="2" class="med-input resize-none bg-slate-50" placeholder="Cth: Rujuk ke puskesmas, beri PMT, Vitamin A..."></textarea>
                        </div>
                    </div>
                </div>

                
                <div class="p-6 md:p-8 bg-white border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] font-bold text-slate-500 flex items-center gap-2"><i class="fas fa-lock text-cyan-500"></i> Data otomatis berstatus 'Tervalidasi' Bidan.</p>
                    <button type="submit" id="btnSubmit" class="w-full md:w-auto px-10 py-4 bg-slate-900 text-white font-black text-[13px] uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-[0_10px_20px_rgba(0,0,0,0.15)] hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i> Simpan EMR Final
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ENGINE ALPINE.JS (Menggantikan Vanilla JS berantakan sebelumnya)
    document.addEventListener('alpine:init', () => {
        Alpine.data('pemeriksaanApp', () => ({
            dbRaw: {},
            kategori: '',
            pasienId: '',
            pasienJk: '',
            searchQuery: '',
            dropdownOpen: false,
            fisik: { bb: '', tb: '', lila: '' },

            // Dipanggil saat halaman dimuat
            initData(data) {
                this.dbRaw = data;
            },

            // Mengganti kategori (Balita, Lansia, dll)
            setKategori(kat) {
                this.kategori = kat;
                this.resetPasien();
            },

            // Membersihkan pilihan pasien saat ini
            resetPasien() {
                this.pasienId = '';
                this.pasienJk = '';
                this.searchQuery = '';
                this.dropdownOpen = false;
                this.fisik = { bb: '', tb: '', lila: '' };
            },

            // Saat pasien diklik dari dropdown
            pilihPasien(pasien) {
                this.pasienId = pasien.id;
                this.pasienJk = pasien.jk;
                this.searchQuery = pasien.nama + (pasien.nik ? ' ('+pasien.nik+')' : '');
                this.dropdownOpen = false;
            },

            // Algoritma pencarian Real-Time Client Side
            filteredPasien() {
                if (!this.kategori || !this.dbRaw[this.kategori]) return [];
                const data = this.dbRaw[this.kategori];
                if (this.searchQuery === '' || this.pasienId !== '') return data;
                
                const q = this.searchQuery.toLowerCase();
                return data.filter(p => p.nama.toLowerCase().includes(q) || (p.nik && p.nik.includes(q)));
            },

    // Mapping Model Laravel untuk Backend
            getModelType() {
                const map = {
                    'balita'    : 'App\\Models\\Balita',
                    'bayi'      : 'App\\Models\\Balita',   // Bayi menggunakan tabel balitas
                    'ibu_hamil' : 'App\\Models\\IbuHamil',
                    'remaja'    : 'App\\Models\\Remaja',
                    'lansia'    : 'App\\Models\\Lansia'
                };
                return map[this.kategori] || '';
            },

            // Penamaan label pintar
            getLabelPencarian() {
                if(this.kategori === 'balita') return 'Cari Nama Bayi / Balita';
                if(this.kategori === 'ibu_hamil') return 'Cari Nama Ibu Hamil / NIK';
                return 'Cari Nama Pasien / NIK';
            },

            // Kalkulator IMT Cerdas Bidan
            hitungIMT() {
                let res = { score: '0.0', status: '-', color: 'bg-slate-500' };
                let bb = parseFloat(this.fisik.bb);
                let tb = parseFloat(this.fisik.tb);
                if (bb > 0 && tb > 50) {
                    let tbM = tb / 100;
                    let imt = bb / (tbM * tbM);
                    res.score = imt.toFixed(1);
                    
                    if(imt < 18.5) { res.status = 'Kurus'; res.color = 'bg-amber-500'; }
                    else if(imt < 25) { res.status = 'Normal'; res.color = 'bg-emerald-500'; }
                    else if(imt <= 27) { res.status = 'Gemuk'; res.color = 'bg-orange-500'; }
                    else { res.status = 'Obesitas'; res.color = 'bg-rose-500'; }
                }
                return res;
            },

            // Peringatan KEK Otomatis
            isKEK() {
                let lila = parseFloat(this.fisik.lila);
                if (!lila || lila >= 23.5) return false;
                if (this.kategori === 'ibu_hamil') return true;
                if (this.kategori === 'remaja' && this.pasienJk === 'P') return true;
                return false;
            }
        }));
    });

    // Validasi Submit
    document.getElementById('formPemeriksaan').addEventListener('submit', function(e) {
        if(!document.querySelector('input[name="pasien_id"]').value) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Data Belum Lengkap', text: 'Anda belum memilih identitas warga pada Langkah 2.', confirmButtonColor: '#06b6d4', customClass: { popup: 'rounded-[24px]' } });
            return;
        }

        e.preventDefault();
        Swal.fire({
            title: 'Simpan Medis Permanen?',
            text: "Data akan langsung tersimpan di EMR dan berstatus Tervalidasi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Simpan EMR',
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pemeriksaan/create.blade.php ENDPATH**/ ?>