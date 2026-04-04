@extends('kader.laporan.pdf.layout')
@section('title', 'Laporan Skrining PTM Lansia')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="18%">Nama Lansia</th>
            <th width="5%">L/P</th>
            <th width="8%">BB/TB</th>
            <th width="10%">Tensi Darah</th>
            <th width="10%">Gula Darah</th>
            <th width="10%">Asam Urat</th>
            <th width="25%">Diagnosa Bidan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td>{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->profil_pasien->jenis_kelamin ?? '-' }}</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center font-weight-bold">{{ $row->tekanan_darah ?? '-' }}</td>
            <td class="txt-center">{{ $row->gula_darah ?? '-' }}</td>
            <td class="txt-center">{{ $row->asam_urat ?? '-' }}</td>
            <td style="font-style: italic;">{{ $row->diagnosa ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="txt-center" style="padding: 20px;">Tidak ada pemeriksaan lansia di periode ini.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection