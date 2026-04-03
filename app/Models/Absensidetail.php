<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    protected $table = 'absensi_detail';

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

    // Resolve pasien dinamis berdasarkan pasien_type
    public function getPasienAttribute()
    {
        return match($this->pasien_type) {
            'bayi'   => \App\Models\Balita::find($this->pasien_id),
            'balita' => \App\Models\Balita::find($this->pasien_id),
            'remaja' => \App\Models\Remaja::find($this->pasien_id),
            'lansia' => \App\Models\Lansia::find($this->pasien_id),
            default  => null,
        };
    }
}