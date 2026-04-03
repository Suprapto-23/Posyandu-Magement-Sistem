<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lansia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_lansia',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'penyakit_bawaan',
        'berat_badan',      // ← TAMBAHAN
        'tinggi_badan',     // ← TAMBAHAN
        'imt',              // ← TAMBAHAN
        'kemandirian',      // ← TAMBAHAN
        'telepon_keluarga',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan'   => 'float',   // ← cast agar $lansia->berat_badan return float
        'tinggi_badan'  => 'float',
        'imt'           => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kunjungans()
    {
        return $this->morphMany(Kunjungan::class, 'pasien');
    }

    public function pemeriksaan_terakhir()
    {
        return $this->hasOne(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'lansia')
            ->latest('tanggal_periksa');
    }

    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}