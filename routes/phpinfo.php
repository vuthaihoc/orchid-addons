<?php

\Illuminate\Support\Facades\Route::screen('phpinfo', \OrchidAddon\Screens\PhpinfoScreen::class)
    ->name('platform.phpinfo')
    ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("PHP Info");
    });