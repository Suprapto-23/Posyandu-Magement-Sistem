@extends('layouts.kader')

@section('title', 'Jadwal Posyandu')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Jadwal Posyandu</h1>
        <p class="text-muted mb-0">Informasi kegiatan yang dijadwalkan oleh Bidan</p>
    </div>
    {{-- TOMBOL BUAT JADWAL DIHAPUS --}}
</div>

<div class="card shadow mb-4 border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Sasaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            <span class="d-block fw-bold text-dark">{{ $jadwal->judul }}</span>
                            <small class="text-muted">{{ Str::limit($jadwal->deskripsi, 30) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="far fa-clock me-1"></i>
                                {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}
                            </span>
                        </td>
                        <td>{{ $jadwal->lokasi }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">
                                {{ ucfirst($jadwal->target_peserta) }}
                            </span>
                        </td>
                        <td>
                            @if($jadwal->status == 'aktif')
                                <span class="badge bg-success">Akan Datang</span>
                            @else
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">Belum ada jadwal kegiatan dari Bidan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-top">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection