<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_balita',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nik_ibu',
        'nama_ibu',
        'nik_ayah',
        'nama_ayah',
        'alamat',
        'berat_lahir',
        'panjang_lahir',
        'created_by'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Kunjungan
    public function kunjungans()
    {
        return $this->morphMany(Kunjungan::class, 'pasien');
    }

    // Relasi Pemeriksaan Terakhir
    public function pemeriksaan_terakhir()
    {
        return $this->hasOne(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'balita')
            ->latest('tanggal_periksa');
    }

    // Helper Umur
    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }
}