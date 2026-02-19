@extends('layouts.kader')

@section('title', 'Tambah Pemeriksaan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Pemeriksaan</h1>
    <a href="{{ route('kader.pemeriksaan.index') }}" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="card shadow rounded-4 border-0">
    <div class="card-header bg-primary text-white rounded-top-4 py-3">
        <h6 class="m-0 fw-bold"><i class="fas fa-stethoscope me-2"></i>Formulir Pemeriksaan Kesehatan</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="pemeriksaanForm">
            @csrf
            
            {{-- 1. IDENTITAS PASIEN --}}
            <div class="mb-4 border-bottom pb-4">
                <h5 class="text-primary fw-bold mb-3">1. Identitas Pasien</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Kategori Pasien <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0" name="pasien_type" id="pasien_type" onchange="changeFormType(this.value)" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="balita" {{ old('pasien_type') == 'balita' ? 'selected' : '' }}>Balita</option>
                            <option value="remaja" {{ old('pasien_type') == 'remaja' ? 'selected' : '' }}>Remaja</option>
                            <option value="lansia" {{ old('pasien_type') == 'lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>
                    
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-muted small">Nama Pasien <span class="text-danger">*</span></label>
                        
                        {{-- Dropdown Balita --}}
                        <select class="form-select pasien-select bg-light border-0" name="pasien_id" id="select_balita" style="display:none;" disabled>
                            <option value="">-- Cari Data Balita --</option>
                            @foreach($balitas as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }} (NIK: {{ $p->nik }})</option>
                            @endforeach
                        </select>

                        {{-- Dropdown Remaja --}}
                        <select class="form-select pasien-select bg-light border-0" name="pasien_id" id="select_remaja" style="display:none;" disabled>
                            <option value="">-- Cari Data Remaja --</option>
                            @foreach($remajas as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }} (NIK: {{ $p->nik }})</option>
                            @endforeach
                        </select>

                        {{-- Dropdown Lansia --}}
                        <select class="form-select pasien-select bg-light border-0" name="pasien_id" id="select_lansia" style="display:none;" disabled>
                            <option value="">-- Cari Data Lansia --</option>
                            @foreach($lansias as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }} (NIK: {{ $p->nik }})</option>
                            @endforeach
                        </select>
                        <div id="no-category-msg" class="form-text text-muted">Pilih kategori pasien terlebih dahulu.</div>
                    </div>
                </div>
            </div>

            {{-- 2. DATA KUNJUNGAN --}}
            <div class="mb-4 border-bottom pb-4">
                <h5 class="text-primary fw-bold mb-3">2. Data Kunjungan</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Tanggal Kunjungan</label>
                        <input type="date" class="form-control bg-light border-0" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Jenis Layanan</label>
                        <select class="form-select bg-light border-0" name="jenis_kunjungan" required>
                            <option value="pemeriksaan" selected>Pemeriksaan Kesehatan</option>
                            <option value="imunisasi">Imunisasi</option>
                            <option value="konsultasi">Konsultasi</option>
                            <option value="umum">Kunjungan Umum</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Keluhan Utama</label>
                        <input type="text" class="form-control bg-light border-0" name="keluhan" placeholder="Contoh: Demam, Batuk, Pusing">
                    </div>
                </div>
            </div>

            {{-- 3. HASIL PEMERIKSAAN --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold mb-3">3. Hasil Pemeriksaan</h5>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Berat Badan (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="berat_badan" required placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="tinggi_badan" required placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Suhu Tubuh (°C)</label>
                        <input type="number" step="0.1" class="form-control" name="suhu_tubuh" placeholder="36.5">
                    </div>
                </div>

                {{-- FIELD KHUSUS BALITA --}}
                <div id="fields_balita" class="specific-fields p-3 rounded-3 bg-info bg-opacity-10 mb-3" style="display:none;">
                    <h6 class="fw-bold text-info"><i class="fas fa-baby me-1"></i> Parameter Khusus Balita</h6>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <label class="form-label small">Lingkar Kepala (cm)</label>
                            <input type="number" step="0.1" class="form-control border-info" name="lingkar_kepala">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Lingkar Lengan (cm)</label>
                            <input type="number" step="0.1" class="form-control border-info" name="lingkar_lengan">
                        </div>
                    </div>
                </div>

                {{-- FIELD KHUSUS UMUM (REMAJA & LANSIA) --}}
                <div id="fields_umum" class="specific-fields p-3 rounded-3 bg-warning bg-opacity-10 mb-3" style="display:none;">
                    <h6 class="fw-bold text-warning"><i class="fas fa-heartbeat me-1"></i> Parameter Kesehatan Umum</h6>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label small">Tekanan Darah (mmHg)</label>
                            <input type="text" class="form-control border-warning" name="tekanan_darah" placeholder="120/80">
                        </div>
                        
                        {{-- DISINI PENAMBAHAN INPUT HEMOGLOBIN --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Hemoglobin (Hb)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" class="form-control border-warning" name="hemoglobin" placeholder="Contoh: 12.5">
                                <span class="input-group-text border-warning bg-warning bg-opacity-10">g/dL</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small">Gula Darah (mg/dL)</label>
                            <input type="number" class="form-control border-warning" name="gula_darah">
                        </div>
                        
                        <div class="col-md-4 mt-3">
                            <label class="form-label small">Kolesterol (mg/dL)</label>
                            <input type="number" class="form-control border-warning" name="kolesterol">
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label small">Asam Urat (mg/dL)</label>
                            <input type="number" step="0.1" class="form-control border-warning" name="asam_urat">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. KESIMPULAN --}}
            <div class="mb-4 border-top pt-4">
                <h5 class="text-primary fw-bold mb-3">4. Kesimpulan & Saran</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Diagnosa / Hasil</label>
                        <textarea class="form-control bg-light border-0" name="diagnosa" rows="2" placeholder="Hasil pemeriksaan kader..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Tindakan / Pemberian Vitamin</label>
                        <textarea class="form-control bg-light border-0" name="tindakan" rows="2" placeholder="Tindakan yang diberikan..."></textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                <button type="reset" class="btn btn-light me-md-2 px-4 rounded-pill fw-bold">Reset</button>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow">
                    <i class="fas fa-save me-2"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function changeFormType(type) {
        // 1. Sembunyikan semua select pasien & field khusus
        document.querySelectorAll('.pasien-select').forEach(el => {
            el.style.display = 'none';
            el.disabled = true; 
        });
        document.querySelectorAll('.specific-fields').forEach(el => el.style.display = 'none');
        document.getElementById('no-category-msg').style.display = 'none';

        // 2. Tampilkan sesuai pilihan
        if (type) {
            // Dropdown Pasien
            let selectId = 'select_' + type;
            let selectEl = document.getElementById(selectId);
            if(selectEl) {
                selectEl.style.display = 'block';
                selectEl.disabled = false;
            }

            // Field Khusus
            if(type === 'balita') {
                document.getElementById('fields_balita').style.display = 'block';
            } else {
                // Remaja dan Lansia sama-sama pakai fields_umum
                document.getElementById('fields_umum').style.display = 'block';
            }
        } else {
            document.getElementById('no-category-msg').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        let currentType = document.getElementById('pasien_type').value;
        if(currentType) changeFormType(currentType);
    });
</script>
@endpush
@endsection