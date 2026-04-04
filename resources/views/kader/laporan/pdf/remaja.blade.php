@extends('kader.laporan.pdf.layout')
@section('title', 'Laporan Skrining Kesehatan Remaja')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="10%">Tanggal</th>
            <th width="18%">Nama Remaja</th>
            <th width="5%">L/P</th>
            <th width="8%">BB/TB</th>
            <th width="8%">LiLA</th>
            <th width="8%">Tensi</th>
            <th width="8%">Hb</th>
            <th width="31%">Edukasi / Tindakan</th>
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
            <td class="txt-center">{{ $row->lingkar_lengan ?? '-' }}</td>
            <td class="txt-center">{{ $row->tekanan_darah ?? '-' }}</td>
            <td class="txt-center">{{ $row->hemoglobin ?? '-' }}</td>
            <td style="font-style: italic;">{{ $row->tindakan ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="9" class="txt-center" style="padding: 20px;">Tidak ada pemeriksaan remaja di periode ini.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection