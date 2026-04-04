@extends('kader.laporan.pdf.layout')
@section('title', 'Laporan Bulanan Pemeriksaan Ibu Hamil')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="22%">Nama Ibu Hamil</th>
            <th width="8%">BB / TB</th>
            <th width="8%">LILA (cm)</th>
            <th width="8%">Tensi</th>
            <th width="15%">Kondisi / Risiko</th>
            <th width="25%">Tindakan Bidan & Resep</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td>{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center {{ $row->is_kek ? 'txt-bold' : '' }}">{{ $row->lingkar_lengan ?? '-' }}</td>
            <td class="txt-center">{{ $row->tekanan_darah ?? '-' }}</td>
            <td class="txt-center" style="font-weight: bold;">{{ $row->is_kek ? 'Risiko KEK' : 'Normal' }}</td>
            <td style="font-style: italic;">{{ $row->tindakan ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" class="txt-center" style="padding: 20px;">Tidak ada pemeriksaan Ibu Hamil di periode ini.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection