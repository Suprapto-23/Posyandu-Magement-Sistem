<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Balita</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .header { text-align: center; margin-bottom: 20px; }
        .no-print { margin-bottom: 20px; padding: 10px; background: #eee; text-align: center; }
        .stunting { color: red; font-weight: bold; }
        
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.history.back()" style="padding: 5px 15px; cursor: pointer;">Kembali</button>
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer; font-weight: bold;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h2 style="margin: 0;">LAPORAN DATA KESEHATAN BALITA</h2>
        <p style="margin: 5px 0;">Posyandu Desa - Dicetak Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Balita</th>
                <th>Jenis Kelamin</th>
                <th>Usia</th>
                <th>Nama Orang Tua</th>
                <th>Berat Badan (Terakhir)</th>
                <th>Status Gizi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($balitas as $index => $item)
            @php
                $periksa = $item->pemeriksaan_terakhir;
                $status = $periksa ? $periksa->status_gizi : '-';
                $bb = $periksa ? $periksa->berat_badan . ' kg' : '-';
                
                $statusClass = '';
                if(in_array($status, ['stunting', 'buruk', 'kurang'])) {
                    $statusClass = 'stunting';
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_lengkap }}<br><small>NIK: {{ $item->nik }}</small></td>
                <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td>
                    {{ $item->usia_tahun }} Thn {{ $item->usia_bulan }} Bln
                </td>
                <td>{{ $item->nama_ibu }} / {{ $item->nama_ayah }}</td>
                <td>{{ $bb }}</td>
                <td class="{{ $statusClass }}">
                    {{ strtoupper($status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data balita.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>