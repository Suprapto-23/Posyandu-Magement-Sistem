@extends('kader.laporan.templates.layout')

@section('title', 'Laporan Hasil Pemeriksaan Kesehatan Balita')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="12%">Tanggal</th>
            <th width="20%">Nama Balita</th>
            <th width="5%">L/P</th>
            <th width="8%">BB (kg)</th>
            <th width="8%">TB (cm)</th>
            <th width="10%">L. Kepala</th>
            <th width="12%">Status Gizi</th>
            <th width="21%">Diagnosa / Catatan Medis</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td class="txt-bold">{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->jenis_kelamin }}</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }}</td>
            <td class="txt-center">{{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center">{{ $row->lingkar_kepala ?? '-' }}</td>
            <td class="txt-center">
                {{-- Logika pewarnaan teks sederhana untuk PDF jika status berisiko --}}
                <span class="{{ in_array(strtolower($row->status_gizi), ['buruk', 'kurang', 'stunting']) ? 'txt-bold' : '' }}">
                    {{ strtoupper($row->status_gizi ?? '-') }}
                </span>
            </td>
            <td class="txt-italic" style="font-size: 9pt;">
                {{ $row->catatan_bidan ?? $row->catatan_kader ?? 'Tidak ada catatan khusus.' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="txt-center" style="padding: 30px;">
                <strong>NIHIL:</strong> Tidak ditemukan rekaman data pemeriksaan balita untuk periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Info Tambahan di bawah tabel (Opsional) --}}
<div style="margin-top: 10px; font-size: 8pt; color: #666;">
    * Data ini diekstrak otomatis dari Sistem Informasi Posyandu Desa Bantar Kulon.
</div>
@endsection