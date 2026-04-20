<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Rujukan Posyandu</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; margin: 0; padding: 20px; }
        
        /* Kop Surat */
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h1 { font-size: 16pt; margin: 0; text-transform: uppercase; font-weight: bold; }
        .kop-surat h2 { font-size: 14pt; margin: 5px 0 0 0; }
        .kop-surat p { font-size: 10pt; margin: 5px 0 0 0; font-style: italic; }
        
        /* Judul Surat */
        .judul-surat { text-align: center; margin-bottom: 20px; }
        .judul-surat h3 { margin: 0; text-decoration: underline; font-size: 14pt; }
        .judul-surat p { margin: 0; font-size: 11pt; }

        /* Tabel Data */
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td { vertical-align: top; padding: 4px 0; }
        .col-label { width: 30%; font-weight: bold; }
        .col-titik { width: 3%; text-align: center; }
        .col-value { width: 67%; }

        /* Kotak Diagnosa */
        .box-diagnosa { border: 1px solid #000; padding: 10px; min-height: 80px; margin-bottom: 20px; background-color: #fcfcfc; }
        
        /* Tanda Tangan */
        .ttd-container { width: 100%; margin-top: 40px; }
        .ttd-box { float: right; width: 40%; text-align: center; }
        .ttd-nama { margin-top: 70px; font-weight: bold; text-decoration: underline; }
        
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    @php
        $namaPasien = $pemeriksaan->balita->nama_lengkap ?? $pemeriksaan->remaja->nama_lengkap ?? $pemeriksaan->lansia->nama_lengkap ?? $pemeriksaan->ibuHamil->nama_lengkap ?? 'Anonim';
        $nik = $pemeriksaan->balita->nik ?? $pemeriksaan->remaja->nik ?? $pemeriksaan->lansia->nik ?? $pemeriksaan->ibuHamil->nik ?? '-';
        $jk = $pemeriksaan->balita->jenis_kelamin ?? $pemeriksaan->remaja->jenis_kelamin ?? $pemeriksaan->lansia->jenis_kelamin ?? 'Perempuan'; // Bumil pasti P
        $kategori = class_basename($pemeriksaan->kategori_pasien ?? $pemeriksaan->pasien_type);
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <h1>POSYANDU DESA / KELURAHAN</h1>
        <h2>SISTEM KESEHATAN DIGITAL (RAD METHOD)</h2>
        <p>Alamat: Jl. Raya Kesehatan No. 1, Kecamatan, Kabupaten/Kota - Kode Pos 12345</p>
    </div>

    {{-- JUDUL SURAT --}}
    <div class="judul-surat">
        <h3>SURAT RUJUKAN MEDIS</h3>
        <p>Nomor: SR/POS/{{ date('Y/m') }}/{{ str_pad($pemeriksaan->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <p>Kepada Yth.,<br>
    <strong>Dokter Jaga / Poliklinik Puskesmas</strong><br>
    Di Tempat</p>

    <p>Dengan hormat,<br>Mohon bantuan pemeriksaan dan penanganan lebih lanjut terhadap pasien di bawah ini:</p>

    {{-- DATA PASIEN --}}
    <table>
        <tr>
            <td class="col-label">Nama Lengkap</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $namaPasien }}</td>
        </tr>
        <tr>
            <td class="col-label">NIK</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $nik }}</td>
        </tr>
        <tr>
            <td class="col-label">Kategori / Jenis Kelamin</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $kategori }} / {{ $jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
    </table>

    <p>Hasil pemeriksaan awal fisik di Posyandu pada tanggal <strong>{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->translatedFormat('d F Y') }}</strong> adalah sebagai berikut:</p>

    {{-- DATA FISIK KLINIS --}}
    <table>
        <tr>
            <td class="col-label">Berat Badan (BB)</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $pemeriksaan->berat_badan ?? '-' }} kg</td>
        </tr>
        <tr>
            <td class="col-label">Tinggi Badan (TB)</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $pemeriksaan->tinggi_badan ?? '-' }} cm</td>
        </tr>
        @if($pemeriksaan->tekanan_darah)
        <tr>
            <td class="col-label">Tekanan Darah (Tensi)</td>
            <td class="col-titik">:</td>
            <td class="col-value">{{ $pemeriksaan->tekanan_darah }} mmHg</td>
        </tr>
        @endif
        @if($kategori == 'Balita' && $pemeriksaan->indikasi_stunting)
        <tr>
            <td class="col-label">Deteksi Stunting</td>
            <td class="col-titik">:</td>
            <td class="col-value"><strong>{{ $pemeriksaan->indikasi_stunting }}</strong></td>
        </tr>
        @endif
    </table>

    {{-- KESIMPULAN BIDAN --}}
    <p><strong>Kesimpulan / Diagnosa Bidan:</strong></p>
    <div class="box-diagnosa">
        {{ $pemeriksaan->diagnosa ?? 'Membutuhkan observasi lanjutan.' }}
    </div>

    <p>Demikian surat rujukan ini dibuat untuk dapat dipergunakan sebagaimana mestinya. Atas bantuan dan kerjasamanya, kami ucapkan terima kasih.</p>

    {{-- TANDA TANGAN --}}
    <div class="ttd-container clearfix">
        <div class="ttd-box">
            <p>Dikeluarkan di: Posyandu<br>
            Pada tanggal: {{ date('d F Y') }}</p>
            <p><strong>Bidan Pemeriksa,</strong></p>
            <div class="ttd-nama">
                {{ $pemeriksaan->verifikator->name ?? 'Bidan Desa' }}
            </div>
            <p style="margin:0; font-size: 10pt;">NIP/STR: .......................................</p>
        </div>
    </div>

</body>
</html>