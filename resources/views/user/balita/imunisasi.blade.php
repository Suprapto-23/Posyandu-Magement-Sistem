@extends('layouts.user')

@section('title', 'Riwayat Imunisasi')

@section('content')
<div class="container-fluid animate-fade-in">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
            <i class="fas fa-syringe text-primary fa-2x"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Riwayat Imunisasi</h5>
            <small class="text-muted">Daftar vaksinasi buah hati Anda</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Jenis Imunisasi</th>
                            <th>Vaksin</th>
                            <th>Penyelenggara</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatImunisasi as $imun)
                        <tr>
                            <td class="ps-4 fw-medium">
                                {{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                    {{ $imun->jenis_imunisasi }}
                                </span>
                            </td>
                            <td>{{ $imun->vaksin ?? '-' }} (Dosis: {{ $imun->dosis ?? '-' }})</td>
                            <td>{{ $imun->penyelenggara ?? 'Posyandu' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-syringe fa-2x mb-3 opacity-25"></i>
                                <p class="mb-0">Belum ada data imunisasi tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection