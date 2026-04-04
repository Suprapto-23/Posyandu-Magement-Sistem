<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('kader.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        // 1. Update Tabel Users (Akun Utama)
        $user->update([
            'email' => $request->email,
            'name' => $request->name, // Asumsi nama login juga diupdate
        ]);

        // 2. Update Tabel Profiles (Detail Personal)
        // BUG FIXED: Menggunakan 'telepon' sesuai model, bukan 'phone_number'
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->name,
                'telepon' => $request->telepon,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
            ]
        );

        return back()->with('success', 'Identitas personal berhasil diperbarui secara menyeluruh.');
    }

    public function password()
    {
        return view('kader.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Sandi saat ini tidak cocok dengan database kami.');
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Sandi keamanan berhasil diperbarui. Akses Anda kini lebih aman.');
    }
}