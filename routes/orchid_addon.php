<?php

use OrchidAddon\LogViewer;

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

Route::screen('logs/{file_name}/preview', \OrchidAddon\Screens\Log\LogPreviewScreen::class)
    ->where('file_name', '.*')
    ->name('platform.logs.preview')
    ->breadcrumbs(function (\Tabuna\Breadcrumbs\Trail $trail, $file_name) {

        $trail = $trail->parent('platform.logs');
        $parts = explode('/', $file_name);
        $currentPath = '';
    
        foreach ($parts as $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $trail->push($part);
        }

        return $trail;
    });

Route::get('logs/{file_name}/download', function ($file_name) {
    return response()->download(LogViewer::pathToLogFile($file_name));
})
    ->where('file_name', '.*')
    ->name('platform.logs.download');
