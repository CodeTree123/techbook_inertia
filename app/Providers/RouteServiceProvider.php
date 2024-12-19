<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::namespace($this->namespace)->group(function () {
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

                Route::middleware('web')->group(base_path('routes/technician.php'));

                Route::middleware('web')->group(base_path('routes/dispatchTeam.php'));

                Route::middleware('web')->group(base_path('routes/ticket.php'));

                Route::middleware('web')->group(base_path('routes/customer.php'));

                Route::middleware('web')->group(base_path('routes/inventory.php'));

                Route::middleware('web')->group(base_path('routes/employee.php'));

                Route::middleware(['web', 'maintenance'])
                    ->prefix('user')
                    ->group(base_path('routes/user.php'));

                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));
            });
        });
    }
}
