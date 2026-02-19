@extends('layouts.bidan')

@section('title', 'Jadwal Posyandu')
@section('page-title', 'Jadwal Kegiatan')
@section('page-subtitle', 'Atur jadwal kegiatan Posyandu bulan ini')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-primary">Buat Jadwal Baru</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('bidan.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Posyandu Melati I" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="posyandu">Posyandu</option>
                            <option value="imunisasi">Imunisasi</option>
                            <option value="pemeriksaan">Pemeriksaan</option>
                            <option value="konseling">Konseling</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Peserta</label>
                        <select name="target_peserta" class="form-select" required>
                            <option value="semua">Semua</option>
                            <option value="balita">Balita</option>
                            <option value="remaja">Remaja</option>
                            <option value="lansia">Lansia</option>
                            <option value="ibu_hamil">Ibu Hamil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Balai Desa..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Catatan untuk kader/warga..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-paper-plane me-2"></i> Terbitkan Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Daftar Jadwal Kegiatan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">
                                    {{-- Pastikan nama kolom sesuai: 'tanggal' bukan 'tanggal_kegiatan' --}}
                                    {{ $jadwal->tanggal ? $jadwal->tanggal->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <span class="d-block fw-bold">{{ $jadwal->judul }}</span>
                                    <small class="text-muted text-uppercase" style="font-size: 10px;">{{ $jadwal->kategori }}</small>
                                </td>
                                <td class="text-muted text-sm">
                                    {{-- Gunakan pengecekan null sebelum format() --}}
                                    {{ $jadwal->waktu_mulai ? $jadwal->waktu_mulai->format('H:i') : '--:--' }} - 
                                    {{ $jadwal->waktu_selesai ? $jadwal->waktu_selesai->format('H:i') : '--:--' }}
                                </td>
                                <td>{{ $jadwal->lokasi }}</td>
                                <td>
                                    @if($jadwal->status == 'dibatalkan')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @elseif($jadwal->tanggal && $jadwal->tanggal->isPast())
                                        <span class="badge bg-secondary">Selesai</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('bidan.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('bidan.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada jadwal.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $jadwals->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection