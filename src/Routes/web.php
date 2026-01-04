<?php

use Illuminate\Support\Facades\Route;
use LWSoftBD\LwSettings\Http\Controllers\SettingController;

Route::middleware(['web', 'auth'])
    ->prefix('admin/site-settings')
    ->group(function () {
        Route::get('/', [SettingController::class, 'index'])
            ->name('site.settings');
        Route::get('settings/search', [SettingController::class, 'search'])
            ->name('settings.search');
        Route::get('/edit', [SettingController::class, 'edit'])
            ->name('site.settings.edit');
        Route::post('/', [SettingController::class, 'update'])
            ->name('site.settings.update');
        Route::get('/create', [SettingController::class, 'create'])
            ->name('site.settings.create');
        Route::post('/create', [SettingController::class, 'store'])
            ->name('site.settings.store');
        Route::post('/cache-clear/{key}', [SettingController::class, 'clearCache'])
            ->name('settings.cache.clear');
        Route::post('cache-clear-all', [SettingController::class, 'clearAllCache'])
            ->name('settings.cache.clear.all');

});
