<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    protected $table = 'absensi_detail';

    // KEMBALIKAN pasien_type KE SINI
    protected $fillable = [
        'absensi_id',
        'pasien_id',
        'pasien_type', 
        'hadir',
        'keterangan',
    ];

    protected $casts = [
        'hadir' => 'boolean',
    ];

    public function absensi()
    {
        return $this->belongsTo(AbsensiPosyandu::class, 'absensi_id');
    }

    public function getPasienDataAttribute()
    {
        // Tetap gunakan pembacaan cerdas
        $kategori = $this->pasien_type ?? ($this->absensi->kategori ?? null);

        return match($kategori) {
            'bayi', 'balita' => \App\Models\Balita::find($this->pasien_id),
            'remaja'         => \App\Models\Remaja::find($this->pasien_id),
            'ibu_hamil'      => \App\Models\IbuHamil::find($this->pasien_id),
            'lansia'         => \App\Models\Lansia::find($this->pasien_id),
            default          => null,
        };
    }
}