@extends('layouts.kader')

@section('title', 'Daftar Pemeriksaan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold"><i class="fas fa-notes-medical me-2"></i>Daftar Pemeriksaan</h1>
    <a href="{{ route('kader.pemeriksaan.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4"><i class="fas fa-plus me-2"></i>Pemeriksaan Baru</a>
</div>

<div class="card shadow mb-4 border-0 rounded-4">
    <div class="card-body p-4">
        {{-- Filter Section --}}
        <form action="{{ route('kader.pemeriksaan.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <select class="form-select bg-light border-0" name="type" onchange="this.form.submit()">
                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    <option value="balita" {{ $type == 'balita' ? 'selected' : '' }}>Balita</option>
                    <option value="remaja" {{ $type == 'remaja' ? 'selected' : '' }}>Remaja</option>
                    <option value="lansia" {{ $type == 'lansia' ? 'selected' : '' }}>Lansia</option>
                </select>
            </div>
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Kode Kunjungan..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Cari</button>
            </div>
        </form>

        {{-- Table Section --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 rounded-start">Tanggal</th>
                        <th>Kode</th>
                        <th>Nama Pasien</th>
                        <th>Kategori</th>
                        <th>Hasil (BB / TB / Hb)</th>
                        <th class="text-end pe-4 rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemeriksaans as $cek)
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">{{ $cek->created_at->format('d/m/Y') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $cek->kunjungan->kode_kunjungan ?? '-' }}</span></td>
                        <td class="fw-bold text-dark">
                            {{ $cek->kunjungan->pasien->nama_lengkap ?? 'Pasien Terhapus' }}
                        </td>
                        <td>
                            @php
                                $cat = $cek->kategori_pasien;
                                $badgeColor = $cat == 'balita' ? 'success' : ($cat == 'remaja' ? 'info' : 'warning');
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} px-3 py-1 rounded-pill text-uppercase" style="font-size: 0.7rem;">{{ $cat }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column small text-muted">
                                <span><i class="fas fa-weight me-1"></i> {{ $cek->berat_badan }} kg / {{ $cek->tinggi_badan }} cm</span>
                                @if($cek->hemoglobin)
                                <span class="text-danger"><i class="fas fa-tint me-1"></i> Hb: {{ $cek->hemoglobin }} g/dL</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kader.pemeriksaan.show', $cek->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('kader.pemeriksaan.edit', $cek->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Edit"><i class="fas fa-edit"></i></a>
                                
                                <form action="{{ route('kader.pemeriksaan.destroy', $cek->id) }}" method="POST" onsubmit="return confirm('Hapus data pemeriksaan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <p class="mb-0">Belum ada data pemeriksaan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pemeriksaans->links() }}
        </div>
    </div>
</div>
@endsection