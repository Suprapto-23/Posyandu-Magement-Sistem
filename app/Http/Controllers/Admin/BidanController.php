<?php
/**
 * PATH   : app/Http/Controllers/Admin/BidanController.php
 * FUNGSI : CRUD akun bidan — login via email, data profil terintegrasi penuh
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class BidanController extends Controller
{
    // ── INDEX ───────────────────────────────────
    public function index(Request $request)
    {
        $query = User::with('profile')->where('role', 'bidan');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', fn($p) =>
                      $p->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                  );
            });
        }
        if ($request->status) $query->where('status', $request->status);

        $bidans = $query->latest()->paginate($request->per_page ?? 15)->withQueryString();
        $stats  = $this->getStats();

        return view('admin.bidans.index', compact('bidans', 'stats'));
    }

    // ── CREATE ──────────────────────────────────
    public function create()
    {
        return view('admin.bidans.create');
    }

    // ── STORE ───────────────────────────────────
    public function store(Request $request)
    {
        // 1. Validasi semua field yang ada di form View
        $request->validate([
            'name'          => 'required|string|max:191',
            'email'         => 'required|email|unique:users,email',
            'nik'           => 'required|digits:16|unique:users,nik|unique:profiles,nik',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon'       => 'nullable|string|max:20',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:active,inactive',
        ], [
            'email.unique' => 'Email ini sudah digunakan.',
            'nik.digits'   => 'NIK harus 16 digit angka.',
            'nik.unique'   => 'NIK ini sudah terdaftar.',
        ]);

        $password = $this->makePassword();

        DB::beginTransaction();
        try {
            // 2. Simpan ke tabel Users (Data Login)
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'nik'      => $request->nik,
                'password' => Hash::make($password),
                'role'     => 'bidan',
                'status'   => $request->status,
            ]);

            // 3. Simpan ke tabel Profiles (Biodata Lengkap)
            $user->profile()->create([
                'user_id'       => $user->id,
                'full_name'     => $request->name,
                'nik'           => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'telepon'       => $request->telepon,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat'        => $request->alamat,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('BidanController::store — ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal membuat akun bidan.');
        }

        return redirect()->route('admin.bidans.index')
            ->with('success', 'Akun bidan berhasil dibuat.')
            ->with('generated_password', $password)
            ->with('user_name', $request->name)
            ->with('user_email', $request->email);
    }

    // ── SHOW ────────────────────────────────────
    public function show($id)
    {
        $bidan = User::with('profile')->where('role', 'bidan')->findOrFail($id);
        return view('admin.bidans.show', compact('bidan'));
    }

    // ── EDIT ────────────────────────────────────
    public function edit($id)
    {
        $bidan = User::with('profile')->where('role', 'bidan')->findOrFail($id);
        return view('admin.bidans.edit', compact('bidan'));
    }

    // ── UPDATE ──────────────────────────────────
    public function update(Request $request, $id)
    {
        $bidan = User::with('profile')->where('role', 'bidan')->findOrFail($id);

        // 1. Validasi Update (Email & NIK tidak divalidasi karena readonly di view)
        $request->validate([
            'name'          => 'required|string|max:191',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon'       => 'nullable|string|max:20',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'nullable|string',
            'status'        => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            // 2. Update Nama Utama & Status Login
            $bidan->update([
                'name'   => $request->name, 
                'status' => $request->status
            ]);

            // 3. Update Seluruh Biodata
            $profileData = [
                'full_name'     => $request->name,
                'jenis_kelamin' => $request->jenis_kelamin,
                'telepon'       => $request->telepon,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat'        => $request->alamat,
            ];

            if ($bidan->profile) {
                $bidan->profile->update($profileData);
            } else {
                // Jika entah kenapa profilenya belum ada, buat baru
                $bidan->profile()->create(array_merge($profileData, ['user_id' => $bidan->id]));
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data bidan.');
        }

        return redirect()->route('admin.bidans.show', $id)
            ->with('success', 'Data bidan berhasil diperbarui.');
    }

    // ── DESTROY ─────────────────────────────────
    public function destroy($id)
    {
        $bidan = User::where('role', 'bidan')->findOrFail($id);
        $name  = $bidan->profile?->full_name ?? $bidan->name;
        $bidan->delete();

        return redirect()->route('admin.bidans.index')
            ->with('success', "Akun bidan {$name} berhasil dihapus.");
    }

    // ── RESET PASSWORD ───────────────────────────
    public function resetPassword($id)
    {
        $bidan    = User::where('role', 'bidan')->findOrFail($id);
        $nik      = $bidan->profile?->nik ?? $bidan->nik ?? '0000000000000000';
        $password = substr($nik, -6) . 'Bdn!';
        $bidan->update(['password' => Hash::make($password)]);

        return redirect()->route('admin.bidans.index')
            ->with('success', 'Password bidan berhasil direset.')
            ->with('reset_password', $password)
            ->with('reset_name', $bidan->profile?->full_name ?? $bidan->name)
            ->with('reset_email', $bidan->email);
    }

    // ── HELPERS ─────────────────────────────────
    private function getStats(): array
    {
        try {
            return [
                'total'    => User::where('role', 'bidan')->count(),
                'aktif'    => User::where('role', 'bidan')->where('status', 'active')->count(),
                'nonaktif' => User::where('role', 'bidan')->where('status', 'inactive')->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'aktif' => 0, 'nonaktif' => 0];
        }
    }

    private function makePassword(): string
    {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#';
        $out   = '';
        for ($i = 0; $i < 8; $i++) $out .= $chars[random_int(0, strlen($chars) - 1)];
        return $out;
    }
}