<?php

Route::post('advanced-relation', [\OrchidAddon\Http\Controllers\AdvancedRelationController::class, 'view'])
    ->name('platform.systems.advanced_relation');

Route::screen('phpinfo', \OrchidAddon\Screens\PhpinfoScreen::class)
    ->name('platform.phpinfo')
    ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("PHP Info");
    });

// Platform > Logs
Route::screen('logs', \OrchidAddon\Screens\Log\LogListScreen::class)
    ->name('platform.logs')
    ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Logs'), route('platform.logs'));
    });

Route::screen('logs/{file_name}/preview', \OrchidAddon\Screens\Log\LogPreviewScreen::class)
    ->name('platform.logs.preview')
    ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail, $file_name) {
        return $trail
            ->parent('platform.logs')
            ->push(decrypt($file_name));
    });
