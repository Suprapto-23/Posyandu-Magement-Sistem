

<?php $__env->startSection('title', 'Tambah Imunisasi'); ?>
<?php $__env->startSection('page-name', 'Input Vaksinasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    .med-input { 
        width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; 
        padding: 14px 18px; color: #0f172a; font-weight: 600; font-size: 13px; 
        transition: all 0.3s ease; outline: none;
    }
    .med-input:focus { background: #ffffff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    
    .tab-kategori { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 2px solid transparent; }
    .tab-kategori.active-balita { background: #ecfeff; color: #0891b2; border-color: #a5f3fc; box-shadow: 0 4px 15px rgba(6,182,212,0.15); transform: translateY(-2px); }
    .tab-kategori.active-bumil { background: #fdf2f8; color: #db2777; border-color: #fbcfe8; box-shadow: 0 4px 15px rgba(219,39,119,0.15); transform: translateY(-2px); }
    .tab-kategori.inactive { background: #f8fafc; color: #94a3b8; border-color: #e2e8f0; }
    .tab-kategori.inactive:hover { background: #f1f5f9; color: #64748b; }
    
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $dataBalita = $balitas->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'tipe' => 'Balita', 'model' => 'App\\Models\\Balita'])->values()->toArray();
    $dataBumil  = $ibuHamils->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'tipe' => 'Ibu Hamil', 'model' => 'App\\Models\\IbuHamil'])->values()->toArray();
?>

<div class="max-w-4xl mx-auto space-y-6 animate-slide-up pb-20" x-data="formImunisasiApp()">
    
    <a href="<?php echo e(route('bidan.imunisasi.index')); ?>" class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-400 hover:text-cyan-600 transition-colors mb-2">
        <i class="fas fa-arrow-left"></i> Kembali ke Register
    </a>

    <form id="formImunisasi" action="<?php echo e(route('bidan.imunisasi.store')); ?>" method="POST" class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_30px_rgb(0,0,0,0.03)] relative z-10">
        <?php echo csrf_field(); ?>
        
        
        <input type="hidden" name="dosis" value="Sesuai Vaksin">

        <div class="p-6 md:p-8 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex flex-col sm:flex-row sm:items-center gap-4 rounded-t-[28px]">
            <div class="w-14 h-14 rounded-[16px] bg-cyan-100 text-cyan-600 flex items-center justify-center text-2xl shadow-inner shrink-0">
                <i class="fas fa-shield-virus"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight font-poppins">Pencatatan Imunisasi Baru</h2>
                <p class="text-[12px] font-medium text-slate-500 mt-1">Pilih kategori layanan KIA, lalu cari identitas penerima vaksin.</p>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            
            
            <div>
                <label class="med-label text-cyan-600"><i class="fas fa-layer-group mr-1"></i> 1. Pilih Kategori Penerima Vaksin</label>
                <div class="flex flex-col sm:flex-row gap-4 mt-3">
                    <button type="button" @click="setKategori('balita')" class="tab-kategori flex-1 flex items-center justify-center gap-3 py-4 rounded-2xl text-[13px] font-black uppercase tracking-widest outline-none" :class="kategori === 'balita' ? 'active-balita' : 'inactive'">
                        <i class="fas fa-baby text-xl"></i> Bayi & Balita
                    </button>
                    <button type="button" @click="setKategori('ibu_hamil')" class="tab-kategori flex-1 flex items-center justify-center gap-3 py-4 rounded-2xl text-[13px] font-black uppercase tracking-widest outline-none" :class="kategori === 'ibu_hamil' ? 'active-bumil' : 'inactive'">
                        <i class="fas fa-female text-xl"></i> Ibu Hamil (TT)
                    </button>
                </div>
            </div>

            
            <div x-show="kategori !== ''" x-transition.opacity x-cloak class="relative w-full" @click.away="dropdownOpen = false">
                <label class="med-label text-cyan-600"><i class="fas fa-search mr-1"></i> 2. Cari Identitas <span x-text="kategori === 'balita' ? 'Balita' : 'Ibu Hamil'"></span></label>
                <input type="hidden" name="pasien_id" x-model="pasienId" required>
                <input type="hidden" name="pasien_type" x-model="pasienModel" required>
                <div class="relative mt-2">
                    <input type="text" x-model="searchQuery" @focus="dropdownOpen = true" @input="dropdownOpen = true" :placeholder="'Ketik nama ' + (kategori === 'balita' ? 'balita' : 'ibu hamil') + '...'" class="w-full bg-slate-50 border-2 border-slate-200 rounded-[16px] pl-5 pr-12 py-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
                    <button type="button" x-show="pasienId !== ''" @click="resetSelection()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600 outline-none" x-cloak><i class="fas fa-times-circle text-xl"></i></button>
                </div>
                <div x-show="dropdownOpen" x-transition x-cloak class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] border border-slate-100 max-h-64 overflow-y-auto custom-scrollbar">
                    <template x-for="k in filteredData()" :key="k.id">
                        <div @click="selectPasien(k)" class="p-4 border-b border-slate-50 cursor-pointer flex items-center gap-4 transition-colors" :class="kategori === 'balita' ? 'hover:bg-cyan-50' : 'hover:bg-pink-50'">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border" :class="kategori === 'balita' ? 'bg-cyan-100 text-cyan-600 border-cyan-200' : 'bg-pink-100 text-pink-600 border-pink-200'"><i class="fas" :class="kategori === 'balita' ? 'fa-baby' : 'fa-female'"></i></div>
                            <div>
                                <p class="text-[13px] font-bold text-slate-800 font-poppins" x-text="k.nama"></p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5" x-text="k.tipe"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredData().length === 0" class="p-8 text-center text-[12px] font-bold text-slate-400 bg-slate-50/50">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100"><i class="fas fa-search-minus text-2xl text-slate-300"></i></div>
                        Tidak ada kecocokan data.
                    </div>
                </div>
            </div>

            
            <div x-show="pasienId !== ''" x-transition.opacity x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-8 border-t border-slate-100">
                <div class="col-span-1 md:col-span-2 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-sm font-black">3</div>
                    <label class="text-[12px] font-black text-slate-800 uppercase tracking-widest font-poppins">Detail Injeksi Medis</label>
                </div>
                
                <div>
                    <label class="med-label">Kategori Program <span class="text-rose-500">*</span></label>
                    <select name="jenis_imunisasi" class="med-input cursor-pointer focus:ring-4 focus:ring-cyan-50" required>
                        <option value="">-- Pilih Program --</option>
                        <option value="Imunisasi Dasar" x-show="kategori === 'balita'">Imunisasi Dasar (0-11 Bulan)</option>
                        <option value="Imunisasi Lanjutan" x-show="kategori === 'balita'">Imunisasi Lanjutan (Baduta)</option>
                        <option value="Imunisasi TT" x-show="kategori === 'ibu_hamil'">Imunisasi TT (Ibu Hamil)</option>
                        <option value="Lainnya">Tambahan / Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="med-label">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_imunisasi" value="<?php echo e(date('Y-m-d')); ?>" class="med-input cursor-pointer focus:ring-4 focus:ring-cyan-50" required>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="med-label">Nama Vaksin (Termasuk Dosis) <span class="text-rose-500">*</span></label>
                    <input type="text" name="vaksin" required placeholder="Contoh: Polio 1, DPT-HB-Hib 2, TT 1, Campak..." class="med-input text-[14px] py-4 focus:ring-4 focus:ring-cyan-50">
                    <p class="text-[10px] font-bold text-slate-400 mt-2"><i class="fas fa-info-circle"></i> Tuliskan nama vaksin beserta urutan dosisnya secara langsung.</p>
                </div>

                <div class="col-span-1 md:col-span-2 mt-2">
                    <div class="p-5 rounded-2xl border-2 border-amber-200 bg-amber-50/50">
                        <label class="med-label text-amber-600 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Kejadian Ikutan Pasca Imunisasi (KIPI)</label>
                        <textarea name="keterangan" rows="2" placeholder="Kosongkan jika kondisi pasien aman. Catat jika ada keluhan (demam, bengkak)..." class="w-full bg-white border border-amber-200 rounded-xl p-4 text-sm font-medium text-slate-700 outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100 transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            
            <div x-show="pasienId !== ''" x-transition x-cloak class="pt-8 border-t border-slate-100 flex justify-end">
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-[13px] uppercase tracking-widest rounded-2xl hover:shadow-[0_10px_20px_rgba(6,182,212,0.3)] hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-save text-lg"></i> Simpan Catatan Vaksin
                </button>
            </div>

        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formImunisasiApp', () => ({
            dbRaw: { balita: <?php echo json_encode($dataBalita); ?>, ibu_hamil: <?php echo json_encode($dataBumil); ?> },
            kategori: '', searchQuery: '', pasienId: '', pasienModel: '', dropdownOpen: false,
            
            setKategori(kat) { this.kategori = kat; this.resetSelection(); },
            filteredData() {
                if (this.kategori === '') return [];
                const data = this.dbRaw[this.kategori];
                if (this.searchQuery === '' || this.pasienId !== '') return data;
                return data.filter(k => k.nama.toLowerCase().includes(this.searchQuery.toLowerCase()));
            },
            selectPasien(k) { this.pasienId = k.id; this.pasienModel = k.model; this.searchQuery = k.nama; this.dropdownOpen = false; },
            resetSelection() { this.pasienId = ''; this.pasienModel = ''; this.searchQuery = ''; this.dropdownOpen = false; }
        }))
    });

    document.getElementById('formImunisasi').addEventListener('submit', function(e) {
        if(!document.querySelector('input[name="pasien_id"]').value) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Silakan cari dan pilih identitas warga terlebih dahulu.', confirmButtonColor: '#06b6d4', customClass: { popup: 'rounded-[24px]' } });
            return;
        }
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-80', 'cursor-wait', 'scale-95');
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/create.blade.php ENDPATH**/ ?>