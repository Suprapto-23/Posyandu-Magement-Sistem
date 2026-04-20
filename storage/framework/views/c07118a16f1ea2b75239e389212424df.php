<?php $__env->startSection('title', 'Buku Rekam Medis'); ?>
<?php $__env->startSection('page-name', 'Database Medis Warga'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .tab-active { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .patient-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 2px solid transparent; }
    .patient-card:hover { transform: translateY(-5px); border-color: #cffafe; box-shadow: 0 15px 30px -5px rgba(6, 182, 212, 0.15); z-index: 10; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1400px] mx-auto animate-slide-up pb-10">

    
    <div class="bg-gradient-to-br from-cyan-500 via-sky-600 to-blue-700 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_rgba(6,182,212,0.3)] flex flex-col md:flex-row items-center gap-6 border border-cyan-400">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="w-20 h-20 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-inner relative z-10 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="relative z-10 w-full text-center md:text-left flex-1">
            <h2 class="text-3xl md:text-4xl font-black text-white font-poppins tracking-tight mb-2">Arsip Rekam Medis</h2>
            <p class="text-cyan-50 text-[13px] sm:text-sm font-medium max-w-2xl mx-auto md:mx-0 leading-relaxed">
                Pusat data klinis longitudinal. Cari pasien untuk melihat riwayat kunjungan, hasil observasi fisik, dan rekam jejak diagnosa medis.
            </p>
        </div>
    </div>

    
    
    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.03)] flex flex-col mb-6 relative z-[50]">
        <div class="p-5 sm:p-6 bg-slate-50/50 rounded-[32px] flex flex-col gap-5">
            
            
            <div class="flex flex-wrap items-center gap-2.5">
                <?php
                    $tabs = [
                        ['id' => 'balita', 'label' => 'Anak & Balita', 'icon' => 'fa-baby', 'col' => 'rose'],
                        ['id' => 'ibu_hamil', 'label' => 'Ibu Hamil', 'icon' => 'fa-female', 'col' => 'pink'],
                        ['id' => 'remaja', 'label' => 'Usia Remaja', 'icon' => 'fa-user-graduate', 'col' => 'indigo'],
                        ['id' => 'lansia', 'label' => 'Lansia', 'icon' => 'fa-wheelchair', 'col' => 'emerald'],
                    ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('bidan.rekam-medis.index', ['type' => $t['id']])); ?>" class="smooth-route px-5 py-2.5 rounded-xl text-[12px] font-black uppercase tracking-widest transition-all flex items-center gap-2 border <?php echo e($type == $t['id'] ? 'bg-'.$t['col'].'-500 text-white border-'.$t['col'].'-600 tab-active' : 'bg-white text-slate-500 border-slate-200 hover:border-'.$t['col'].'-300 hover:text-'.$t['col'].'-600 shadow-sm'); ?>">
                        <i class="fas <?php echo e($t['icon']); ?> text-lg"></i> <span><?php echo e($t['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <form action="<?php echo e(route('bidan.rekam-medis.index')); ?>" method="GET" class="w-full relative" id="omniSearchContainer">
                <input type="hidden" name="type" value="<?php echo e($type); ?>">
                
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400" id="searchIcon"></i>
                </div>
                
                <input type="text" name="search" id="omniSearchInput" value="<?php echo e(request('search')); ?>" autocomplete="off" placeholder="Ketik nama pasien yang ingin dicari (Lalu tekan Enter)..." class="w-full pl-12 pr-12 py-4 bg-white border-2 border-slate-200 rounded-[16px] text-[14px] font-bold text-slate-700 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 outline-none transition-all shadow-sm">
                
                
                <button type="submit" class="hidden"></button>

                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('bidan.rekam-medis.index', ['type' => $type])); ?>" class="absolute inset-y-0 right-4 flex items-center text-rose-400 hover:text-rose-600 transition-colors">
                        <i class="fas fa-times-circle text-lg"></i>
                    </a>
                <?php endif; ?>

                
                <div id="omniDropdown" class="absolute top-[110%] left-0 w-full bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] border border-slate-200 overflow-hidden hidden transition-all origin-top">
                    <div class="px-4 py-2.5 bg-slate-50/90 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><i class="fas fa-magic text-amber-400 mr-1"></i> Telusuri Otomatis Di Kategori Lain</span>
                    </div>
                    <div class="p-2 space-y-1" id="omniList">
                        
                    </div>
                </div>
            </form>

        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pasien): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="patient-card bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 flex flex-col relative overflow-hidden group">
            
            <?php
                if($type == 'balita') { $bgC = 'bg-rose-50'; $txC = 'text-rose-500'; $icn = 'fa-baby'; $bdC = 'border-rose-100'; }
                elseif($type == 'remaja') { $bgC = 'bg-indigo-50'; $txC = 'text-indigo-500'; $icn = 'fa-user-graduate'; $bdC = 'border-indigo-100'; }
                elseif($type == 'ibu_hamil') { $bgC = 'bg-pink-50'; $txC = 'text-pink-500'; $icn = 'fa-female'; $bdC = 'border-pink-100'; }
                else { $bgC = 'bg-emerald-50'; $txC = 'text-emerald-500'; $icn = 'fa-wheelchair'; $bdC = 'border-emerald-100'; }
            ?>
            
            <div class="absolute -right-8 -top-8 w-32 h-32 <?php echo e($bgC); ?> rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10"></div>

            <div class="flex items-start gap-4 mb-6 z-10">
                <div class="w-14 h-14 rounded-[16px] flex items-center justify-center text-2xl shrink-0 <?php echo e($bgC); ?> <?php echo e($txC); ?> border <?php echo e($bdC); ?> shadow-inner group-hover:bg-cyan-500 group-hover:text-white group-hover:border-cyan-600 transition-colors duration-300">
                    <i class="fas <?php echo e($icn); ?>"></i>
                </div>
                
                <div class="flex-1 min-w-0 pt-1">
                    <h4 class="font-black text-slate-800 text-[16px] mb-1 group-hover:text-cyan-700 transition-colors truncate font-poppins"><?php echo e($pasien->nama_lengkap); ?></h4>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest"><i class="far fa-id-card"></i> <?php echo e($pasien->nik ?? 'TANPA NIK'); ?></p>
                </div>
            </div>

            <div class="mt-auto z-10 space-y-4">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 shadow-sm"><i class="fas fa-birthday-cake"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Usia / Tgl Lahir</p>
                        <p class="text-[12px] font-bold text-slate-700"><?php echo e(\Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d M Y')); ?> <span class="text-cyan-600 ml-1">(<?php echo e(\Carbon\Carbon::parse($pasien->tanggal_lahir)->age); ?> Thn)</span></p>
                    </div>
                </div>

                <a href="<?php echo e(route('bidan.rekam-medis.show', ['pasien_type' => $type, 'pasien_id' => $pasien->id])); ?>" class="smooth-route w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white border-2 border-slate-200 text-slate-600 font-black text-[12px] uppercase tracking-widest rounded-xl hover:bg-cyan-600 hover:text-white hover:border-cyan-600 transition-all shadow-sm group-hover:shadow-[0_8px_20px_rgba(6,182,212,0.3)]">
                    <i class="fas fa-folder-open text-lg"></i> Buka Arsip Medis
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-20 px-4 bg-white rounded-[32px] border border-slate-200 shadow-sm">
            <div class="w-24 h-24 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-5 text-5xl shadow-inner border border-slate-100">
                <i class="fas fa-search-minus"></i>
            </div>
            <h4 class="font-black text-slate-800 text-[18px] font-poppins mb-1">Pencarian Tidak Ditemukan</h4>
            <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto">Pasien "<span class="text-cyan-600 font-bold"><?php echo e(request('search')); ?></span>" tidak ditemukan di kategori ini. Coba klik kategori lain di bawah kolom pencarian.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if($data->hasPages()): ?>
    <div class="mt-8 pagination-wrapper">
        <?php echo e($data->appends(request()->query())->links()); ?>

    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const showLoader = () => { document.getElementById('globalLoader').classList.replace('opacity-0', 'opacity-100'); };
    document.querySelectorAll('.smooth-route, .pagination-wrapper a').forEach(l => l.addEventListener('click', (e) => { if(l.target !== '_blank' && !e.ctrlKey) showLoader(); }));

    // ========================================================
    // DATABASE LIVE SEARCH (AUTOCOMPLETE ENGINE)
    // ========================================================
    const searchInput = document.getElementById('omniSearchInput');
    const searchDropdown = document.getElementById('omniDropdown');
    const searchList = document.getElementById('omniList');
    const searchIcon = document.getElementById('searchIcon');

    let debounceTimer;

    searchInput.addEventListener('input', function() {
        const val = this.value.trim();
        
        // Hapus timer sebelumnya jika user masih mengetik (mencegah spam ke server)
        clearTimeout(debounceTimer);
        
        if (val.length > 0) {
            searchIcon.classList.replace('fa-search', 'fa-spinner');
            searchIcon.classList.add('fa-spin', 'text-cyan-500');
            searchDropdown.classList.remove('hidden');
            
            // Tampilkan status memuat sementara
            searchList.innerHTML = `<div class="p-6 text-center text-[12px] font-bold text-slate-400"><i class="fas fa-circle-notch fa-spin mr-2 text-cyan-500"></i>Mencari data "${val}"...</div>`;
            
            // Tunggu 400ms setelah user berhenti mengetik, baru fetch ke database
            debounceTimer = setTimeout(() => {
                fetch(`<?php echo e(route('bidan.rekam-medis.index')); ?>?search=${encodeURIComponent(val)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Memberi tahu Controller bahwa ini AJAX
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    
                    if (data.length === 0) {
                        html = `<div class="p-6 text-center text-[12px] font-bold text-slate-400">Tidak ada pasien bernama "<span class="text-cyan-600">${val}</span>" di seluruh database.</div>`;
                    } else {
                        data.forEach(pasien => {
                            // Generate URL dinamis berdasarkan data pasien dari Controller
                            let link = "<?php echo e(route('bidan.rekam-medis.show', ['pasien_type' => ':type', 'pasien_id' => ':id'])); ?>";
                            link = link.replace(':type', pasien.type).replace(':id', pasien.id);

                            html += `
                                <a href="${link}" onclick="showLoader()" class="flex items-center justify-between p-3 rounded-xl hover:bg-${pasien.color}-50 border border-transparent hover:border-${pasien.color}-100 transition-colors group cursor-pointer mb-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-[12px] bg-${pasien.color}-100 text-${pasien.color}-600 flex items-center justify-center text-sm shadow-inner border border-${pasien.color}-200"><i class="fas ${pasien.icon}"></i></div>
                                        <div>
                                            <p class="text-[14px] font-bold text-slate-800 font-poppins">${pasien.nama}</p>
                                            <p class="text-[9px] font-black text-${pasien.color}-500 uppercase tracking-widest mt-0.5"><i class="fas fa-folder-open mr-1 opacity-50"></i> Database ${pasien.kategori}</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right text-[12px] text-slate-300 group-hover:text-${pasien.color}-500 group-hover:-translate-x-2 transition-transform"></i>
                                </a>
                            `;
                        });
                    }
                    
                    searchList.innerHTML = html;
                    searchIcon.classList.remove('fa-spin', 'text-cyan-500');
                    searchIcon.classList.replace('fa-spinner', 'fa-search');
                })
                .catch(error => {
                    searchList.innerHTML = `<div class="p-4 text-center text-rose-500 text-xs">Gagal terhubung ke database.</div>`;
                    searchIcon.classList.remove('fa-spin', 'text-cyan-500');
                    searchIcon.classList.replace('fa-spinner', 'fa-search');
                });

            }, 400); // <-- Delay 400ms

        } else {
            searchDropdown.classList.add('hidden');
            searchIcon.classList.remove('fa-spin', 'text-cyan-500');
            searchIcon.classList.replace('fa-spinner', 'fa-search');
        }
    });

    // Menutup dropdown jika klik sembarang tempat di luar kotak pencarian
    document.addEventListener('click', function(e) {
        if (!document.getElementById('omniSearchContainer').contains(e.target)) {
            searchDropdown.classList.add('hidden');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/rekam-medis/index.blade.php ENDPATH**/ ?>