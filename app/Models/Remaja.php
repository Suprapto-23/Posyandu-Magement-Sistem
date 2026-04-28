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
        'golongan_darah', // FIX BUG 1: Tambahkan ini agar bisa disimpan!
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
     * FIX BUG 2: Relasi ke Tabel Kunjungan (Sistem Terpadu, BUKAN morphMany)
     */
    public function kunjungans()
    {
        return $this->morphMany(\App\Models\Kunjungan::class, 'pasien')
                    ->orderBy('tanggal_kunjungan', 'desc');
    }

    /**
     * Relasi ke Semua Pemeriksaan
     */
    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'pasien_id')
            ->where('kategori_pasien', 'remaja');
    }

    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }
}