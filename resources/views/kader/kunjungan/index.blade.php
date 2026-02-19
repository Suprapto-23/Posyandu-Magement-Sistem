@extends('layouts.kader')

@section('title', 'Riwayat Kunjungan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Kunjungan</h1>
    <a href="{{ route('kader.pemeriksaan.create') }}" class="btn btn-success shadow-sm"><i class="fas fa-plus me-2"></i>Catat Kunjungan Baru</a>
</div>

<div class="card shadow mb-4 border-0 rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Layanan</th>
                        <th>Keluhan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $k)
                    <tr>
                        <td>{{ $k->created_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $k->kode_kunjungan }}</span></td>
                        <td class="fw-bold">{{ $k->pasien->nama_lengkap ?? 'Pasien Terhapus' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($k->jenis_kunjungan) }}</span></td>
                        <td>{{ Str::limit($k->keluhan, 30) ?? '-' }}</td>
                        <td>
                            <a href="{{ route('kader.kunjungan.show', $k->id) }}" class="btn btn-sm btn-info text-white rounded-circle"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada data kunjungan yang Anda catat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $kunjungan->links() }}
        </div>
    </div>
</div>
@endsection