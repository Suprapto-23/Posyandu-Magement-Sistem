@extends('layouts.bidan')

@section('title', 'Input Pemeriksaan')
@section('page-title', 'Pemeriksaan Pasien')
@section('page-subtitle', 'Input data pemeriksaan kesehatan warga')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'balita' ? 'active bg-primary' : '' }}" href="?kategori=balita">
                            <i class="fas fa-baby me-2"></i>Balita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'remaja' ? 'active bg-success' : '' }}" href="?kategori=remaja">
                            <i class="fas fa-user-graduate me-2"></i>Remaja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill {{ $kategori == 'lansia' ? 'active bg-warning text-dark' : '' }}" href="?kategori=lansia">
                            <i class="fas fa-wheelchair me-2"></i>Lansia
                        </a>
                    </li>
                </ul>

                <form action="{{ route('bidan.pemeriksaan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kategori_pasien" value="{{ $kategori }}">

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Identitas Pasien</label>
                            <select name="pasien_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Nama Pasien {{ ucfirst($kategori) }} --</option>
                                @foreach($pasien as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_lengkap }} (NIK: {{ $p->nik }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12"><hr class="text-muted opacity-25"></div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat_badan" class="form-control" placeholder="0.0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan" class="form-control" placeholder="0.0">
                        </div>

                        @if($kategori == 'balita')
                        <div class="col-md-6">
                            <label class="form-label">Lingkar Kepala (cm)</label>
                            <input type="number" step="0.01" name="lingkar_kepala" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1" name="suhu_tubuh" class="form-control">
                        </div>
                        @endif

                        @if($kategori == 'lansia' || $kategori == 'remaja')
                        <div class="col-md-6">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gula Darah (mg/dL)</label>
                            <input type="number" name="gula_darah" class="form-control">
                        </div>
                        @endif

                        <div class="col-12"><hr class="text-muted opacity-25"></div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Status Kesehatan</label>
                            <div class="d-flex gap-3">
                                @if($kategori == 'balita')
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="baik" checked>
                                    <label class="form-check-label">Gizi Baik</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="stunting">
                                    <label class="form-check-label text-danger fw-bold">Stunting</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="buruk">
                                    <label class="form-check-label text-danger fw-bold">Gizi Buruk</label>
                                </div>
                                @else
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="baik" checked>
                                    <label class="form-check-label">Sehat</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="risiko">
                                    <label class="form-check-label text-warning fw-bold">Berisiko</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status_gizi" value="sakit">
                                    <label class="form-check-label text-danger fw-bold">Sakit (Rujuk)</label>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Catatan Medis / Diagnosa</label>
                            <textarea name="hasil_diagnosa" class="form-control" rows="3" placeholder="Contoh: Kondisi umum baik, nafsu makan normal..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Tindakan / Resep Vitamin</label>
                            <textarea name="tindakan" class="form-control" rows="2" placeholder="Contoh: Pemberian Vitamin A, Rujukan ke Puskesmas..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-bidan px-5 py-2">
                            <i class="fas fa-save me-2"></i> Simpan Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection