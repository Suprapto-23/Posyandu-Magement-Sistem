@extends('layouts.kader')

@section('title', 'Data Imunisasi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Imunisasi</h1>
</div>

<div class="card shadow mb-4 border-0 rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Anak</th>
                        <th>Jenis Vaksin</th>
                        <th>Dosis</th>
                        <th>Batch No</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imunisasis as $imun)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $imun->kunjungan->pasien->nama_lengkap ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $imun->jenis_imunisasi }}</span> {{ $imun->vaksin }}</td>
                        <td>{{ $imun->dosis }}</td>
                        <td>{{ $imun->batch_number }}</td>
                        <td>
                            <a href="{{ route('kader.imunisasi.show', $imun->id) }}" class="btn btn-sm btn-info text-white rounded-circle"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada data imunisasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $imunisasis->links() }}
        </div>
    </div>
</div>
@endsection