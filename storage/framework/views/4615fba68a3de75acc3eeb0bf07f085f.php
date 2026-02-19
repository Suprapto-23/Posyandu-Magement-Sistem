<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4 py-3 text-uppercase text-muted small fw-bold">Identitas Balita</th>
                <th class="text-uppercase text-muted small fw-bold">Usia</th>
                <th class="text-uppercase text-muted small fw-bold">Orang Tua</th>
                <th class="text-uppercase text-muted small fw-bold">Jenis Kelamin</th>
                <th class="text-center text-uppercase text-muted small fw-bold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $balitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3 <?php echo e($item->jenis_kelamin == 'L' ? 'bg-info bg-opacity-10 text-info' : 'bg-pink-50 text-danger'); ?>">
                            <?php echo e(substr($item->nama_lengkap, 0, 1)); ?>

                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e($item->nama_lengkap); ?></h6>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                NIK: <?php echo e($item->nik); ?>

                            </small>
                        </div>
                    </div>
                </td>
                <td>
                    <?php
                        $usia = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(\Carbon\Carbon::now());
                    ?>
                    <span class="fw-medium text-dark"><?php echo e($usia->format('%y Thn %m Bln')); ?></span>
                    <br>
                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y')); ?></small>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-medium text-dark"><?php echo e($item->nama_ibu); ?></span>
                        <small class="text-muted" style="font-size: 0.7rem;">Ibu Kandung</small>
                    </div>
                </td>
                <td>
                    <?php if($item->jenis_kelamin == 'L'): ?>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">Laki-laki</span>
                    <?php else: ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Perempuan</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        
                        <a href="#" class="btn btn-sm btn-light text-primary border" title="Lihat Riwayat KMS">
                            <i class="fas fa-file-medical-alt"></i>
                        </a>
                        
                        
                        <a href="<?php echo e(route('bidan.pemeriksaan.create', ['kategori' => 'balita', 'pasien_id' => $item->id])); ?>" class="btn btn-sm btn-info text-white" title="Periksa Sekarang">
                            <i class="fas fa-stethoscope"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-baby fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Data balita tidak ditemukan.</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card-footer bg-white border-0 py-3">
    <?php echo e($balitas->links()); ?>

</div>

<style>
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 1.1rem;
    }
    .bg-pink-50 { background-color: #fff1f2; }
</style><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pasien/partials/table_balita.blade.php ENDPATH**/ ?>