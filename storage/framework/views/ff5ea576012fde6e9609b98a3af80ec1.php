

<?php $__env->startSection('title', 'Laporan Bulanan'); ?>
<?php $__env->startSection('page-title', 'Laporan Bulanan'); ?>
<?php $__env->startSection('page-subtitle', 'Generate dan cetak laporan pemeriksaan posyandu'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom fw-semibold rounded-top-4 py-3">
        <i class="fas fa-filter me-2 text-primary"></i>Pilih Periode & Jenis Laporan
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('bidan.laporan.index')); ?>" class="row g-3 align-items-end" id="formFilter">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-muted small">Periode Bulan</label>
                <select name="bulan" class="form-select form-select-lg bg-light" id="selectBulan">
                    <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($b); ?>" <?php echo e($bulan == $b ? 'selected' : ''); ?>>
                            <?php echo e(Carbon\Carbon::create()->month($b)->translatedFormat('F')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted small">Tahun</label>
                <input type="number" name="tahun" class="form-control form-control-lg bg-light" id="inputTahun"
                       value="<?php echo e($tahun); ?>" min="2020" max="2030">
            </div>
            
            <div class="col-md-4">
                <label class="form-label fw-semibold text-primary small">Pilih Kategori Laporan</label>
                <select name="jenis" class="form-select form-select-lg border-primary shadow-sm" id="selectJenis">
                    <option value="semua" <?php echo e($jenis == 'semua' ? 'selected' : ''); ?>>📊 Semua Kategori (Rekap Total)</option>
                    <option value="balita" <?php echo e($jenis == 'balita' ? 'selected' : ''); ?>>👶 Khusus Laporan Balita</option>
                    <option value="remaja" <?php echo e($jenis == 'remaja' ? 'selected' : ''); ?>>🎓 Khusus Laporan Remaja</option>
                    <option value="lansia" <?php echo e($jenis == 'lansia' ? 'selected' : ''); ?>>🧓 Khusus Laporan Lansia</option>
                </select>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg flex-fill shadow-sm">
                    <i class="fas fa-search me-2"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-dark fw-bold mb-0">
            Ringkasan Data — <?php echo e(Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')); ?>

            <?php if($jenis !== 'semua'): ?> 
                <span class="badge bg-primary ms-2"><?php echo e(ucfirst($jenis)); ?></span>
            <?php endif; ?>
        </h6>
    </div>

    <?php
        // Filter card yang tampil berdasarkan jenis laporan yang dipilih
        $allCards = [
            ['Total Kunjungan',    $ringkasan['total'] ?? 0,      'primary', 'list', 'semua'],
            ['Balita',             $ringkasan['balita'] ?? 0,     'info',    'baby', 'balita'],
            ['Remaja',             $ringkasan['remaja'] ?? 0,     'success', 'user-graduate', 'remaja'],
            ['Lansia',             $ringkasan['lansia'] ?? 0,     'warning', 'wheelchair', 'lansia'],
            ['Sudah Diverifikasi', $ringkasan['verified'] ?? 0,   'success', 'check-circle', 'semua'],
            ['Belum Diverifikasi', $ringkasan['pending'] ?? 0,    'warning', 'clock', 'semua'],
            ['Kasus Stunting',     $ringkasan['stunting'] ?? 0,   'danger',  'exclamation-triangle', 'balita'],
            ['Kasus Hipertensi',   $ringkasan['hipertensi'] ?? 0, 'danger',  'heartbeat', 'lansia'],
        ];

        // Menyaring kartu (card) apa saja yang relevan untuk ditampilkan
        $displayCards = array_filter($allCards, function($card) use ($jenis) {
            return $jenis == 'semua' || $card[4] == 'semua' || $card[4] == $jenis;
        });
    ?>

    <?php $__currentLoopData = $displayCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $nilai, $color, $icon, $target]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-<?php echo e($color); ?> border-3">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="rounded-circle bg-<?php echo e($color); ?> bg-opacity-10 p-3 flex-shrink-0">
                    <i class="fas fa-<?php echo e($icon); ?> text-<?php echo e($color); ?> fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1 text-dark"><?php echo e($nilai); ?></div>
                    <div class="text-muted mt-1" style="font-size:0.75rem; font-weight: 500;"><?php echo e($label); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center rounded-top-4 py-3">
        <span class="fw-bold text-dark">
            <i class="fas fa-table me-2 text-primary"></i>
            Preview Dokumen (<?php echo e($data->count()); ?> Data)
        </span>
        
       
        <form action="<?php echo e(route('bidan.laporan.cetak')); ?>" method="GET" target="_blank" class="m-0 p-0">
            <input type="hidden" name="bulan" value="<?php echo e($bulan ?? date('n')); ?>">
            <input type="hidden" name="tahun" value="<?php echo e($tahun ?? date('Y')); ?>">
            <input type="hidden" name="kategori" value="<?php echo e($jenis ?? 'semua'); ?>">
            
            <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm rounded-pill" <?php echo e(isset($data) && $data->count() == 0 ? 'disabled' : ''); ?>>
                <i class="fas fa-print me-1"></i> Cetak / Simpan PDF
            </button>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Kategori</th>
                        <th>Pemeriksaan Fisik</th>
                        <th>Status Gizi / Kesehatan</th>
                        <th>Diagnosa Bidan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?php echo e($i + 1); ?></td>
                        <td class="small"><?php echo e(\Carbon\Carbon::parse($item->tanggal_periksa)->format('d/m/Y')); ?></td>
                        <td class="fw-bold text-dark small"><?php echo e($item->nama_pasien); ?></td>
                        <td>
                            <?php
                                $badgeColor = match($item->kategori_pasien) {
                                    'balita' => 'info',
                                    'remaja' => 'success',
                                    'lansia' => 'warning',
                                    default => 'secondary'
                                };
                            ?>
                            <span class="badge bg-<?php echo e($badgeColor); ?> bg-opacity-10 text-<?php echo e($badgeColor); ?> border border-<?php echo e($badgeColor); ?>">
                                <?php echo e(ucfirst($item->kategori_pasien)); ?>

                            </span>
                        </td>
                        <td class="small text-muted">
                            BB: <span class="text-dark fw-semibold"><?php echo e($item->berat_badan ?? '-'); ?> kg</span><br>
                            TB: <span class="text-dark fw-semibold"><?php echo e($item->tinggi_badan ?? '-'); ?> cm</span>
                        </td>
                        <td>
                            <?php if(in_array(strtolower($item->status_gizi), ['stunting', 'buruk', 'risiko', 'bahaya'])): ?>
                                <span class="badge bg-danger"><?php echo e(strtoupper($item->status_gizi)); ?></span>
                            <?php else: ?>
                                <span class="text-capitalize small fw-semibold"><?php echo e($item->status_gizi ?? '-'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="small" style="max-width: 200px;">
                            <?php if($item->diagnosa): ?>
                                <div class="text-truncate" title="<?php echo e($item->diagnosa); ?>">
                                    <?php echo e(Str::limit($item->diagnosa, 40)); ?>

                                </div>
                            <?php else: ?>
                                <span class="text-danger fst-italic"><i class="fas fa-exclamation-circle me-1"></i>Belum divalidasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open fa-3x text-muted opacity-25"></i>
                            </div>
                            <h6 class="text-muted fw-bold">Data Kosong</h6>
                            <p class="text-muted small mb-0">Tidak ada data pemeriksaan <?php echo e($jenis == 'semua' ? '' : $jenis); ?> pada periode ini.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/laporan/index.blade.php ENDPATH**/ ?>