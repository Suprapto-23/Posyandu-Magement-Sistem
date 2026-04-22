<?php $__env->startSection('title', 'Buku Induk EMR'); ?>
<?php $__env->startSection('page-name', 'Arsip Digital Warga'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Highlight Pencarian */
    .mark-search { background-color: #fef08a; color: #854d0e; padding: 0 3px; border-radius: 3px; font-weight: 900; }
    
    /* Tab Compact */
    .tab-active { background: #0891b2; color: white; border-color: #0891b2; box-shadow: 0 4px 10px rgba(8, 145, 178, 0.2); }
    .tab-inactive { background: white; color: #64748b; border-color: #e2e8f0; }
    .tab-inactive:hover { background: #f8fafc; color: #475569; }
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    function highlightText($text, $search) {
        if (!$search || strlen($search) < 3) return $text;
        $pattern = '/' . preg_quote($search, '/') . '/i';
        return preg_replace($pattern, "<span class='mark-search'>$0</span>", $text);
    }
?>

<div class="space-y-5 animate-slide-up pb-10" x-data="emrSearchApp()">
    
    
    <div class="bg-white rounded-[20px] p-5 md:p-6 border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-5 relative z-20">
        
        <div class="flex items-center gap-4 w-full lg:w-auto">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl border border-cyan-100 shadow-inner shrink-0">
                <i class="fas fa-notes-medical"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight font-poppins">Buku Rekam Medis</h1>
                <p class="text-[12px] font-medium text-slate-500">Ketik minimal 3 huruf untuk mencari data otomatis.</p>
            </div>
        </div>
        
        
        <div class="relative w-full lg:w-[400px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                
                <i class="fas fa-search text-slate-400" x-show="!isSearching"></i>
                <i class="fas fa-circle-notch fa-spin text-cyan-500" x-show="isSearching" x-cloak></i>
            </div>
            
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari nama atau NIK..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-[14px] pl-10 pr-10 py-3 text-[13px] font-bold text-slate-700 focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-100 outline-none transition-all">
            
            <button type="button" x-show="searchQuery !== ''" @click="clearSearch()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-rose-400 hover:text-rose-600 transition-colors" x-cloak>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    
    <div class="flex gap-3 overflow-x-auto pb-1 hide-scrollbar">
        <?php $tabs = ['balita' => ['icon'=>'baby', 'label'=>'Bayi & Balita'], 'ibu_hamil' => ['icon'=>'female', 'label'=>'Ibu Hamil'], 'remaja' => ['icon'=>'user-graduate', 'label'=>'Remaja'], 'lansia' => ['icon'=>'user-clock', 'label'=>'Lansia']]; ?>
        
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" 
                    @click="switchTab('<?php echo e($key); ?>')" 
                    class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all border flex items-center gap-2 shrink-0"
                    :class="currentType === '<?php echo e($key); ?>' ? 'tab-active' : 'tab-inactive'">
                <i class="fas fa-<?php echo e($t['icon']); ?> text-sm"></i> <?php echo e($t['label']); ?>

            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div id="emr-grid-container" class="relative min-h-[300px] transition-opacity duration-300" :class="isSearching ? 'opacity-50' : 'opacity-100'">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-lg hover:border-cyan-200 transition-all group flex flex-col relative overflow-hidden">
                
                
                <div class="flex items-start justify-between mb-3 border-b border-slate-50 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-lg text-slate-400 group-hover:bg-cyan-50 group-hover:text-cyan-500 transition-colors shrink-0">
                            <i class="fas fa-<?php echo e($type == 'balita' ? 'baby' : ($type == 'ibu_hamil' ? 'female' : 'user')); ?>"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="text-[14px] font-black text-slate-800 truncate group-hover:text-cyan-700 transition-colors">
                                <?php echo highlightText($row->nama_lengkap, $search); ?>

                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 truncate mt-0.5">NIK: <?php echo highlightText($row->nik, $search); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="flex-1">
                    <div class="flex flex-wrap gap-2 text-[11px] font-bold text-slate-600 mb-4">
                        <span class="bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="fas fa-venus-mars text-slate-300 mr-1"></i> <?php echo e($row->jenis_kelamin == 'L' ? 'L' : 'P'); ?></span>
                        <span class="bg-slate-50 px-2 py-1 rounded border border-slate-100"><i class="fas fa-calendar-alt text-slate-300 mr-1"></i> <?php echo e(\Carbon\Carbon::parse($row->tanggal_lahir)->age); ?> Thn</span>
                    </div>
                </div>

                
                <div class="pt-3 border-t border-slate-50 flex items-center justify-between mt-auto">
                    <div class="text-[9px] font-bold text-slate-400">
                        Update:<br><span class="text-slate-500"><?php echo e($row->updated_at->diffForHumans()); ?></span>
                    </div>
                    <a href="<?php echo e(route('bidan.rekam-medis.show', ['pasien_type' => $type, 'pasien_id' => $row->id])); ?>" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-cyan-600 hover:text-white transition-all">
                        Buka EMR <i class="fas fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <i class="fas fa-folder-open text-4xl text-slate-200 mb-3"></i>
                <h3 class="text-[15px] font-black text-slate-700 font-poppins mb-1">Data Tidak Ditemukan</h3>
                <p class="text-[12px] font-medium text-slate-500 max-w-xs mx-auto">
                    <?php if($search): ?>
                        Tidak ada warga bernama/NIK <b class="text-slate-700">"<?php echo e($search); ?>"</b> di kategori ini.
                    <?php else: ?>
                        Belum ada data warga di kategori ini.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($data->hasPages()): ?>
        <div class="mt-5 bg-white py-3 px-4 rounded-[16px] border border-slate-200 shadow-sm flex justify-center custom-pagination">
            <?php echo e($data->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('emrSearchApp', () => ({
            searchQuery: '<?php echo e($search); ?>',
            currentType: '<?php echo e($type); ?>',
            isSearching: false,

            init() {
                // Konsep RAD: Observasi ketikan, jalankan hanya jika min 3 huruf atau kosong.
                this.$watch('searchQuery', Alpine.debounce((value) => {
                    const trimmed = value.trim();
                    // Cegah request jika huruf kurang dari 3 (kecuali dihapus sampai kosong)
                    if (trimmed.length >= 3 || trimmed.length === 0) {
                        this.fetchData();
                    }
                }, 400)); // Debounce 400ms agar tidak membebani server saat mengetik cepat
            },

            switchTab(type) {
                if(this.currentType === type) return; 
                this.currentType = type;
                this.searchQuery = ''; // Reset pencarian
                this.fetchData();
            },

            clearSearch() {
                this.searchQuery = '';
                // fetchData otomatis terpanggil oleh $watch karena length menjadi 0
            },

            async fetchData() {
                this.isSearching = true;
                
                let url = new URL('<?php echo e(route('bidan.rekam-medis.index')); ?>');
                url.searchParams.append('type', this.currentType);
                if(this.searchQuery.trim() !== '') {
                    url.searchParams.append('search', this.searchQuery.trim());
                }
                
                // Update URL browser tanpa reload
                window.history.pushState({}, '', url);

                try {
                    let response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    let html = await response.text();
                    
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContent = doc.getElementById('emr-grid-container').innerHTML;
                    
                    document.getElementById('emr-grid-container').innerHTML = newContent;
                } catch (e) {
                    console.error('Fetch error:', e);
                } finally {
                    this.isSearching = false;
                }
            }
        }));
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/rekam-medis/index.blade.php ENDPATH**/ ?>