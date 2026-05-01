@extends('kader.laporan.templates.layout')

@section('title', 'Laporan Hasil Skrining Kesehatan Remaja')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="18%">Nama Remaja</th>
            <th width="5%">L/P</th>
            <th width="10%">BB (kg) / TB (cm)</th>
            <th width="8%">LiLA (cm)</th>
            <th width="8%">L. Perut (cm)</th>
            <th width="8%">Tensi</th>
            <th width="8%">Hb (g/dL)</th>
            <th width="21%">Edukasi / Tindakan Medis</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td class="txt-bold">{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->jenis_kelamin }}</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center">{{ $row->lingkar_lengan ?? '-' }}</td>
            <td class="txt-center">{{ $row->lingkar_perut ?? '-' }}</td>
            <td class="txt-center txt-bold">{{ $row->tekanan_darah ?? '-' }}</td>
            <td class="txt-center">{{ $row->hemoglobin ?? '-' }}</td>
            <td class="txt-italic" style="font-size: 9pt;">
                {{ $row->catatan_bidan ?? $row->catatan_kader ?? $row->keluhan ?? '-' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="txt-center" style="padding: 30px;">
                <strong>NIHIL:</strong> Tidak ditemukan rekam medis skrining Remaja untuk periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 10px; font-size: 8pt; color: #666;">
    * LiLA: Lingkar Lengan Atas. Hb: Hemoglobin. Data diekstrak dari Sistem Informasi Posyandu.
</div>
@endsection