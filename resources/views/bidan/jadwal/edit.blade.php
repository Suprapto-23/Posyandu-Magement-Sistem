@extends('layouts.bidan')

@section('title', 'Edit Jadwal Posyandu')
@section('page-title', 'Edit Jadwal')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-primary">Edit Detail Jadwal</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('bidan.jadwal.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $jadwal->judul) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                @foreach(['imunisasi', 'pemeriksaan', 'konseling', 'posyandu', 'lainnya'] as $cat)
                                    <option value="{{ $cat }}" {{ $jadwal->kategori == $cat ? 'selected' : '' }}>
                                        {{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Peserta</label>
                            <select name="target_peserta" class="form-select" required>
                                @foreach(['semua', 'balita', 'remaja', 'lansia', 'ibu_hamil'] as $target)
                                    <option value="{{ $target }}" {{ $jadwal->target_peserta == $target ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $target)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pelaksanaan</label>
                        {{-- Gunakan format Y-m-d untuk input type="date" --}}
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ $jadwal->tanggal ? $jadwal->tanggal->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" 
                                   value="{{ $jadwal->waktu_mulai ? $jadwal->waktu_mulai->format('H:i') : '' }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" 
                                   value="{{ $jadwal->waktu_selesai ? $jadwal->waktu_selesai->format('H:i') : '' }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $jadwal->lokasi) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Jadwal</label>
                        <select name="status" class="form-select" required>
                            <option value="aktif" {{ $jadwal->status == 'aktif' ? 'selected' : '' }}>Aktif / Mendatang</option>
                            <option value="selesai" {{ $jadwal->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $jadwal->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $jadwal->deskripsi) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('bidan.jadwal.index') }}" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection