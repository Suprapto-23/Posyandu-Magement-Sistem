<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaans';

    // Menggunakan guarded ['id'] agar otomatis menerima kolom baru 
    // (seperti indikasi_stunting, tfu, djj, posisi_janin) tanpa perlu update fillable manual.
    protected $guarded = ['id'];

    // Memastikan tipe data (casting) aman saat ditarik ke View atau JSON
    protected $casts = [
        'tanggal_periksa' => 'date',
        'verified_at'     => 'datetime',
        'berat_badan'     => 'float',
        'tinggi_badan'    => 'float',
        'imt'             => 'float',
        'suhu_tubuh'      => 'float',
        'lingkar_kepala'  => 'float',
        'lingkar_lengan'  => 'float',
        'lingkar_perut'   => 'float',
        'hemoglobin'      => 'float',
        'asam_urat'       => 'float',
        'kolesterol'      => 'integer',
        // Kolom teks seperti tfu, djj, kemandirian, indikasi_stunting otomatis dibaca sebagai string
    ];

    // =========================================================
    // RELASI DATABASE (UNIFIED / POLYMORPHIC APPROACH)
    // =========================================================

    /** Relasi ke Pasien: Balita */
    public function balita()
    {
        return $this->belongsTo(Balita::class, 'pasien_id');
    }

    /** Relasi ke Pasien: Remaja */
    public function remaja()
    {
        return $this->belongsTo(Remaja::class, 'pasien_id');
    }

    /** Relasi ke Pasien: Lansia */
    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'pasien_id');
    }

    /** * [BARU] Relasi ke Pasien: Ibu Hamil 
     * Menggantikan fungsi tabel/model terpisah
     */
    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class, 'pasien_id');
    }

    /** Relasi ke Petugas: Kader (Meja 1-4) */
    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'pemeriksa_id');
    }

    /** Relasi ke Petugas: Bidan (Meja 5 / Validasi) */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =========================================================
    // ACCESSORS (Membantu Format Tampilan di Blade)
    // =========================================================

    /** Label bahasa manusia untuk status verifikasi */
    public function getStatusVerifikasiLabelAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'Terverifikasi (Selesai)',
            'ditolak'  => 'Ditolak (Kembali ke Kader)',
            default    => 'Menunggu Validasi',
        };
    }

    /** Warna badge Tailwind/Bootstrap untuk status verifikasi */
    public function getStatusVerifikasiBadgeAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'emerald', // Hijau
            'ditolak'  => 'rose',    // Merah
            default    => 'amber',   // Kuning/Oranye
        };
    }

    /** Mengecek apakah ini data mentah dari Kader */
    public function getFromKaderAttribute(): bool
    {
        return ($this->status_verifikasi ?? 'pending') === 'pending';
    }

    // =========================================================
    // SCOPES (Mempercepat Penulisan Query di Controller)
    // =========================================================

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', 'ditolak');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_periksa', now()->month)
                     ->whereYear('tanggal_periksa', now()->year);
    }
}