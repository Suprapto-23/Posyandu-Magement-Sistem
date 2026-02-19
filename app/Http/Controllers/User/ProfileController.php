<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile; // Pastikan Model Profile di-import

class ProfileController extends Controller
{
    public function edit()
    {
        // Load relasi profile
        $user = Auth::user()->load('profile');
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // Validasi data profil tambahan
            'nik' => 'nullable|numeric|digits:16',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|numeric',
        ]);

        // 1. Update Tabel Users (Login Info)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik, // Update NIK di user juga agar sinkron
        ]);

        // 2. Update atau Create Tabel Profiles (Detail Info)
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->name,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L', // Default atau dari form
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
            ]
        );

        return back()->with('success', 'Profil dan data diri berhasil diperbarui.');
    }
}