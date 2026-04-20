<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    use HasFactory;

    protected $table = 'konselings';

    protected $fillable = [
        'pasien_type', // balita, ibu_hamil, remaja, lansia
        'pasien_id',
        'tanggal',
        'waktu',
        'keluhan',
        'tindakan',
        'bidan_id'
    ];

    // Accessor dinamis untuk menarik relasi pasien (Polymorphic manual yang aman)
    public function getPasienAttribute()
    {
        return match($this->pasien_type) {
            'balita'    => Balita::find($this->pasien_id),
            'ibu_hamil' => IbuHamil::find($this->pasien_id),
            'remaja'    => Remaja::find($this->pasien_id),
            'lansia'    => Lansia::find($this->pasien_id),
            default     => null,
        };
    }
}