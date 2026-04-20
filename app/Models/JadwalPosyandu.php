<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPosyandu extends Model
{
    use HasFactory;

    protected $table = 'jadwal_posyandu';

    protected $fillable = [
        'judul', 'deskripsi', 'tanggal', 'waktu_mulai',
        'waktu_selesai', 'lokasi', 'kategori',
        'target_peserta', 'status', 'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        // waktu_mulai & waktu_selesai dibiarkan string (kolom TIME di MySQL)
        // parsing manual di blade/controller agar tidak error
    ];

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope: akan datang (hari ini ke depan)
    public function scopeMendatang($query)
    {
        return $query->whereDate('tanggal', '>=', Carbon::today('Asia/Jakarta'));
    }

    // Scope: sudah lewat
    public function scopeLewat($query)
    {
        return $query->whereDate('tanggal', '<', Carbon::today('Asia/Jakarta'));
    }

    // Accessor: apakah hari ini
    public function getIsTodayAttribute(): bool
    {
        return Carbon::parse($this->tanggal)->setTimezone('Asia/Jakarta')->isToday();
    }

    // Accessor: apakah sudah lewat
    public function getIsPastAttribute(): bool
    {
        return Carbon::parse($this->tanggal)->setTimezone('Asia/Jakarta')->isPast()
            && !Carbon::parse($this->tanggal)->setTimezone('Asia/Jakarta')->isToday();
    }

    // Warna strip berdasarkan target peserta
    public function getStripColorAttribute(): string
    {
        return match($this->target_peserta) {
            'balita'    => '#06b6d4',
            'remaja'    => '#6366f1',
            'lansia'    => '#f59e0b',
            'ibu_hamil' => '#ec4899',
            default     => '#10b981',
        };
    }

    // Label target peserta
    public function getLabelTargetAttribute(): string
    {
        return match($this->target_peserta) {
            'balita'    => 'Balita',
            'remaja'    => 'Remaja',
            'lansia'    => 'Lansia',
            'ibu_hamil' => 'Ibu Hamil',
            default     => 'Semua',
        };
    }
}