

<?php $__env->startSection('title', 'Detail Balita'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.show-wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 .25rem;
}

.show-back {
    display: inline-flex; align-items: center; gap: .45rem;
    font-size: .83rem; font-weight: 600; color: #94a3b8;
    text-decoration: none; margin-bottom: 1.5rem;
    transition: color .15s;
}
.show-back:hover { color: #0d9488; }

/* Profile Card */
.show-profile-card {
    background: #fff;
    border: 1px solid #e8f4f2;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(13,148,136,.08);
    overflow: hidden;
    height: 100%;
}
.show-profile-top {
    background: linear-gradient(135deg, #0ea5e9 0%, #14b8a6 100%);
    padding: 2rem 1.5rem 3.5rem;
    text-align: center;
    position: relative;
}
.show-profile-top::after {
    content: '';
    position: absolute; bottom: -1px; left: 0; right: 0;
    height: 32px; background: #fff;
    border-radius: 32px 32px 0 0;
}
.show-avatar {
    width: 84px; height: 84px;
    border-radius: 22px;
    background: rgba(255,255,255,.25);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255,255,255,.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; font-weight: 900; color: #fff;
    margin: 0 auto .9rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
}
.show-profile-name {
    color: #fff; font-size: 1.1rem; font-weight: 800;
    margin: 0 0 .3rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    text-shadow: 0 1px 4px rgba(0,0,0,.15);
}

.show-kode-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    background: rgba(255,255,255,.25); color: rgba(255,255,255,.95);
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 50px; padding: .25rem .8rem;
    font-size: .75rem; font-weight: 700;
    backdrop-filter: blur(8px);
}

.show-profile-body { padding: 1.25rem 1.5rem 1.75rem; }

.show-info-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: .85rem; margin-top: .25rem;
}
.show-info-item { }
.show-info-label {
    font-size: .63rem; font-weight: 700; letter-spacing: .09em;
    text-transform: uppercase; color: #94a3b8; margin-bottom: .2rem;
}
.show-info-value {
    font-size: .88rem; font-weight: 700; color: #0f172a;
}

