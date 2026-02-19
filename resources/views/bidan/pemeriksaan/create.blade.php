@extends('layouts.bidan')

@section('title', 'Input Pemeriksaan')
@section('page-title', 'Pemeriksaan Pasien')
@section('page-subtitle', 'Input data pemeriksaan kesehatan warga')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                {{-- Tab Kategori --}}
                <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="pills-tab">
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'balita' ? 'active bg-primary' : '' }}"
                           href="?kategori=balita">
                            <i class="fas fa-baby me-2"></i>Balita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'remaja' ? 'active bg-success' : '' }}"
                           href="?kategori=remaja">
                            <i class="fas fa-user-graduate me-2"></i>Remaja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'lansia' ? 'active bg-warning text-dark' : '' }}"
                           href="?kategori=lansia">
                            <i class="fas fa-wheelchair me-2"></i>Lansia
                        </a>
                    </li>
                </ul>

                <form action="{{ route('bidan.pemeriksaan.store') }}" method="POST">
                    @csrf
                    {{-- Wajib: kategori_pasien (bukan kategori) agar sesuai validasi controller --}}
                    <input type="hidden" name="kategori_pasien" value="{{ $kategori }}">
                    <input type="hidden" name="tanggal_periksa" value="{{ date('Y-m-d') }}">

                    <div class="row g-4">

                        {{-- Pilih Pasien --}}
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">
                                Identitas Pasien
                            </label>
                            <select name="pasien_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Nama Pasien {{ ucfirst($kategori) }} --</option>
                                @foreach($pasien as $p)
                                    <option value="{{ $p->id }}" {{ old('pasien_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_lengkap }} (NIK: {{ $p->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pasien_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25"></div>

                        {{-- Antropometri Dasar --}}
                        <div class="col-md-6">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat_badan"
                                   class="form-control" placeholder="0.0"
                                   value="{{ old('berat_badan') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan"
                                   class="form-control" placeholder="0.0"
                                   value="{{ old('tinggi_badan') }}">
                        </div>

                        {{-- Field khusus Balita --}}
                        @if($kategori == 'balita')
                        <div class="col-md-6">
                            <label class="form-label">Lingkar Kepala (cm)</label>
                            <input type="number" step="0.01" name="lingkar_kepala"
                                   class="form-control" value="{{ old('lingkar_kepala') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lingkar Lengan (cm)</label>
                            <input type="number" step="0.01" name="lingkar_lengan"
                                   class="form-control" value="{{ old('lingkar_lengan') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1" name="suhu_tubuh"
                                   class="form-control" placeholder="36.5"
                                   value="{{ old('suhu_tubuh') }}">
                        </div>
                        @endif

                        {{-- Field khusus Remaja & Lansia --}}
                        @if($kategori == 'remaja' || $kategori == 'lansia')
                        <div class="col-md-6">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah"
                                   class="form-control" placeholder="120/80"
                                   value="{{ old('tekanan_darah') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gula Darah (mg/dL)</label>
                            <input type="number" name="gula_darah"
                                   class="form-control" value="{{ old('gula_darah') }}">
                        </div>
                        @endif

                        {{-- Field khusus Lansia tambahan --}}
                        @if($kategori == 'lansia')
                        <div class="col-md-6">
                            <label class="form-label">Kolesterol (mg/dL)</label>
                            <input type="number" name="kolesterol"
                                   class="form-control" value="{{ old('kolesterol') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Asam Urat (mg/dL)</label>
                            <input type="number" step="0.01" name="asam_urat"
                                   class="form-control" value="{{ old('asam_urat') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hemoglobin (g/dL)</label>
                            <input type="number" step="0.01" name="hemoglobin"
                                   class="form-control" value="{{ old('hemoglobin') }}">
                        </div>
                        @endif

                        <div class="col-12"><hr class="text-muted opacity-25"></div>

                        {{-- Status Kesehatan --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Status Kesehatan / Gizi</label>
                            <div class="d-flex flex-wrap gap-3">
                                @if($kategori == 'balita')
                                    @foreach(['baik' => ['Normal/Baik', 'success'], 'kurang' => ['Gizi Kurang', 'warning'], 'stunting' => ['Stunting', 'danger'], 'buruk' => ['Gizi Buruk', 'danger'], 'obesitas' => ['Obesitas', 'warning']] as $val => $opt)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_gizi"
                                               value="{{ $val }}" id="gizi_{{ $val }}"
                                               {{ old('status_gizi', 'baik') == $val ? 'checked' : '' }}>
                                        <label class="form-check-label text-{{ $opt[1] }} fw-semibold" for="gizi_{{ $val }}">
                                            {{ $opt[0] }}
                                        </label>
                                    </div>
                                    @endforeach
                                @else
                                    @foreach(['baik' => ['Sehat', 'success'], 'risiko' => ['Berisiko', 'warning'], 'buruk' => ['Sakit/Rujuk', 'danger']] as $val => $opt)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_gizi"
                                               value="{{ $val }}" id="gizi_{{ $val }}"
                                               {{ old('status_gizi', 'baik') == $val ? 'checked' : '' }}>
                                        <label class="form-check-label text-{{ $opt[1] }} fw-semibold" for="gizi_{{ $val }}">
                                            {{ $opt[0] }}
                                        </label>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Keluhan --}}
                        <div class="col-12">
                            <label class="form-label">Keluhan Pasien</label>
                            <input type="text" name="keluhan" class="form-control"
                                   placeholder="Contoh: demam, pusing, tidak nafsu makan..."
                                   value="{{ old('keluhan') }}">
                        </div>

                        {{-- Diagnosa (field name: hasil_diagnosa → controller map ke diagnosa) --}}
                        <div class="col-12">
                            <label class="form-label">Catatan Medis / Diagnosa</label>
                            <textarea name="hasil_diagnosa" class="form-control" rows="3"
                                      placeholder="Contoh: Kondisi umum baik, nafsu makan normal...">{{ old('hasil_diagnosa') }}</textarea>
                        </div>

                        {{-- Tindakan --}}
                        <div class="col-12">
                            <label class="form-label">Tindakan / Resep / Rekomendasi</label>
                            <textarea name="tindakan" class="form-control" rows="2"
                                      placeholder="Contoh: Pemberian Vitamin A, Rujukan ke Puskesmas...">{{ old('tindakan') }}</textarea>
                        </div>

                    </div>

                    {{-- Info: data bidan langsung verified --}}
                    <div class="alert alert-info rounded-3 mt-4 mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <span class="small">Data yang Anda input sebagai Bidan akan otomatis berstatus <strong>Terverifikasi</strong>.</span>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('bidan.pemeriksaan.index') }}" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2">
                            <i class="fas fa-save me-2"></i>Simpan Pemeriksaan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection