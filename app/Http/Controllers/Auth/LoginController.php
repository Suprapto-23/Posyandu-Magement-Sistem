<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectBasedOnRole($user->role);
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('=== LOGIN ATTEMPT START ===');
        
        // Validasi input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Login attempt', ['login' => $request->login]);

        // Deteksi tipe login
        $loginType = $this->getLoginType($request->login);
        
        if (!$loginType) {
            Log::warning('Invalid login format', ['login' => $request->login]);
            return back()->withErrors([
                'login' => 'Format login tidak valid. Gunakan email atau NIK (16 digit).',
            ])->onlyInput('login');
        }

        Log::info('Login type detected', ['type' => $loginType]);

        // Cari user berdasarkan tipe login
        $user = $this->findUserByLogin($request->login, $loginType);
        
        if (!$user) {
            Log::warning('User not found', ['login' => $request->login, 'type' => $loginType]);
            return back()->withErrors([
                'login' => 'Akun tidak ditemukan.',
            ])->onlyInput('login');
        }

        Log::info('User found', ['user_id' => $user->id, 'role' => $user->role, 'status' => $user->status]);

        // Cek status akun
        if ($user->status !== 'active') {
            Log::warning('Inactive user tried to login', ['user_id' => $user->id]);
            return back()->withErrors([
                'login' => 'Akun Anda tidak aktif. Hubungi admin.',
            ])->onlyInput('login');
        }

        // Verifikasi password
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Wrong password', ['user_id' => $user->id]);
            
            // Log attempt gagal
            try {
                LoginLog::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at' => now(),
                    'status' => 'failed',
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log failed attempt', ['error' => $e->getMessage()]);
            }

            return back()->withErrors([
                'password' => 'Password salah.',
            ])->onlyInput('login');
        }

        Log::info('Password verified, logging in user');

        // Login user
        Auth::login($user, $request->filled('remember'));

        Log::info('Auth::login called', [
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);

        // Regenerate session untuk security
        $request->session()->regenerate();

        Log::info('Session regenerated');

        // Log aktivitas login
        try {
            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'status' => 'success',
            ]);
            Log::info('Login log created');
        } catch (\Exception $e) {
            Log::error('Failed to create login log', ['error' => $e->getMessage()]);
        }

        // Update last login
        try {
            $user->update(['last_login_at' => now()]);
            Log::info('Last login updated');
        } catch (\Exception $e) {
            Log::error('Failed to update last login', ['error' => $e->getMessage()]);
        }

        // Redirect berdasarkan role
        $redirectUrl = $this->getRedirectUrl($user->role);
        
        Log::info('Preparing redirect', [
            'role' => $user->role,
            'url' => $redirectUrl,
        ]);

        Log::info('=== LOGIN ATTEMPT END - SUCCESS ===');

        // PENTING: Gunakan redirect()->to() bukan route() untuk debugging
        return redirect()->intended($redirectUrl)->with('success', 'Login berhasil!');
    }

    /**
     * Get redirect URL based on role
     */
    private function getRedirectUrl($role)
    {
        $role = strtolower($role);
        
        return match($role) {
            'admin' => '/admin/dashboard',
            'bidan' => '/bidan/dashboard',
            'kader' => '/kader/dashboard',
            'user' => '/user/dashboard',
            default => '/home',
        };
    }

    /**
     * Deteksi tipe login berdasarkan format input
     */
    private function getLoginType($login)
    {
        // Email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        // NIK (16 digit angka)
        if (preg_match('/^\d{16}$/', $login)) {
            return 'nik';
        }
        
        // Username (hanya untuk admin)
        if (preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
            return 'username';
        }
        
        return null;
    }

    /**
     * Cari user berdasarkan tipe login
     */
    private function findUserByLogin($login, $loginType)
    {
        switch ($loginType) {
            case 'email':
                return \App\Models\User::where('email', $login)->first();
            
            case 'nik':
                // Cari di tabel users untuk user biasa (warga)
                $user = \App\Models\User::where('nik', $login)->first();
                
                // Jika tidak ditemukan, coba cari di tabel profiles
                if (!$user) {
                    $profile = \App\Models\Profile::where('nik', $login)->first();
                    if ($profile) {
                        return $profile->user;
                    }
                }
                
                return $user;
            
            case 'username':
                return \App\Models\User::where('username', $login)->first();
            
            default:
                return null;
        }
    }

    /**
     * Redirect berdasarkan role user (backup method)
     */
    private function redirectBasedOnRole($role)
    {
        $role = strtolower($role);
        
        return match($role) {
            'admin' => redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, Admin!'),
            
            'bidan' => redirect()->route('bidan.dashboard')
                ->with('success', 'Selamat datang, Bidan!'),
            
            'kader' => redirect()->route('kader.dashboard')
                ->with('success', 'Selamat datang, Kader!'),
            
            'user' => redirect()->route('user.dashboard')
                ->with('success', 'Selamat datang di SIPOSYANDU!'),
            
            default => redirect('/home')
                ->with('info', 'Selamat datang!'),
        };
    }

    public function logout(Request $request)
    {
        Log::info('Logout attempt', ['user_id' => Auth::id()]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('info', 'Anda telah logout.');
    }
}