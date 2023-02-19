<?php

namespace OrchidAddon;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Orchid\Platform\Dashboard;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'orchid_addon');
        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            Route::domain((string) config('platform.domain'))
                ->prefix(Dashboard::prefix('/'))
                ->middleware(config('platform.middleware.private'))
                ->group(__DIR__.'/../routes/orchid_addon.php');
        }
    }
}