

<?php $__env->startSection('title', 'Upload Data Import'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-upload me-2"></i>Upload Data Import
        </h1>
        <div>
            <a href="<?php echo e(route('kader.import.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-excel me-2"></i>Form Upload Data
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('kader.import.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label for="jenis_data" class="form-label">Jenis Data</label>
                            <select class="form-select <?php $__errorArgs = ['jenis_data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="jenis_data" name="jenis_data" required>
                                <option value="">Pilih Jenis Data</option>
                                <option value="balita" <?php echo e(old('jenis_data', $type ?? '') == 'balita' ? 'selected' : ''); ?>>
                                    Data Balita
                                </option>
                                <option value="remaja" <?php echo e(old('jenis_data', $type ?? '') == 'remaja' ? 'selected' : ''); ?>>
                                    Data Remaja
                                </option>
                                <option value="lansia" <?php echo e(old('jenis_data', $type ?? '') == 'lansia' ? 'selected' : ''); ?>>
                                    Data Lansia
                                </option>
                            </select>
                            <?php $__errorArgs = ['jenis_data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-4">
                            <label for="file" class="form-label">File Excel/CSV</label>
                            <div class="input-group">
                                <input type="file" class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="file" name="file" accept=".xlsx,.xls,.csv" required>
                                <button class="btn btn-outline-secondary" type="button" 
                                        onclick="downloadTemplate()">
                                    <i class="fas fa-download me-2"></i>Template
                                </button>
                            </div>
                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">
                                Format: .xlsx, .xls, .csv | Maksimal: 5MB
                            </small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Perhatian:</strong> Pastikan file sesuai dengan template yang disediakan. 
                            Data dengan NIK yang sudah ada akan diabaikan.
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function downloadTemplate() {
    const jenisData = document.getElementById('jenis_data').value;
    if (!jenisData) {
        alert('Silakan pilih jenis data terlebih dahulu');
        return;
    }
    
    window.location.href = "<?php echo e(route('kader.import.download-template', '')); ?>/" + jenisData;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/import/create.blade.php ENDPATH**/ ?>