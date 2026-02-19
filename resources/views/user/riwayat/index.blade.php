@extends('layouts.user')

@section('title', 'Riwayat Kunjungan')
@section('page-title', 'Catatan Kesehatan')

@section('content')
<div class="container-fluid animate-fade-in">
    {{-- Header Sederhana --}}
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
            <i class="fas fa-history text-primary fa-2x"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Riwayat Kunjungan</h5>
            <small class="text-muted">Daftar semua aktivitas pemeriksaan dan imunisasi Anda</small>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th class="py-3">Layanan</th>
                            <th class="py-3">Hasil / Keluhan</th>
                            <th class="py-3">Petugas</th>
                            <th class="pe-4 py-3 text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">
                                {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d M Y') }}
                            </td>
                            <td>
                                @php
                                    $color = match($item->jenis_kunjungan) {
                                        'imunisasi' => 'info',
                                        'pemeriksaan' => 'success',
                                        'konsultasi' => 'primary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} px-3 py-1 rounded-pill text-uppercase" style="font-size: 0.7rem;">
                                    {{ $item->jenis_kunjungan }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    {{-- Cek jika ada keluhan --}}
                                    @if($item->keluhan)
                                        <small class="text-muted">Keluhan: <span class="text-dark">{{ Str::limit($item->keluhan, 30) }}</span></small>
                                    @endif

                                    {{-- Cek jika ada hasil pemeriksaan --}}
                                    @if($item->pemeriksaan)
                                        <small class="text-muted">Diagnosa: <span class="text-dark fw-bold">{{ $item->pemeriksaan->diagnosa ?? '-' }}</span></small>
                                    @elseif($item->imunisasis->count() > 0)
                                        <small class="text-muted">Vaksin: <span class="text-dark fw-bold">{{ $item->imunisasis->first()->jenis_imunisasi }}</span></small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-1 me-2 text-center" style="width: 25px; height: 25px;">
                                        <i class="fas fa-user-nurse text-secondary" style="font-size: 0.7rem;"></i>
                                    </div>
                                    <small>{{ $item->petugas->name ?? 'Admin' }}</small>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <span class="badge bg-light text-dark border">Selesai</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-clipboard-list fa-3x text-muted opacity-25"></i>
                                </div>
                                <h6 class="fw-bold text-muted">Belum ada riwayat kunjungan.</h6>
                                <p class="small text-muted mb-0">Data akan muncul setelah Anda melakukan pemeriksaan di Posyandu.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
@endsection