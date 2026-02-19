<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            // A. Settings
            $settings = cache()->remember('app_settings', 3600, function () {
                try { return Setting::getAll(); } catch (\Exception $e) { return []; }
            });
            $view->with('settings', $settings);

            // B. Logika Sidebar & Dashboard Eksklusif
            $peranUser = ['umum']; 

            if (Auth::check() && Auth::user()->role === 'user') {
                $user = Auth::user();
                $nikUser = $user->nik ?? ($user->profile->nik ?? null);

                if ($nikUser) {
                    $peranDitemukan = [];

                    // Cek Prioritas: Lansia -> Remaja -> Orang Tua
                    if (Lansia::where('nik', $nikUser)->exists()) {
                        $peranDitemukan[] = 'lansia';
                    } 
                    
                    if (Remaja::where('nik', $nikUser)->exists()) {
                        $peranDitemukan[] = 'remaja';
                    }

                    if (Balita::where('nik_ibu', $nikUser)->orWhere('nik_ayah', $nikUser)->exists()) {
                        $peranDitemukan[] = 'orang_tua';
                    }

                    if (!empty($peranDitemukan)) {
                        $peranUser = $peranDitemukan;
                    }
                }
            }
            $view->with('peranUser', $peranUser);
        });
    }
}