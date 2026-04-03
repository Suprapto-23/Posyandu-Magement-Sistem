<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IbuHamil extends Model
{
    protected $table = 'ibu_hamils';

    protected $fillable = [
        'user_id', 'kode_hamil', 'nama_lengkap', 'nik',
        'tempat_lahir', 'tanggal_lahir', 'nama_suami',
        'alamat', 'telepon_ortu', 'hpht', 'hpl',
        'golongan_darah', 'riwayat_penyakit',
        'berat_badan', 'tinggi_badan', 'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'hpht'          => 'date',
        'hpl'           => 'date',
    ];

    // Relasi ke user (warga)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kader yang mencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ===== ACCESSORS =====

    // Hitung usia kehamilan (minggu) dari HPHT
    public function getUsiaKehamilanAttribute(): ?int
    {
        if (!$this->hpht) return null;
        return (int) $this->hpht->diffInWeeks(now());
    }

    // Label trimester
    public function getTrimesterAttribute(): ?string
    {
        $minggu = $this->usia_kehamilan;
        if ($minggu === null) return null;
        if ($minggu <= 12) return 'Trimester I (1–12 minggu)';
        if ($minggu <= 27) return 'Trimester II (13–27 minggu)';
        return 'Trimester III (28+ minggu)';
    }

    // Angka trimester saja (1/2/3)
    public function getTrimesterAngkaAttribute(): ?int
    {
        $minggu = $this->usia_kehamilan;
        if ($minggu === null) return null;
        if ($minggu <= 12) return 1;
        if ($minggu <= 27) return 2;
        return 3;
    }

    // Sisa hari menuju HPL
    public function getSisaHariAttribute(): ?int
    {
        if (!$this->hpl) return null;
        $sisa = now()->diffInDays($this->hpl, false);
        return (int) $sisa;
    }

    // IMT
    public function getImtAttribute(): ?float
    {
        if (!$this->berat_badan || !$this->tinggi_badan) return null;
        $tm = $this->tinggi_badan / 100;
        return round($this->berat_badan / ($tm * $tm), 2);
    }

    // Label status (aktif / sudah lahir)
    public function getStatusKehamilanAttribute(): string
    {
        if (!$this->hpl) return 'Aktif';
        return now()->gt($this->hpl) ? 'Perkiraan Sudah Lahir' : 'Aktif';
    }
}