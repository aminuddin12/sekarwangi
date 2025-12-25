<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/system/error', [\App\Http\Controllers\System\ErrorPageController::class, 'show'])->name('system.error.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
