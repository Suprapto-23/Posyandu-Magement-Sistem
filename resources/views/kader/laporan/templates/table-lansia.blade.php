<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Lansia</title>
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

    <div class="judul-laporan">Laporan Hasil Skrining Kesehatan Lansia</div>
    <div class="periode">Periode: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Nama Lansia</th>
                <th width="5%">L/P</th>
                <th width="8%">BB(kg)/TB(cm)</th>
                <th width="8%">Tensi</th>
                <th width="8%">Gula Darah</th>
                <th width="8%">Asam Urat</th>
                <th width="8%">Kolesterol</th>
                <th width="21%">Diagnosa Medis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr>
                <td class="txt-center">{{ $index + 1 }}</td>
                <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
                <td>{{ $row->nama_pasien }}</td>
                <td class="txt-center">{{ $row->jenis_kelamin }}</td>
                <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
                <td class="txt-center font-weight-bold">{{ $row->tekanan_darah ?? '-' }}</td>
                <td class="txt-center">{{ $row->gula_darah ?? '-' }}</td>
                <td class="txt-center">{{ $row->asam_urat ?? '-' }}</td>
                <td class="txt-center">{{ $row->kolesterol ?? '-' }}</td>
                <td style="font-style: italic; font-size: 9pt;">{{ $row->catatan_bidan ?? $row->catatan_kader ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="txt-center">Tidak ada data pemeriksaan lansia di bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrap">
        <div class="signature-box">
            Bantar Kulon, {{ now()->translatedFormat('d F Y') }}<br>
            Mengetahui,<br>
            Bidan / Kader Posyandu
            <br><br><br><br><br>
            <span style="font-weight: bold; text-decoration: underline;">_________________________</span><br>
            NIP. ..............................
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>