<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonselingRemaja extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit agar tidak error lagi
    protected $table = 'konseling_remajas';

    protected $fillable = [
        'remaja_id',
        'petugas_id',
        'tanggal_konseling',
        'topik',
        'keluhan',
        'saran'
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
    ];

    // Relasi ke Remaja
    public function remaja()
    {
        return $this->belongsTo(Remaja::class, 'remaja_id');
    }

    // Relasi ke Petugas (Bidan/Kader)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}