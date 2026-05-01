@extends('kader.laporan.templates.layout')

@section('title', 'Laporan Data Pemberian Imunisasi')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal</th>
            <th width="25%">Nama Pasien</th>
            <th width="18%">Jenis Vaksin</th>
            <th width="10%">Dosis</th>
            <th width="30%">Penyelenggara / Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_imunisasi)->format('d/m/Y') }}</td>
            <td class="txt-bold">{{ $row->kunjungan->pasien->nama_lengkap ?? 'Data Terhapus' }}</td>
            <td class="txt-center txt-bold">{{ $row->vaksin }}</td>
            <td class="txt-center">Ke-{{ $row->dosis }}</td>
            <td class="txt-italic" style="font-size: 9pt;">
                {{ $row->penyelenggara ?? 'Posyandu Terpadu' }} <br>
                (Petugas: {{ $row->kunjungan->petugas->name ?? 'Bidan' }})
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="txt-center" style="padding: 30px;">
                <strong>NIHIL:</strong> Tidak ditemukan riwayat pemberian imunisasi vaksin untuk periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection