<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiPosyandu extends Model
{
    protected $table = 'absensi_posyandu';

    protected $fillable = [
        'kode_absensi',
        'kategori',
        'tanggal_posyandu',
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

    // Kader yang mencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // Semua detail kehadiran
    public function details()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_id');
    }

    // Hanya yang hadir
    public function hadir()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_id')->where('hadir', true);
    }

    // Helper: label bulan Indonesia
    public function getBulanLabelAttribute(): string
    {
        $bulan = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
        ];
        return $bulan[$this->bulan] ?? '-';
    }

    // Helper: label kategori
    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'bayi'   => 'Bayi (0–11 bln)',
            'balita' => 'Balita (12–59 bln)',
            'remaja' => 'Remaja',
            'lansia' => 'Lansia',
            default  => $this->kategori,
        };
    }
}