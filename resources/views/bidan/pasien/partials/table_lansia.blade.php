<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4 py-3 text-uppercase text-muted small fw-bold">Identitas Lansia</th>
                <th class="text-uppercase text-muted small fw-bold">Usia & Kontak</th>
                <th class="text-uppercase text-muted small fw-bold">Status Tensi</th>
                <th class="text-uppercase text-muted small fw-bold">Riwayat Penyakit</th>
                <th class="text-center text-uppercase text-muted small fw-bold">Aksi Medis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lansias as $item)
            @php
                $usia = \Carbon\Carbon::parse($item->tanggal_lahir)->age;
                $pemeriksaan = $item->pemeriksaan_terakhir;
                $tensi = $pemeriksaan ? $pemeriksaan->tekanan_darah : null;
                
                // Logika Warna Tensi
                $bgClass = 'bg-light text-muted';
                $label = 'Belum Cek';
                
                if ($tensi) {
                    $sistolik = (int) explode('/', $tensi)[0];
                    if ($sistolik >= 180) {
                        $bgClass = 'bg-danger text-white'; $label = 'Kritis';
                    } elseif ($sistolik >= 140) {
                        $bgClass = 'bg-warning text-dark'; $label = 'Hipertensi';
                    } elseif ($sistolik >= 120) {
                        $bgClass = 'bg-info text-white'; $label = 'Pra-Hipertensi';
                    } else {
                        $bgClass = 'bg-success text-white'; $label = 'Normal';
                    }
                }
            @endphp
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3 bg-secondary bg-opacity-10 text-secondary">
                            {{ substr($item->nama_lengkap, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $item->nama_lengkap }}</h6>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                NIK: {{ $item->nik }}
                            </small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="fw-medium text-dark">{{ $usia }} Tahun</span>
                    @if($item->no_hp)
                        <br><small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $item->no_hp }}</small>
                    @endif
                </td>
                <td>
                    @if($tensi)
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold" style="font-size: 1.1rem;">{{ $tensi }}</span>
                            <span class="badge {{ $bgClass }} rounded-pill px-2">{{ $label }}</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            Cek: {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->diffForHumans() }}
                        </small>
                    @else
                        <span class="badge bg-light text-muted border">Belum ada data</span>
                    @endif
                </td>
                <td>
                    @if($item->riwayat_penyakit)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(explode(',', $item->riwayat_penyakit) as $penyakit)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10">
                                    {{ trim($penyakit) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted small">-</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        {{-- Tombol Pantau Grafik --}}
                        <a href="#" class="btn btn-sm btn-light text-warning border" title="Pantau Grafik Kesehatan">
                            <i class="fas fa-chart-line"></i>
                        </a>
                        
                        {{-- Tombol Periksa Cepat --}}
                        <a href="{{ route('bidan.pemeriksaan.create', ['kategori' => 'lansia', 'pasien_id' => $item->id]) }}" 
                           class="btn btn-sm btn-primary" title="Input Pemeriksaan Baru">
                            <i class="fas fa-stethoscope me-1"></i> Periksa
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-wheelchair fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Data lansia tidak ditemukan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card-footer bg-white border-0 py-3">
    {{ $lansias->links() }}
</div>

<style>
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 1.1rem;
    }
</style>