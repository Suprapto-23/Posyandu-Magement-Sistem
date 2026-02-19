<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan <?php echo e($judulJenis); ?> — <?php echo e($periode->translatedFormat('F Y')); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: white;
            padding: 20px;
        }

        /* ====== HEADER ====== */
        .header {
            text-align: center;
            border-bottom: 3px solid #1a56db;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 2px;
        }
        .header h2 {
            font-size: 13px;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .header .meta {
            font-size: 10px;
            color: #666;
        }

        /* ====== STATS GRID ====== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }
        .stat-box {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }
        .stat-box .val { font-size: 18px; font-weight: bold; color: #1a56db; }
        .stat-box .lbl { font-size: 9px; color: #666; margin-top: 2px; }
        .stat-box.danger .val { color: #dc2626; }
        .stat-box.success .val { color: #16a34a; }
        .stat-box.warning .val { color: #d97706; }

        /* ====== TABLE ====== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        thead tr {
            background: #1a56db;
            color: white;
        }
        thead th {
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #eff6ff; }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #713f12; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-info    { background: #dbeafe; color: #1e40af; }

        /* ====== FOOTER ====== */
        .footer {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .ttd-box {
            text-align: center;
            width: 180px;
        }
        .ttd-box .ttd-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 10px;
        }
        .catatan-box {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 12px;
            width: 300px;
            min-height: 80px;
            font-size: 10px;
            color: #666;
        }
        .catatan-box .catatan-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        /* ====== PRINT ====== */
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
            @page {
                margin: 15mm;
                size: A4 landscape;
            }
        }

        /* Tombol cetak - hanya muncul di layar */
        .print-bar {
            background: #1a56db;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -20px -20px 20px -20px;
            border-radius: 0;
        }
        .print-bar button {
            background: white;
            color: #1a56db;
            border: none;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            font-size: 12px;
        }
        .print-bar button:hover { background: #eff6ff; }
    </style>
</head>
<body>

    
    <div class="print-bar no-print">
        <span>📄 Laporan <?php echo e($judulJenis); ?> — <?php echo e($periode->translatedFormat('F Y')); ?></span>
        <div style="display:flex;gap:8px">
            <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
            <button onclick="window.close()" style="background:#fee2e2;color:#991b1b">✕ Tutup</button>
        </div>
    </div>

    
    <div class="header">
        <h1>LAPORAN PEMERIKSAAN POSYANDU</h1>
        <h2><?php echo e(strtoupper($judulJenis)); ?> — BULAN <?php echo e(strtoupper($periode->translatedFormat('F Y'))); ?></h2>
        <div class="meta">
            Dicetak: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WIB &nbsp;|&nbsp;
            Petugas: <?php echo e(auth()->user()->name); ?>

        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-box">
            <div class="val"><?php echo e($stats['total']); ?></div>
            <div class="lbl">Total Pemeriksaan</div>
        </div>
        <div class="stat-box">
            <div class="val"><?php echo e($stats['balita']); ?></div>
            <div class="lbl">Balita</div>
        </div>
        <div class="stat-box">
            <div class="val"><?php echo e($stats['remaja']); ?></div>
            <div class="lbl">Remaja</div>
        </div>
        <div class="stat-box">
            <div class="val"><?php echo e($stats['lansia']); ?></div>
            <div class="lbl">Lansia</div>
        </div>
        <div class="stat-box success">
            <div class="val"><?php echo e($stats['normal']); ?></div>
            <div class="lbl">Status Normal</div>
        </div>
        <div class="stat-box danger">
            <div class="val"><?php echo e($stats['stunting']); ?></div>
            <div class="lbl">Stunting/Gizi Buruk</div>
        </div>
        <div class="stat-box warning">
            <div class="val"><?php echo e($stats['obesitas']); ?></div>
            <div class="lbl">Obesitas/Lebih</div>
        </div>
        <div class="stat-box danger">
            <div class="val"><?php echo e($stats['hipertensi']); ?></div>
            <div class="lbl">Hipertensi</div>
        </div>
    </div>

    
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th style="width:70px">Tanggal</th>
                <th style="width:140px">Nama Pasien</th>
                <th style="width:60px">Kategori</th>
                <th style="width:80px">BB / TB</th>
                <th style="width:60px">IMT</th>
                <th style="width:70px">Tekanan Darah</th>
                <th style="width:70px">Status Gizi</th>
                <th>Diagnosa Bidan</th>
                <th style="width:100px">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pemeriksaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($item->tanggal_periksa?->format('d/m/Y') ?? '-'); ?></td>
                <td><strong><?php echo e($item->nama_pasien); ?></strong></td>
                <td>
                    <?php
                        $kBadge = ['balita'=>'info','remaja'=>'success','lansia'=>'warning'];
                    ?>
                    <span class="badge badge-<?php echo e($kBadge[$item->kategori_pasien] ?? 'info'); ?>">
                        <?php echo e(ucfirst($item->kategori_pasien)); ?>

                    </span>
                </td>
                <td><?php echo e($item->berat_badan ?? '-'); ?> / <?php echo e($item->tinggi_badan ?? '-'); ?></td>
                <td><?php echo e($item->imt > 0 ? $item->imt : '-'); ?></td>
                <td><?php echo e($item->tekanan_darah ?? '-'); ?></td>
                <td>
                    <?php
                        $giziBadge = ['baik'=>'success','kurang'=>'warning','buruk'=>'danger','stunting'=>'danger','obesitas'=>'warning','lebih'=>'warning','risiko'=>'warning'];
                    ?>
                    <span class="badge badge-<?php echo e($giziBadge[$item->status_gizi ?? ''] ?? 'info'); ?>">
                        <?php echo e(ucfirst($item->status_gizi ?? '-')); ?>

                    </span>
                </td>
                <td><?php echo e($item->diagnosa ?? '-'); ?></td>
                <td><?php echo e(Str::limit($item->tindakan ?? '-', 40)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="10" style="text-align:center;padding:20px;color:#666">
                    Tidak ada data pemeriksaan yang terverifikasi untuk periode ini.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div class="footer">
        <div class="catatan-box">
            <div class="catatan-title">Catatan / Keterangan:</div>
            <div style="margin-top:6px;line-height:1.8">
                ___________________________________<br>
                ___________________________________<br>
                ___________________________________
            </div>
        </div>

        <div class="ttd-box">
            <div>Mengetahui,</div>
            <div>Bidan Posyandu</div>
            <div class="ttd-line">
                ( __________________________ )
            </div>
        </div>

        <div class="ttd-box">
            <div><?php echo e($periode->translatedFormat('d F Y')); ?></div>
            <div>Petugas Kader</div>
            <div class="ttd-line">
                ( __________________________ )
            </div>
        </div>
    </div>

    
    <script>
        <?php if(request('autoprint')): ?>
        window.onload = function() { window.print(); }
        <?php endif; ?>
    </script>

</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/laporan/cetak.blade.php ENDPATH**/ ?>