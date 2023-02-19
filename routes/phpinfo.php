Route::screen('phpinfo', \App\Orchid\Screens\PhpVersionScreen::class)
    ->name('platform.phpinfo')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push("PHP Info");
    });