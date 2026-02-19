@extends('layouts.kader')

@section('title', 'Data Remaja')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Data Remaja</h1>
        <p class="text-muted mb-0">Kelola data remaja di wilayah kerja Anda</p>
    </div>
    <a href="{{ route('kader.data.remaja.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Tambah Remaja
    </a>
</div>

<div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-body p-4">
        {{-- Search Form --}}
        <form action="{{ route('kader.data.remaja.index') }}" method="GET" class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" class="form-control border-0 bg-light py-2" name="search" 
                       placeholder="Cari nama, NIK, atau sekolah..." value="{{ $search }}">
                <button class="btn btn-primary px-4 fw-bold" type="submit">Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 rounded-start">Remaja</th>
                        <th>Info</th>
                        <th>Sekolah</th>
                        <th class="text-end pe-4 rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($remajas as $remaja)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-{{ $remaja->jenis_kelamin == 'L' ? 'primary' : 'danger' }}-subtle text-{{ $remaja->jenis_kelamin == 'L' ? 'primary' : 'danger' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <i class="fas fa-user-graduate fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $remaja->nama_lengkap }}</h6>
                                    <small class="text-muted">NIK: {{ $remaja->nik }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                {{-- PERBAIKAN: Gunakan ->age agar bulat (integer) --}}
                                <span class="badge bg-light text-dark border fw-normal" style="width: fit-content;">
                                    <i class="fas fa-birthday-cake me-1 text-warning"></i> 
                                    {{ \Carbon\Carbon::parse($remaja->tanggal_lahir)->age }} Tahun
                                </span>
                                <small class="text-muted mt-1">{{ $remaja->alamat }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $remaja->sekolah ?? '-' }}</span>
                                <small class="text-muted">Kelas: {{ $remaja->kelas ?? '-' }}</small>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kader.data.remaja.show', $remaja->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('kader.data.remaja.edit', $remaja->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('kader.data.remaja.destroy', $remaja->id) }}" method="POST" onsubmit="return confirm('Hapus data remaja ini?');">
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
                            <p class="mb-0">Tidak ada data remaja.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $remajas->links() }}
        </div>
    </div>
</div>
@endsection