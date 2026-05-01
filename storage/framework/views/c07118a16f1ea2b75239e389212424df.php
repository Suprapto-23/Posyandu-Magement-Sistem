<?php $__env->startSection('title', 'Buku Induk EMR'); ?>
<?php $__env->startSection('page-name', 'Arsip Digital Warga'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Animasi Masuk Halus */
    .fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* List Item Premium */
    .emr-row { 
        background: #ffffff; 
        border-radius: 20px; 
        border: 1px solid #f1f5f9; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .emr-row:hover { 
        transform: translateY(-3px); 
        border-color: #e0f2fe; 
        box-shadow: 0 12px 35px -10px rgba(14, 165, 233, 0.15); 
    }

    /* Tab Switcher */
    .emr-tab { transition: all 0.3s ease; }
    .emr-tab.active { background: #0f172a; color: #ffffff; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15); font-weight: 700; }
    .emr-tab.inactive { background: #ffffff; color: #64748b; border: 1px solid #e2e8f0; font-weight: 600; }
    .emr-tab.inactive:hover { background: #f8fafc; color: #334155; }

    /* Highlight Pencarian */
    .mark-search { background-color: #fde047; color: #854d0e; padding: 0.1em 0.3em; border-radius: 4px; font-weight: 800; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    function safeHighlight($text, $search) {
        if (empty($text)) return '-';
        if (!$search || strlen(trim($search)) < 1) return htmlspecialchars($text);
        $pattern = '/' . preg_quote(trim($search), '/') . '/i';
        return preg_replace($pattern, "<span class='mark-search'>$0</span>", htmlspecialchars($text));
    }
?>

<div class="max-w-[1300px] mx-auto space-y-6 fade-in-up pb-24" x-data="emrSearchApp()">
    
    
    <div class="flex items-center justify-between mb-4 px-2">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-[14px] bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center text-xl shadow-[0_8px_20px_rgba(6,182,212,0.3)] shrink-0">
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h1 class="text-[22px] font-black text-slate-800 tracking-tight leading-none mb-1">Buku Induk EMR</h1>
                <p class="text-[13px] font-medium text-slate-500">Pusat Data Rekam Medis Elektronik Terpadu</p>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-4 items-center bg-white p-3 rounded-[24px] border border-slate-100 shadow-sm">
        
        
        <div class="xl:col-span-7 flex gap-2 overflow-x-auto hide-scrollbar px-1">
            <?php 
                $tabs = [
                    'balita'    => ['icon'=>'baby', 'label'=>'Bayi & Balita'], 
                    'ibu_hamil' => ['icon'=>'female', 'label'=>'Ibu Hamil'], 
                    'remaja'    => ['icon'=>'user-graduate', 'label'=>'Remaja'], 
                    'lansia'    => ['icon'=>'user-clock', 'label'=>'Lansia']
                ]; 
            ?>
            
            <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" 
                        @click="switchTab('<?php echo e($key); ?>')" 
                        class="emr-tab px-6 py-3 rounded-[16px] text-[12px] uppercase tracking-wide flex items-center gap-2 shrink-0"
                        :class="currentType === '<?php echo e($key); ?>' ? 'active' : 'inactive'">
                    <i class="fas fa-<?php echo e($t['icon']); ?> text-[14px]"></i> <?php echo e($t['label']); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="xl:col-span-5 relative px-1">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none z-10">
                <i class="fas fa-search transition-colors duration-300" :class="searchQuery.length > 0 ? 'text-cyan-500 text-[16px]' : 'text-slate-400 text-[14px]'"></i>
            </div>
            
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Ketik 1 huruf nama / NIK..." 
                   class="w-full bg-slate-50 border-transparent rounded-[16px] pl-12 pr-12 py-3.5 text-[14px] font-semibold text-slate-700 focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-50 outline-none transition-all placeholder:font-medium placeholder:text-slate-400">
            
            <div class="absolute inset-y-0 right-4 flex items-center gap-2">
                <i class="fas fa-spinner fa-spin text-cyan-500" x-show="isSearching" x-cloak></i>
                <button type="button" x-show="searchQuery !== '' && !isSearching" @click="clearSearch()" class="text-slate-400 hover:text-rose-500 transition-colors" x-cloak>
                    <i class="fas fa-times-circle text-[18px]"></i>
                </button>
            </div>
        </div>
    </div>

    
    <div id="emr-grid-container" class="relative transition-opacity duration-200" :class="isSearching ? 'opacity-60' : 'opacity-100'">
        
        <div class="flex flex-col gap-3">
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="emr-row flex flex-col lg:flex-row lg:items-center justify-between p-4 md:p-5 gap-4 lg:gap-6 group relative overflow-hidden">
                
                
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-cyan-500 opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-l-full"></div>

                
                <div class="flex items-center gap-4 min-w-[280px] pl-2">
                    <div class="w-12 h-12 rounded-full bg-cyan-50 text-cyan-500 flex items-center justify-center text-[20px] shrink-0 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300 shadow-sm">
                        <i class="fas fa-<?php echo e($type == 'balita' ? 'baby' : ($type == 'ibu_hamil' ? 'female' : 'user')); ?>"></i>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-[15px] font-bold text-slate-800 tracking-tight group-hover:text-cyan-600 transition-colors">
                            <?php echo safeHighlight($row->nama_lengkap, $search); ?>

                        </h3>
                        <p class="text-[12px] font-medium text-slate-400 mt-0.5 font-mono">
                            NIK: <span class="text-slate-500"><?php echo safeHighlight($row->nik, $search); ?></span>
                        </p>
                    </div>
                </div>

                
                <div class="flex-1 flex flex-wrap md:flex-nowrap items-center gap-x-8 gap-y-3 px-2 lg:px-6">
                    
                    
                    <div class="flex items-center gap-2.5 text-[13px] font-semibold text-slate-600 min-w-[110px]">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas <?php echo e($row->jenis_kelamin == 'L' ? 'fa-mars text-blue-500' : 'fa-venus text-pink-500'); ?>"></i>
                        </div>
                        <?php echo e($row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?>

                    </div>

                    
                    <div class="flex items-center gap-2.5 text-[13px] font-semibold text-slate-600 min-w-[100px]">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <?php echo e($row->tanggal_lahir ? \Carbon\Carbon::parse($row->tanggal_lahir)->age : 0); ?> Tahun
                    </div>

                    
                    <div class="flex items-center gap-2.5 text-[13px] font-semibold text-slate-600 truncate flex-1 min-w-[150px]">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fas <?php echo e($type === 'balita' ? 'fa-user-nurse' : 'fa-map-marker-alt'); ?>"></i>
                        </div>
                        <span class="truncate">
                            <?php if($type === 'balita'): ?>
                                <?php echo safeHighlight($row->nama_ibu, $search); ?>

                            <?php else: ?>
                                <?php echo safeHighlight($row->alamat ?? '-', $search); ?>

                            <?php endif; ?>
                        </span>
                    </div>

                </div>

                
                <div class="flex items-center justify-between lg:justify-end gap-5 shrink-0 pl-2 lg:pl-0 border-t lg:border-none border-slate-50 pt-3 lg:pt-0 mt-2 lg:mt-0">
                    <div class="hidden sm:flex items-center gap-2 text-[11px] font-medium text-slate-400">
                        <i class="fas fa-clock"></i>
                        <?php echo e($row->updated_at ? $row->updated_at->diffForHumans() : 'Baru'); ?>

                    </div>
                    
                    <a href="<?php echo e(route('bidan.rekam-medis.show', ['pasien_type' => $type, 'pasien_id' => $row->id])); ?>" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-cyan-50 text-cyan-600 rounded-[12px] text-[12px] font-bold tracking-wide hover:bg-cyan-500 hover:text-white transition-all duration-300 w-full sm:w-auto">
                        BUKA EMR <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-20 text-center bg-white rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center justify-center mt-2">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-5 border-[3px] border-white shadow-sm relative">
                    <div class="absolute inset-0 bg-cyan-400 rounded-full animate-ping opacity-10"></div>
                    <i class="fas fa-search text-4xl text-slate-300 relative z-10"></i>
                </div>
                <h3 class="text-[18px] font-bold text-slate-800 font-poppins mb-2">Arsip Tidak Ditemukan</h3>
                <p class="text-[14px] font-medium text-slate-500 max-w-md leading-relaxed">
                    <?php if($search): ?>
                        Data rekam medis dengan kata kunci <b class="text-rose-500 bg-rose-50 px-2 py-0.5 rounded">"<?php echo e($search); ?>"</b> tidak terdaftar di sistem.
                    <?php else: ?>
                        Belum ada data rekam medis yang masuk ke dalam kluster ini.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        
        <?php if($data->hasPages()): ?>
        <div class="mt-6 bg-white py-3 px-5 rounded-[20px] border border-slate-100 shadow-sm flex items-center justify-center">
            <?php echo e($data->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('emrSearchApp', () => ({
            searchQuery: '<?php echo e($search ?? ''); ?>',
            currentType: '<?php echo e($type ?? 'balita'); ?>',
            isSearching: false,

            init() {
                // Kecepatan di-set menjadi 150ms agar benar-benar instan
                this.$watch('searchQuery', Alpine.debounce((value) => {
                    const trimmed = value.trim();
                    if (trimmed.length >= 1 || trimmed.length === 0) {
                        this.fetchData();
                    }
                }, 150));
            },

            switchTab(type) {
                if(this.currentType === type) return; 
                this.currentType = type;
                this.searchQuery = ''; 
                this.fetchData();
            },

            clearSearch() { 
                this.searchQuery = ''; 
            },

            async fetchData() {
                this.isSearching = true;
                let url = new URL(window.location.origin + '<?php echo e(route('bidan.rekam-medis.index', [], false)); ?>');
                url.searchParams.append('type', this.currentType);
                if(this.searchQuery.trim() !== '') {
                    url.searchParams.append('search', this.searchQuery.trim());
                }
                
                window.history.pushState({}, '', url);

                try {
                    let response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!response.ok) throw new Error("Server Error");
                    
                    let html = await response.text();
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('emr-grid-container').innerHTML = doc.getElementById('emr-grid-container').innerHTML;
                } catch (e) {
                    console.error('AJAX Error:', e);
                    window.location.reload(); 
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