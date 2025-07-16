<?php

use App\Orchid\Screens\PlatformScreen;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'platform'])->group(function () {
    Route::screen('/', PlatformScreen::class)
        ->name('platform.main');
});
