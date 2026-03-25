<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Remaja</title>
    <style>
        @page { size: A4 landscape; margin: 2cm; }
        .cetak-body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.5; background: #fff;}
        .kop-surat { width: 100%; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px; border-collapse: collapse;}
        .kop-teks { text-align: center; }
        .kop-teks h3 { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; }
        .kop-teks h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-teks p { margin: 0; font-size: 10pt; font-style: italic; }
        .judul-laporan { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 5px; text-transform: uppercase;}
        .periode { text-align: center; font-size: 11pt; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px 8px; }
        .data-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .data-table td { vertical-align: middle; }
        .txt-center { text-align: center; }
        .signature-wrap { width: 100%; margin-top: 40px; }
        .signature-box { width: 250px; text-align: center; float: right; page-break-inside: avoid; }
    </style>
</head>
<body class="cetak-body">

    <table class="kop-surat">
        <tr>
            <td width="15%" class="txt-center"></td>
            <td width="85%" class="kop-teks">
                <h3>Pemerintah Kabupaten Pekalongan</h3>
                <h3>Kecamatan Lebakbarang</h3>
                <h2>PEMERINTAH DESA BANTAR KULON</h2>
                <p>Alamat: Jl. Raya Bantar Kulon No. 123, Kode Pos 51193</p>
                <p>Email: desabantarkulon@email.com | Telp: (0285) 123456</p>
            </td>
        </tr>
    </table>

    <div class="judul-laporan">Laporan Hasil Skrining Kesehatan Remaja</div>
    <div class="periode">Periode: <?php echo e(\Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F')); ?> <?php echo e($tahun); ?></div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="18%">Nama Remaja</th>
                <th width="5%">L/P</th>
                <th width="8%">BB(kg)/TB(cm)</th>
                <th width="8%">LiLA (cm)</th>
                <th width="8%">L. Perut (cm)</th>
                <th width="9%">Tensi</th>
                <th width="8%">Hb (g/dL)</th>
                <th width="22%">Tindakan / Edukasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="txt-center"><?php echo e($index + 1); ?></td>
                <td class="txt-center"><?php echo e(\Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y')); ?></td>
                <td><?php echo e($row->nama_pasien); ?></td>
                <td class="txt-center"><?php echo e($row->jenis_kelamin); ?></td>
                <td class="txt-center"><?php echo e($row->berat_badan ?? '-'); ?> / <?php echo e($row->tinggi_badan ?? '-'); ?></td>
                <td class="txt-center"><?php echo e($row->lingkar_lengan ?? '-'); ?></td>
                <td class="txt-center"><?php echo e($row->lingkar_perut ?? '-'); ?></td>
                <td class="txt-center"><?php echo e($row->tekanan_darah ?? '-'); ?></td>
                <td class="txt-center"><?php echo e($row->hemoglobin ?? '-'); ?></td>
                <td style="font-style: italic; font-size: 9pt;"><?php echo e($row->tindakan ?? '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="10" class="txt-center">Tidak ada data pemeriksaan remaja di bulan ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-wrap">
        <div class="signature-box">
            Bantar Kulon, <?php echo e(now()->translatedFormat('d F Y')); ?><br>
            Mengetahui,<br>
            Bidan / Kader Posyandu
            <br><br><br><br><br>
            <span style="font-weight: bold; text-decoration: underline;">_________________________</span><br>
            NIP. ..............................
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/laporan/templates/table-remaja.blade.php ENDPATH**/ ?>