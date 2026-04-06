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
        if (Auth::check()) {
            $user = Auth::user();
            return $this->redirectBasedOnRole($user->role);
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = $this->getLoginType($request->login);
        
        if (!$loginType) {
            return back()->withErrors([
                'login' => 'Format login tidak valid. Gunakan email atau NIK (16 digit).',
            ])->onlyInput('login');
        }

        $user = $this->findUserByLogin($request->login, $loginType);
        
        if (!$user) {
            return back()->withErrors([
                'login' => 'Akun tidak ditemukan.',
            ])->onlyInput('login');
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'login' => 'Akun Anda tidak aktif. Hubungi admin.',
            ])->onlyInput('login');
        }

        if (!Hash::check($request->password, $user->password)) {
            try {
                LoginLog::create([
                    'user_id'    => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login_at'   => now(),
                    'status'     => 'failed',
                ]);
            } catch (\Exception $e) {}

            return back()->withErrors([
                'password' => 'Password salah.',
            ])->onlyInput('login');
        }

        // Login user
        Auth::login($user, $request->filled('remember'));

        // Regenerate session
        $request->session()->regenerate();

        // Simpan data ke session secara eksplisit
        $request->session()->put('login_role', $user->role);
        $request->session()->put('login_user_id', $user->id);
        $request->session()->save();

        // Log aktivitas login
        try {
            LoginLog::create([
                'user_id'    => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at'   => now(),
                'status'     => 'success',
            ]);
        } catch (\Exception $e) {}

        // Update last login
        try {
            $user->update(['last_login_at' => now()]);
        } catch (\Exception $e) {}

        // Redirect berdasarkan role
        $redirectUrl = $this->getRedirectUrl($user->role);

        return redirect()->to($redirectUrl);
    }

    private function getRedirectUrl($role)
    {
        return match(strtolower($role)) {
            'admin'  => '/admin/dashboard',
            'bidan'  => '/bidan/dashboard',
            'kader'  => '/kader/dashboard',
            'user'   => '/user/dashboard',
            default  => '/home',
        };
    }

    private function getLoginType($login)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        if (preg_match('/^\d{16}$/', $login)) {
            return 'nik';
        }
        
        if (preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
            return 'username';
        }
        
        return null;
    }

    private function findUserByLogin($login, $loginType)
    {
        switch ($loginType) {
            case 'email':
                return \App\Models\User::where('email', $login)->first();
            
            case 'nik':
                $user = \App\Models\User::where('nik', $login)->first();
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

    private function redirectBasedOnRole($role)
    {
        return match(strtolower($role)) {
            'admin'  => redirect()->route('admin.dashboard'),
            'bidan'  => redirect()->route('bidan.dashboard'),
            'kader'  => redirect()->route('kader.dashboard'),
            'user'   => redirect()->route('user.dashboard'),
            default  => redirect('/home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Anda telah logout.');
    }
}