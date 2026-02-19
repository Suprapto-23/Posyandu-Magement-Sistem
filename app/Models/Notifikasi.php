<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BARIS INI ---
    protected $table = 'notifikasi_user'; 
    // ---------------------------

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'link',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk notifikasi belum dibaca
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }
}