<?php

namespace App\Imports;

use App\Models\Remaja;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date; // 👈 Wajib untuk membaca serial angka Excel

class RemajaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama_lengkap'])) return null;

        // ==========================================
        // LOGIKA BACA TANGGAL (SUPER PARSER)
        // ==========================================
        $tglLahirStr = $row['tanggal_lahir_yyyy_mm_dd'] ?? $row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? null;
        $tglLahir = now()->format('Y-m-d'); 
        
        if (!empty($tglLahirStr)) {
            try {
                if (is_numeric($tglLahirStr)) {
                    $tglLahir = Date::excelToDateTimeObject($tglLahirStr)->format('Y-m-d');
                } else {
                    $cleanDate = str_replace('/', '-', $tglLahirStr);
                    $tglLahir = Carbon::parse($cleanDate)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $tglLahir = now()->format('Y-m-d');
            }
        }

        // Cek NIK duplikat
        if (!empty($row['nik']) && Remaja::where('nik', $row['nik'])->exists()) {
            return null;
        }

        $linkedUser = $this->findLinkedUser($row['nik'] ?? '', $row['nama_lengkap']);
        $kode = 'RMJ-' . date('ym') . rand(1000, 9999);

        return new Remaja([
            'user_id'       => $linkedUser ? $linkedUser->id : null,
            'kode_remaja'   => $kode,
            'nik'           => $row['nik'] ?? null,
            'nama_lengkap'  => $row['nama_lengkap'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L'),
            'tempat_lahir'  => $row['tempat_lahir'] ?? '-',
            'tanggal_lahir' => $tglLahir, // 👈 TANGGAL PASTI AKURAT!
            'sekolah'       => $row['nama_sekolah'] ?? null,
            'kelas'         => $row['kelas'] ?? null,
            'nama_ortu'     => $row['nama_ortu'] ?? null,
            'telepon_ortu'  => $row['no_hp_ortu'] ?? null,
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
}