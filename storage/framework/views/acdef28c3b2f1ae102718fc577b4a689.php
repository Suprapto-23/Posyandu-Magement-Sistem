

<?php $__env->startSection('title', 'Tambah Imunisasi'); ?>
<?php $__env->startSection('page-name', 'Input Vaksinasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .med-input { width: 100%; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; font-weight: 600; font-size: 13px; transition: all 0.3s ease; outline: none; }
    .med-input:focus { background: #ffffff; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6,182,212,0.1); }
    .med-label { display: block; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    .tab-kategori { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pb-20" x-data="imunisasiHandler()">
    <form action="<?php echo e(route('bidan.imunisasi.store')); ?>" method="POST" id="formImunisasi" class="space-y-6">
        <?php echo csrf_field(); ?>

        
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
            <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center text-xs">01</span>
                Pilih Identitas Warga
            </h3>

            <div class="grid grid-cols-2 gap-4 mb-8 p-1.5 bg-slate-100 rounded-2xl">
                <div @click="setKategori('balita')" :class="kategori === 'balita' ? 'bg-white text-cyan-600 shadow-md' : 'text-slate-500 hover:text-slate-700'" class="tab-kategori py-3 rounded-xl text-center font-black text-[13px] uppercase tracking-widest">
                    <i class="fas fa-baby mr-2"></i> Balita
                </div>
                <div @click="setKategori('ibu_hamil')" :class="kategori === 'ibu_hamil' ? 'bg-white text-pink-600 shadow-md' : 'text-slate-500 hover:text-slate-700'" class="tab-kategori py-3 rounded-xl text-center font-black text-[13px] uppercase tracking-widest">
                    <i class="fas fa-female mr-2"></i> Ibu Hamil
                </div>
            </div>

            <div class="relative" x-show="kategori !== ''">
                <label class="med-label">Cari Nama / NIK Pasien</label>
                <input type="text" x-model="searchQuery" @input="dropdownOpen = true" placeholder="Ketik minimal 3 huruf..." class="med-input">
                
                <input type="hidden" name="pasien_id" :value="pasienId">
                <input type="hidden" name="pasien_type" :value="pasienModel">

                <div x-show="dropdownOpen && filteredData().length > 0" @click.away="dropdownOpen = false" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-60 overflow-y-auto p-2">
                    <template x-for="k in filteredData()" :key="k.id">
                        <div @click="selectPasien(k)" class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-xl cursor-pointer border-b border-slate-50 last:border-0 transition-colors">
                            <div>
                                <p class="font-black text-slate-800 text-[14px]" x-text="k.nama"></p>
                                <p class="text-[11px] text-slate-400 font-bold tracking-tight">NIK: <span x-text="k.nik"></span></p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100" x-show="pasienId !== ''">
            <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center text-xs">02</span>
                Detail Vaksinasi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="med-label">Nama Vaksin <span class="text-rose-500">*</span></label>
                    <input list="list-vaksin" name="vaksin" required placeholder="Pilih atau ketik nama vaksin..." class="med-input">
                    <datalist id="list-vaksin">
                        <option value="Hepatitis B (HB0)">
                        <option value="BCG">
                        <option value="Polio 1">
                        <option value="DPT-HB-Hib 1">
                        <option value="Campak Rubella (MR)">
                        <option value="Tetanus Toxoid (TT) 1">
                    </datalist>
                </div>

                <div>
                    <label class="med-label">Dosis (Urutan)</label>
                    <input type="text" name="dosis" placeholder="Cth: I, II, atau Booster" class="med-input">
                </div>

                <div>
                    <label class="med-label">Tanggal Injeksi <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_imunisasi" value="<?php echo e(date('Y-m-d')); ?>" required class="med-input">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="med-label">Catatan Tambahan / KIPI</label>
                    <textarea name="keterangan" rows="3" placeholder="Tulis gejala pasca injeksi jika ada..." class="med-input"></textarea>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 mt-8 flex justify-end">
                <button type="submit" id="btnSubmit" class="px-10 py-4 bg-cyan-600 text-white rounded-2xl font-black shadow-lg shadow-cyan-200 hover:bg-cyan-700 transition-all flex items-center gap-3">
                    <i class="fas fa-save"></i> Simpan Rekam Medis
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function imunisasiHandler() {
        return {
            kategori: '',
            searchQuery: '',
            pasienId: '',
            pasienModel: '',
            dropdownOpen: false,
            dbRaw: {
                balita: <?php echo json_encode($balitas->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_lengkap, 'nik' => $b->nik, 'model' => 'App\\Models\\Balita'])); ?>,
                ibu_hamil: <?php echo json_encode($ibuHamils->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_lengkap, 'nik' => $i->nik, 'model' => 'App\\Models\\IbuHamil'])); ?>

            },
            setKategori(kat) { this.kategori = kat; this.pasienId = ''; },
            filteredData() {
                if (!this.kategori) return [];
                let data = this.dbRaw[this.kategori];
                return data.filter(i => i.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) || i.nik.includes(this.searchQuery));
            },
            selectPasien(k) { this.pasienId = k.id; this.pasienModel = k.model; this.searchQuery = k.nama; this.dropdownOpen = false; }
        };
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/create.blade.php ENDPATH**/ ?>