<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * =========================================================================
 * PEMERIKSAAN MODEL (ULTIMATE EDITION)
 * =========================================================================
 * Jantung dari sistem Rekam Medis (EMR) Posyandu.
 * Menghubungkan log kunjungan, petugas, bidan, dan data antropometri fisik.
 */
class Pemeriksaan extends Model
{
    use HasFactory;

    // 1. DEFINISI TABEL SECARA EKSPLISIT
    protected $table = 'pemeriksaans';

    // 2. PROTEKSI MASS-ASSIGNMENT
    // Hanya memproteksi 'id' agar Eloquent bisa menerima field dinamis 
    // tanpa harus menulis fillable satu per satu. Sangat aman.
    protected $guarded = ['id'];

    // 3. AUTO-APPEND VIRTUAL COLUMNS
    // WAJIB ADA: Mencegah error saat me-render UI AJAX / JSON / Cetak Laporan
    // Atribut ini akan otomatis menempel setiap kali model dipanggil.
    protected $appends = [
        'nama_pasien',
        'nik_pasien',
        'status_verifikasi_text',
        'status_verifikasi_badge'
    ];

    // 4. DATA CASTING (PRESISI MATEMATIS)
    // Mengubah tipe data secara instan saat ditarik dari Database
    // Mencegah error perhitungan grafik pertumbuhan di sisi Bidan
    protected $casts = [
        'tanggal_periksa' => 'date',
        'verified_at'     => 'datetime',
        'berat_badan'     => 'float',
        'tinggi_badan'    => 'float',
        'suhu_tubuh'      => 'float',
        'lingkar_kepala'  => 'float',
        'lingkar_lengan'  => 'float',
        'lingkar_perut'   => 'float', // Opsional untuk lansia/dewasa
        'imt'             => 'float', // Indeks Massa Tubuh
        'gula_darah'      => 'float',
        'kolesterol'      => 'integer',
        'asam_urat'       => 'float',
        'usia_kehamilan'  => 'integer',
        // Catatan: Tensi dan Hemoglobin tidak di-cast float karena sering 
        // mengandung karakter (misal: "120/80" atau "12.5 g/dL")
    ];

    /**
     * =================================================================
     * 5. RELASI DATABASE (RELATIONSHIPS)
     * =================================================================
     */

    // Menghubungkan pemeriksaan dengan tiket kedatangan (Kunjungan) pasien
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    // Mengetahui siapa Kader yang mencatat data ini di lapangan
    public function petugas()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Mengetahui siapa Bidan/Nakes yang memverifikasi data ini
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * =================================================================
     * 6. ACCESSORS / VIRTUAL COLUMNS (UNTUK KEAMANAN TAMPILAN UI)
     * =================================================================
     */

    /** * SAFE PULL: Nama Pasien
     * Jika data Pasien terhapus (Orphan Data), sistem TIDAK AKAN CRASH.
     */
    public function getNamaPasienAttribute(): string
    {
        if ($this->kunjungan && $this->kunjungan->pasien) {
            return $this->kunjungan->pasien->nama_lengkap;
        }
        return 'Warga Tidak Diketahui (Data Terhapus)';
    }

    /** * SAFE PULL: NIK Pasien
     */
    public function getNikPasienAttribute(): string
    {
        if ($this->kunjungan && $this->kunjungan->pasien) {
            return $this->kunjungan->pasien->nik ?? '-';
        }
        return '0000000000000000';
    }

    /** * NORMALIZER: Label Status Verifikasi
     * Merangkum semua bentuk kata 'approved' menjadi bahasa Indonesia rapi
     */
    public function getStatusVerifikasiTextAttribute(): string
    {
        return match($this->status_verifikasi) {
            'tervalidasi', 'verified', 'approved' => 'Tervalidasi Bidan',
            'ditolak', 'rejected'                 => 'Revisi / Ditolak',
            default                               => 'Menunggu Validasi',
        };
    }

    /** * NORMALIZER: Warna Badge Tailwind
     */
    public function getStatusVerifikasiBadgeAttribute(): string
    {
        return match($this->status_verifikasi) {
            'tervalidasi', 'verified', 'approved' => 'emerald', // Hijau
            'ditolak', 'rejected'                 => 'rose',    // Merah
            default                               => 'amber',   // Kuning
        };
    }

    /**
     * =================================================================
     * 7. SCOPES (MACRO BUILDER UNTUK CONTROLLER)
     * Mempersingkat kode di Controller saat melakukan pencarian
     * =================================================================
     */

    // Ambil semua data yang masih menggantung / butuh validasi bidan
    public function scopePending($query)
    {
        return $query->where(function($q) {
            $q->where('status_verifikasi', 'pending')
              ->orWhereNull('status_verifikasi'); // Failsafe jika data lama bernilai NULL
        });
    }

    // Ambil data yang sudah sah secara medis
    public function scopeVerified($query)
    {
        return $query->whereIn('status_verifikasi', ['tervalidasi', 'verified', 'approved']);
    }

    // Filter berdasarkan Kategori Warga (Balita, Lansia, dll)
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori_pasien', $kategori);
    }

    // Filter cepat untuk melihat laporan kinerja posyandu bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_periksa', Carbon::now()->month)
                     ->whereYear('tanggal_periksa', Carbon::now()->year);
    }
}