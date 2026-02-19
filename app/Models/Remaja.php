<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remaja extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_remaja',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'sekolah',
        'kelas',
        'nama_ortu',
        'telepon_ortu',
        'alamat',
        'created_by'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

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
     * Relasi ke Semua Pemeriksaan
     */
    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'remaja');
    }

    /**
     * Relasi ke Pemeriksaan Terakhir
     */
    public function pemeriksaan_terakhir()
    {
        return $this->hasOne(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'remaja')
            ->latest('tanggal_periksa');
    }

    /**
     * Helper Umur Bulat
     */
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }
}