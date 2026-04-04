@extends('kader.laporan.pdf.layout')
@section('title', 'Laporan Bulanan Pemeriksaan Balita')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="20%">Nama Balita</th>
            <th width="5%">L/P</th>
            <th width="8%">Umur</th>
            <th width="8%">BB (kg)</th>
            <th width="8%">TB (cm)</th>
            <th width="12%">Status Gizi</th>
            <th width="25%">Catatan / Diagnosa</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        @php $umurBulan = $row->profil_pasien ? \Carbon\Carbon::parse($row->profil_pasien->tanggal_lahir)->diffInMonths(now()) : 0; @endphp
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td>{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->profil_pasien->jenis_kelamin ?? '-' }}</td>
            <td class="txt-center">{{ $umurBulan }} bln</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }}</td>
            <td class="txt-center">{{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center" style="font-weight: bold;">{{ strtoupper($row->status_gizi ?? 'Normal') }}</td>
            <td style="font-style: italic;">{{ $row->diagnosa ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="txt-center" style="padding: 20px;">Tidak ada rekam medis di periode ini.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection