@extends('layouts.kader')

@section('title', 'Detail Pemeriksaan')
@section('page-title', 'Detail Pemeriksaan')
@section('page-subtitle', 'Informasi lengkap hasil pemeriksaan pasien')

@section('content')
<div class="mb-3">
    <a href="{{ route('kader.pemeriksaan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row g-4">
    {{-- KIRI: DATA PEMERIKSAAN --}}
    <div class="col-lg-8">

        {{-- Header Status --}}
        @php
            $sv = $pemeriksaan->status_verifikasi ?? 'pending';
            $svConfig = [
                'verified' => ['success', 'check-circle',  'Data Terverifikasi oleh Bidan'],
                'rejected' => ['danger',  'times-circle',  'Data Ditolak oleh Bidan'],
                'pending'  => ['warning', 'clock',         'Menunggu Verifikasi Bidan'],
            ];
            [$svColor, $svIcon, $svLabel] = $svConfig[$sv] ?? $svConfig['pending'];
        @endphp

        <div class="alert alert-{{ $svColor }} rounded-4 d-flex align-items-center gap-3 border-0">
            <i class="fas fa-{{ $svIcon }} fa-2x"></i>
            <div>
                <div class="fw-bold">{{ $svLabel }}</div>
                @if($pemeriksaan->verified_at)
                    <div class="small opacity-75">
                        oleh {{ $pemeriksaan->verifikator?->name ?? '-' }}
                        · {{ \Carbon\Carbon::parse($pemeriksaan->verified_at)->format('d M Y H:i') }}
                    </div>
                @endif
            </div>
            @if($sv === 'pending')
                <div class="ms-auto">
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-hourglass-half me-1"></i>Dalam Antrian
                    </span>
                </div>
            @endif
        </div>

        {{-- Catatan Bidan (jika ada) --}}
        @if($pemeriksaan->catatan_bidan)
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-{{ $svColor }} border-3">
            <div class="card-body">
                <div class="fw-semibold mb-1">
                    <i class="fas fa-comment-medical me-2 text-{{ $svColor }}"></i>Catatan Bidan
                </div>
                <div class="text-muted">{{ $pemeriksaan->catatan_bidan }}</div>
            </div>
        </div>
        @endif

        {{-- Info Pasien --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom fw-semibold rounded-top-4">
                <i class="fas fa-user me-2 text-primary"></i>Data Pasien
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">Nama Pasien</div>
                        <div class="fw-semibold">{{ $pemeriksaan->nama_pasien }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Kategori</div>
                        <span class="badge bg-info">{{ ucfirst($pemeriksaan->kategori_pasien) }}</span>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Tanggal Periksa</div>
                        <div class="fw-semibold">{{ $pemeriksaan->tanggal_periksa?->format('d F Y') ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Diinput Oleh</div>
                        <div>{{ $pemeriksaan->pemeriksa?->name ?? 'Sistem' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hasil Pemeriksaan --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom fw-semibold rounded-top-4">
                <i class="fas fa-stethoscope me-2 text-danger"></i>Hasil Pemeriksaan
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $fields = [
                            'berat_badan'   => ['Berat Badan',   'kg'],
                            'tinggi_badan'  => ['Tinggi Badan',  'cm'],
                            'suhu_tubuh'    => ['Suhu Tubuh',    '°C'],
                            'tekanan_darah' => ['Tekanan Darah', 'mmHg'],
                            'hemoglobin'    => ['Hemoglobin',    'g/dL'],
                            'gula_darah'    => ['Gula Darah',    'mg/dL'],
                            'kolesterol'    => ['Kolesterol',    'mg/dL'],
                            'asam_urat'     => ['Asam Urat',     'mg/dL'],
                            'lingkar_kepala'=> ['Lingkar Kepala','cm'],
                            'lingkar_lengan'=> ['Lingkar Lengan','cm'],
                        ];
                    @endphp
                    @foreach($fields as $col => [$label, $satuan])
                        @if(!empty($pemeriksaan->$col))
                        <div class="col-6 col-md-4">
                            <div class="text-muted small">{{ $label }}</div>
                            <div class="fw-semibold">{{ $pemeriksaan->$col }} {{ $satuan }}</div>
                        </div>
                        @endif
                    @endforeach
                    @if($pemeriksaan->berat_badan && $pemeriksaan->tinggi_badan)
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">IMT</div>
                        <div class="fw-semibold">{{ $pemeriksaan->imt }}</div>
                    </div>
                    @endif
                    @if($pemeriksaan->status_gizi)
                    <div class="col-6 col-md-4">
                        <div class="text-muted small">Status Gizi</div>
                        <div class="fw-semibold text-capitalize">{{ $pemeriksaan->status_gizi }}</div>
                    </div>
                    @endif
                    @if($pemeriksaan->keluhan)
                    <div class="col-12">
                        <div class="text-muted small">Keluhan</div>
                        <div>{{ $pemeriksaan->keluhan }}</div>
                    </div>
                    @endif
                    @if($pemeriksaan->tindakan)
                    <div class="col-12">
                        <div class="text-muted small">Tindakan</div>
                        <div>{{ $pemeriksaan->tindakan }}</div>
                    </div>
                    @endif
                    @if($pemeriksaan->diagnosa)
                    <div class="col-12">
                        <div class="text-muted small">Diagnosa Bidan</div>
                        <div class="fw-semibold text-primary">{{ $pemeriksaan->diagnosa }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: AKSI --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom fw-semibold rounded-top-4">
                <i class="fas fa-cog me-2 text-secondary"></i>Aksi
            </div>
            <div class="card-body d-grid gap-2">

                @if($sv !== 'verified')
                    {{-- Kader bisa edit jika belum verified --}}
                    <a href="{{ route('kader.pemeriksaan.edit', $pemeriksaan->id) }}"
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Data
                    </a>
                @else
                    <div class="alert alert-success rounded-3 small mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Data sudah dikunci karena telah diverifikasi Bidan.
                    </div>
                @endif

                @if($sv === 'pending')
                    <div class="alert alert-warning rounded-3 small mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Data sedang menunggu verifikasi dari Bidan. Anda masih bisa mengedit data ini.
                    </div>
                @endif

                @if($sv === 'rejected')
                    <div class="alert alert-danger rounded-3 small mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data ditolak Bidan. Silakan edit dan perbaiki data, lalu simpan kembali.
                    </div>
                @endif

                @if($sv !== 'verified')
                    <form action="{{ route('kader.pemeriksaan.destroy', $pemeriksaan->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Data
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection