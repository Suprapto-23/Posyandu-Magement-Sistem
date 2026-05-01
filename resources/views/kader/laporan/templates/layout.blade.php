<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        /* ============================================================
           DOMPDF OPTIMIZED CSS (STANDARD KEMENKES/PEMDA)
           ============================================================ */
        @page { size: A4 landscape; margin: 1.5cm; }
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 10pt; 
            color: #000; 
            line-height: 1.3; 
            background: #fff; 
            margin: 0; 
            padding: 0;
        }

        /* HEADER / KOP SURAT */
        .kop-surat { width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 5px; }
        .kop-double-line { border-bottom: 1px solid #000; margin-top: 2px; margin-bottom: 20px; }
        .kop-teks { text-align: center; }
        .kop-teks h3 { margin: 0; font-size: 13pt; font-weight: normal; text-transform: uppercase; }
        .kop-teks h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-teks p { margin: 0; font-size: 9pt; font-style: italic; }
        
        /* JUDUL DOKUMEN */
        .judul-area { text-align: center; margin-bottom: 25px; }
        .judul-laporan { font-weight: bold; font-size: 12pt; text-decoration: underline; text-transform: uppercase; display: block; }
        .periode-teks { font-size: 10pt; margin-top: 5px; font-weight: bold; }

        /* DATA TABLE (ANTI-BREAK LOGIC) */
        .data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .data-table th { 
            background-color: #f2f2f2; 
            text-align: center; 
            font-weight: bold; 
            border: 1px solid #000; 
            padding: 8px 4px; 
            font-size: 9pt;
            text-transform: uppercase;
        }
        .data-table td { 
            border: 1px solid #000; 
            padding: 6px 4px; 
            vertical-align: top; 
            word-wrap: break-word;
        }
        
        /* HELPER CLASSES */
        .txt-center { text-align: center; }
        .txt-bold { font-weight: bold; }
        .txt-italic { font-style: italic; }
        
        /* TANDA TANGAN (SIGNATURE) */
        .footer-wrap { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .sig-box { width: 300px; text-align: center; float: right; }
        .sig-space { height: 70px; }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI --}}
    <table class="kop-surat">
        <tr>
            <td width="100%" class="kop-teks">
                <h3>Pemerintah Kabupaten Pekalongan</h3>
                <h3>Kecamatan Lebakbarang</h3>
                <h2>PEMERINTAH DESA BANTAR KULON</h2>
                <p>Alamat: Jl. Raya Bantar Kulon No. 123, Kode Pos 51193 | Email: pemdes@bantarkulon.desa.id</p>
            </td>
        </tr>
    </table>
    <div class="kop-double-line"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="judul-area">
        <span class="judul-laporan">@yield('title')</span>
        <div class="periode-teks">Periode: {{ $namaBulan ?? '-' }} {{ $tahun ?? '-' }}</div>
    </div>

    {{-- KONTEN DINAMIS --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- PENUTUP & TANDA TANGAN --}}
    <div class="footer-wrap">
        <div class="sig-box">
            Bantar Kulon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Mengetahui,<br>
            <strong>Bidan Desa / Kader Posyandu</strong>
            <div class="sig-space"></div>
            <span style="font-weight: bold; text-decoration: underline;">( _________________________ )</span><br>
            <span style="font-size: 9pt;">NIP/ID. ........................................</span>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>