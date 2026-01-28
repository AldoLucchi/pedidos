<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // /**
    //  * Register any application services.
    //  */
    // public function register(): void
    // {
    //     //
    // }

    // public function boot(): void
    // {
    //     Vite::prefetch(concurrency: 3);
    
    //     $archivo = storage_path('app/.system_cache');
    
    //     if (!file_exists($archivo)) {
    //         abort(403, "Error en la configuración del sistema. Contacta al soporte.");
    //     }
    
    //     $fechaLimite = trim(file_get_contents($archivo));
    
    //     if (now()->greaterThan($fechaLimite)) {
    //         abort(403, "Este software ha expirado. Contacta al desarrollador.");
    //     }
    // }     
}
