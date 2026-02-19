@extends('layouts.kader')

@section('title', 'Data Lansia')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Data Lansia</h1>
        <p class="text-muted mb-0">Kelola data lansia & pra-lansia</p>
    </div>
    <a href="{{ route('kader.data.lansia.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Tambah Lansia
    </a>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('kader.data.lansia.index') }}" method="GET" class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 bg-light py-2" name="search" 
                       placeholder="Cari nama, NIK, atau kode lansia..." value="{{ $search }}">
                <button class="btn btn-primary px-4 fw-bold" type="submit">Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 rounded-start">Lansia</th>
                        <th>Usia & TTL</th>
                        <th>Riwayat Penyakit</th>
                        <th class="text-end pe-4 rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lansias as $lansia)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-{{ $lansia->jenis_kelamin == 'L' ? 'primary' : 'danger' }}-subtle text-{{ $lansia->jenis_kelamin == 'L' ? 'primary' : 'danger' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <i class="fas fa-user-clock fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $lansia->nama_lengkap }}</h6>
                                    <small class="text-muted">NIK: {{ $lansia->nik }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge bg-light text-dark border fw-normal" style="width: fit-content;">
                                    <i class="fas fa-hourglass-half me-1 text-warning"></i> 
                                    {{ \Carbon\Carbon::parse($lansia->tanggal_lahir)->age }} Tahun
                                </span>
                                <small class="text-muted mt-1">{{ $lansia->tempat_lahir }}, {{ $lansia->tanggal_lahir->format('d M Y') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                @if($lansia->penyakit_bawaan)
                                    @foreach(explode(',', $lansia->penyakit_bawaan) as $sakit)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill" style="font-size: 0.7rem">{{ trim($sakit) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-success small"><i class="fas fa-check me-1"></i> Sehat</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kader.data.lansia.show', $lansia->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('kader.data.lansia.edit', $lansia->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('kader.data.lansia.destroy', $lansia->id) }}" method="POST" onsubmit="return confirm('Hapus data lansia ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">Tidak ada data lansia.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $lansias->links() }}
        </div>
    </div>
</div>
@endsection