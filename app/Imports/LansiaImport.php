<?php

namespace App\Imports;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class LansiaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama_lengkap'])) return null;

        try {
            $tglLahir = Carbon::parse($row['tanggal_lahir_yyyy_mm_dd'])->format('Y-m-d');
        } catch (\Exception $e) {
            $tglLahir = now()->format('Y-m-d');
        }

        if (!empty($row['nik']) && Lansia::where('nik', $row['nik'])->exists()) {
            return null;
        }

        $linkedUser = $this->findLinkedUser($row['nik'] ?? '', $row['nama_lengkap']);
        $kode = 'LNS-' . date('ym') . rand(1000, 9999);

        return new Lansia([
            'user_id'         => $linkedUser ? $linkedUser->id : null,
            'kode_lansia'     => $kode,
            'nik'             => $row['nik'] ?? null,
            'nama_lengkap'    => $row['nama_lengkap'],
            'jenis_kelamin'   => strtoupper($row['jenis_kelamin'] ?? 'L'),
            'tempat_lahir'    => $row['tempat_lahir'] ?? '-',
            'tanggal_lahir'   => $tglLahir,
            'penyakit_bawaan' => $row['riwayat_penyakit'] ?? null,
            'alamat'          => $row['alamat_lengkap'] ?? '-',
            'created_by'      => auth()->id(),
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
                if (!empty($cleanName) && stripos($p->full_name ?? '', $cleanName) !== false) return User::find($p->user_id);
            }
        }
        return null;
    }
}