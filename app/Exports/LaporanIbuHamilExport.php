<?php

namespace App\Exports\Laporan; // <-- PERHATIKAN NAMESPACE INI

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\IbuHamil;

class LaporanIbuHamilExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun) {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection() {
        return IbuHamil::with(['kunjungans' => function($q) {
            $q->whereMonth('tanggal_kunjungan', $this->bulan)
              ->whereYear('tanggal_kunjungan', $this->tahun)
              ->latest('tanggal_kunjungan');
        }, 'kunjungans.pemeriksaan'])->get();
    }

    public function headings(): array {
        return [
            'NIK', 'Nama Ibu Hamil', 'Nama Suami', 'HPHT', 'HPL', 
            'BB Bulan Ini', 'TB', 'LILA', 'Tensi', 'Status Risiko', 'Tgl Periksa'
        ];
    }

    public function map($bumil): array {
        $kunjungan = $bumil->kunjungans->first();
        $pemeriksaan = $kunjungan ? $kunjungan->pemeriksaan : null;
        
        return [
            $bumil->nik ?? '-',
            $bumil->nama_lengkap,
            $bumil->nama_suami ?? '-',
            $bumil->hpht ? $bumil->hpht->format('d/m/Y') : '-',
            $bumil->hpl ? $bumil->hpl->format('d/m/Y') : '-',
            $pemeriksaan ? $pemeriksaan->berat_badan : '-',
            $pemeriksaan ? $pemeriksaan->tinggi_badan : '-',
            $pemeriksaan ? $pemeriksaan->lingkar_lengan : '-',
            $pemeriksaan ? $pemeriksaan->tekanan_darah : '-',
            $pemeriksaan ? ($pemeriksaan->is_kek ? 'Risiko KEK' : 'Normal') : '-',
            $kunjungan ? $kunjungan->tanggal_kunjungan->format('d/m/Y') : 'Belum Hadir',
        ];
    }
}