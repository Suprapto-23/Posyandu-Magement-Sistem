<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * =========================================================================
 * PEMERIKSAAN MODEL (CORE MEDICAL RECORD)
 * =========================================================================
 * Model ini menyimpan data klinis hasil pemeriksaan fisik pasien posyandu.
 * Terhubung secara struktural ke tabel Kunjungan sebagai referensi induk.
 */
class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaans';

    /**
     * Menggunakan guarded ['id'] agar model fleksibel menerima kolom baru 
     * tanpa perlu mendaftarkan fillable satu per satu.
     */
    protected $guarded = ['id'];

    /**
     * Casting data agar tipe data di database (string/decimal) otomatis 
     * dikonversi menjadi tipe data PHP yang tepat (float/date) saat diakses.
     */
    protected $casts = [
        'tanggal_periksa' => 'date',
        'verified_at'     => 'datetime',
        'berat_badan'     => 'float',
        'tinggi_badan'    => 'float',
        'suhu_tubuh'      => 'float',
        'lingkar_kepala'  => 'float',
        'lingkar_lengan'  => 'float',
        'gula_darah'      => 'float',
        'kolesterol'      => 'integer',
        'asam_urat'       => 'float',
        'usia_kehamilan'  => 'integer',
    ];

    /**
     * =================================================================
     * RELASI DATABASE (RELATIONSHIPS)
     * =================================================================
     */

    /** * 1. Relasi ke Tabel Kunjungan (PENTING)
     * Menghubungkan pemeriksaan dengan tiket kedatangan pasien.
     */
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    /** * 2. Relasi ke Petugas (User)
     * Siapa Kader yang menginput data pemeriksaan ini pertama kali.
     */
    public function petugas()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** * 3. Relasi ke Verifikator (User)
     * Siapa Bidan yang memverifikasi data medis ini.
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * =================================================================
     * ACCESSORS (FORMATTING UNTUK UI)
     * =================================================================
     */

    /** Mendapatkan label teks status verifikasi yang manusiawi */
    public function getStatusVerifikasiTextAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'Terverifikasi (Selesai)',
            'ditolak'  => 'Ditolak (Kembali ke Kader)',
            default    => 'Menunggu Validasi',
        };
    }

    /** Mendapatkan warna badge Tailwind berdasarkan status */
    public function getStatusVerifikasiBadgeAttribute(): string
    {
        return match($this->status_verifikasi ?? 'pending') {
            'verified' => 'emerald', // Hijau
            'ditolak'  => 'rose',    // Merah
            default    => 'amber',   // Kuning/Oranye
        };
    }

    /**
     * =================================================================
     * SCOPES (UNTUK FILTERING CEPAT DI CONTROLLER)
     * =================================================================
     */

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_periksa', now()->month)
                     ->whereYear('tanggal_periksa', now()->year);
    }
}