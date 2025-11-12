<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // 🔹 Rutas Web generales
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // 🔹 Rutas del panel admin
            Route::middleware('web')
                ->prefix('admin')
                ->as('admin.') // 👈 este "as" es clave para que el nombre comience con admin.
                ->group(base_path('routes/admin.php'));
        });
    }
}
