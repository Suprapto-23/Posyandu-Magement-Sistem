<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lansia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // Relasi ke Akun Login
        'kode_lansia',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'penyakit_bawaan',
        'telepon_keluarga',
        'created_by'        // Kader yang menginput
    ];

    // PENTING: Agar $lansia->tanggal_lahir bisa dipanggil ->format() atau ->age
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke Akun User (Login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Tabel Kunjungan (Polimorfik)
     */
    public function kunjungans()
    {
        return $this->morphMany(Kunjungan::class, 'pasien');
    }

    /**
     * Relasi ke Pemeriksaan Terakhir
     * Digunakan untuk menampilkan Tensi/Gula Darah di Dashboard/Index
     */
    public function pemeriksaan_terakhir()
    {
        return $this->hasOne(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'lansia')
            ->latest('tanggal_periksa');
    }

    /**
     * Helper Umur (Otomatis hitung umur bulat)
     * Panggil di view: {{ $lansia->usia }}
     */
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }
    
    /**
     * Relasi ke User yang menginput data (Kader/Admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}