.show-ibu-row {
    display: flex; align-items: center; gap: .85rem;
    background: #f8fafc; border: 1px solid #f1f5f9;
    border-radius: 12px; padding: .85rem 1rem;
    margin-top: .85rem;
}
.show-ibu-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #ec4899, #f472b6);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .85rem;
    box-shadow: 0 3px 10px rgba(236,72,153,.25);
}
.show-ibu-label { font-size: .65rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94a3b8; }
.show-ibu-name  { font-weight: 700; font-size: .88rem; color: #0f172a; margin-top: .05rem; }

/* History Card */
.show-history-card {
    background: #fff;
    border: 1px solid #e8f4f2;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(13,148,136,.07);
    overflow: hidden;
    height: 100%;
}
.show-history-header {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
}
.show-history-title {
    font-size: .92rem; font-weight: 800; color: #0f172a; margin: 0;
    display: flex; align-items: center; gap: .55rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.show-title-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg, #0ea5e9, #14b8a6);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .75rem;
}

/* Table */
.show-table { width: 100%; border-collapse: collapse; }
.show-table thead tr { background: #f8fafc; }
.show-table thead th {
    padding: .8rem 1rem;
    font-size: .65rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #94a3b8;
    border-bottom: 1px solid #f1f5f9; white-space: nowrap;
}
.show-table thead th:first-child { padding-left: 1.5rem; }
.show-table tbody tr { border-bottom: 1px solid #f8fafc; transition: background .12s; }
.show-table tbody tr:last-child { border-bottom: none; }
.show-table tbody tr:hover { background: #f0fdfa; }
.show-table tbody td { padding: .9rem 1rem; font-size: .84rem; color: #334155; vertical-align: middle; }
.show-table tbody td:first-child { padding-left: 1.5rem; }

/* Status gizi badges */
.show-gizi {
    display: inline-flex; align-items: center;
    padding: .27rem .65rem; border-radius: 50px;
    font-size: .72rem; font-weight: 700; white-space: nowrap;
}
.gizi-baik   { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.gizi-kurang { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.gizi-buruk  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.gizi-lebih  { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.gizi-default{ background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

/* Count badge */
.show-count {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .27rem .7rem; border-radius: 50px;
    font-size: .72rem; font-weight: 600;
    background: #ccfbf1; color: #0d9488; border: 1px solid #99f6e4;
}

/* Empty state */
.show-empty { text-align: center; padding: 3rem 1.5rem; }
.show-empty-icon {
    width: 68px; height: 68px; border-radius: 18px;
    background: #f0fdfa; border: 1.5px solid #ccfbf1;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; color: #5eead4; margin: 0 auto .9rem;
}
.show-empty h6 { font-weight: 800; color: #0f172a; margin-bottom: .35rem; font-size: .92rem; }
.show-empty p  { color: #94a3b8; font-size: .82rem; margin: 0; }

/* Tab Navigation */
.show-tabs {
    display: flex; gap: .5rem;
    border-bottom: 1px solid #f1f5f9;
    padding: 0 1.5rem;
}
.show-tab {
    padding: .75rem 1rem;
    font-size: .85rem; font-weight: 600;
    color: #64748b;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all .15s;
}
.show-tab:hover { color: #0f172a; }
.show-tab.active {
    color: #0d9488;
    border-bottom-color: #0d9488;
}
.tab-content { display: none; }
.tab-content.active { display: block; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="show-wrap animate-fade-in">

    
    <a href="<?php echo e(route('user.balita.index')); ?>" class="show-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Balita
    </a>

    <div class="row g-4">

        
        <div class="col-lg-4">
            <div class="show-profile-card">

                
                <div class="show-profile-top">
                    <div class="show-avatar"><?php echo e(strtoupper(substr($balita->nama_lengkap, 0, 1))); ?></div>
                    <h5 class="show-profile-name"><?php echo e($balita->nama_lengkap); ?></h5>
                    <span class="show-kode-badge">
                        <i class="fas fa-tag" style="font-size:.65rem;"></i>
                        <?php echo e($balita->kode_balita); ?>

                    </span>
                </div>

                
                <div class="show-profile-body">

                    
                    <div class="show-info-grid">
                        <div class="show-info-item">
                            <div class="show-info-label">Tanggal Lahir</div>
                            <div class="show-info-value">
                                <?php echo e(\Carbon\Carbon::parse($balita->tanggal_lahir)->format('d M Y')); ?>

                            </div>
                        </div>
                        <div class="show-info-item">
                            <div class="show-info-label">Usia</div>
                            <div class="show-info-value">
                                <?php echo e($usia_tahun); ?> Th <?php echo e($usia_bulan); ?> Bln
                            </div>
                        </div>
                        <div class="show-info-item">
                            <div class="show-info-label">Jenis Kelamin</div>
                            <div class="show-info-value" style="color: <?php echo e($balita->jenis_kelamin == 'L' ? '#0ea5e9' : '#ec4899'); ?>;">
                                <i class="fas fa-<?php echo e($balita->jenis_kelamin == 'L' ? 'mars' : 'venus'); ?> me-1"></i>
                                <?php echo e($balita->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="show-ibu-row">
                        <div class="show-ibu-icon"><i class="fas fa-female"></i></div>
                        <div>
                            <div class="show-ibu-label">Nama Ibu</div>
                            <div class="show-ibu-name"><?php echo e($balita->nama_ibu); ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="col-lg-8">
            <div class="show-history-card">
                
                
                <div class="show-tabs">
                    <div class="show-tab active" data-tab="pemeriksaan">
                        <i class="fas fa-stethoscope me-1"></i> Pemeriksaan
                    </div>
                    <div class="show-tab" data-tab="imunisasi">
                        <i class="fas fa-syringe me-1"></i> Imunisasi
                    </div>
                </div>

                
                <div class="tab-content active" id="tab-pemeriksaan">
                    <div class="show-history-header">
                        <h6 class="show-history-title">
                            <div class="show-title-icon"><i class="fas fa-stethoscope"></i></div>
                            Riwayat Pemeriksaan
                        </h6>
                        <span class="show-count"><?php echo e($riwayatPemeriksaan->count()); ?> Data</span>
                    </div>
                    <div class="table-responsive">
                        <table class="show-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Berat</th>
                                    <th>Tinggi</th>
                                    <th>Status Gizi</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $riwayatPemeriksaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periksa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span style="font-weight:700;color:#0f172a;display:block;">
                                            <?php echo e(\Carbon\Carbon::parse($periksa->tanggal_periksa)->format('d M Y')); ?>

                                        </span>
                                        <span style="font-size:.75rem;color:#94a3b8;">
                                            <?php echo e(\Carbon\Carbon::parse($periksa->tanggal_periksa)->format('H:i')); ?> WIB
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-weight:700;color:#0f172a;"><?php echo e($periksa->berat_badan); ?></span>
                                        <span style="font-size:.75rem;color:#94a3b8;">kg</span>
                                    </td>
                                    <td>
                                        <span style="font-weight:700;color:#0f172a;"><?php echo e($periksa->tinggi_badan); ?></span>
                                        <span style="font-size:.75rem;color:#94a3b8;">cm</span>
                                    </td>
                                    <td>
                                        <?php
                                            $g = strtolower($periksa->status_gizi ?? '');
                                            $gc = str_contains($g,'baik') ? 'gizi-baik'
                                                : (str_contains($g,'kurang') ? 'gizi-kurang'
                                                : (str_contains($g,'buruk') ? 'gizi-buruk'
                                                : (str_contains($g,'lebih') ? 'gizi-lebih' : 'gizi-default')));
                                        ?>
                                        <span class="show-gizi <?php echo e($gc); ?>">
                                            <?php echo e(strtoupper($periksa->status_gizi ?? '—')); ?>

                                        </span>
                                    </td>
                                    <td style="color:#64748b;font-size:.82rem;">
                                        <?php echo e(Str::limit($periksa->tindakan ?? '—', 32)); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="show-empty">
                                            <div class="show-empty-icon"><i class="fas fa-stethoscope"></i></div>
                                            <h6>Belum Ada Riwayat</h6>
                                            <p>Belum ada catatan pemeriksaan yang tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="tab-content" id="tab-imunisasi">
                    <div class="show-history-header">
                        <h6 class="show-history-title">
                            <div class="show-title-icon"><i class="fas fa-syringe"></i></div>
                            Riwayat Imunisasi
                        </h6>
                        <span class="show-count"><?php echo e($riwayatImunisasi->count()); ?> Data</span>
                    </div>
                    <div class="table-responsive">
                        <table class="show-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Imunisasi</th>
                                    <th>Vaksin</th>
                                    <th>Dosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $riwayatImunisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span style="font-weight:700;color:#0f172a;">
                                            <?php echo e(\Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d M Y')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <?php echo e($imun->jenis_imunisasi); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($imun->vaksin ?? '-'); ?></td>
                                    <td><span class="badge bg-light text-dark"><?php echo e($imun->dosis ?? '-'); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="show-empty">
                                            <div class="show-empty-icon"><i class="fas fa-syringe"></i></div>
                                            <h6>Belum Ada Imunisasi</h6>
                                            <p>Belum ada catatan imunisasi yang tersimpan.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.show-tab');
    const contents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            
            // Add active class to current tab and corresponding content
            this.classList.add('active');
            document.getElementById(`tab-${target}`).classList.add('active');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/user/balita/show.blade.php ENDPATH**/ ?>