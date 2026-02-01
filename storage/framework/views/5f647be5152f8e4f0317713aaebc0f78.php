

<?php $__env->startSection('title', 'Jadwal Posyandu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar me-2"></i>Jadwal Posyandu
        </h1>
        <a href="<?php echo e(route('kader.jadwal.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tambah Jadwal
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('kader.jadwal.index')); ?>" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari judul atau deskripsi..." 
                                   value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="dibatalkan" <?php echo e(request('status') == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="<?php echo e(route('kader.jadwal.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-sync me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-calendar-alt me-2"></i>Data Jadwal Posyandu
            </h6>
        </div>
        <div class="card-body">
            <?php if($jadwals->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kategori</th>
                            <th>Target Peserta</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($jadwal->judul); ?></strong><br>
                                <small class="text-muted"><?php echo e(Str::limit($jadwal->deskripsi, 50)); ?></small>
                            </td>
                            <td>
                                <strong><?php echo e($jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') : '-'); ?></strong><br>
                                <small class="text-muted">
                                    <?php echo e($jadwal->waktu_mulai); ?> - <?php echo e($jadwal->waktu_selesai); ?>

                                </small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($jadwal->kategori == 'imunisasi' ? 'success' : ($jadwal->kategori == 'pemeriksaan' ? 'primary' : 'warning')); ?>">
                                    <?php echo e(ucfirst($jadwal->kategori)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($jadwal->target_peserta == 'semua'): ?>
                                <span class="badge bg-info">Semua</span>
                                <?php elseif($jadwal->target_peserta == 'balita'): ?>
                                <span class="badge bg-danger">Balita</span>
                                <?php elseif($jadwal->target_peserta == 'remaja'): ?>
                                <span class="badge bg-secondary">Remaja</span>
                                <?php elseif($jadwal->target_peserta == 'lansia'): ?>
                                <span class="badge bg-dark">Lansia</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Ibu Hamil</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($jadwal->status == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php elseif($jadwal->status == 'selesai'): ?>
                                <span class="badge bg-secondary">Selesai</span>
                                <?php else: ?>
                                <span class="badge bg-danger">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($jadwal->creator->profile->full_name ?? 'Sistem'); ?><br>
                                <small class="text-muted"><?php echo e($jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') : '-'); ?></small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('kader.jadwal.show', $jadwal->id)); ?>" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('kader.jadwal.edit', $jadwal->id)); ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($jadwal->status == 'aktif'): ?>
                                    <button type="button" class="btn btn-success btn-sm" 
                                            title="Broadcast" data-bs-toggle="modal" 
                                            data-bs-target="#broadcastModal<?php echo e($jadwal->id); ?>">
                                        <i class="fas fa-bullhorn"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            title="Hapus" data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?php echo e($jadwal->id); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Broadcast Modal -->
                                <div class="modal fade" id="broadcastModal<?php echo e($jadwal->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Broadcast Jadwal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Kirim notifikasi jadwal ini ke semua user?</p>
                                                <div class="alert alert-info">
                                                    <strong><?php echo e($jadwal->judul); ?></strong><br>
                                                    <?php echo e($jadwal->deskripsi); ?><br>
                                                    <strong>Tanggal:</strong> <?php echo e($jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') : '-'); ?><br>
                                                    <strong>Waktu:</strong> <?php echo e($jadwal->waktu_mulai); ?> - <?php echo e($jadwal->waktu_selesai); ?><br>
                                                    <strong>Lokasi:</strong> <?php echo e($jadwal->lokasi); ?>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="<?php echo e(route('kader.jadwal.broadcast', $jadwal->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-bullhorn me-2"></i>Ya, Broadcast
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal<?php echo e($jadwal->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                                                <div class="alert alert-warning">
                                                    <strong><?php echo e($jadwal->judul); ?></strong><br>
                                                    <strong>Tanggal:</strong> <?php echo e($jadwal->tanggal ? \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') : '-'); ?><br>
                                                    <strong>Waktu:</strong> <?php echo e($jadwal->waktu_mulai); ?> - <?php echo e($jadwal->waktu_selesai); ?><br>
                                                    <strong>Lokasi:</strong> <?php echo e($jadwal->lokasi); ?>

                                                </div>
                                                <p class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Batal
                                                </button>
                                                <form action="<?php echo e(route('kader.jadwal.destroy', $jadwal->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash me-1"></i>Ya, Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <?php echo e($jadwals->links()); ?>

            </div>
            <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                <?php if(request('search') || request('status')): ?>
                    Tidak ditemukan jadwal dengan filter yang dipilih.
                <?php else: ?>
                    Belum ada jadwal posyandu. Silakan tambah jadwal terlebih dahulu.
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Jadwal Mendatang -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calendar-check me-2"></i>Jadwal Mendatang (7 Hari)
                    </h6>
                </div>
                <div class="card-body">
                    <?php
                        $jadwal_mendatang = \App\Models\JadwalPosyandu::where('tanggal', '>=', now())
                            ->where('tanggal', '<=', now()->addDays(7))
                            ->where('status', 'aktif')
                            ->orderBy('tanggal')
                            ->take(5)
                            ->get();
                    ?>
                    
                    <?php if($jadwal_mendatang->count() > 0): ?>
                    <div class="list-group">
                        <?php $__currentLoopData = $jadwal_mendatang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('kader.jadwal.show', $jadwal->id)); ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo e($jadwal->judul); ?></h6>
                                <small><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d/m')); ?></small>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i><?php echo e($jadwal->waktu_mulai); ?> - <?php echo e($jadwal->waktu_selesai); ?>

                                <i class="fas fa-map-marker-alt ms-2 me-1"></i><?php echo e(Str::limit($jadwal->lokasi, 30)); ?>

                            </small>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p>Tidak ada jadwal dalam 7 hari mendatang</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Jadwal
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="jadwalChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<?php if($jadwals->count() > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Jadwal statistics chart
    const jadwalCtx = document.getElementById('jadwalChart').getContext('2d');
    
    <?php
        $aktif = \App\Models\JadwalPosyandu::where('status', 'aktif')->count();
        $selesai = \App\Models\JadwalPosyandu::where('status', 'selesai')->count();
        $dibatalkan = \App\Models\JadwalPosyandu::where('status', 'dibatalkan')->count();
    ?>
    
    new Chart(jadwalCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: [<?php echo e($aktif); ?>, <?php echo e($selesai); ?>, <?php echo e($dibatalkan); ?>],
                backgroundColor: ['#1cc88a', '#6c757d', '#e74a3b'],
                borderColor: ['#1cc88a', '#6c757d', '#e74a3b'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
});
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<style>
.list-group-item:hover {
    background-color: #f8f9fa;
}
.badge {
    font-size: 0.75em;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/jadwal/index.blade.php ENDPATH**/ ?>