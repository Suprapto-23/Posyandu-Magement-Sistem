<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    use HasFactory;

    // Arahkan ke tabel yang baru saja kita buat via SQL
    protected $table = 'konselings';

    protected $guarded = [];

    // Relasi ke Warga (User)
    public function user() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    // Relasi ke Bidan (User)
    public function bidan() 
    { 
        return $this->belongsTo(User::class, 'bidan_id'); 
    }
}