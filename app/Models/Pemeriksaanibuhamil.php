<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemeriksaanIbuHamil extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_ibu_hamil';

    protected $fillable = [
        'ibu_hamil_id',
        'bidan_id',
        'tanggal_periksa',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'kenaikan_bb',
        'tekanan_darah',
        'denyut_nadi',
        'suhu_tubuh',
        'detak_jantung_janin',
        'tinggi_fundus_uteri',
        'presentasi_janin',
        'usia_kehamilan_minggu',
        'hemoglobin',
        'protein_urin',
        'gula_darah',
        'keluhan',
        'diagnosa',
        'tindakan',
        'resep_obat',
        'jadwal_kunjungan_berikutnya',
        'catatan_bidan',
        'status_kehamilan',  // normal | berisiko | darurat
    ];

    protected $casts = [
        'tanggal_periksa'              => 'date',
        'jadwal_kunjungan_berikutnya'  => 'date',
        'berat_badan'                  => 'float',
        'tinggi_badan'                 => 'float',
        'imt'                          => 'float',
        'kenaikan_bb'                  => 'float',
        'hemoglobin'                   => 'float',
        'denyut_nadi'                  => 'integer',
        'detak_jantung_janin'          => 'integer',
    ];

    // ── Relasi ────────────────────────────────────────────────────

    public function ibuHamil()
    {
        return $this->belongsTo(IbuHamil::class, 'ibu_hamil_id');
    }

    public function bidan()
    {
        return $this->belongsTo(User::class, 'bidan_id');
    }

    // ── Accessor: Label status kehamilan ─────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_kehamilan) {
            'berisiko' => 'Berisiko Tinggi',
            'darurat'  => 'Darurat / Perlu Rujuk',
            default    => 'Normal',
        };
    }

    // ── Accessor: Warna badge status ─────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return match($this->status_kehamilan) {
            'berisiko' => 'amber',
            'darurat'  => 'rose',
            default    => 'emerald',
        };
    }
}