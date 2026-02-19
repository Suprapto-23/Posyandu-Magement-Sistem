<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                return redirect()->to($this->getDashboardUrl($user->role));
            }
        }

        return $next($request);
    }

    private function getDashboardUrl(string $role): string
    {
        return match(strtolower($role)) {
            'admin'  => '/admin/dashboard',
            'bidan'  => '/bidan/dashboard',
            'kader'  => '/kader/dashboard',
            'user'   => '/user/dashboard',
            default  => '/home',
        };
    }
}