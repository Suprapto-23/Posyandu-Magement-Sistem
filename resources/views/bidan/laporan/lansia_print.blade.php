<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Lansia</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
        .no-print { margin-bottom: 20px; padding: 10px; background: #eee; text-align: center; }
        .status-danger { color: red; font-weight: bold; }
        .status-warning { color: orange; font-weight: bold; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.history.back()" style="padding: 5px 15px; cursor: pointer;">Kembali</button>
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer; font-weight: bold;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h2 style="margin: 0;">LAPORAN DATA KESEHATAN LANSIA</h2>
        <p style="margin: 5px 0;">Posyandu Desa - Dicetak Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Lansia</th>
                <th>NIK</th>
                <th>Usia</th>
                <th>Alamat</th>
                <th>Tensi Terakhir</th>
                <th>Status Kesehatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lansias as $index => $item)
            @php
                $tensi = $item->pemeriksaan_terakhir->tekanan_darah ?? '-';
                $sistolik = (int) explode('/', $tensi)[0];
                $status = 'Normal';
                $class = '';

                if($sistolik >= 180) { $status = 'Bahaya (Kritis)'; $class = 'status-danger'; }
                elseif($sistolik >= 140) { $status = 'Hipertensi'; $class = 'status-warning'; }
                elseif($tensi == '-') { $status = 'Belum Periksa'; }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->usia }} Thn</td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $tensi }}</td>
                <td class="{{ $class }}">{{ $status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data lansia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>