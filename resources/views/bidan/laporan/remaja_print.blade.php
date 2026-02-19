<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Remaja</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
        .no-print { margin-bottom: 20px; padding: 10px; background: #eee; text-align: center; }
        .danger { color: red; font-weight: bold; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.history.back()" style="padding: 5px 15px; cursor: pointer;">Kembali</button>
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer; font-weight: bold;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h2 style="margin: 0;">LAPORAN KESEHATAN REMAJA</h2>
        <p style="margin: 5px 0;">Posyandu Desa - Dicetak Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Remaja</th>
                <th>L/P</th>
                <th>Usia</th>
                <th>Sekolah / Kelas</th>
                <th>Alamat</th>
                <th>Diagnosa Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($remajas as $index => $item)
            @php
                $periksa = $item->pemeriksaan_terakhir;
                $diagnosa = $periksa ? $periksa->hasil_diagnosa : '-';
                
                // Highlight jika ada kata anemia atau risiko
                $isDanger = false;
                if ($diagnosa && (stripos($diagnosa, 'anemia') !== false || stripos($diagnosa, 'kurang darah') !== false)) {
                    $isDanger = true;
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->usia }} Tahun</td>
                <td>{{ $item->sekolah ?? '-' }} ({{ $item->kelas ?? '-' }})</td>
                <td>{{ $item->alamat }}</td>
                <td class="{{ $isDanger ? 'danger' : '' }}">
                    {{ $diagnosa }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data remaja.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>