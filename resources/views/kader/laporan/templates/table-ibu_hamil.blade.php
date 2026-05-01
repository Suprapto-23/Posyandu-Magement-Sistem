@extends('kader.laporan.templates.layout')

@section('title', 'Laporan Hasil Pemeriksaan Ibu Hamil')

@section('content')
<table class="data-table">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th width="12%">Tanggal</th>
            <th width="22%">Nama Ibu Hamil</th>
            <th width="10%">BB (kg) / TB (cm)</th>
            <th width="8%">LILA (cm)</th>
            <th width="10%">Tensi Darah</th>
            <th width="12%">Status Risiko</th>
            <th width="22%">Tindakan / Resep Bidan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr>
            <td class="txt-center">{{ $index + 1 }}</td>
            <td class="txt-center">{{ \Carbon\Carbon::parse($row->tanggal_periksa)->format('d/m/Y') }}</td>
            <td class="txt-bold">{{ $row->nama_pasien }}</td>
            <td class="txt-center">{{ $row->berat_badan ?? '-' }} / {{ $row->tinggi_badan ?? '-' }}</td>
            <td class="txt-center {{ $row->is_kek ? 'txt-bold' : '' }}">{{ $row->lingkar_lengan ?? '-' }}</td>
            <td class="txt-center">{{ $row->tekanan_darah ?? '-' }}</td>
            <td class="txt-center">
                @if($row->is_kek)
                    <strong style="color: #b91c1c;">RISIKO KEK</strong>
                @else
                    <span>Normal</span>
                @endif
            </td>
            <td class="txt-italic" style="font-size: 9pt;">
                {{ $row->catatan_bidan ?? $row->catatan_kader ?? 'Tidak ada catatan.' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="txt-center" style="padding: 30px;">
                <strong>NIHIL:</strong> Tidak ditemukan catatan pemeriksaan Ibu Hamil untuk periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 10px; font-size: 8pt; color: #666;">
    * LILA: Lingkar Lengan Atas. KEK: Kekurangan Energi Kronis.
</div>
@endsection