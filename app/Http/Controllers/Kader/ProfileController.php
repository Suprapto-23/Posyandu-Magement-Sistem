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
            'no_hp' => 'nullable|string|max:15',
        ]);

        // Update User
        $user->update(['email' => $request->email]);

        // Update Profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->name,
                'phone_number' => $request->no_hp,
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
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
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}