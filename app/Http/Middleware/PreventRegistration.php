<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Blokir route register
        if ($request->is('register') || $request->is('register/*')) {
            return redirect('/')->with('error', 'Pendaftaran user hanya dapat dilakukan oleh administrator.');
        }

        return $next($request);
    }
}