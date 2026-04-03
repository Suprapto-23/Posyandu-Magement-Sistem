<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaans';

    // Menggunakan guarded agar kita tidak perlu repot menulis fillable yang sangat panjang
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_periksa' => 'date',
        'verified_at'     => 'datetime',
        'berat_badan'     => 'float',
        'tinggi_badan'    => 'float',
        'imt'             => 'float',   // Tambahan: Casting ke desimal
        'suhu_tubuh'      => 'float',
        'lingkar_kepala'  => 'float',
        'lingkar_lengan'  => 'float',
        'lingkar_perut'   => 'float',
        'hemoglobin'      => 'float',
        'asam_urat'       => 'float',
        'kolesterol'      => 'integer',
        // gula_darah dan kemandirian sengaja tidak di-cast karena varchar/enum
    ];

    // =========================================================
    // RELASI DATABASE
    // =========================================================

    public function balita()
    {
        return $this->belongsTo(Balita::class, 'pasien_id');
    }

    public function remaja()
    {
        return $this->belongsTo(Remaja::class, 'pasien_id');
    }

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'pasien_id');
    }

    // Tambahan: Relasi ke tabel Ibu Hamil
    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class, 'pasien_id');
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    /** Kader/Bidan yang menginput data */
    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'pemeriksa_id');
    }

    /** Bidan yang memverifikasi */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =========================================================
    // ACCESSORS (Logika Cerdas Pengolah Data Otomatis)
    // =========================================================

    /** Nama pasien otomatis mendeteksi dari berbagai tabel (Termasuk Bayi & Bumil) */
    public function getNamaPasienAttribute(): string
    {
        return match($this->kategori_pasien) {
            'bayi', 'balita' => $this->balita?->nama_lengkap ?? 'Anak/Balita Tidak Ditemukan',
            'remaja'         => $this->remaja?->nama_lengkap ?? 'Remaja Tidak Ditemukan',
            'lansia'         => $this->lansia?->nama_lengkap ?? 'Lansia Tidak Ditemukan',
            'ibu_hamil'      => $this->ibuHamil?->nama_lengkap ?? 'Ibu Hamil Tidak Ditemukan',
            default          => 'Pasien Tidak Ditemukan',
        };
    }

    /** * Mengambil IMT dari Database. 
     * Tapi jika di Database terlanjur kosong (0), sistem akan menghitungnya secara instan (Fallback)
     */
    public function getImtAttribute(): float
    {
        if (!empty($this->attributes['imt'])) {
            return (float) $this->attributes['imt'];
        }

        // Rumus Fallback IMT: Berat (kg) / (Tinggi (m) x Tinggi (m))
        if ($this->berat_badan > 0 && $this->tinggi_badan > 0) {
            $tb_m = $this->tinggi_badan / 100; // Konversi cm ke meter
            return round($this->berat_badan / ($tb_m * $tb_m), 2);
        }
        
        return 0;
    }

    /** Label teks status verifikasi */
    public function getStatusVerifikasiLabelAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default    => 'Menunggu Validasi',
        };
    }

    /** Warna badge Bootstrap untuk status verifikasi */
    public function getStatusVerifikasiBadgeAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'success',
            'rejected' => 'danger',
            default    => 'warning',
        };
    }

    /** True jika data masih menunggu validasi bidan */
    public function getFromKaderAttribute(): bool
    {
        return ($this->status_verifikasi ?? 'pending') === 'pending';
    }

    // =========================================================
    // SCOPES (Pencarian Cepat)
    // =========================================================

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_verifikasi', 'rejected');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_periksa', now()->month)
                     ->whereYear('tanggal_periksa', now()->year);
    }
}