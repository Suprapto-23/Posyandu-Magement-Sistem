<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingController extends Controller
{
    // Key yang digunakan sesuai kebutuhan laporan Posyandu
    private array $settingKeys = [
        'posyandu_name', 'posyandu_alamat', 'posyandu_telepon',
        'posyandu_email', 'posyandu_kelurahan'
    ];

    public function index()
    {
        // Mengambil data dari tabel settings sesuai database kamu
        $settings = DB::table('settings')
            ->whereIn('key', $this->settingKeys)
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'posyandu_name' => 'required|string|max:191',
            'posyandu_email' => 'nullable|email',
            'posyandu_telepon' => 'nullable|string',
        ]);

        // Simpan setiap key ke database (Update jika ada, Insert jika belum ada)
        foreach ($this->settingKeys as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $request->input($key, ''),
                    'group' => 'posyandu', // Mengelompokkan setelan posyandu
                    'updated_at' => now()
                ]
            );
        }

        return back()->with('success', 'Konfigurasi Posyandu berhasil diperbarui. Data ini akan otomatis muncul pada Kop Surat Laporan Kader dan Bidan.');
    }

    public function changePassword(Request $request)
    {
        // Validasi dengan pesan kustom bahasa Indonesia
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek kecocokan password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah!']);
        }

        // Update password baru
        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Password berhasil diperbarui. Keamanan akun Anda sekarang terjaga.');
    }
}