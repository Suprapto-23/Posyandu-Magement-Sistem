<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    // 1. Menentukan Struktur Baris Atas (Tetap dipertahankan agar sistem Import tidak error)
    public function headings(): array
    {
        // Baris 1: Judul Dokumen (Sangat Formal)
        $title = ['FORMULIR REGISTRASI MASSAL - SISTEM INFORMASI POSYANDU'];
        
        // Baris 2: Sub Judul / Instruksi (Sangat Rapi)
        $subtitle = ['Kategori Dokumen: ' . strtoupper(str_replace('_', ' ', $this->type)) . '  |  Petunjuk: Isilah data mulai baris ke-4 ke bawah. Format tanggal lahir wajib YYYY-MM-DD.'];

        // Baris 3: Header Tabel Asli (Sesuai dengan nama kolom di database)
        $headers = match($this->type) {
            'balita'    => ['no', 'nama_lengkap', 'nik_balita', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'nama_ibu', 'nik_ibu', 'berat_lahir_kg', 'panjang_lahir_cm', 'alamat_lengkap'],
            'ibu_hamil' => ['no', 'nama_lengkap', 'nik', 'nama_suami', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'telepon_ortu', 'alamat_lengkap'],
            'remaja'    => ['no', 'nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'nama_sekolah', 'kelas', 'nama_ortu', 'no_hp_ortu', 'alamat_lengkap'],
            'lansia'    => ['no', 'nama_lengkap', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir_yyyy_mm_dd', 'riwayat_penyakit', 'status_keluarga', 'no_hp', 'alamat_lengkap'],
            default     => []
        };

        return [
            $title,
            $subtitle,
            $headers
        ];
    }

    // 2. Kosongkan Data (Tinggal diisi Kader)
    public function array(): array
    {
        return [];
    }

    // 3. EVENT LISTENER: Fitur Advanced Excel (Freeze Panes)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Freeze baris 1 sampai 3, sehingga saat di-scroll ke bawah, judul tabel tetap terlihat!
                $event->sheet->getDelegate()->freezePane('A4');
            },
        ];
    }

    // 4. MENGGAMBAR DESAIN PREMIUM (FORMAL & KORPORAT)
    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $lastRow = 103; // Kita sediakan 100 baris kosong bergaris untuk diisi

        // MENGGABUNGKAN SEL (MERGE) UNTUK JUDUL AGAR RAPI KE TENGAH
        $sheet->mergeCells('A1:' . $highestColumn . '1');
        $sheet->mergeCells('A2:' . $highestColumn . '2');

        // STYLE BARIS 1: Judul Utama (Elegan, Font Besar, Background Putih Bersih)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '0F172A']], // Dark Navy
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFFFFF']]
        ]);

        // STYLE BARIS 2: Instruksi (Warna Abu-abu Netral, Garis Bawah Tegas)
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '475569']], // Gray
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F8FAFC']],
            'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1E293B']], // Garis tebal pemisah
            ],
        ]);

        // STYLE BARIS 3: Header Kolom Tabel (Warna Navy Blue Gelap, Teks Putih)
        $sheet->getStyle('A3:' . $highestColumn . '3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E293B']], // Sangat Formal (Dark Blue/Slate)
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // STYLE BARIS 3 s/d 103: Memberikan Border (Garis Kotak-Kotak Tipis)
        $sheet->getStyle('A3:' . $highestColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'], // Abu-abu lembut
                ],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // MENGATUR TINGGI BARIS (Biar tidak sesak saat diketik)
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(25);
        for ($i = 4; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        // KECILKAN KOLOM 'NO' (Kolom A) SECARA SPESIFIK
        $sheet->getColumnDimension('A')->setAutoSize(false);
        $sheet->getColumnDimension('A')->setWidth(5);

        return [];
    }
}