<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes — London InstantPrint
|--------------------------------------------------------------------------
*/

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

// PRODUCTS
Route::get('/products', function () {
    return app(HomeController::class)->index();
})->name('products');

Route::get('/banners', function () {
    return app(HomeController::class)->index();
})->name('banners');

Route::get('/business-cards', function () {
    return app(HomeController::class)->index();
})->name('business-cards');

Route::get('/brochures', function () {
    return app(HomeController::class)->index();
})->name('brochures');

// BLOG
Route::get('/blog', function () {
    return app(HomeController::class)->index();
})->name('blog');

// SEARCH
Route::get('/search', function (\Illuminate\Http\Request $request) {
    return app(HomeController::class)->index();
})->name('search');
