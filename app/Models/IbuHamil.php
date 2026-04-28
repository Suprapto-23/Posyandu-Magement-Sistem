<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IbuHamil extends Model
{
    use HasFactory;

    protected $table = 'ibu_hamils';

    protected $fillable = [
        'user_id',
        'kode_hamil',
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_suami',
        'alamat',
        'telepon_ortu',
        'hpht',
        'hpl',
        'golongan_darah',
        'riwayat_penyakit',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'status',     // aktif | selesai
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'hpht'          => 'date',
        'hpl'           => 'date',
        'berat_badan'   => 'float',
        'tinggi_badan'  => 'float',
        'imt'           => 'float',
    ];

   // ── Relasi ──────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** * MENGGUNAKAN SISTEM TERPADU: Relasi ke tabel Kunjungan
     * (Nantinya Kunjungan yang akan membawa data Pemeriksaan) 
     */
    public function kunjungans()
    {
        // Tambahkan 'pasien_id' sebagai foreign key yang benar
        return $this->hasMany(Kunjungan::class, 'pasien_id')
                    ->orderBy('tanggal_kunjungan', 'desc');
    }

    /** Pemeriksaan terakhir */
    public function pemeriksaan_terakhir()
    {
        return $this->hasOne(PemeriksaanIbuHamil::class, 'ibu_hamil_id')
                    ->latest('tanggal_periksa');
    }

    // ── Accessor: Usia kehamilan dalam minggu ────────────────────

    public function getUsiaKehamilanAttribute(): ?int
    {
        if (!$this->hpht) return null;
        return (int) now()->diffInWeeks($this->hpht);
    }

    // ── Accessor: Nomor trimester (1/2/3) ────────────────────────

    public function getTrimesterAngkaAttribute(): ?int
    {
        $minggu = $this->usia_kehamilan;
        if ($minggu === null) return null;
        if ($minggu <= 12) return 1;
        if ($minggu <= 27) return 2;
        return 3;
    }

    // ── Accessor: Label trimester ─────────────────────────────────

    public function getTrimesterAttribute(): string
    {
        return match($this->trimester_angka) {
            1       => 'Trimester I',
            2       => 'Trimester II',
            3       => 'Trimester III',
            default => 'Belum Diisi',
        };
    }

    // ── Accessor: Sisa hari menuju HPL ───────────────────────────

    public function getSisaHariAttribute(): ?int
    {
        if (!$this->hpl) return null;
        return (int) now()->diffInDays($this->hpl, false);
    }

    // ── Accessor: IMT dihitung otomatis ──────────────────────────

    public function getImtHitungAttribute(): ?float
    {
        if (!$this->berat_badan || !$this->tinggi_badan || $this->tinggi_badan < 50) return null;
        $tinggiM = $this->tinggi_badan / 100;
        return round($this->berat_badan / ($tinggiM * $tinggiM), 2);
    }

    // ── Scope: Hanya yang masih aktif (hamil) ────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // ── Scope: Hampir melahirkan (HPL dalam 30 hari) ─────────────

    public function scopeHampirLahir($query, int $hari = 30)
    {
        return $query->whereNotNull('hpl')
                     ->whereDate('hpl', '>=', now())
                     ->whereDate('hpl', '<=', now()->addDays($hari));
    }
}