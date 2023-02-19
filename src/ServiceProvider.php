<?php

namespace OrchidAddon;

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
        $this->loadRoutesFrom(__DIR__.'/../routes/phpinfo.php');
    }
}