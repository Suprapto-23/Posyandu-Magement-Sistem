@extends('layouts.bidan')

@section('title', 'Riwayat Medis & Validasi')
@section('page-title', 'Riwayat Pemeriksaan')
@section('page-subtitle', 'Rekam jejak pemeriksaan & validasi data dari Kader')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- KARTU STATISTIK --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['pending',  'warning', 'clock',        'Menunggu Validasi'],
            ['verified', 'success', 'check-circle', 'Terverifikasi'],
            ['rejected', 'danger',  'times-circle', 'Ditolak'],
            ['total',    'primary', 'list',         'Total Data'],
        ];
    @endphp
    @foreach($statCards as [$key, $color, $icon, $label])
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-3 flex-shrink-0">
                    <i class="fas fa-{{ $icon }} text-{{ $color }} fa-lg"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold lh-1">{{ $stats[$key] }}</div>
                    <div class="text-muted small mt-1">{{ $label }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FILTER --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label small fw-semibold">Cari Nama Pasien</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari nama..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['balita','remaja','lansia'] as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>
                            {{ ucfirst($k) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Status Validasi</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label small fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control"
                       value="{{ request('tahun', date('Y')) }}" min="2020" max="2030">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('bidan.pemeriksaan.index') }}" class="btn btn-outline-secondary" title="Reset">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- TABEL --}}
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
                        <th>Status Kesehatan</th>
                        <th>Validasi</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                    <tr class="{{ ($item->status_verifikasi ?? 'pending') === 'pending' ? 'table-warning bg-opacity-25' : '' }}">
                        <td class="ps-4">
                            <div class="small fw-semibold">
                                {{ $item->tanggal_periksa?->format('d/m/Y') ?? '-' }}
                            </div>
                            <div class="text-muted" style="font-size:0.75rem">
                                {{ $item->pemeriksa?->name ?? 'Sistem' }}
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold d-block">{{ $item->nama_pasien }}</span>
                        </td>
                        <td>
                            @php
                                $kMap = ['balita'=>['info','baby'], 'remaja'=>['success','user-graduate'], 'lansia'=>['warning','wheelchair']];
                                [$kColor, $kIcon] = $kMap[$item->kategori_pasien] ?? ['secondary','user'];
                            @endphp
                            <span class="badge bg-{{ $kColor }} bg-opacity-10 text-{{ $kColor }}">
                                <i class="fas fa-{{ $kIcon }} me-1"></i>{{ ucfirst($item->kategori_pasien) }}
                            </span>
                        </td>
                        <td class="text-muted small" style="max-width:180px">
                            {{ Str::limit($item->diagnosa ?? 'Belum ada diagnosa', 45) }}
                        </td>
                        <td>
                            @if(in_array($item->status_gizi, ['stunting','buruk','risiko']))
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>PERHATIAN
                                </span>
                            @elseif(in_array($item->status_gizi, ['obesitas','lebih']))
                                <span class="badge bg-warning text-dark">OBESITAS</span>
                            @else
                                <span class="badge bg-light text-secondary border">Normal</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status_verifikasi_badge }} bg-opacity-75">
                                <i class="fas fa-{{ ($item->status_verifikasi ?? 'pending') === 'verified' ? 'check' : (($item->status_verifikasi ?? 'pending') === 'rejected' ? 'times' : 'clock') }} me-1"></i>
                                {{ $item->status_verifikasi_label }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('bidan.pemeriksaan.show', $item->id) }}"
                                   class="btn btn-sm btn-light text-primary border" title="Detail & Validasi">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(($item->status_verifikasi ?? 'pending') === 'pending')
                                    <button type="button"
                                            class="btn btn-sm btn-success btn-verifikasi-cepat"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_pasien }}"
                                            title="Verifikasi Cepat">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                            Belum ada riwayat pemeriksaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($riwayat->hasPages())
    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center rounded-bottom-4">
        <div class="text-muted small">
            Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }}
            dari {{ $riwayat->total() }} data
        </div>
        {{ $riwayat->links() }}
    </div>
    @endif
</div>

{{-- MODAL VERIFIKASI CEPAT --}}
<div class="modal fade" id="modalVerifikasiCepat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Verifikasi Cepat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVerifikasiCepat">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">
                        Memverifikasi: <strong id="namaModalCepat"></strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Diagnosa <span class="text-danger">*</span>
                        </label>
                        <textarea name="diagnosa" id="diagnosaCepat" class="form-control" rows="3"
                                  placeholder="Tulis diagnosa bidan..." required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Catatan Bidan</label>
                        <textarea name="catatan_bidan" id="catatanBidanCepat" class="form-control" rows="2"
                                  placeholder="Catatan tambahan (opsional)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" id="btnSubmitVerifikasi">
                        <i class="fas fa-check me-2"></i>Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentId = null;

document.querySelectorAll('.btn-verifikasi-cepat').forEach(btn => {
    btn.addEventListener('click', function () {
        currentId = this.dataset.id;
        document.getElementById('namaModalCepat').textContent = this.dataset.nama;
        document.getElementById('diagnosaCepat').value = '';
        document.getElementById('catatanBidanCepat').value = '';
        new bootstrap.Modal(document.getElementById('modalVerifikasiCepat')).show();
     // ✅ Tambahkan ini — pindahkan modal ke body agar tidak ter-overlap
        const modal = document.getElementById('modalVerifikasiCepat');
        document.body.appendChild(modal);

        new bootstrap.Modal(modal).show();
    });
});

document.getElementById('formVerifikasiCepat').addEventListener('submit', async function (e) {
    e.preventDefault();
    if (!currentId) return;

    const btn = document.getElementById('btnSubmitVerifikasi');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    try {
        const res = await fetch(`/bidan/pemeriksaan/${currentId}/verifikasi-cepat`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new FormData(this),
        });
        const data = await res.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalVerifikasiCepat')).hide();
            window.location.reload();
        } else {
            alert('Gagal menyimpan. Coba lagi.');
        }
    } catch {
        alert('Terjadi kesalahan. Coba lagi.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Verifikasi';
    }
});
</script>
@endpush
@endsection