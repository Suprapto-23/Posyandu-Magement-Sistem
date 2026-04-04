<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPosyandu extends Model
{
    protected $table = 'absensi_posyandu';

    // HANYA masukkan kolom yang benar-benar ADA di database
    protected $fillable = [
        'kode_absensi',
        'kategori',
        'tanggal_posyandu',
        'nomor_pertemuan',
        'bulan',
        'tahun',
        'catatan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_posyandu' => 'date',
        'bulan'            => 'integer',
        'tahun'            => 'integer',
    ];

    // Relasi ke Kader yang mencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_id');
    }

    public function hadir()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_id')->where('hadir', true);
    }

    // =========================================================================
    // 🪄 MAGIC ACCESSORS (Menerjemahkan data otomatis tanpa simpan ke DB)
    // =========================================================================
    
    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'bayi'      => 'Bayi (0-11 Bulan)',
            'balita'    => 'Balita (12-59 Bulan)',
            'remaja'    => 'Remaja',
            'lansia'    => 'Lansia',
            'ibu_hamil' => 'Ibu Hamil',
            default     => 'Tidak Diketahui',
        };
    }

    public function getBulanLabelAttribute(): string
    {
        $bulanList = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];
        return $bulanList[$this->bulan] ?? '-';
    }
}