<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\OrganizationController;
use App\Http\Controllers\Public\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/about', [OrganizationController::class, 'about'])->name('about');
Route::get('/vision-mission', [OrganizationController::class, 'visionMission'])->name('vision-mission');
Route::get('/organization', [OrganizationController::class, 'organization'])->name('organization');
Route::get('/legality', [OrganizationController::class, 'legality'])->name('legality');

