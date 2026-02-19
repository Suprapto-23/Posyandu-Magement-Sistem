<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Bidan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BidanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $bidans = User::where('role', 'bidan')
            ->with(['profile', 'bidan'])
            ->when($search, function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    // Hapus pencarian pada kolom yang tidak ada
                    $query->where('email', 'like', "%{$search}%")
                          ->orWhere('name', 'like', "%{$search}%")
                          ->orWhereHas('profile', function($q) use ($search) {
                              $q->where('full_name', 'like', "%{$search}%")
                                ->orWhere('nik', 'like', "%{$search}%");
                          });
                });
            })
            ->latest()
            ->paginate(15);
            
        return view('admin.bidans.index', compact('bidans', 'search'));
    }

    public function create()
    {
        return view('admin.bidans.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI: Hanya data User & Profile dasar
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:profiles,nik',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        
        try {
            $password = Str::random(8);
            
            // 2. Buat User Login
            $userData = [
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'bidan',
                'status' => $request->status,
            ];

            if (Schema::hasColumn('users', 'created_by')) {
                $userData['created_by'] = auth()->id();
            }
            
            $user = User::create($userData);
            
            // 3. Buat Profile
            Profile::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nik' => $request->nik,
                'jenis_kelamin' => 'P', // Default
                'alamat' => '-',
                'telepon' => '-',
            ]);
            
            // 4. Buat Data Bidan (HANYA user_id)
            // KITA HAPUS 'spesialisasi' => 'Umum' KARENA KOLOMNYA TIDAK ADA
            Bidan::create([
                'user_id' => $user->id,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.bidans.index')
                ->with('success', 'Akun Bidan berhasil dibuat.')
                ->with('email', $request->email)
                ->with('password', $password);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat bidan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        // Gunakan try-catch atau conditional loading untuk menghindari error relasi
        $bidan = User::with(['profile'])->findOrFail($id); 
        
        if ($bidan->role !== 'bidan') abort(404);
        
        return view('admin.bidans.show', compact('bidan'));
    }

    public function edit($id)
    {
        $bidan = User::with(['profile'])->findOrFail($id);
        if ($bidan->role !== 'bidan') abort(404);
        return view('admin.bidans.edit', compact('bidan'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            // Update User
            $user->update([
                'status' => $request->status,
                'name' => $request->full_name,
            ]);
            
            // Update Profile
            $user->profile()->update([
                'full_name' => $request->full_name,
            ]);
            
            // KITA TIDAK UPDATE TABEL BIDAN KARENA TIDAK ADA DATA YANG DIUBAH (SIP/SPESIALISASI HILANG)
            
            DB::commit();
            return redirect()->route('admin.bidans.show', $user->id)
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'bidan') abort(404);

        DB::beginTransaction();
        try {
            // Hapus relasi jika ada
            if($user->bidan) $user->bidan()->delete();
            if($user->profile) $user->profile()->delete();
            
            $user->delete();
            
            DB::commit();
            return redirect()->route('admin.bidans.index')->with('success', 'Bidan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newPassword = Str::random(8);
        $user->update(['password' => Hash::make($newPassword)]);
        
        return back()->with('success', 'Password berhasil direset. Password baru: ' . $newPassword)
                     ->with('password', $newPassword);
    }
}