<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPosyandu extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (biasanya laravel mencari jadwal_posyandus)
    // Jika nama tabel di database Anda 'jadwal_posyandu', gunakan ini:
    protected $table = 'jadwal_posyandu';

    // IZINKAN KOLOM INI DIISI (Solusi Masalah Anda)
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kategori',
        'target_peserta',
        'status',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',        // Ubah tanggal jadi objek Date
        'waktu_mulai' => 'datetime',  // Ubah waktu jadi objek Datetime
        'waktu_selesai' => 'datetime' // Ubah waktu jadi objek Datetime
    ];
}