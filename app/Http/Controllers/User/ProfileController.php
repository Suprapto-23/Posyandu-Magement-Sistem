<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ProfileController (User/Warga)
 * [BUG-11 FIX] Kolom 'full_name' ADA di tabel profiles → wajib diisi.
 */
class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'nik'           => 'nullable|digits:16',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat'        => 'nullable|string|max:500',
            'telepon'       => 'nullable|string|max:15|regex:/^[0-9]+$/',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'nik'   => $request->nik ?: null,
        ]);

        // full_name ADA di tabel profiles (NOT NULL) — wajib diisi
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name'     => $request->name,
                'nik'           => $request->nik ?: null,
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat'        => $request->alamat,
                'telepon'       => $request->telepon,
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}