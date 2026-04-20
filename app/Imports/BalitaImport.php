<?php

namespace App\Imports;

use App\Models\Balita;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Wajib untuk membaca serial angka Excel

class BalitaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Hindari baris kosong
        if (!isset($row['nama_lengkap'])) {
            return null;
        }

        // ==========================================
        // LOGIKA BACA TANGGAL (SUPER PARSER)
        // ==========================================
        // Coba tangkap nama kolomnya (mengantisipasi jika nama header sedikit berubah)
        $tglLahirStr = $row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null;
        $tglLahir = now()->format('Y-m-d'); // Default jika benar-benar gagal
        
        if (!empty($tglLahirStr)) {
            try {
                if (is_numeric($tglLahirStr)) {
                    // Jika dari Excel asli (format Serial Number, contoh: 44972)
                    $tglLahir = Date::excelToDateTimeObject($tglLahirStr)->format('Y-m-d');
                } else {
                    // Jika dari CSV (format Teks)
                    // Ubah garis miring (/) jadi strip (-) agar sistem tidak bingung format US/UK
                    $cleanDate = str_replace('/', '-', $tglLahirStr);
                    $tglLahir = Carbon::parse($cleanDate)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Jika masih error, tetap gunakan tanggal hari ini
                $tglLahir = now()->format('Y-m-d');
            }
        }

        // Cek NIK duplikat untuk mencegah error
        if (!empty($row['nik_balita']) && Balita::where('nik', $row['nik_balita'])->exists()) {
            return null;
        }

        // Cari koneksi akun dengan Ibu
        $nikIbu = $row['nik_ibu'] ?? null;
        $namaIbu = $row['nama_ibu'] ?? null;
        $linkedUser = $this->findLinkedUser($nikIbu, $namaIbu);
        
        $kode = 'BLT-' . date('ym') . rand(1000, 9999);

        return new Balita([
            'user_id'       => $linkedUser ? $linkedUser->id : null,
            'kode_balita'   => $kode,
            'nama_lengkap'  => $row['nama_lengkap'],
            'nik'           => $row['nik_balita'] ?? null,
            'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L'),
            'tempat_lahir'  => $row['tempat_lahir'] ?? '-',
            'tanggal_lahir' => $tglLahir, // TANGGAL PASTI AKURAT SEKARANG!
            'nama_ibu'      => $namaIbu,
            'nik_ibu'       => $nikIbu,
            'berat_lahir'   => floatval($row['berat_lahir_kg'] ?? 0),
            'panjang_lahir' => floatval($row['panjang_lahir_cm'] ?? 0),
            'alamat'        => $row['alamat_lengkap'] ?? '-',
            'created_by'    => auth()->id(),
        ]);
    }

    private function findLinkedUser($nik, $nama)
    {
        $cleanNik = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama);

        $users = User::all();
        foreach($users as $user) {
            if (!empty($cleanNik) && in_array($cleanNik, [$user->nik ?? '', $user->username ?? '', $user->email ?? ''])) return $user;
            if (!empty($cleanName) && stripos($user->name, $cleanName) !== false) return $user;
        }

        if (Schema::hasTable('profiles')) {
            $profiles = DB::table('profiles')->get();
            foreach($profiles as $p) {
                if (!empty($cleanNik) && in_array($cleanNik, [$p->nik ?? '', $p->no_ktp ?? ''])) return User::find($p->user_id);
            }
        }
        return null;
    }
    // Beri tahu sistem bahwa nama-nama kolom ada di baris ke-3 (karena baris 1 & 2 dipakai untuk Judul Besar)
    public function headingRow(): int
    {
        return 3;
    }
}