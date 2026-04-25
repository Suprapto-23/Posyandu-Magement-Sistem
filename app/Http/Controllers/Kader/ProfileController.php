<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Load relasi profile agar query tidak dilakukan berulang kali di view
        $user->load('profile'); 
        return view('kader.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email'         => 'required|email|unique:users,email,'.$user->id,
            'name'          => 'required|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'nik'           => 'nullable|string|max:16',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string',
        ]);

        // 🔥 WAJIB GUNAKAN TRANSACTION JIKA UPDATE > 1 TABEL
        DB::beginTransaction();
        try {
            // 1. Update Tabel Users (Kredensial Utama)
            $user->update([
                'email' => $request->email,
                'name'  => $request->name, 
            ]);

            // 2. Update Tabel Profiles (Detail Tambahan)
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name'     => $request->name,
                    'telepon'       => $request->telepon,
                    'nik'           => $request->nik,
                    'tempat_lahir'  => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat'        => $request->alamat,
                ]
            );

            DB::commit();
            return back()->with('success', 'Identitas profil berhasil diperbarui secara menyeluruh.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PROFILE_UPDATE_ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui profil. Terjadi kesalahan pada database.');
        }
    }

    public function password()
    {
        return view('kader.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Kata sandi saat ini yang Anda masukkan tidak tepat.');
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Keamanan ditingkatkan! Kata sandi Anda berhasil diperbarui.');
    }
}