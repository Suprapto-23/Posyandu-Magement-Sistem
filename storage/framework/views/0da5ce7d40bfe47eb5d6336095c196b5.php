

<?php $__env->startSection('title', 'Import Data'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-import me-2"></i>Import Data
        </h1>
        <div>
            <a href="<?php echo e(route('kader.import.history')); ?>" class="btn btn-secondary">
                <i class="fas fa-history me-2"></i>Riwayat Import
            </a>
        </div>
    </div>

    <!-- Cards -->
    <div class="row">
        <!-- Balita Import Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Import Data Balita
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Upload data balita dalam format Excel
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('kader.import.create')); ?>?type=balita" class="btn btn-outline-danger btn-sm me-2">
                            <i class="fas fa-upload me-2"></i>Upload
                        </a>
                        <a href="<?php echo e(route('kader.import.download-template', 'balita')); ?>" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download me-2"></i>Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remaja Import Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Import Data Remaja
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Upload data remaja dalam format Excel
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('kader.import.create')); ?>?type=remaja" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-upload me-2"></i>Upload
                        </a>
                        <a href="<?php echo e(route('kader.import.download-template', 'remaja')); ?>" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download me-2"></i>Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lansia Import Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Import Data Lansia
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Upload data lansia dalam format Excel
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('kader.import.create')); ?>?type=lansia" class="btn btn-outline-dark btn-sm me-2">
                            <i class="fas fa-upload me-2"></i>Upload
                        </a>
                        <a href="<?php echo e(route('kader.import.download-template', 'lansia')); ?>" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download me-2"></i>Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panduan Import -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-info-circle me-2"></i>Panduan Import Data
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary">Langkah-langkah Import:</h6>
                    <ol>
                        <li>Download template sesuai jenis data</li>
                        <li>Isi data sesuai format template</li>
                        <li>Pastikan format tanggal: YYYY-MM-DD</li>
                        <li>Jenis kelamin: L (Laki-laki) atau P (Perempuan)</li>
                        <li>Simpan file dalam format .xlsx atau .csv</li>
                        <li>Upload file melalui menu upload</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-success">Tips & Panduan:</h6>
                    <ul>
                        <li>Gunakan Excel versi terbaru untuk kompatibilitas</li>
                        <li>Jangan mengubah header/kolom template</li>
                        <li>Maksimal ukuran file: 5MB</li>
                        <li>Format file yang didukung: .xlsx, .xls, .csv</li>
                        <li>Pastikan NIK unik untuk setiap data</li>
                        <li>Periksa kembali data sebelum diupload</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/import/index.blade.php ENDPATH**/ ?>