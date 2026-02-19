

<?php $__env->startSection('title', 'Data Balita'); ?>
<?php $__env->startSection('page-title', 'Data Balita'); ?>
<?php $__env->startSection('page-subtitle', 'Monitoring tumbuh kembang & kesehatan balita'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-start-0 bg-light" 
                   placeholder="Cari nama balita, ibu, atau NIK..." autocomplete="off">
        </div>

        <div class="d-flex gap-2 align-items-center">
            <a href="<?php echo e(route('bidan.laporan.balita')); ?>" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                <span class="d-none d-md-inline">Laporan PDF</span>
            </a>
            <div class="d-none d-md-block">
                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                    <i class="fas fa-sync-alt me-1"></i> Data Realtime
                </span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div id="dataContainer">
            <?php echo $__env->make('bidan.pasien.partials.table_balita', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let searchTimer;
    
    // 1. Live Search
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        let query = $(this).val();
        searchTimer = setTimeout(function() { fetchData(query); }, 300);
    });

    // 2. Pagination Handling
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fetchData($('#searchInput').val(), url);
    });

    // 3. Auto Refresh (15 Detik)
    setInterval(function() {
        if ($('#searchInput').val() === '') {
            fetchData('', window.location.href, false);
        }
    }, 15000);

    function fetchData(query = '', url = null, showLoading = true) {
        if(!url) url = "<?php echo e(route('bidan.pasien.balita')); ?>";
        
        if(showLoading) $('#dataContainer').css('opacity', '0.5');

        $.ajax({
            url: url,
            type: "GET",
            data: { search: query },
            success: function(response) {
                $('#dataContainer').html(response).css('opacity', '1');
            },
            error: function() { $('#dataContainer').css('opacity', '1'); }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/pasien/balita.blade.php ENDPATH**/ ?>