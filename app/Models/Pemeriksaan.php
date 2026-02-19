<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaans';

    protected $guarded = ['id'];

    // Casts untuk memastikan tipe data yang keluar benar
    protected $casts = [
        'tanggal_periksa' => 'date',
        'berat_badan'     => 'float',
        'tinggi_badan'    => 'float',
        'suhu_tubuh'      => 'float',
        'lingkar_kepala'  => 'float',
        'lingkar_lengan'  => 'float',
        'asam_urat'       => 'float',
        'kolesterol'      => 'integer',
        'gula_darah'      => 'integer',
    ];

    /**
     * =========================================================
     * RELASI KE TABEL LAIN (WAJIB ADA UNTUK MENGHILANGKAN ERROR)
     * =========================================================
     */

    // 1. Relasi ke Data Balita
    public function balita()
    {
        return $this->belongsTo(Balita::class, 'pasien_id');
    }

    // 2. Relasi ke Data Remaja
    public function remaja()
    {
        return $this->belongsTo(Remaja::class, 'pasien_id');
    }

    // 3. Relasi ke Data Lansia
    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'pasien_id');
    }

    // Relasi ke Kunjungan (Parent)
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    // Relasi ke Petugas (User/Bidan/Kader)
    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'pemeriksa_id');
    }

    /**
     * =========================================================
     * ACCESSOR / HELPER TAMBAHAN
     * =========================================================
     */

    // Helper untuk mengambil Nama Pasien secara otomatis apapun kategorinya
    public function getNamaPasienAttribute()
    {
        if ($this->kategori_pasien === 'balita' && $this->balita) {
            return $this->balita->nama_lengkap;
        }
        if ($this->kategori_pasien === 'remaja' && $this->remaja) {
            return $this->remaja->nama_lengkap;
        }
        if ($this->kategori_pasien === 'lansia' && $this->lansia) {
            return $this->lansia->nama_lengkap;
        }
        return 'Pasien Tidak Ditemukan';
    }

    // Helper untuk menghitung IMT otomatis (jika kolom imt kosong di DB)
    public function getImtAttribute()
    {
        // Jika sudah ada nilai di database, gunakan itu
        if (!empty($this->attributes['imt'])) {
            return $this->attributes['imt'];
        }

        // Jika tidak, hitung manual: BB / (TB * TB) dalam meter
        if ($this->berat_badan > 0 && $this->tinggi_badan > 0) {
            $tb_meter = $this->tinggi_badan / 100;
            return round($this->berat_badan / ($tb_meter * $tb_meter), 1);
        }

        return 0;
    }
}