@extends('kader.laporan.templates.layout')

@section('title', 'Laporan Hasil Skrining Kesehatan Lansia')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="18%">Nama Lansia</th>
            <th width="5%">L/P</th>
            <th width="10%">BB (kg) / TB (cm)</th>
            <th width="8%">Tensi Darah</th>
            <th width="8%">Gula Darah</th>
            <th width="8%">Asam Urat</th>
            <th width="8%">Kolesterol</th>
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
            <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
            
            {{-- Penyorotan Tensi Darah (Opsional: Jika ingin menonjolkan data vital) --}}
            <td class="txt-center txt-bold">{{ $row->tekanan_darah ?? '-' }}</td>
            
            <td class="txt-center">{{ $row->gula_darah ?? '-' }}</td>
            <td class="txt-center">{{ $row->asam_urat ?? '-' }}</td>
            <td class="txt-center">{{ $row->kolesterol ?? '-' }}</td>
            <td class="txt-italic" style="font-size: 9pt;">
                {{ $row->catatan_bidan ?? $row->catatan_kader ?? 'Tidak ada catatan.' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="txt-center" style="padding: 30px;">
                <strong>NIHIL:</strong> Tidak ditemukan rekam medis skrining Lansia untuk periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 10px; font-size: 8pt; color: #666;">
    * PTM: Penyakit Tidak Menular. Data diekstrak dari Sistem Informasi Posyandu.
</div>
@endsection