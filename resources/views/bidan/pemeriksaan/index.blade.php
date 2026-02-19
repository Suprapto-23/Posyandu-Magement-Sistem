@extends('layouts.bidan')

@section('title', 'Riwayat Medis')
@section('page-title', 'Riwayat Pemeriksaan')
@section('page-subtitle', 'Rekam jejak pemeriksaan seluruh pasien')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Kategori</th>
                        <th>Hasil Diagnosa</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                    <tr>
                        <td class="ps-4">{{ $item->tanggal_periksa->format('d/m/Y') }}</td>
                        <td>
                            <span class="fw-bold d-block">
                                @if($item->balita) {{ $item->balita->nama_lengkap }}
                                @elseif($item->lansia) {{ $item->lansia->nama_lengkap }}
                                @elseif($item->remaja) {{ $item->remaja->nama_lengkap }}
                                @else - @endif
                            </span>
                        </td>
                        <td>
                            @if($item->kategori_pasien == 'balita') <span class="badge bg-info bg-opacity-10 text-info">Balita</span>
                            @elseif($item->kategori_pasien == 'lansia') <span class="badge bg-warning bg-opacity-10 text-warning">Lansia</span>
                            @else <span class="badge bg-success bg-opacity-10 text-success">Remaja</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ Str::limit($item->hasil_diagnosa, 40) }}</td>
                        <td>
                            @if(in_array($item->status_gizi, ['stunting', 'buruk', 'sakit', 'risiko']))
                                <span class="badge bg-danger">PERHATIAN</span>
                            @else
                                <span class="badge bg-light text-secondary border">Normal</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light text-primary"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">Belum ada riwayat pemeriksaan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $riwayat->links() }}
    </div>
</div>
@endsection