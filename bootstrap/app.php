<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ FIX UTAMA: Alias middleware menggunakan nama class yang BENAR
        $middleware->alias([
            'role'        => \App\Http\Middleware\RoleMiddleware::class,   // Bukan CheckRole!
            'checkstatus' => \App\Http\Middleware\CheckUserStatus::class,
            'logactivity' => \App\Http\Middleware\LogUserActivity::class,
        ]);

        // Redirect guest ke halaman login
        $middleware->redirectGuestsTo('/login');

        // Redirect user yang sudah login ke dashboard sesuai role
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            if (!$user) {
                return '/login';
            }

            return match(strtolower($user->role)) {
                'admin'  => '/admin/dashboard',
                'bidan'  => '/bidan/dashboard',
                'kader'  => '/kader/dashboard',
                'user'   => '/user/dashboard',
                default  => '/home',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();